<?php
define("BASEPATH", dirname(__FILE__));
require_once '../assets/library/PHPExcel.php';
include "dbfunction.php";
function CellsToMergeByColsRow($start = -1, $end = -1, $row = -1, $n = 0)
{
	$merge = 'A1:A1';
	if ($start >= 0 && $end >= 0 && $row >= 0) {
		$start = PHPExcel_Cell::stringFromColumnIndex($start);
		$end = PHPExcel_Cell::stringFromColumnIndex($end);
		$baris = $row + $n - 1;
		$merge = "$start{$row}:$end{$baris}";
	}
	return $merge;
}

function GetTglUjian()
{
	$sqlsesi = "SELECT jd.tglujian FROM tbjadwal jd LEFT JOIN tbujian u USING(idujian) WHERE u.status='1' GROUP BY jd.tglujian";
	return vquery($sqlsesi);
}
$namafile = 'template_sesi';
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Kasworo Wardani")
	->setLastModifiedBy("Kasworo Wardani");
$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A1', 'TEMPLATE PENGATURAN SESI')
	->setCellValue('A3', 'No.')
	->setCellValue('A5', '(1)')
	->setCellValue('B3', 'Nomor Peserta')
	->setCellValue('B5', '(2)')
	->setCellValue('C3', 'NIS')
	->setCellValue('C5', '(3)')
	->setCellValue('D3', 'NISN')
	->setCellValue('D5', '(4)')
	->setCellValue('E3', 'Nama Peserta Didik')
	->setCellValue('E5', '(5)')
	->setCellValue('F3', 'Rombel')
	->setCellValue('F5', '(6)')
	->setCellValue('G3', 'Ruang')
	->setCellValue('G5', '(7)')
	->setCellValue('H3', 'Tanggal Ujian')
	->MergeCells('A3:A4')
	->MergeCells('B3:B4')
	->MergeCells('C3:C4')
	->MergeCells('D3:D4')
	->MergeCells('E3:E4')
	->MergeCells('F3:F4')
	->MergeCells('G3:G4');
$kol = 7;
$qtgl = GetTglUjian();
foreach ($qtgl as $tgl) {
	$nomor = $kol + 1;
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow($kol, 4, $tgl['tglujian']);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow($kol, 5, '(' . $nomor . ')');
	$kol++;
}
$objPHPExcel->setActiveSheetIndex(0)->mergeCells(cellsToMergeByColsRow(0, $kol - 1, 1, 1));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells(cellsToMergeByColsRow(7, $kol - 1, 3, 1));
$objPHPExcel->getActiveSheet()->freezePane('A6');
$sql = "SELECT ps.nis, ps.nisn, ps.nmsiswa, ps.nmpeserta, r.nmrombel, u.nmujian, r1.nmruang, ps.idruang, jd.idjadwal, su.idsesi FROM tbpeserta ps INNER JOIN tbrombelsiswa rs ON ps.idsiswa=rs.idsiswa INNER JOIN tbrombel r ON r.idrombel=rs.idrombel INNER JOIN tbthpel t ON r.idthpel=t.idthpel INNER JOIN tbujian u ON u.idujian=ps.idujian LEFT JOIN tbruang r1 ON r1.idruang=ps.idruang LEFT JOIN tbjadwal jd ON jd.idujian=u.idujian LEFT JOIN tbsesiujian su ON su.idjadwal=jd.idjadwal AND su.idsiswa=ps.idsiswa WHERE ps.deleted='0' AND u.status='1' AND t.aktif='1' GROUP BY ps.nmpeserta ORDER BY ps.nmpeserta";
$i = 5;
$no = 0;
$qgetsesi = vquery($sql);
foreach ($qgetsesi as $s) {
	$i++;
	$no++;
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A' . $i, $no)
		->setCellValue('B' . $i, $s['nmpeserta'])
		->setCellValue('C' . $i, $s['nis'])
		->setCellValue('D' . $i, $s['nisn'])
		->setCellValue('E' . $i, ucwords(strtolower($s['nmsiswa'])))
		->setCellValue('F' . $i, $s['nmrombel'])
		->setCellValue('G' . $i, $s['nmruang']);
}
$kolAkhir = $objPHPExcel->getActiveSheet()->getHighestColumn();
$barisAkhir = $objPHPExcel->getActiveSheet()->getHighestRow();
$objPHPExcel->getActiveSheet()->getStyle('A5:' . $kolAkhir . '5')->getAlignment()->setWrapText(true);
$objPHPExcel->setActiveSheetIndex()->getStyle('A5:' . $kolAkhir . '5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$center = array();
$center['alignment'] = array();
$center['alignment']['horizontal'] = PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
$objPHPExcel->setActiveSheetIndex()->getStyle('A3:' . $kolAkhir . '5')->applyFromArray($center);

$objPHPExcel->setActiveSheetIndex()->getStyle("A6:D" . $barisAkhir)->applyFromArray($center);
$objPHPExcel->setActiveSheetIndex()->getStyle("F6:F" . $barisAkhir)->applyFromArray($center);
$objPHPExcel->setActiveSheetIndex()->getStyle("H6:" . $kolAkhir . $barisAkhir)->applyFromArray($center);


$objPHPExcel->setActiveSheetIndex()->getStyle('A1:' . $kolAkhir . $barisAkhir)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$thick = array();
$thick['borders'] = array();
$thick['borders']['allborders'] = array();
$thick['borders']['allborders']['style'] = PHPExcel_Style_Border::BORDER_THIN;
$objPHPExcel->setActiveSheetIndex()
	->getStyle('A3:' . $objPHPExcel->getActiveSheet()
		->getHighestColumn() . $objPHPExcel
		->getActiveSheet()->getHighestRow())
	->applyFromArray($thick);

$objPHPExcel->getActiveSheet()->setTitle($namafile);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $namafile . '.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
