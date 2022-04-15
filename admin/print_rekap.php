<?php
define("BASEPATH", dirname(__FILE__));
require_once("assets/library/phpqrcode/qrlib.php");
require('assets/library/fpdf/fpdf.php');
include "dbfunction.php";

function getDataSkul()
{
	$qsk = "SELECT*FROM tbskul";
	$ad = vquery($qsk)[0];
	$skul = array(
		'nama' => $ad['nmskul'],
		'alamat' => $ad['alamat'],
		'desa' => $ad['desa']
	);
	return $skul;
}

function getuser()
{
	$quser = "SELECT us.username, us.level FROM tbuser us WHERE username='$_COOKIE[id]'";
	$u = vquery($quser)[0];
	$users = array(
		'user' => $u['username'],
		'level' => $u['level']
	);
	return $users;
}
function getmapel()
{
	return viewdata('tbmapel');
}

function GetStatistik($rb, $mp)
{
	$sqlsta = "SELECT MAX(n.nilai) as maks, MIN(n.nilai) as mins, AVG(n.nilai) as rata FROM tbpeserta ps INNER JOIN tbujian u USING(idujian) INNER JOIN tbnilai n using(idsiswa,idujian) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) WHERE rs.idrombel='$rb' AND n.idmapel='$mp' GROUP BY n.idmapel, rs.idrombel";
	return vquery($sqlsta)[0];
}

class PDF extends FPDF
{
	function Header()
	{
		global $conn;
		$qsk = $conn->query("SELECT*FROM tbskul");
		$ad = $qsk->fetch_array();
		$namsek = strtoupper($ad['nmskul']);
		$logsek = $ad['logoskul'];
		$quji = $conn->query("SELECT u.idthpel, u.idujian, t.nmtes, th.nmthpel, th.desthpel FROM tbujian u INNER JOIN tbtes t USING(idtes) INNER JOIN tbthpel th USING(idthpel) WHERE u.status='1'");
		$uji = $quji->fetch_array();
		$nmuji = strtoupper($uji['nmtes']);
		$thpel = substr($uji['desthpel'], 0, 9);
		if ($logsek == '') {
			$logo = 'images/tutwuri.jpg';
		} else {
			if (file_exists('images/' . $logsek)) {
				$logo = 'images/' . $logsek;
			} else {
				$logo = 'images/tutwuri.jpg';
			}
		}
		$this->Image($logo, 1.0, 0.65, 1.35);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial', 'B', '11');
		$this->Cell(1.75, 0.5, '');
		$this->Cell(29.25, 0.5, 'REKAPITULASI HASIL UJIAN', 0, 0, 'C', 0);
		$this->Ln();
		$this->SetFont('Arial', 'B', '10');
		$this->Cell(1.75, 0.5, '');
		$this->Cell(29.25, 0.5, strtoupper($nmuji), 0, 0, 'C', 0);
		$this->Ln();
		$this->Cell(1.75, 0.5, '');
		$this->Cell(29.25, 0.5, strtoupper('Tahun Pelajaran ') . $thpel, 0, 0, 'C', 0);
		$this->SetLineWidth(0.05);
		$this->Line(1.0, 2.5, 32.0, 2.5);
		$this->SetY(3.75);
		$this->SetLineWidth(0.015);
		$this->SetFont('Arial', 'B', '10');
		$this->Cell(1.0, 0.75, 'No.', 'LTB', 0, 'C');
		$this->Cell(3.5, 0.75, 'No. Induk / NISN', 'LTB', 0, 'C');
		$this->Cell(7.25, 0.75, 'Nama Peserta', 'LTB', 0, 'C');
		$qmp = getmapel();
		$i = 0;
		foreach ($qmp as $mp) {
			$i++;
			$this->Cell(1.35, 0.75, $mp['akmapel'], 'LTB', 0, 'C');
		}
		$this->Cell(1.75, 0.75, 'Jumlah', 'LTB', 0, 'C');
		$this->Cell(1.75, 0.75, 'Rerata', 'LTB', 0, 'C');
		$this->Cell(2.0, 0.75, 'Ket.', 'LTBR', 0, 'C');
		$this->Ln();
	}
	function Judul($id, $i)
	{
		if ($i == 1) {
			$qrm = "SELECT nmrombel FROM tbrombel WHERE idrombel='$id'";
			$rm = vquery($qrm)[0];
			$this->SetFont('Arial', '', '10');
			$this->SetY(3.0);
			$this->Cell(3.25, 0.5, 'Kelas', '', 0, 'L');
			$this->Cell(0.25, 0.5, ':', 0, 0, 'C');
			$this->Cell(6, 0.5, $rm['nmrombel'], '', 0, 'L');
			$this->Cell(14.5, 0.5);
			$this->Cell(3.0, 0.5, 'Semester', '', 0, 'L');
			$this->Cell(0.25, 0.5, ':', 0, 0, 'C');
			$quji = "SELECT u.idthpel, u.idujian, t.nmtes, th.nmthpel, th.desthpel FROM tbujian u INNER JOIN tbtes t USING(idtes) INNER JOIN tbthpel th USING(idthpel) WHERE u.status='1'";
			$uji = vquery($quji)[0];
			$sem = substr($uji['nmthpel'], -1);
			if ($sem == '1') {
				$semester = 'I (Satu)';
			} else {
				$semester = 'II (Genap)';
			}
			$this->Cell(3.25, 0.5, $semester, 0, 0, 'L');
			$this->Ln(0.75);
		}
	}

