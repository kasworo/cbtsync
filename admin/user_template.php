<?php
define("BASEPATH", dirname(__FILE__));
require_once '../assets/library/PHPExcel.php';
include "../config/konfigurasi.php";
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Kasworo Wardani")
  ->setLastModifiedBy("Kasworo Wardani")
  ->setCategory("Daftar User");
$objPHPExcel->setActiveSheetIndex(0)
  ->mergeCells('A1:N1')
  ->mergeCells('A3:A4')
  ->mergeCells('B3:B4')
  ->mergeCells('C3:C4')
  ->mergeCells('D3:E3')
  ->mergeCells('F3:F4')
  ->mergeCells('G3:G4')
  ->mergeCells('H3:H4')
  ->setCellValue('A1', 'TEMPLATE DATA USER')
  ->setCellValue('A3', 'No')
  ->setCellValue('A5', '(1)')
  ->setCellValue('B3', 'Nama Lengkap')
  ->setCellValue('B5', '(2)')
  ->setCellValue('C3', 'Tempat Dan Tanggal Lahir')
  ->setCellValue('C4', 'Tempat')
  ->setCellValue('D5', '(3)')
  ->setCellValue('D4', 'Tanggal')
  ->setCellValue('D5', '(4)')
  ->setCellValue('E3', 'Gender')
  ->setCellValue('E5', '(5)')
  ->setCellValue('F3', 'Agama')
  ->setCellValue('F5', '(6)')
  ->setCellValue('G3', 'Alamat Lengkap')
  ->setCellValue('G5', '(7)');
$semua = 0;
$no = 0;
$baris = 6;

$qsiswa = $conn->query("SELECT*FROM tbuser WHERE level='2'");
$ceksiswa = $qsiswa->num_rows;
if ($ceksiswa > 0) {
  while ($s = $qsiswa->fetch_array()) {
    $no++;
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue("A$baris", $no)->setCellValue("B$baris", ucwords(strtolower($s['nama'])))->setCellValue("C$baris", ucwords(strtolower($s['tmplahir'])))->setCellValue("D$baris", $s['tgllahir'])->setCellValue("E$baris", $s['gender'])->setCellValue("F$baris", $s['agama'])->setCellValue("G$baris", $s['alamat']);
    $baris++;
  }
} else {
  while ($no < 70) {
    $no++;
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue("A$baris", $no)->setCellValue("B$baris", '')->setCellValue("C$baris", '')->setCellValue("D$baris", '')->setCellValue("E$baris", '')->setCellValue("F$baris", '')->setCellValue("G$baris", '')->setCellValue("H$baris", '');
    $baris++;
  }
}
$semua = $baris - 1;
$objPHPExcel->getActiveSheet()->freezePane('A6');

$objPHPExcel->setActiveSheetIndex()->getStyle('A1:N5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$center = array();
$center['alignment'] = array();
$center['alignment']['horizontal'] = PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
$objPHPExcel->setActiveSheetIndex()->getStyle('A3:N5')->applyFromArray($center);

$thick = array();
$thick['borders'] = array();
$thick['borders']['allborders'] = array();
$thick['borders']['allborders']['style'] = PHPExcel_Style_Border::BORDER_THIN;
$objPHPExcel->setActiveSheetIndex()->getStyle("A3:H$semua")->applyFromArray($thick);

$objPHPExcel->getActiveSheet()->getStyle('A3:A5')->getAlignment()->setWrapText(true);

$objPHPExcel->getSheet(0)->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getSheet(0)->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getSheet(0)->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getSheet(0)->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getSheet(0)->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getSheet(0)->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getSheet(0)->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getSheet(0)->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->setTitle('tb_user');
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="tb_user.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
