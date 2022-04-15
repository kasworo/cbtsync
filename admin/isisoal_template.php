<?php
define("BASEPATH", dirname(__FILE__));
require_once "assets/library/PHPExcel.php";
include "dbfunction.php";
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Kasworo Wardani")
	->setLastModifiedBy("Kasworo Wardani")
	->setTitle("Office 2007 XLSX Test Document")
	->setSubject("Office 2007 XLSX Test Document")
	->setDescription("Soal Export")
	->setKeywords("office 2007 openxml php")
	->setCategory("Template");
$sql = "SELECT bs.*, m.nmmapel, k.nmkelas FROM tbbanksoal bs INNER JOIN tbmapel m USING(idmapel) INNER JOIN tbkelas k USING(idkelas) WHERE idbank='$_GET[id]'";
$b = vquery($sql)[0];
$nmbank = $b['nmbank'];
$objPHPExcel->setActiveSheetIndex(0)
	->MergeCells('A1:D1')
	->setCellValue('A1', 'TEMPLATE IMPORT SOAL')
	->setCellValue('A3', 'Kode Soal')
	->setCellValue('B3', $b['nmbank'])
	->setCellValue('A4', 'Mata Pelajaran')
	->setCellValue('B4', $b['idmapel'] . ' - ' . $b['nmmapel'])
	->setCellValue('A5', 'Kelas')
	->setCellValue('B5', $b['idkelas'] . ' - ' . $b['nmkelas']);

$qstm = "SELECT*FROM tbstimulus st INNER JOIN tbsoal so USING(idstimulus) INNER JOIN tbopsi op USING(idbutir) WHERE st.idbank='$_GET[id]' GROUP BY so.idbutir";
$i = 0;
$no = 1;
$ceksoal = cquery($qstm);
if ($ceksoal == 0) {
	while ($i < 40) {
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A' . (9 * $i + 7), 'ID Stimulus')
			->setCellValue('A' . (9 * $i + 8), 'Stimulus')
			->setCellValue('A' . (9 * $i + 9), 'Nomor Soal')
			->setCellValue('B' . (9 * $i + 9), $no++)
			->setCellValue('A' . (9 * $i + 10), 'Jenis Soal')
			->setCellValue('A' . (9 * $i + 11), 'Butir Soal')
			->setCellValue('A' . (9 * $i + 12), 'Opsi Jawaban')
			->setCellValue('A' . (9 * $i + 13), 'Opsi Alternatif')
			->setCellValue('A' . (9 * $i + 14), 'Kunci Jawaban');
		$i++;
	}
} else {
	$qst = vquery($qstm);
	foreach ($qst as $st) {
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A' . (9 * $i + 7), 'ID Stimulus')
			->setCellValue('B' . (9 * $i + 7), $st['idstimulus'])
			->setCellValue('A' . (9 * $i + 8), 'Stimulus')
			->setCellValue('B' . (9 * $i + 8), $st['stimulus'])
			->setCellValue('A' . (9 * $i + 9), 'Nomor Soal')
			->setCellValue('B' . (9 * $i + 9), $no++)
			->setCellValue('A' . (9 * $i + 10), 'Jenis Soal')
			->setCellValue('A' . (9 * $i + 11), 'Butir Soal')
			->setCellValue('A' . (9 * $i + 12), 'Opsi Jawaban')
			->setCellValue('A' . (9 * $i + 13), 'Opsi Alternatif')
			->setCellValue('A' . (9 * $i + 14), 'Kunci Jawaban');
		$i++;
	}
}
$objPHPExcel->getActiveSheet(0)->setTitle($nmbank . '_tmp');
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $nmbank . '_tmp.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
