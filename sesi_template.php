<?php
	define("BASEPATH", dirname(__FILE__));
	require_once '../assets/library/PHPExcel.php';
	include "../config/konfigurasi.php"; 
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator("Kasworo Wardani")
		->setLastModifiedBy("Kasworo Wardani");
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'TEMPLATE PENGATURAN SESI '.$level)
		->setCellValue('A3', 'No.')
		->setCellValue('A4','(1)')
		->setCellValue('B3', 'Nomor Peserta')
		->setCellValue('B4','(2)')
		->setCellValue('C3', 'NIS')
		->setCellValue('C4','(3)')
		->setCellValue('D3', 'NISN')
		->setCellValue('D4','(4)')
		->setCellValue('E3', 'Nama Peserta Didik')
		->setCellValue('E4','(5)')
		->setCellValue('F3', 'Rombel')
		->setCellValue('F4','(6)')
		->setCellValue('G3', 'Ruang')
		->setCellValue('G4','(7)')
		->setCellValue('H3','Kode Jadwal')
		->setCellValue('H4','(8)')
		->setCellValue('I3','Sesi')
		->setCellValue('I4','(9)')
		->MergeCells('A1:I1');	
	$objPHPExcel->getActiveSheet()->freezePane('A5');
	$sql="SELECT ps.nis, ps.nisn, ps.nmsiswa, ps.nmpeserta, r.nmrombel, u.nmujian, r1.nmruang, ps.idruang, jd.idjadwal, su.idsesi FROM tbpeserta ps INNER JOIN tbrombelsiswa rs ON ps.idsiswa=rs.idsiswa INNER JOIN tbrombel r ON r.idrombel=rs.idrombel INNER JOIN tbthpel t ON r.idthpel=t.idthpel INNER JOIN tbujian u ON u.idujian=ps.idujian LEFT JOIN tbruang r1 ON r1.idruang=ps.idruang LEFT JOIN tbjadwal jd ON jd.idujian=u.idujian LEFT JOIN tbsesiujian su ON su.idjadwal=jd.idjadwal AND su.idsiswa=ps.idsiswa WHERE ps.deleted='0' AND u.status='1' AND t.aktif='1' GROUP BY ps.nmpeserta, jd.idjadwal ORDER BY jd.idjadwal,ps.nmpeserta";	
	$i=4;
	$no=0;
	$qgetsesi=$conn->query($sql);
	while($s=$qgetsesi->fetch_array()){
		$i++;
		$no++;
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$i, $no)
		->setCellValue('B'.$i, $s['nmpeserta'])
		->setCellValue('C'.$i, $s['nis'])
		->setCellValue('D'.$i, $s['nisn'])
		->setCellValue('E'.$i, $s['nmsiswa'])
		->setCellValue('F'.$i, $s['nmrombel'])
		->setCellValue('G'.$i, $s['nmruang'])
		->setCellValue('H'.$i, $s['idjadwal'])
		->setCellValue('I'.$i, $s['idsesi']);
	}

	$objPHPExcel->getActiveSheet()->freezePane('A5');

	$objPHPExcel->setActiveSheetIndex()->getStyle('A1:H4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$center = array();
	$center ['alignment']=array();
	$center ['alignment']['horizontal']=PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
	$objPHPExcel->setActiveSheetIndex()->getStyle ('A3:H4' )->applyFromArray ($center);

	$thick = array ();
	$thick['borders']=array();
	$thick['borders']['allborders']=array();
	$thick['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_THIN;
	$objPHPExcel->setActiveSheetIndex()->getStyle ("A3:H$i" )->applyFromArray ($thick); 

	$objPHPExcel->getActiveSheet()->getStyle('A3:H4')->getAlignment()->setWrapText(true);

	$objPHPExcel->getSheet(0)->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getSheet(0)->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getSheet(0)->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getSheet(0)->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getSheet(0)->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getSheet(0)->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getSheet(0)->getColumnDimension('G')->setAutoSize(true);
	$objPHPExcel->getSheet(0)->getColumnDimension('H')->setAutoSize(true);
	$objPHPExcel->getActiveSheet(0)->setTitle('tmp_setsesi');
	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="tmp_setsesi.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>