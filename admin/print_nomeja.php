<?php
session_start();
if (!isset($_SESSION['login'])) {
	header("Location: login.php");
	exit;
}
define("BASEPATH", dirname(__FILE__));
require('../assets/library/fpdf/fpdf.php');
include "dbfunction.php";
function getuser()
{
	return viewdata('tbuser', array('username' => $_COOKIE['id']))[0];
}

function getdataskul()
{
	return viewdata('tbskul')[0];
}

function getujian()
{
	$quji = "SELECT idujian, nmthpel, desthpel, nmtes FROM tbujian u INNER JOIN tbtes t USING(idtes) INNER JOIN tbthpel tp USING(idthpel) WHERE u.status='1' AND tp.aktif='1'";
	return vquery($quji)[0];
}


class PDF extends FPDF
{
	protected $col = 0;
	protected $y0;

	function Header()
	{
		$this->SetY(1.25);
		$this->y0 = $this->GetY();
		$this->x0 = $this->GetX();
	}


	function SetCol($col)
	{
		$this->col = $col;
		$x = 0.75 + $col * 10.0;
		$this->SetLeftMargin($x);
		$this->SetX($x);
	}

	function AcceptPageBreak()
	{
		if ($this->col < 1) {
			$this->SetCol($this->col + 1);
			$this->SetY($this->y0);
			return false;
		} else {
			$this->SetCol(0);
			return true;
		}
	}

	function IsiKartu($id)
	{
		$sk = getdataskul();
		$logsek = $sk['logoskul'];
		if ($logsek == '') {
			$logo = '../images/tutwuri.jpg';
		} else {
			if (file_exists('../images/' . $logsek)) {
				$logo = '../images/' . $logsek;
			} else {
				$logo = '../images/tutwuri.jpg';
			}
		}
		$uji = getujian();
		$semester = (substr($uji['nmthpel'], -1) == '1') ? "I (Satu)" : "II (Dua)";
		$nmuji = strtoupper($uji['nmtes']);
		$thpel = substr($uji['desthpel'], 0, 9);
		$this->SetFont('Times', 'B', '10');
		$this->Cell(1.0, 0.55, '', 'LT', 0, 'C');
		$this->Cell(8.6, 0.55, 'PANITIA PENYELENGGARA', 'TR', 0, 'C');
		$this->Ln();
		$this->Image($logo, $this->GetX() + 0.25, $this->GetY() - 0.45, 1.25);
		$this->Cell(1.0, 0.55, '', 'L', 0, 'C');
		$this->Cell(8.6, 0.55, strtoupper($nmuji), 'R', 0, 'C');
		$this->Ln();
		$this->Cell(1.0, 0.55, '', 'LB', 0, 'C');
		$this->Cell(8.6, 0.55, 'TAHUN PELAJARAN ' . $thpel, 'BR', 0, 'C');
		$this->Ln(0.60);
		$sql = "SELECT ps.fotosiswa, ps.nmsiswa, ps.nis, ps.nisn, ps.tgllahir, ps.tmplahir, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian, r1.kdruang, r1.nmruang FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel)  INNER JOIN tbujian u USING(idujian) INNER JOIN tbruang r1 USING(idruang) INNER JOIN tbthpel tp ON tp.idthpel=r.idthpel WHERE u.status='1' AND tp.aktif='1' AND ps.idsiswa='$id'";
		$ds = vquery($sql)[0];
		$this->SetFont('Arial', 'B', '10');
		$this->Cell(0.25, 0.70, '', 'LT');
		$this->Cell(2.375, 0.70, '', 'T');
		$this->Cell(6.975, 0.70, substr($ds['nmpeserta'], 3, 2) . '-' . substr($ds['nmpeserta'], 5, 4) . '-' . substr($ds['nmpeserta'], 9, 4) . '-' . substr($ds['nmpeserta'], -1), 'TR');
		$this->Ln();
		$this->Image('../assets/img/kotakfoto.png', $this->GetX() + 0.25, $this->GetY() - 0.375, 2.2);
		$this->SetFont('Arial', 'B', '9');
		$this->Cell(0.25, 0.60, '', 'L');
		$this->Cell(2.375, 0.60);
		$this->Cell(6.975, 0.60,  ucwords(strtolower($ds['nmsiswa'])), 'R');
		$this->Ln();
		$this->SetFont('Arial', '', '10');
		$this->Cell(0.25, 0.60, '', 'L');
		$this->Cell(2.375, 0.60);
		$this->Cell(6.975, 0.60, ucwords(strtolower($ds['tmplahir'])) . ', ' . date('d-m-Y', strtotime($ds['tgllahir'])), 'R');
		$this->Ln();
		$this->Cell(0.25, 0.60, '', 'L');
		$this->Cell(2.375, 0.60);
		$this->Cell(6.975, 0.60,  $ds['nis'] . ' - ' . $ds['nisn'], 'R');
		$this->Ln();
		$this->Cell(0.25, 0.60, '', 'L');
		$this->Cell(2.375, 0.60);
		$this->Cell(6.975, 0.60,  $ds['nmrombel'] . ' / ' . $semester, 'R');
		$this->Ln();
		$this->Cell(0.25, 0.60, '', 'L');
		$this->Cell(2.375, 0.60, '', '');
		$this->Cell(6.975, 0.60, $ds['kdruang'] . ' - ' . $ds['nmruang'], 'R');
		$this->Ln();
		$this->Cell(9.6, 0.60, '', 'LBR');
		$this->Ln(1.15);
	}
}
$pdf = new PDF('P', 'cm', 'A4');
$pdf->AliasNbPages();
$pdf->SetMargins(0.75, 1.25, 0.75);
$pdf->SetAutoPageBreak(true, 2.65);
$du = getuser();
if ($du['level'] == '1') {
	$sql = "SELECT ps.idsiswa, ps.fotosiswa, ps.nmsiswa, ps.nis, ps.nisn, ps.tgllahir, ps.tmplahir, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian, r1.nmruang FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel)  INNER JOIN tbujian u USING(idujian) INNER JOIN tbruang r1 USING(idruang) INNER JOIN tbthpel tp ON tp.idthpel=r.idthpel WHERE u.status='1' AND tp.aktif='1' GROUP BY ps.idsiswa ORDER BY ps.nmpeserta";
} else {
	$sql = "SELECT ps.idsiswa, ps.fotosiswa, ps.nmsiswa, ps.nis, ps.nisn, ps.tgllahir, ps.tmplahir, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian, r1.nmruang FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbpengampu pg USING(idrombel) INNER JOIN tbgtk g ON pg.idgtk=g.idgtk INNER JOIN tbuser us USING(username) INNER JOIN tbujian u USING(idujian) INNER JOIN tbruang r1 USING(idruang) INNER JOIN tbthpel tp ON tp.idthpel=r.idthpel WHERE u.status='1' AND tp.aktif='1' AND us.username='$_COOKIE[id]' GROUP BY ps.idsiswa ORDER BY ps.nmpeserta";
}
$ds = vquery($sql);
$pdf->AddPage();
foreach ($ds as $d) {
	$pdf->IsiKartu($d['idsiswa']);
}
$pdf->Output();
