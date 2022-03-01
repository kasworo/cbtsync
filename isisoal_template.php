<?php
	define("BASEPATH", dirname(__FILE__));
	require_once '../assets/library/PHPExcel.php';
	include "../config/konfigurasi.php"; 
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator("Kasworo Wardani")
		->setLastModifiedBy("Kasworo Wardani")
		->setTitle("Office 2007 XLSX Test Document")
		->setSubject("Office 2007 XLSX Test Document")
		->setDescription("Soal Export")
		->setKeywords("office 2007 openxml php")
		->setCategory("Template");
	$sql=$conn->query("SELECT bs.*, m.nmmapel, k.nmkelas FROM tbbanksoal bs INNER JOIN tbmapel m USING(idmapel) INNER JOIN tbkelas k USING(idkelas) WHERE idbank='$_GET[id]'");
	$b=$sql->fetch_array();
	$nmbank=$b['nmbank'];
	$objPHPExcel->setActiveSheetIndex(0)
		->MergeCells('A1:D1')
		->setCellValue('A1', 'TEMPLATE IMPORT SOAL')
		->setCellValue('A3', 'Kode Soal')
		->setCellValue('A4',$b['nmbank'])
		->setCellValue('A5', 'Mata Pelajaran')
		->setCellValue('A6', $b['idmapel'].' - '.$b['nmmapel'])
		->setCellValue('A7', 'Kelas')
		->setCellValue('A8',$b['idkelas'].' - '.$b['nmkelas'])
		->setCellValue('A10','No')
		->setCellValue('B10','Rumusan Soal')
		->setCellValue('D10','Ket.')
		->MergeCells('A3:D3')->MergeCells('A4:D4')
		->MergeCells('A5:D5')->MergeCells('A6:D6')
		->MergeCells('A7:D7')->MergeCells('A8:D8')
		->MergeCells('B10:C10');
	

	$qsoal=$conn->query("SELECT*FROM tbsoal WHERE idbank='$_GET[id]'");
	$i=0;
	$no=0;
	$ceksoal=$qsoal->num_rows;
	if($ceksoal==0){
		while($i<40){
			$i++;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.(6*($i-1)+11), $i)
				->setCellValue('B'.(6*($i-1)+11), '');
				//->MergeCells('B'.(6*($i-1)+11).':C'.(6*($i-1)+11));
			$j=0;
			while($j<5){
				$j++;
				switch($j){
					case '1':{$opsi='A.';break;}
					case '2':{$opsi='B.';break;}
					case '3':{$opsi='C.';break;}
					case '4':{$opsi='D.';break;}
					case '5':{$opsi='E.';break;}
					default :{$opsi='-';break;}
				}
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.(6*($i-1)+$j+11),'')
				->setCellValue('B'.(6*($i-1)+$j+11), $opsi)
				->setCellValue('C'.(6*($i-1)+$j+11),'');
			}
			
		}
	}
	else {
			while($so=$qsoal->fetch_array()){
			$i++;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.(6*($i-1)+11), $i)
				->setCellValue('B'.(6*($i-1)+11),$so['butirsoal'])
				->setCellValue('D'.(6*($i-1)+11),$so['jnssoal'])
				->MergeCells('B'.(6*($i-1)+11).':C'.(6*($i-1)+11));
			$qops=$conn->query("SELECT*FROM tbopsi WHERE idbutir='$so[idbutir]'");
			$j=0;
			while($op=$qops->fetch_array()){
				$j++;
				switch($j){
					case '1':{$opsi='A.';break;}
					case '2':{$opsi='B.';break;}
					case '3':{$opsi='C.';break;}
					case '4':{$opsi='D.';break;}
					case '5':{$opsi='E.';break;}
					default :{$opsi='-';break;}
				}
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.(6*($i-1)+$j+11))
				->setCellValue('B'.(6*($i-1)+$j+11), $opsi)
				->setCellValue('C'.(6*($i-1)+$j+11), $op['opsi'])
				->setCellValue('D'.(6*($i-1)+$j+11), $op['benar']);
			}
		}
	}
	$objPHPExcel->getActiveSheet(0)->setTitle($nmbank.'_tmp');
	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$nmbank.'_tmp.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>