	function Footer()
	{
		global $conn;
		$sqlad0 = $conn->query("SELECT a.idjenjang, j.akjenjang, a.nmskpd, a.kec, r.nmrayon, p.nmprov FROM tbskul a INNER JOIN tbjenjang j ON j.idjenjang=a.idjenjang INNER JOIN tbrayon r ON a.idrayon=r.idrayon INNER JOIN tbprov p ON r.idprov=p.idprov");
		$dt = mysqli_fetch_array($sqlad0);
		$nmjenjang = $dt['akjenjang'];
		if ($nmjenjang == 'SMA' || $nmjenjang == 'SMK') {
			$nmtmp = strtoupper($dt['nmskpd'] . ' provinsi ' . $dt['nmprov']);
		} elseif ($nmjenjang == 'SMP') {
			$nmtmp = strtoupper($dt['nmskpd'] . ' ' . $dt['nmrayon']);
		} else {
			$nmtmp = strtoupper($dt['nmskpd'] . ' kecamatan ' . $dt['kec']);
		}
		$this->SetFont('Arial', '', '10');
		$this->SetY(-1.65, 5);
		$this->Rect(1, 19.86, 0.65, 0.65);
		$this->Rect(1.75, 19.86, 29.375, 0.65);
		$this->Rect(31.25, 19.86, 0.65, 0.65);
		$this->Cell(30.125, 0.65, $nmtmp, 0, 0, 'C');
		$this->Cell(0.65, 0.65, $this->PageNo(), 0, 0, 'C');
	}

