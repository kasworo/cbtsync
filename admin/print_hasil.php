<?php
define("BASEPATH", dirname(__FILE__));
require('../assets/library/fpdf/fpdf.php');
include "dbfunction.php";

function DataSkul()
{
	return viewdata('tbskul')[0];
}
function DataThpel()
{
	return viewdata('tbthpel', array('aktif' => '1'))[0];
}
function DataUjian()
{
	$sql = "SELECT nmtes FROM tbujian u INNER JOIN tbtes t USING(idtes) WHERE u.idujian = '$_POST[uji]'";
	return vquery($sql)[0];
}

function IsiGuru($r, $m)
{
	$sqlp = "SELECT g.nama, g.nip FROM tbpengampu pg INNER JOIN tbgtk g USING(idgtk) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbrombel rb USING(idrombel)INNER JOIN tbthpel tp USING(idthpel) WHERE tp.aktif='1' AND pg.idrombel='$r' AND pg.idmapel='$m'";
	return vquery($sqlp)[0];
}
class PDF extends FPDF
{
	function Header()
	{
		$ad = DataSkul();
		$namsek = strtoupper($ad['nmskul']);
		$logsek = $ad['logoskul'];
		$setid = DataThpel();
		$thpel = substr($setid['desthpel'], 0, 9);

		$uji = DataUjian();
		$nmuji = strtoupper($uji['nmtes']);

		if ($logsek == '') {
			$logo = '../images/tutwuri.jpg';
		} else {
			if (file_exists('../images/' . $logsek)) {
				$logo = '../images/' . $logsek;
			} else {
				$logo = '../images/tutwuri.jpg';
			}
		}

		$this->Image($logo, 1.0, 0.575, 1.35);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', '10');
		$this->Cell(1.75, 0.5, '');
		$this->Cell(16.25, 0.5, 'LAPORAN HASIL TES', 0, 0, 'C', 0);
		$this->Ln();
		$this->Cell(1.75, 0.5, '');
		$this->Cell(16.25, 0.5, strtoupper($nmuji), 0, 0, 'C', 0);
		$this->Ln();
		$this->Cell(1.75, 0.5, '');
		$this->Cell(16.25, 0.5, strtoupper($namsek), 0, 0, 'C', 0);
		$this->Ln();
		$this->Cell(1.75, 0.5, '');
		$this->Cell(16.25, 0.5, strtoupper('Tahun Pelajaran ') . $thpel, 0, 0, 'C', 0);
		$this->SetLineWidth(0.05);
		$this->Line(1.0, 2.75, 20.0, 2.75);
		$this->Ln(1.25);
		$this->SetLineWidth(0.015);
	}

	function Footer()
	{
		$sqlad0 = "SELECT a.idjenjang, j.akjenjang, a.nmskpd, a.kec, r.nmrayon, p.nmprov FROM tbskul a INNER JOIN tbjenjang j ON j.idjenjang=a.idjenjang INNER JOIN tbrayon r ON a.idrayon=r.idrayon INNER JOIN tbprov p ON r.idprov=p.idprov";
		$dt = vquery($sqlad0)[0];
		$nmjenjang = $dt['akjenjang'];
		if ($nmjenjang == 'SMA' || $nmjenjang == 'SMK') {
			$nmtmp = strtoupper($dt['nmskpd'] . ' provinsi ' . $dt['nmprov']);
		} elseif ($nmjenjang == 'SMP') {
			$nmtmp = strtoupper($dt['nmskpd'] . ' ' . $dt['nmrayon']);
		} else {
			$nmtmp = strtoupper($dt['nmskpd'] . ' kecamatan ' . $dt['kec']);
		}
		$this->SetFont('Arial', 'BI', '9');
		$this->SetY(-1.675, 5);
		$this->Rect(1, 28.0, 0.575, 0.75);
		$this->Rect(2, 28.0, 17.0, 0.75);
		$this->Rect(19.25, 28.0, 0.575, 0.75);
		$this->Cell(19, 0.575, $nmtmp, 0, 0, 'C');
		$this->Cell(0.575, 0.575, '', 0, 0, 'C');
	}

	function Judul($r, $m)
	{
		$setid = DataThpel();
		$sem = substr($setid['nmthpel'], -1);
		if ($sem == '1') {
			$semester = 'I (Satu)';
		} else {
			$semester = 'II (Genap)';
		}

		$qrmb = "SELECT nmrombel FROM tbrombel WHERE idrombel='$r'";
		$rm = vquery($qrmb)[0];
		$nmrombel = $rm['nmrombel'];

		$qmap = "SELECT nmmapel FROM tbmapel WHERE idmapel='$m'";
		$mp = vquery($qmap)[0];
		$mapel = $mp['nmmapel'];
		$this->SetFont('Arial', '', '10');
		$this->Cell(2.5, 0.575, 'Mata Pelajaran', '', 0, 'L');
		$this->Cell(0.25, 0.575, ':', 0, 0, 'C');
		$this->Cell(7, 0.575, $mapel, '', 0, 'L');
		$this->Ln(0.575);
		$this->Cell(2.5, 0.575, 'Kelas', '', 0, 'L');
		$this->Cell(0.25, 0.575, ':', 0, 0, 'C');
		$this->Cell(7, 0.575, $nmrombel, '', 0, 'L');
		$this->Ln(0.575);
		$this->Cell(2.5, 0.575, 'Semester', '', 0, 'L');
		$this->Cell(0.25, 0.575, ':', 0, 0, 'C');
		$this->Cell(7, 0.575, $semester, '', 0, 'L');
		$this->Ln(1.0);
	}

