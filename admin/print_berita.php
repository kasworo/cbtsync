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

function GetJadwal($id)
{
	return viewdata('tbjadwal', array('idjadwal' => $id))[0];
}

function GetRuang($id)
{
	return viewdata('tbruang', array('idruang' => $id))[0];
}

function GetSesi($id)
{
	switch ($id) {
		case '1':
			$sesi = '1 (Satu)';
			break;
		case '2':
			$sesi = '2 (Dua)';
			break;
		case '3':
			$sesi = '3 (Tiga)';
			break;
		case '4':
			$sesi = '4 (Empat)';
			break;
		default:
			$sesi = '-';
			break;
	}
	return $sesi;
}

function DataUjian()
{
	$sql = "SELECT nmtes FROM tbujian u INNER JOIN tbtes t USING(idtes) WHERE u.status = '1'";
	return vquery($sql)[0];
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

		$this->Image($logo, 1.75, 1.35, 1.35);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Times', 'B', '11');
		$this->Cell(1.75, 0.575, '');
		$this->Cell(16.25, 0.575, 'BERITA ACARA PELAKSANAAN', 0, 0, 'C', 0);
		$this->Ln();
		$this->Cell(1.75, 0.575, '');
		$this->Cell(16.25, 0.575, strtoupper($nmuji), 0, 0, 'C', 0);
		$this->Ln();
		$this->Cell(1.75, 0.575, '');
		$this->Cell(16.25, 0.575, strtoupper('Tahun Pelajaran ') . $thpel, 0, 0, 'C', 0);
		$this->Ln(1.25);
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
		$this->SetFont('Times', '', '10');
		$this->SetY(-1.675, 5);
		$this->Rect(1, 28.0, 0.625, 0.75);
		$this->Rect(2, 28.0, 17.0, 0.75);
		$this->Rect(19.25, 28.0, 0.625, 0.75);
		$this->Cell(19, 0.625, $nmtmp, 0, 0, 'C');
		$this->Cell(0.625, 0.625, '', 0, 0, 'C');
	}

	function IsiData($ru, $jd, $se)
	{
		$ad = DataSkul();
		$kdsek = $ad['kdskul'];
		$namsek = $ad['nmskul'];
		$desa = $ad['desa'];

		$rg = GetRuang($ru);
		$nmruang = $rg['nmruang'];

		$jd = GetJadwal($jd);
		$tgluji = $jd['tglujian'];
		$matauji = $jd['matauji'];
		$mulai = substr($jd['mulai'], 0, 5);

		$awal = $jd['mulai'];
		$lama = $jd['durasi'];
		$jam = floor($lama / 60);
		$menit = $lama - ($jam * 60);
		$jum_menit = "+$jam hour +$menit minutes";
		$start = $tgluji . ' ' . $awal;
		$selesai = date('H:i', strtotime($jum_menit, strtotime($start)));

		$utm = $jd['susulan'];
		if ($utm == '0') $jnsuji = 'Utama';
		else $jnsuji = 'Susulan';

		$hari = date('l', strtotime($tgluji));
		switch ($hari) {
			case 'Sunday':
				$haritmp = "Minggu";
				break;
			case 'Monday':
				$haritmp = "Senin";
				break;
			case 'Tuesday':
				$haritmp = "Selasa";
				break;
			case 'Wednesday':
				$haritmp = "Rabu";
				break;
			case 'Thursday':
				$haritmp = "Kamis";
				break;
			case 'Friday':
				$haritmp = "Jumat";
				break;
			case 'Saturday':
				$haritmp = "Sabtu";
				break;
		}
		$tglasli = date('d', strtotime($tgluji));
		switch ($tglasli) {
			case '01':
				$tgltmp = terbilang(1);
				break;
			case '02':
				$tgltmp = terbilang(2);
				break;
			case '03':
				$tgltmp = terbilang(3);
				break;
			case '04':
				$tgltmp = terbilang(4);
				break;
			case '05':
				$tgltmp = terbilang(5);
				break;
			case '06':
				$tgltmp = terbilang(6);
				break;
			case '07':
				$tgltmp = terbilang(7);
				break;
			case '08':
				$tgltmp = terbilang(8);
				break;
			case '09':
				$tgltmp = terbilang(9);
				break;
			default:
				$tgltmp = terbilang($tglasli);
				break;
		}

		$bln = date('m', strtotime($tgluji));
		switch ($bln) {
			case '01':
				$blntmp = 'Januari';
				break;
			case '02':
				$blntmp = 'Februari';
				break;
			case '03':
				$blntmp = 'Maret';
				break;
			case '04':
				$blntmp = 'April';
				break;
			case '05':
				$blntmp = 'Mei';
				break;
			case '06':
				$blntmp = 'Juni';
				break;
			case '07':
				$blntmp = 'Juli';
				break;
			case '08':
				$blntmp = 'Agustus';
				break;
			case '09':
				$blntmp = 'September';
				break;
			case '10':
				$blntmp = 'Oktober';
				break;
			case '11':
				$blntmp = 'November';
				break;
			case '12':
				$blntmp = 'Desember';
				break;
		}
		$thn = date('Y', strtotime($tgluji));
		$thntmp = terbilang($thn);

		$uji = DataUjian();
		$nmuji = ucwords(strtolower($uji['nmtes']));

		$namsesi = GetSesi($se);

		$this->SetFont('Times', '', '11');
		$this->MultiCell(17.5, 0.75, "Pada hari ini " . $haritmp . " tanggal " . $tgltmp . " bulan " . $blntmp . " tahun " . $thntmp . " telah diselenggarakan " . $nmuji . " " . $jnsuji . " untuk mata pelajaran " . $matauji . " dari pukul " . $mulai . " WIB sampai dengan pukul " . $selesai . " WIB.", 0, 'J');

		$this->Cell(0.75, 0.75, "1.");
		$this->Cell(5, 0.75, "Kode Sekolah");
		$this->Cell(0.25, 0.75, ":");
		$this->Cell(11.5, 0.75, $kdsek);
		$this->Ln();
		$this->Cell(0.75, 0.75, "");
		$this->Cell(5, 0.75, "Satuan Pendidikan");
		$this->Cell(0.25, 0.75, ":");
		$this->Cell(11.5, 0.75, $namsek);
		$this->Ln();

		$this->Cell(0.75, 0.75, "");
		$this->Cell(5, 0.75, "Ruang Ujian");
		$this->Cell(0.25, 0.75, ":");
		$this->Cell(11.5, 0.75, $nmruang, "");
		$this->Ln();
		$this->Cell(0.75, 0.75, "");
		$this->Cell(5, 0.75, "Sesi Ujian");
		$this->Cell(0.25, 0.75, ":");
		$this->Cell(11.5, 0.75, $namsesi);
		$this->Ln();
		$this->Cell(0.75, 0.75, "");
		$this->Cell(5, 0.75, "Jumlah Peserta Seharusnya");
		$this->Cell(0.25, 0.75, ":");
		$this->Cell(2, 0.75, ' ................ ');
		$this->Cell(9.5, 0.75, "Siswa");;
		$this->Ln();
		$this->Cell(0.75, 0.75, "");
		$this->Cell(5, 0.75, "Jumlah Hadir");
		$this->Cell(0.25, 0.75, ":");
		$this->Cell(2, 0.75, ' ................ ');
		$this->Cell(9.5, 0.75, "Siswa");
		$this->Ln();
		$this->Cell(0.75, 0.75, "");
		$this->Cell(5, 0.75, "Jumlah Tidak Hadir");
		$this->Cell(0.25, 0.75, ":");
		$this->Cell(2, 0.75, ' ................ ');
		$this->Cell(9.5, 0.75, "Siswa");
		$this->Ln();
		$this->Cell(0.75, 0.75, "");
		$this->Cell(5, 0.75, "Nomor Yang Tidak Hadir");
		$this->Cell(0.25, 0.75, ":");
		$this->Cell(11, 0.75, ' ......................................................................................................... ');
		$this->Ln();
		$this->Cell(6, 0.75);
		$this->Cell(11, 0.75, ' ......................................................................................................... ');
		$this->Ln();
		$this->Cell(6, 0.75);
		$this->Cell(11, 0.75, ' ......................................................................................................... ');
		$this->Ln();
		$this->Cell(0.75, 0.75, "2.");
		$this->Cell(4, 0.75, "Catatan Selama Pelaksanaan ");
		$this->Ln(0.75);
		$this->Cell(0.85, 0.75, "");
		$this->Cell(16.5, 4, '', 'LTBR', 0, 'C');
		$this->Ln(4.5);
		$this->Cell(17.5, 0.75, "Demikian berita acara ini dibuat dengan sesungguhnya, untuk dapat dipergunakan sebagaimana mestinya.");
		$this->Ln(1.5);
		$this->Cell(10.75, 0.75, "");
		$this->Cell(2, 0.75, "Dibuat di", '', 0, 'L');
		$this->Cell(0.25, 0.75, ":", '', 0, 'L');
		$this->Cell(6.75, 0.75, $desa, '', 0, 'L');
		$this->Ln(0.75);

		$this->Cell(10.75, 0.75, "");
		$this->Cell(2, 0.75, "Tanggal", '', 0, 'L');
		$this->Cell(0.25, 0.75, ":");
		$this->Cell(6.75, 0.75, indonesian_date($tgluji));
		$this->Ln(0.75);

		$this->Cell(10.75, 0.75, "");
		$this->Cell(7, 0.75, "Pengawas Ujian,", '', 0, 'L');
		$this->Ln(1.8);

		$this->Cell(10.75, 0.75);
		$this->Cell(7, 0.75, '........................................................', '', 0, 'L');
		$this->Ln(0.75);
		$this->Cell(10.75, 0.75);
		$this->Cell(7, 0.75, "NIP. ", '', 0, 'L');
		$this->Ln(1.5);
		$this->SetFont('Times', 'BI', '11');
		$this->Cell(14, 0.75, "Keterangan: ", 0, 'L');
		$this->Ln(0.75);
		$this->SetFont('Times', '', '11');
		$this->Cell(14, 0.75, "Harap dibuat rangkap 2 (Dua), masing-masing untuk Guru Bidang Studi, dan Panitia", '');
		$this->Ln(0.75);
	}
	function Cetak($ru, $jd, $se)
	{
		$this->AddPage();
		$this->IsiData($ru, $jd, $se);
	}
}

$pdf = new PDF('P', 'cm', 'A4');
$pdf->AliasNbPages();
$pdf->SetMargins(1.75, 1.25, 0.75);
$saiki = date('Y-m-d');
$sql = "SELECT ru.idruang, jd.idjadwal, su.idsesi FROM tbpeserta ps INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbruang ru USING(idruang) INNER JOIN tbjadwal jd USING(idjadwal) WHERE jd.tglujian='$saiki' GROUP BY ps.idruang, jd.idjadwal, su.idsesi ORDER BY ps.idruang, jd.idjadwal";
$ds = vquery($sql);
foreach ($ds as $s) {
	$pdf->Cetak($s['idruang'], $s['idjadwal'], $s['idsesi']);
}

$pdf->Output();