	function IsiData($id, $hal, $uji)
	{

		if ($hal == 1) {
			$opset = 0;
		} else {
			$opset = 20;
		}
		$qisi = "SELECT s.idsiswa, s.nis, s.nisn, s.nmsiswa, s.nmpeserta, s.passwd, r.nmrombel, u.nmujian, u.idujian FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbujian u USING(idujian) INNER JOIN tbthpel t ON t.idthpel=r.idthpel WHERE u.status='1' AND r.idrombel='$id' AND t.aktif='1' GROUP BY s.idsiswa ORDER BY s.nmsiswa LIMIT 20 OFFSET $opset";
		$dq = vquery($qisi);
		$i = $opset;
		$this->SetY(4.5);
		foreach ($dq as $d) {
			$i++;
			$this->Cell(1.0, 0.575, $i . '.', 'LB', 0, 'C');
			$this->Cell(3.5, 0.575, $d['nis'] . ' / ' . $d['nisn'], 'LB', 0, 'C');
			$this->Cell(7.25, 0.575, ucwords(strtolower($d['nmsiswa'])), 'LB', 0, 'L');
			$qmp = getmapel();
			$j = 4;
			foreach ($qmp as $mp) {
				$j++;
				$sqnilai = "SELECT nilai FROM tbnilai n INNER JOIN tbmapel mp USING(idmapel) WHERE n.idsiswa='$d[idsiswa]' AND n.idmapel='$mp[idmapel]' AND n.idujian='$d[idujian]'";
				$cnilai = cquery($sqnilai);
				if ($cnilai > 0) {
					$nil = vquery($sqnilai)[0];
					if ($nil['nilai'] == 0 || $nil['nilai'] == '') {
						$nilai = '-';
					} else {
						$nilai = number_format($nil['nilai'], 2, ',', '.');
					}
				} else {
					$nilai = '';
				}
				$this->Cell(1.35, 0.575, $nilai, 'LB', 0, 'C');
			}

			$qstnil = "SELECT SUM(nilai) as jml, AVG(nilai) as rata FROM tbnilai n INNER JOIN  tbmapel mp USING(idmapel) INNER JOIN tbujian u ON u.idujian=n.idujian WHERE n.idsiswa='$d[idsiswa]' AND u.status='1' GROUP BY n.idsiswa, n.idujian";
			$cekstnil = cquery($qstnil);
			if ($cekstnil > 0) {
				$snil = vquery($qstnil)[0];
				if ($snil['jml'] == 0) {
					$jmlnilai = '-';
				} else {
					$jmlnilai = number_format($snil['jml'], 1, ',', '.');
				}
				if ($snil['rata'] == 0) {
					$rata = '';
				} else {
					$rata = number_format($snil['rata'], 2, ',', '.');
				}
			} else {
				$jmlnilai = '';
				$rata = '';
			}
			$this->Cell(1.75, 0.575, $jmlnilai, 'LB', 0, 'C');
			$this->Cell(1.75, 0.575, $rata, 'LB', 0, 'C');
			$this->Cell(2.0, 0.575, '', 'LBR', 0, 'C');
			$this->Ln();
		}
		if ($hal == 2) {
			$y0 = ($i - 20) * 0.575 + 4.575;
			$this->SetY($y0);
			$this->Cell(11.75, 0.575, 'Nilai Terendah', 'LTBR', 0, 'C');
			$y1 = ($i - 19) * 0.575 + 4.575;
			$this->SetY($y1);
			$this->Cell(11.75, 0.575, 'Nilai Rata-rata', 'LBR', 0, 'C');
			$y2 = ($i - 18) * 0.575 + 4.575;
			$this->SetY($y2);
			$this->Cell(11.75, 0.575, 'Nilai Tertinggi', 'LBR', 0, 'C');
			$qmp = getmapel();
			$j = 0;
			foreach ($qmp as $mp) {
				$sta = GetStatistik($id, $mp['idmapel']);
				$this->SetXY($j * 1.35 + 12.9, $y0);
				$this->Cell(1.35, 0.575, number_format($sta['mins'], 2, ',', '.'), 'BTR', 0, 'C');
				$this->SetXY($j * 1.35 + 12.9, $y1);
				$this->Cell(1.35, 0.575, number_format($sta['rata'], 2, ',', '.'), 'BR', 0, 'C');
				$this->SetXY($j * 1.35 + 12.9, $y2);
				$this->Cell(1.35, 0.575, number_format($sta['maks'], 2, ',', '.'), 'BR', 0, 'C');
				$j++;
			}
			$this->SetXY($j * 1.35 + 12.9, $y0);
			$this->Cell(5.5, 0.575, '', 'BTR', 0, 'C');
			$this->SetXY($j * 1.35 + 12.9, $y1);
			$this->Cell(5.5, 0.575, '', 'BR', 0, 'C');
			$this->SetXY($j * 1.35 + 12.9, $y2);
			$this->Cell(5.5, 0.575, '', 'BR', 0, 'C');


			$y3 = ($i - 18) * 0.575 + 5.675;
			$this->SetXY(4, $y3);
			$this->Cell(8.0, 0.5, 'Mengetahui:', 0, 0, 'C');
			$this->Cell(10.0, 0.5);
			$trb = viewdata('tbsetrapor', array('idujian' => $uji))[0];
			$this->Cell(8.0, 0.5, $trb['tmpterbit'] . ', ' . indonesian_date($trb['tglterbit']), 0, 0, 'C');
			$this->Ln();
			$this->SetX(4);
			$this->Cell(8.0, 0.5, 'Kepala Sekolah,', 0, 0, 'C');
			$this->Cell(10.0, 0.5);
			$this->Cell(8.0, 0.5, 'Wali Kelas,', 0, 0, 'C');
			$this->Ln(1.5);

			$qkepsek = "SELECT g.nama, g.ttd, g.nip FROM tbgtk g WHERE jbtdinas='1'";
			$kep = vquery($qkepsek)[0];
			$this->SetX(4);
			$this->Cell(8.0, 0.5, $kep['nama'], 0, 0, 'C');
			$this->Cell(10.0, 0.5);
			$qwalas = "SELECT g.nama, g.ttd, g.nip, r.nmrombel FROM tbrombel r INNER JOIN tbgtk g USING(idgtk) WHERE r.idrombel='$id'";
			$wl = vquery($qwalas)[0];
			$this->Cell(8.0, 0.5, $wl['nama'], 0, 0, 'C');
			$this->Ln();
			$this->SetX(4);
			$this->Cell(8.0, 0.5, 'NIP. ' . $kep['nip'], 0, 0, 'C');
			$this->Cell(10.0, 0.5);
			if ($wl['nip'] == 'Non PNS' || $wl['nip'] == '') {
				$this->Cell(8.0, 0.5, '', 0, 0, 'C');
			} else {
				$this->Cell(8.0, 0.5, 'NIP. ' . $wl['nip'], 0, 0, 'C');
			}
			$y4 = ($i - 17) * 0.575 + 5.675;
			$this->Image("gambar/" . $kep['ttd'], 6.5, $y4, 2.5);
			$this->Image("gambar/" . $wl['ttd'], 25, $y4 + 0.35, 2.25);
		}
	}

