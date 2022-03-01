<?php
	define("BASEPATH", dirname(__FILE__));
	require_once '../assets/library/PHPExcel.php';
	include "../config/konfigurasi.php"; 
	
	$objPHPExcel = new PHPExcel();	
	$objPHPExcel->getProperties()->setCreator("Kasworo Wardani")
		->setLastModifiedBy("Kasworo Wardani")
		->setCategory("Report");    
    $sql=$conn->query("SELECT bs.*, m.nmmapel, k.nmkelas, t.nmtes, u.nmujian, tp.nmthpel, tp.desthpel FROM tbbanksoal bs INNER JOIN tbmapel m USING(idmapel) INNER JOIN tbkelas k USING(idkelas) INNER JOIN tbujian u USING(idujian) INNER JOIN tbtes t USING(idtes) INNER JOIN tbthpel tp USING(idthpel) WHERE idbank='$_GET[id]'");
    $b=$sql->fetch_array();
	$nmbank=$b['nmbank'];
	$sem=substr($b['nmthpel'],-1);
	$thpel=substr($b['desthpel'],0,9);
	if($sem=='1'){$semester='I (Satu)';} else {$semester='II (Genap)';}$qkkm=$conn->query("SELECT kkm FROM tbkkm k INNER JOIN tbbanksoal bs USING(idmapel, idkelas) WHERE bs.idbank='$_GET[id]'");
	$km=$qkkm->fetch_array();
	$kkm=$km['kkm'];	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'ANALISIS BUTIR SOAL '.strtoupper($b['nmtes']))
		->setCellValue('A3','Mata Pelajaran')
		->setCellValue('C3',': '.$b['nmmapel'])
		->setCellValue('A4','Semester')
		->setCellValue('C4',': '.$thpel.' - '.$semester)
		->setCellValue('A5','Kelas')
		->setCellValue('C5',': '.str_replace('Kelas ','',$b['nmkelas']))
		->setCellValue('A6','K K M')
		->setCellValue('C6',': '.$kkm)
		->setCellValue('A8', 'No.')
		->setCellValue('B8', 'No. Peserta')
		->setCellValue('C8', 'Nama Peserta');
	$qno = $conn->query("SELECT*FROM tbsoal WHERE idbank='$_GET[id]' ORDER BY nomersoal");
	$baris=8;
	$kol=3;
	while($no=$qno->fetch_array())
	{
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow($kol++, $baris,$no['nomersoal']);
	}
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow($kol, $baris,'Benar')
		->setCellValueByColumnAndRow($kol+1, $baris,'Salah')
		->setCellValueByColumnAndRow($kol+2, $baris,'Nilai')
		->setCellValueByColumnAndRow($kol+3, $baris,'Keterangan');
	
	$qpst=$conn->query("SELECT ps.idsiswa, ps.nmpeserta, ps.nmsiswa FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbthpel t USING(idthpel) INNER JOIN tbkelas k USING(idkelas) INNER JOIN tbbanksoal bs USING(idkelas,idujian) WHERE bs.idbank='$_GET[id]' AND t.aktif='1'");
	$i=0;
	while($pst=$qpst->fetch_array()){
		$i++;
		$objPHPExcel->setActiveSheetIndex()
			->setCellValue('A'.($i+8), $i)
			->setCellValue('B'.($i+8), $pst['nmpeserta'])
			->setCellValue('C'.($i+8), $pst['nmsiswa']);
		$qhsl = $conn->query("SELECT idbutir FROM tbsoal WHERE idbank='$_GET[id]' ORDER BY nomersoal");
		$j=3;
		while($hs=$qhsl->fetch_array())
		{
			$qhjb = $conn->query("SELECT skor FROM tbjawaban WHERE idbutir='$hs[idbutir]' AND idsiswa='$pst[idsiswa]'");
			$hjb=$qhjb->fetch_array();
			if($hjb['skor']==''){$skor='';} else {$skor=number_format($hjb['skor'],2,',','.');}
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($j++, $i+8,$skor);
		}
		$qbnr = $conn->query("SELECT COUNT(*) as semua, sum(jw.skor) as benar FROM tbsoal so LEFT JOIN tbjawaban jw USING(idbutir) WHERE so.idbank='$_GET[id]' AND jw.idsiswa='$pst[idsiswa]' GROUP BY jw.idsiswa");
		$bn=$qbnr->fetch_array();
		if($bn['benar']==''){$salah='';$nilai='';}
		else{
		$salah=$bn['semua']-$bn['benar'];
		$nilai=$bn['benar']/$bn['semua']*100;}
		if($nilai>$kkm){$ket='Terlampaui';} else if($nilai==$kkm){$ket='Tuntas';} else {$ket='Tidak Tuntas';}
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow($j, $i+8,number_format($bn['benar'],2,',','.'))
			->setCellValueByColumnAndRow($j+1, $i+8,number_format($salah,2,',','.'))
			->setCellValueByColumnAndRow($j+2, $i+8,number_format($nilai,2,',','.'))
			->setCellValueByColumnAndRow($j+3, $i+8,$ket);
	}
	$objPHPExcel->getActiveSheet(0)->setTitle($nmbank.'_analisis');
	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$nmbank.'_analisis.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>