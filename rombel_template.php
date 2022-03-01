<?php
	define("BASEPATH", dirname(__FILE__));
	require_once '../assets/library/PHPExcel.php';
	include "../config/konfigurasi.php"; 
	$sqthn=$conn->query("SELECT idthpel FROM tbthpel WHERE aktif='1'");
	$thn=$sqthn->fetch_array();
	$idthpel=$thn['idthpel'];
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator("Kasworo Wardani")
		 ->setLastModifiedBy("Kasworo Wardani")
		 ->setTitle("Office 2007 XLSX Test Document")
		 ->setSubject("Office 2007 XLSX Test Document")
		 ->setDescription("Soal Export")
		 ->setKeywords("office 2007 openxml php")
		 ->setCategory("Template");
	$objPHPExcel->setActiveSheetIndex(0)
		 ->MergeCells('A1:F1')
		 ->setCellValue('A1', 'TEMPLATE REGISTRASI ROMBEL')
		 ->setCellValue('A3', 'No.')
		 ->setCellValue('A4','(1)')
		 ->setCellValue('B3', 'NIS')
		 ->setCellValue('B4','(2)')
		 ->setCellValue('C3', 'NISN')
		 ->setCellValue('C4','(3)')
		 ->setCellValue('D3', 'Nama Peserta Didik')
		 ->setCellValue('D4','(4)')
		 ->setCellValue('E3', 'Kode Rombel')
		 ->setCellValue('E4','(5)')
		 ->setCellValue('F3', 'Tahun Pelajaran')
		 ->setCellValue('F4','(6)');
	$objPHPExcel->getActiveSheet()->freezePane('A5');
	$sql="SELECT s.nis, s.nisn,s.nmsiswa, rs.idrombel, rb.idthpel FROM tbpeserta s LEFT JOIN tbrombelsiswa rs ON rs.idsiswa=s.idsiswa LEFT JOIN tbrombel rb ON rb.idrombel=rs.idrombel LEFT JOIN tbthpel th ON th.idthpel=rb.idthpel WHERE s.deleted='0' AND rb.idthpel='$idthpel'";
	$qsiswa=$conn->query($sql);
	$ceksiswa=$qsiswa->num_rows;
	if($ceksiswa>0){
		 $i=4;
		 $no=0;
		 while($s=$qsiswa->fetch_array()){
				$i++;$no++;
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i, $no)
				->setCellValue('B'.$i, $s['nis'])
				->setCellValue('C'.$i, $s['nisn'])
				->setCellValue('D'.$i, $s['nmsiswa'])
				->setCellValue('E'.$i, $s['idrombel'])
				->setCellValue('F'.$i, $s['idthpel']);
		 }
	}
	else{
		 $sql="SELECT s.nis, s.nisn,s.nmsiswa FROM tbpeserta s WHERE s.deleted='0' AND s.aktif='0'";
		 $i=4;
		 $no=0;
		 $qsiswa=$conn->query($sql);
		 while($s2=$qsiswa->fetch_array()){
				$i++;
				$no++;
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i, $no)
				->setCellValue('B'.$i, $s2['nis'])
				->setCellValue('C'.$i, $s2['nisn'])
				->setCellValue('D'.$i, $s2['nmsiswa'])
				->setCellValue('E'.$i, '')
				->setCellValue('F'.$i, '');
		 }
	}

	$objPHPExcel->setActiveSheetIndex()
		 ->getStyle('A1:F4')
		 ->getAlignment()
		 ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$center = array();
	$center ['alignment']=array();
	$center ['alignment']['horizontal']=PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
	$objPHPExcel->setActiveSheetIndex()->getStyle ( 'A3:F4' )->applyFromArray ($center);
	$thick = array ();
	$thick['borders']=array();
	$thick['borders']['allborders']=array();
	$thick['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_THIN;
	$objPHPExcel->setActiveSheetIndex()->getStyle ("A3:F$i" )->applyFromArray ($thick); 
	$objPHPExcel->getActiveSheet()->getStyle('A3:F5')->getAlignment()->setWrapText(true);
	$objPHPExcel->getSheet(0)->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getSheet(0)->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getSheet(0)->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getSheet(0)->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getSheet(0)->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getSheet(0)->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet(0)->setTitle('tmp_setrombel');
	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="tmp_setrombel.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>