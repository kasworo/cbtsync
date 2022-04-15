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

function GetStatistik($rb, $mp)
{
    $sqlsta = "SELECT MAX(n.nilai) as maks, MIN(n.nilai) as mins, AVG(n.nilai) as rata FROM tbpeserta ps INNER JOIN tbujian u USING(idujian) INNER JOIN tbnilai n using(idsiswa,idujian) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) WHERE rs.idrombel='$rb' AND n.idmapel='$mp' GROUP BY n.idmapel, rs.idrombel";
    return vquery($sqlsta)[0];
}

class PDF extends FPDF
{
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
        $this->SetFont('Arial', '', '9');
        $this->SetY(-1.675, 5);
        $this->Rect(1, 28.0, 0.65, 0.75);
        $this->Rect(2, 28.0, 17.0, 0.75);
        $this->Rect(19.25, 28.0, 0.65, 0.75);
        $this->Cell(19, 0.65, $nmtmp, 0, 0, 'C');
        $this->Cell(0.65, 0.65, '', 0, 0, 'C');
    }

    function Judul($data)
    {
        $sk = getDataSkul();
        $this->SetFont('Arial', '', '10');
        $this->Cell(3.25, 0.5, 'Nama Sekolah', 0, 0, 'L');
        $this->Cell(0.25, 0.5, ':', 0, 0, 'L');
        $this->Cell(9.25, 0.5, $sk['nama'], 0, 0, 'L');
        $this->Cell(1, 0.5, '', 0, 0, 'L');
        $this->Cell(2.75, 0.5, 'Kelas', 0, 0, 'L');
        $this->Cell(0.25, 0.5, ':', 0, 0, 'L');
        $this->Cell(5, 0.5, $data['rombel'], 0, 0, 'L');
        $this->Ln(0.5);
        $this->Cell(3.25, 0.5, 'Alamat Sekolah', 0, 0, 'L');
        $this->Cell(0.25, 0.5, ':', 0, 0, 'L');
        $this->Cell(9.25, 0.5, $sk['alamat'] . ', ' . $sk['desa'], 0, 0, 'L');
        $this->Cell(1, 0.5, '', 0, 0, 'L');
        $th = viewdata('tbthpel', array('idthpel' => $data['tahun']))[0];
        $thpel = substr($th['desthpel'], 0, 9);
        $sem = substr($th['nmthpel'], -1);
        if ($sem == '1') {
            $semester = 'I (Satu)';
        } else {
            $semester = 'II (Dua)';
        }

        $this->Cell(2.75, 0.5, 'Semester', 0, 0, 'L');
        $this->Cell(0.25, 0.5, ':', 0, 0, 'L');
        $this->Cell(5, 0.5, $semester, 0, 0, 'L');
        $this->Ln(0.5);
        $this->Cell(3.25, 0.5, 'Nama Peserta Didik', 0, 0, 'L');
        $this->Cell(0.25, 0.5, ':', 0, 0, 'L');
        $this->Cell(9.25, 0.5, strtoupper($data['nama']), 0, 0, 'L');
        $this->Cell(1, 0.5, '', 0, 0, 'L');
        $this->Cell(2.75, 0.5, 'Tahun Pelajaran', 0, 0, 'L');
        $this->Cell(0.25, 0.5, ':', 0, 0, 'L');
        $this->Cell(5, 0.5, $thpel, 0, 0, 'L');
        $this->Ln(0.5);
        $this->Cell(3.25, 0.5, 'No. Induk / N I S N', 0, 0, 'L');
        $this->Cell(0.25, 0.5, ':', 0, 0, 'L');
        $this->Cell(9.25, 0.5, $data['nis'] . ' / ' . $data['nisn'], 0, 0, 'L');
        $this->Ln(0.5);
        $this->SetLineWidth(0.035);
        $this->Line(1, 3.5, 20, 3.5);
        $this->Ln();
        $iduji = $data['ujian'];
        $squji = "SELECT nmtes FROM tbujian u INNER JOIN tbtes t USING(idtes) WHERE idujian='$iduji'";
        $uji = vquery($squji)[0];
        $nmuji = strtoupper($uji['nmtes']);
        $this->SetFont('Arial', 'B', '11');
        $this->Cell(19.0, 0.675, 'LAPORAN HASIL ' . $nmuji, 0, 0, 'C');
        $this->Ln(1.0);
    }

    function GetJudulKolom()
    {

        $this->SetFont('Arial', '', '9');
        $this->SetLineWidth(0.025);
        $this->Cell(1, 1.35, 'No.', 'LTB', 0, 'C');
        $this->Cell(8.5, 1.35, 'Mata Pelajaran', 'LTB', 0, 'C');
        $this->Cell(5.4, 0.675, 'Statistik Hasil Tes Kelas Ini', 'LTB', 0, 'C');
        $this->Cell(4.3, 0.675, 'Hasil Tes', 'LTBR', 0, 'C');;
        $this->Ln(0.675);
        $this->Cell(9.5, 0.675);
        $this->Cell(1.8, 0.675, 'Minimum', 'LB', 0, 'C');
        $this->Cell(1.8, 0.675, 'Rerata', 'LB', 0, 'C');
        $this->Cell(1.8, 0.675, 'Maksimum', 'LB', 0, 'C');
        $this->Cell(1.0, 0.675, 'KKM', 'LB', 0, 'C');
        $this->Cell(1.25, 0.675, 'Nilai', 'LB', 0, 'C');
        $this->Cell(2.05, 0.675, 'Ketercapaian', 'LBR', 0, 'C');
        $this->Ln();
        $this->Image('assets/img/tandaair.png', 5.75, 7.675, 9.765);
    }

    function IsiData($rb, $id, $u)
    {

        $qmapel = "SELECT mp.nmmapel, kkm.kkm, n.idujian, n.nilai, rb.idrombel, mp.idmapel FROM tbpeserta ps INNER JOIN tbujian u USING(idujian) INNER JOIN tbnilai n using(idsiswa,idujian) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel)  INNER JOIN tbkkm kkm USING(idmapel, idkelas) WHERE ps.idsiswa='$id' AND rb.idrombel='$rb' ORDER BY mp.idmapel";
        $qmp = vquery($qmapel);
        $i = 0;
        foreach ($qmp as $mp) {
            $i++;
            $sta = GetStatistik($rb, $mp['idmapel']);
            $nilai = $mp['nilai'];
            if ($nilai > $mp['kkm']) {
                $pred = 'Terlampaui';
            } else if ($nilai == $mp['kkm']) {
                $pred = 'Tercapai';
            } else {
                $pred = '-';
            }
            $this->Cell(1, 0.675, $i . '.', 'LB', 0, 'C');
            $this->Cell(8.5, 0.675, $mp['nmmapel'], 'LB', 0, 'L');
            $this->Cell(1.8, 0.675, number_format($sta['mins'], 2, ',', '.'), 'LB', 0, 'C');
            $this->Cell(1.8, 0.675, number_format($sta['rata'], 2, ',', '.'), 'LB', 0, 'C');
            $this->Cell(1.8, 0.675, number_format($sta['maks'], 2, ',', '.'), 'LB', 0, 'C');
            $this->Cell(1.0, 0.675, $mp['kkm'], 'LB', 0, 'C');
            $this->Cell(1.25, 0.675, number_format($nilai, 2, ',', '.'), 'LB', 0, 'C');
            $this->Cell(2.05, 0.675, $pred, 'LBR', 0, 'C');
            $this->Ln();
        }

        $skul = getDataSkul();
        $this->Ln(0.25);
        $this->SetFont('Arial', 'B', '10');
        $this->Cell(19.25, 0.675, "CATATAN WALI KELAS", 'LBTR', 0, 'C');
        $this->Ln();
        $this->SetFont('Arial', '', '10');
        $squji = "SELECT nmtes FROM tbujian u INNER JOIN tbtes t USING(idtes) WHERE idujian='$u'";
        $uji = vquery($squji)[0];
        $this->MultiCell(19.25, 0.675, ' Laporan hasil tes ini merupakan nilai murni pada ' . ucwords(strtolower($uji['nmtes'])), 'LR', 'L');
        $this->MultiCell(19.25, 0.675, ' Mohon kerjasama Bapak/Ibu untuk memberikan dorongan kepada peserta didik agar lebih giat dalam belajar', 'LBR', 'L');
        $this->Ln(0.25);
        $this->SetFont('Arial', 'B', '10');
        $this->Cell(19.25, 0.675, "TANGGAPAN ORANG TUA / WALI PESERTA DIDIK", 'LBTR', 0, 'C');
        $this->Ln();
        $this->Cell(19.25, 1.5, '', 'LBR', 0, 'C');
        $this->Ln(1.75);
        $this->SetFont('Arial', '', '10');
        $trb = viewdata('tbsetrapor', array('idujian' => $u))[0];
        $this->Cell(12.0, 0.675);
        $this->Cell(2.5, 0.675, 'Diberikan di');
        $this->Cell(0.5, 0.675, ':');
        $this->Cell(4.0, 0.675, $trb['tmpterbit']);
        $this->Ln();
        $this->Cell(12.0, 0.675);
        $this->Cell(2.5, 0.675, 'Tanggal');
        $this->Cell(0.5, 0.675, ':');
        $this->Cell(4.0, 0.675, indonesian_date($trb['tglterbit']));
        $this->Ln(0.5);
        $this->Cell(1.0, 0.5);
        $this->Cell(8.0, 0.5, 'Mengetahui:', 0, 0, 'C');
        $this->Cell(2.0, 0.5);
        $this->Cell(8.0, 0.5);
        $this->Ln();
        $this->Cell(1.0, 0.5);
        $this->Cell(8.0, 0.5, 'Kepala Sekolah,', 0, 0, 'C');
        $this->Cell(2.0, 0.5);
        $this->Cell(8.0, 0.5, 'Wali Kelas,', 0, 0, 'C');
        $this->Ln(1.5);
        $this->Cell(1.0, 0.5);
        $qkepsek = "SELECT g.nama, g.ttd, g.nip FROM tbgtk g WHERE jbtdinas='1'";
        $kep = vquery($qkepsek)[0];
        $this->Cell(8.0, 0.5, $kep['nama'], 0, 0, 'C');
        $this->Cell(2.0, 0.5);
        $qwalas = "SELECT g.nama, g.ttd, g.nip FROM tbrombel r INNER JOIN tbgtk g USING(idgtk) WHERE r.idrombel='$rb'";
        $wl = vquery($qwalas)[0];
        $this->Cell(8.0, 0.5, $wl['nama'], 0, 0, 'C');
        $this->Ln();
        $this->Cell(1.0, 0.5);
        $this->Cell(8.0, 0.5, $kep['nip'], 0, 0, 'C');
        $this->Cell(2.0, 0.5);
        if ($wl['nip'] == 'Non PNS' || $wl['nip'] == '') {
            $this->Cell(8.0, 0.5, '', 0, 0, 'C');
        } else {
            $this->Cell(8.0, 0.5, 'NIP. ' . $wl['nip'], 0, 0, 'C');
        }
        $y0 = $i * 0.675 + 13.0;
        $this->Image("gambar/" . $kep['ttd'], 4.5, $y0, 2.5);
        $this->Image("gambar/" . $wl['ttd'], 15, $y0 + 0.15, 2.0);
        $this->Ln(1.0);
        $this->Cell(19.0, 0.5, 'Mengetahui:', 0, 0, 'C');
        $this->Ln();
        $this->Cell(19.0, 0.5, 'Orang Tua / Wali,', 0, 0, 'C');
        $this->Ln(1.5);
        $this->Cell(19.0, 0.5, '_________________________', 0, 0, 'C');
    }

    function IsiQrCode($s, $u)
    {
        $sql = "SELECT nisn FROM tbpeserta WHERE idsiswa='$s'";
        $ds = vquery($sql)[0];
        $nisn = $ds['nisn'];
        QRcode::png($_SERVER['HTTP_HOST'] . '/downloadrapor.php?id=' . $s . '&uji=' . $u, setclient . "qr_rp/" . $nisn . "_" . $u . ".png");
        $this->Image(setclient . "qr_rp/" . $nisn . "_" . $u . ".png", 3.25, 24.10, 2.0, 'png');
    }

    function Cetak($id)
    {
        if (isset($_GET['id'])) {
            $qrmb = "SELECT ps.idsiswa, ps.nmsiswa, ps.nisn, ps.nis, r.nmrombel, u.idujian, t.idthpel FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbujian u USING(idujian) INNER JOIN tbthpel t ON (t.idthpel=r.idthpel AND u.idthpel=t.idthpel AND u.idthpel=r.idthpel) WHERE u.status='1' AND t.aktif='1' AND ps.idsiswa='$_GET[id]'";
        } else {
            $qrmb = "SELECT ps.idsiswa, ps.nmsiswa, ps.nisn, ps.nis, r.nmrombel, u.idujian, t.idthpel FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbujian u USING(idujian) INNER JOIN tbthpel t ON (t.idthpel=r.idthpel AND u.idthpel=t.idthpel AND u.idthpel=r.idthpel) WHERE r.idrombel='$id' AND u.status='1' AND t.aktif='1'";
        }
        $ds = vquery($qrmb);
        foreach ($ds as $s) {
            $datane = array(
                'nama' => $s['nmsiswa'],
                'nis' => $s['nis'],
                'nisn' => $s['nisn'],
                'rombel' => $s['nmrombel'],
                'ujian' => $s['idujian'],
                'tahun' => $s['idthpel']
            );
            $this->AddPage();
            $this->Judul($datane);
            $this->GetJudulKolom();
            $this->IsiData($id, $s['idsiswa'], $s['idujian']);
            $this->IsiQrCode($s['idsiswa'], $s['idujian']);
        }
    }
}

$pdf = new PDF('P', 'cm', 'A4');
$pdf->SetMargins(1, 1.25, 1);
$pdf->SetAutoPageBreak('true', 2.5);
$pdf->AliasNbPages();
if (isset($_GET['id'])) {
    $sql = "SELECT rb.idrombel FROM tbrombelsiswa rs INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbthpel tp USING(idthpel) WHERE tp.aktif='1' AND rs.idsiswa='$_GET[id]'";
} else {
    $us = getuser();
    $level = $us['level'];
    if ($level == '1') {
        $sql = "SELECT rb.idrombel FROM tbrombel rb  INNER JOIN tbthpel tp USING(idthpel) WHERE tp.aktif='1'";
    } else {
        $sql = "SELECT rb.idrombel FROM tbrombel rb INNER JOIN tbthpel tp USING(idthpel) INNER JOIN tbgtk g USING(idgtk) INNER JOIN tbuser us USING(username) WHERE tp.aktif='1' AND us.username='$_COOKIE[id]'";
    }
}

$qrmb = vquery($sql);
foreach ($qrmb as $rm) {
    $pdf->Cetak($rm['idrombel']);
}
$pdf->Output();
