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
		$this->SetY(0.75);
		$this->y0 = $this->GetY();
		$this->x0 = $this->GetX();
	}


	function SetCol($col)
	{
		$this->col = $col;
		$x = 1.5 + $col * 9.5;
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
		$this->SetFont('Times', 'B', '11');
		$this->Cell(1.0, 0.575, '', 'LT', 0, 'C');
		$this->Cell(7.5, 0.575, 'KARTU TANDA PESERTA', 'TR', 0, 'C');
		$this->Ln(0.575);
		$this->Image($logo, $this->GetX() + 0.25, $this->GetY() - 0.425, 1.25);
		$this->Cell(1.0, 0.575, '', 'L', 0, 'C');
		$this->Cell(7.5, 0.575, strtoupper($nmuji), 'R', 0, 'C');
		$this->Ln(0.575);
		$this->Cell(1.0, 0.575, '', 'LB', 0, 'C');
		$this->Cell(7.5, 0.575, 'TAHUN PELAJARAN ' . $thpel, 'BR', 0, 'C');
		$this->Ln(0.625);
		$sql = "SELECT ps.fotosiswa, ps.nmsiswa, ps.nis, ps.nisn, ps.tgllahir, ps.tmplahir, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian, r1.kdruang, r1.nmruang FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel)  INNER JOIN tbujian u USING(idujian) INNER JOIN tbruang r1 USING(idruang) INNER JOIN tbthpel tp ON tp.idthpel=r.idthpel WHERE u.status='1' AND tp.aktif='1' AND ps.idsiswa='$id'";
		$ds = vquery($sql)[0];
		$this->SetFont('Arial', 'I', '8');
		$this->Cell(0.25, 0.475, '', 'LT');
		$this->Cell(5.0, 0.475, 'Username / Password', 'T');
		$this->Cell(3.25, 0.475, 'Ruang Ujian', 'TR');
		$this->Ln(0.375);
		$this->SetFont('Arial', 'B', '10');
		$this->Cell(0.25, 0.475, '', 'L');
		$this->Cell(5.0, 0.475, $ds['nmpeserta'] . ' (' . $ds['passwd'] . ')');
		$this->Cell(3.25, 0.475,  $ds['kdruang'] . ' - ' . $ds['nmruang'], 'R');
		$this->Ln(0.475);
		$this->SetFont('Arial', 'I', '8');
		$this->Cell(0.25, 0.475, '', 'L');
		$this->Cell(8.25, 0.475, 'Nama Peserta', 'R');
		$this->Ln(0.375);
		$this->SetFont('Arial', 'B', '10');
		$this->Cell(0.25, 0.475, '', 'L');
		$this->Cell(8.25, 0.475, ucwords(strtolower($ds['nmsiswa'])), 'R');
		$this->Ln(0.475);
		$this->SetFont('Arial', 'I', '8');
		$this->Cell(0.25, 0.475, '', 'L');
		$this->Cell(8.25, 0.475, 'Tempat / Tanggal Lahir', 'R');
		$this->Ln(0.375);
		$this->SetFont('Arial', 'B', '10');
		$this->Cell(0.25, 0.475, '', 'L');
		$this->Cell(8.25, 0.475, ucwords(strtolower($ds['tmplahir'])) . ', ' . date('d-m-Y', strtotime($ds['tgllahir'])), 'R');
		$this->Ln(0.475);
		$this->SetFont('Arial', 'I', '8');
		$this->Cell(0.25, 0.475, '', 'L');
		$this->Cell(5.0, 0.475, 'Nomor Induk / N I S N');
		$this->Cell(3.25, 0.475, 'Kelas / Semester', 'R');
		$this->Ln(0.375);
		$this->SetFont('Arial', 'B', '10');
		$this->Cell(0.25, 0.475, '', 'L');
		$this->Cell(5.0, 0.475, $ds['nis'] . ' - ' . $ds['nisn']);
		$this->Cell(3.25, 0.475, $ds['nmrombel'] . ' / ' . $semester, 'R');
		$this->Ln(0.475);
		$this->Image('../assets/img/kotakfoto.png', $this->GetX() + 0.375, $this->GetY() + 0.375, 2.2);
		$this->SetFont('Arial', '', '10');
		$this->Cell(8.5, 0.475, '', 'LR');
		$this->Ln(0.475);
		$this->Cell(0.25, 0.475, '', 'L');
		$this->Cell(2.75, 0.475);
		$this->Cell(5.5, 0.475, 'Mulia Bhakti, 23 April 2022', 'R');
		$this->Ln(0.475);
		$this->Cell(0.25, 0.475, '', 'L');
		$this->Cell(2.75, 0.475);
		$this->Cell(5.5, 0.475, 'Kepala Sekolah,', 'R');
		$this->Ln(0.475);
		$this->Cell(0.25, 0.475, '', 'L');
		$this->Cell(2.75, 0.475);
		$this->Cell(5.5, 0.475, '', 'R');
		$this->Ln(0.475);
		$this->Cell(0.25, 0.475, '', 'L');
		$this->Cell(2.75, 0.475);
		$this->Cell(5.5, 0.475, '', 'R');
		$this->Ln(0.475);
		$qkepsek = "SELECT g.nama, g.ttd, g.nip FROM tbgtk g WHERE jbtdinas='1'";
		$kep = vquery($qkepsek)[0];
		$this->Cell(0.25, 0.475, '', 'L');
		$this->Cell(2.75, 0.475);
		$this->Cell(5.5, 0.475, $kep['nama'], 'R');
		$this->Ln(0.475);
		$this->Cell(0.25, 0.475, '', 'L');
		$this->Cell(2.75, 0.475);
		$this->Cell(5.5, 0.475, 'NIP. ' . $kep['nip'], 'R');
		$this->Image("gambar/" . $kep['ttd'], $this->GetX() - 5.5, $this->GetY() - 1.5, 2.2);
		$this->Ln(0.275);
		$this->Cell(8.5, 0.475, '', 'LBR');
		$this->Ln(1);
	}
}
$pdf = new PDF('P', 'cm', 'A4');
$pdf->AliasNbPages();
$pdf->SetMargins(1.5, 0.75, 0.75);
$pdf->SetAutoPageBreak(true, 1.25);
$du = getuser();
if ($du['level'] == '1') {
	$sql = "SELECT ps.idsiswa, ps.fotosiswa, ps.nmsiswa, ps.nis, ps.nisn, ps.tgllahir, ps.tmplahir, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian, r1.nmruang FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel)  INNER JOIN tbujian u USING(idujian) INNER JOIN tbruang r1 USING(idruang) INNER JOIN tbthpel tp ON tp.idthpel=r.idthpel WHERE u.status='1' AND tp.aktif='1' GROUP BY ps.idsiswa ORDER BY rs.idrombel DESC, ps.nmpeserta";
} else {
	$sql = "SELECT ps.idsiswa, ps.fotosiswa, ps.nmsiswa, ps.nis, ps.nisn, ps.tgllahir, ps.tmplahir, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian, r1.nmruang FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbpengampu pg USING(idrombel) INNER JOIN tbgtk g ON pg.idgtk=g.idgtk INNER JOIN tbuser us USING(username) INNER JOIN tbujian u USING(idujian) INNER JOIN tbruang r1 USING(idruang) INNER JOIN tbthpel tp ON tp.idthpel=r.idthpel WHERE u.status='1' AND tp.aktif='1' AND us.username='$_COOKIE[id]' GROUP BY ps.idsiswa ORDER BY rs.idrombel, ps.nmpeserta";
}
$ds = vquery($sql);
$pdf->AddPage();
foreach ($ds as $d) {
	$pdf->IsiKartu($d['idsiswa']);
}
$pdf->Output();
