<?php
  define("BASEPATH", dirname(__FILE__));
  require_once '../assets/library/PHPExcel.php';
  include "../config/konfigurasi.php";
  function getskul(){
    global $conn;
    $sql=$conn->query("SELECT idskul FROM tbskul");
    $ds=$sql->fetch_array();
    return $ds['idskul'];
  }
  $objPHPExcel = new PHPExcel();
  $objPHPExcel->getProperties()->setCreator("Kasworo Wardani")
        ->setLastModifiedBy("Kasworo Wardani")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Soal Export")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Daftar Siswa");
  $objPHPExcel->setActiveSheetIndex(0)
    ->mergeCells('A1:R1')->mergeCells('A3:A4')->mergeCells('B3:B4')->mergeCells('C3:C4')->mergeCells('D3:D4')->mergeCells('E3:E4')->mergeCells('F3:G3')->mergeCells('H3:H4')->mergeCells('I3:I4')->mergeCells('J3:J4')->mergeCells('K3:K4')->mergeCells('L3:L4')->setCellValue('A1', 'TEMPLATE DATA PESERTA DIDIK')
    ->setCellValue('A3', 'No')
    ->setCellValue('A5', '(1)')
    ->setCellValue('B3', 'Kode Sekolah')
    ->setCellValue('B5', '(2)')
    ->setCellValue('C3', 'NIS') 
    ->setCellValue('C5', '(3)') 
    ->setCellValue('D3', 'NISN')
    ->setCellValue('D5', '(4)') 
    ->setCellValue('E3', 'Nama Peserta')
    ->setCellValue('E5', '(5)')
    ->setCellValue('F3', 'Tempat Dan Tanggal Lahir')
    ->setCellValue('F4', 'Tempat')
    ->setCellValue('F5', '(6)')
    ->setCellValue('G4', 'Tanggal')
    ->setCellValue('G5', '(7)')
    ->setCellValue('H3', 'Gender')
    ->setCellValue('H5', '(8)')
    ->setCellValue('I3', 'Agama')
    ->setCellValue('I5', '(9)')
    ->setCellValue('J3', 'Alamat Peserta Didik')
    ->setCellValue('J5', '(10)');
    $semua=0;
    $no=0;
    $baris=6;
    $idskul=getskul();
    $qsiswa=$conn->query("SELECT*FROM tbpeserta WHERE idskul='$idskul' AND deleted='0'");
    $ceksiswa=$qsiswa->num_rows;
    if($ceksiswa>0){
      while($s=$qsiswa->fetch_array())
      {
          $no++;
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue("A$baris", $no)->setCellValue("B$baris",$s['idskul'])->setCellValue("C$baris",$s['nis'])->setCellValue("D$baris",$s['nisn'])->setCellValue("E$baris",ucwords(strtolower($s['nmsiswa'])))->setCellValue("F$baris",ucwords(strtolower($s['tmplahir'])))->setCellValue("G$baris",$s['tgllahir'])->setCellValue("H$baris",$s['gender'])->setCellValue("I$baris",$s['idagama'])->setCellValue("J$baris",$s['alamat']);
          $baris++;
      }
    }
    else
    {
      while($no<15)
      {
          $no++;
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue("A$baris", $no)->setCellValue("B$baris",$_COOKIE['c_skul'])->setCellValue("C$baris",'')->setCellValue("D$baris", '')->setCellValue("E$baris", '')->setCellValue("F$baris", '')->setCellValue("G$baris", '')->setCellValue("H$baris", '')->setCellValue("I$baris", '')->setCellValue("J$baris", '');
          $baris++; 
      }
      }
  $semua=$baris-1;
  $objPHPExcel->getActiveSheet()->freezePane('A6');

  $objPHPExcel->setActiveSheetIndex()->getStyle('A1:J5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

  $center = array();
  $center ['alignment']=array();
  $center ['alignment']['horizontal']=PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
  $objPHPExcel->setActiveSheetIndex()->getStyle ( 'A3:J5' )->applyFromArray ($center);
  $thick = array ();
  $thick['borders']=array();
  $thick['borders']['allborders']=array();
  $thick['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_THIN;
  $objPHPExcel->setActiveSheetIndex()->getStyle ("A3:J$semua" )->applyFromArray ($thick); 
  $objPHPExcel->getActiveSheet()->getStyle('A3:A5')->getAlignment()->setWrapText(true);
  $objPHPExcel->getSheet(0)->getColumnDimension('A')->setAutoSize(true);
  $objPHPExcel->getSheet(0)->getColumnDimension('B')->setAutoSize(true);
  $objPHPExcel->getSheet(0)->getColumnDimension('C')->setAutoSize(true);
  $objPHPExcel->getSheet(0)->getColumnDimension('D')->setAutoSize(true);
  $objPHPExcel->getSheet(0)->getColumnDimension('E')->setAutoSize(true);
  $objPHPExcel->getSheet(0)->getColumnDimension('F')->setAutoSize(true);
  $objPHPExcel->getSheet(0)->getColumnDimension('G')->setAutoSize(true);
  $objPHPExcel->getSheet(0)->getColumnDimension('H')->setAutoSize(true);
  $objPHPExcel->getSheet(0)->getColumnDimension('I')->setAutoSize(true);
  $objPHPExcel->getSheet(0)->getColumnDimension('J')->setAutoSize(true);
  $objPHPExcel->getActiveSheet()->setTitle('tb_siswa');
  $objPHPExcel->setActiveSheetIndex(0);
  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="tb_siswa.xls"');
  header('Cache-Control: max-age=0');
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
  $objWriter->save('php://output');
  exit;
?>