	function Cetak($id, $uji)
	{
		$qrmb = "SELECT rs.idrombel, rb.nmrombel FROM tbrombelsiswa rs INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbthpel tp USING(idthpel) WHERE tp.aktif='1' AND rs.idrombel='$id'";
		$rmb = vquery($qrmb)[0];
		$nsiswa = cquery($qrmb);
		$hal = ceil($nsiswa / 20);
		for ($i = 1; $i <= $hal; $i++) {
			$this->AddPage();
			$this->Judul($id, $i);
			$this->IsiData($id, $i, $uji);
		}
	}
}
$pdf = new PDF('L', 'cm', array(21.5, 33.0));
$pdf->AliasNbPages();
$pdf->SetMargins(1.15, 0.75, 0.75);
$pdf->SetAutoPageBreak('true', 3.75);
$us = getuser();
$level = $us['level'];
if ($level == '1') {
	$sql = "SELECT r.idrombel, r.nmrombel, u.idujian FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbujian u USING(idujian) INNER JOIN tbthpel t ON t.idthpel=r.idthpel WHERE u.status='1' AND t.aktif='1' GROUP BY r.idrombel";
} else {
	$sql = "SELECT r.idrombel, r.nmrombel, u.idujian FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbgtk g USING(idgtk) INNER JOIN tbujian u USING(idujian) INNER JOIN tbthpel t ON t.idthpel=r.idthpel INNER JOIN tbuser us USING(username) WHERE u.status='1' AND t.aktif='1' AND us.username='$_COOKIE[id]' GROUP BY r.idrombel";
}
$qrmb = vquery($sql);
foreach ($qrmb as $rm) {
	$pdf->Cetak($rm['idrombel'], $rm['idujian']);
}
$pdf->Output();