	function JudulKolom()
	{
		$this->SetFont('Arial', 'B', '10');
		$this->Cell(1, 1.3, 'No.', 'LTB', 0, 'C');
		$this->Cell(3.25, 1.3, 'No. Induk / NISN', 'LTB', 0, 'C');
		$this->Cell(7.375, 1.3, 'Nama Peserta Didik', 'LTB', 0, 'C');
		$this->Cell(4.5, 0.65, 'Hasil Tes', 'LTR', 0, 'C');
		$this->Cell(2.75, 1.3, 'Keterangan', 'LTBR', 0, 'C');
		$this->Ln(0.65);
		$this->Cell(11.625);
		$this->Cell(1.5, 0.65, 'Benar', 'LTB', 0, 'C');
		$this->Cell(1.5, 0.65, 'Salah', 'LTB', 0, 'C');
		$this->Cell(1.5, 0.65, 'Nilai', 'LTB', 0, 'C');
		$this->Ln();
	}

	function IsiData($r, $m, $u, $hal)
	{
		$this->JudulKolom($u);
		if ($hal == 1) {
			$opset = 0;
			$i = 0;
			$this->IsiNilai($i, $r, $m, $u, $opset);
		} else {
			$i = 25;
			$opset = 25;
			$this->IsiNilai($i, $r, $m, $u, $opset);
			$this->IsiKeterangan($r, $m);
		}
	}
	function IsiNilai($i, $r, $m, $u, $opset)
	{
		$qisi = "SELECT ps.nmsiswa, ps.nmpeserta, ps.nis, ps.nisn, ni.jmlsoal, ni.benar, ni.salah, ni.nilai FROM tbpeserta ps INNER JOIN tbnilai ni USING(idsiswa) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbujian u ON ni.idujian=u.idujian WHERE rs.idrombel='$r' AND ni.idmapel='$m' AND u.idujian='$u' ORDER BY ps.nmsiswa LIMIT 25 OFFSET $opset";
		//var_dump($qisi);
		$dt = vquery($qisi);
		$this->SetFont('Arial', '', '10');
		foreach ($dt as $d) {
			$i++;
			$this->Cell(1, 0.65, $i . '.', 'LB', 0, 'C');
			$this->Cell(3.25, 0.65, $d['nis'] . ' / ' . $d['nisn'], 'LB', 0, 'C');
			$this->Cell(7.375, 0.65, ucwords(strtolower($d['nmsiswa'])), 'LB', 0, 'L');
			$this->Cell(1.5, 0.65, number_format($d['benar'], 2, ',', '.'), 'LB', 0, 'C');
			$this->Cell(1.5, 0.65, number_format($d['salah'], 2, ',', '.'), 'LB', 0, 'C');
			$this->Cell(1.5, 0.65, number_format($d['nilai'], 2, ',', '.'), 'LB', 0, 'C');
			$this->Cell(2.75, 0.65, '', 'LBR', 0, 'C');
			$this->Ln();
		}
	}

	function IsiKeterangan($r, $m)
	{
		$this->SetFont('Arial', 'BI', '11');
		$this->Cell(5, 1, "Keterangan :");
		$this->Ln(0.725);
		$this->SetFont('Arial', '', '11');
		$this->Cell(17.5, 0.675, "Daftar Nilai dibuat rangkap 2 (dua), masing-masing  untuk panitia, dan guru bidang studi.");
		$this->Ln(1.25);
		$this->Cell(12, 0.575);
		$gr = IsiGuru($r, $m);
		$this->Cell(9, 0.675, "Guru Bidang Studi,", 0, 0, 'L');
		$this->Ln(0.75);
		$this->Ln(1.5);
		$this->Cell(12, 0.575);
		$this->Cell(9, 0.5, $gr['nama'], 0, 0, 'L');
		$this->Ln();
		$this->Cell(12, 0.5, "", 0, 0, 'C');
		if ($gr['nip'] !== 'Non PNS') {
			$this->Cell(9, 0.5, "NIP. " . $gr['nip']);
		}
	}

	function Cetak($r, $m, $u)
	{
		$qisi = "SELECT ps.nmsiswa, ps.nisn, ps.nis, r.nmrombel FROM tbpeserta ps INNER JOIN tbnilai ni USING(idsiswa) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) WHERE rs.idrombel='$r' AND ni.idmapel='$m' AND ni.idujian='$u'";
		$nsiswa = cquery($qisi);
		$hal = ceil($nsiswa / 25);
		for ($i = 1; $i <= $hal; $i++) {
			$this->AddPage();
			$this->Judul($r, $m);
			$this->IsiData($r, $m, $u, $i);
		}
	}
}

$pdf = new PDF('P', 'cm', 'A4');
$pdf->AliasNbPages();
$pdf->SetMargins(1.15, 0.575, 0.75);
$pdf->Cetak($_POST['rmb'], $_POST['map'], $_POST['uji']);
$pdf->Output();
