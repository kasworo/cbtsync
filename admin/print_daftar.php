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
    $sql = "SELECT nmtes FROM tbujian u INNER JOIN tbtes t USING(idtes) WHERE u.status = '1'";
    return vquery($sql)[0];
}

function GetJadwal($id)
{
    return viewdata('tbjadwal', array('idjadwal' => $id))[0];
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

        $this->Image($logo, 1.0, 0.625, 1.35);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Times', 'B', '11');
        $this->Cell(1.75, 0.5, '');
        $this->Cell(16.25, 0.5, 'DAFTAR PESERTA', 0, 0, 'C', 0);
        $this->Ln();
        $this->SetFont('Times', 'B', '10');
        $this->Cell(1.75, 0.5, '');
        $this->Cell(16.25, 0.5, strtoupper($nmuji), 0, 0, 'C', 0);
        $this->Ln();
        $this->Cell(1.75, 0.5, '');
        $this->Cell(16.25, 0.5, strtoupper('Tahun Pelajaran ') . $thpel, 0, 0, 'C', 0);
        $this->SetLineWidth(0.05);
        $this->Line(1.0, 2.5, 20.0, 2.5);
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
        $this->SetFont('Times', '', '10');
        $this->SetY(-1.675, 5);
        $this->Rect(1, 28.0, 0.625, 0.75);
        $this->Rect(2, 28.0, 17.0, 0.75);
        $this->Rect(19.25, 28.0, 0.625, 0.75);
        $this->Cell(19, 0.625, $nmtmp, 0, 0, 'C');
        $this->Cell(0.625, 0.625, '', 0, 0, 'C');
    }

    function Judul($r, $s)
    {
        $setid = DataThpel();
        $sem = substr($setid['nmthpel'], -1);
        if ($sem == '1') {
            $semester = 'I (Satu)';
        } else {
            $semester = 'II (Genap)';
        }

        $qruang = "SELECT kdruang, nmruang FROM tbruang WHERE idruang='$r'";
        $ru = vquery($qruang)[0];
        $nmruang = $ru['kdruang'] . ' (' . $ru['nmruang'] . ')';


        switch ($s) {
            case '1': {
                    $namsesi = '1 (Satu)';
                    break;
                }
            case '2': {
                    $namsesi = '2 (Dua)';
                    break;
                }
            case '3': {
                    $namsesi = '3 (Tiga)';
                    break;
                }
            case '4': {
                    $namsesi = '4 (Empat)';
                    break;
                }
            default: {
                    $namsesi = '...................................';
                    break;
                }
        }

        $this->SetFont('Times', '', '11');
        $this->Cell(2.5, 0.625, 'Sesi Ujian', '', 0, 'L');
        $this->Cell(0.25, 0.625, ':', 0, 0, 'C');
        $this->Cell(7, 0.625, $namsesi, '', 0, 'L');
        $this->Cell(2.0, 0.625);
        $this->Cell(2.5, 0.625, 'Ruang', '', 0, 'L');
        $this->Cell(0.25, 0.625, ':', 0, 0, 'C');
        $this->Cell(7, 0.625, $nmruang, '', 0, 'L');
        $this->Ln(1.0);
    }

    function JudulKolom()
    {
        $this->SetFont('Times', 'B', '11');
        $this->Cell(1, 0.85, 'No.', 'LTB', 0, 'C');
        $this->Cell(3.25, 0.85, 'Nomor Peserta', 'LTB', 0, 'C');
        $this->Cell(8.5, 0.85, 'Nama Peserta', 'LTB', 0, 'C');
        $this->Cell(1.5, 0.85, 'L/P', 'LTBR', 0, 'C');
        $this->Cell(1.5, 0.85, 'Kelas', 'LTBR', 0, 'C');
        $this->Cell(3.0, 0.85, 'Ket.', 'LTBR', 0, 'C');
        $this->Ln();
    }

    function IsiData($r, $s, $hal = '')
    {
        if (empty($hal)) {
            $i = 0;
            $opset = 0;
            $this->JudulKolom();
            $qisi = "SELECT ps.nmsiswa, ps.nmpeserta, ps.gender, r.nmrombel, su.idsesi FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t ON t.idthpel=r.idthpel INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbruang ru USING(idruang) WHERE su.idsesi='$s' AND ps.idruang='$r' AND t.aktif='1' GROUP BY su.idsesi, ps.nmpeserta LIMIT 25 OFFSET $opset";
            $dt = vquery($qisi);
            $this->SetFont('Times', '', '11');
            foreach ($dt as $d) {
                $i++;
                $this->Cell(1, 0.675, $i . '.', 'LB', 0, 'C');
                $this->Cell(3.25, 0.675, substr($d['nmpeserta'], 3, 2) . '-' . substr($d['nmpeserta'], 5, 4) . '-' . substr($d['nmpeserta'], 9, 4) . '-' . substr($d['nmpeserta'], -1), 'LB', 0, 'C');
                $this->Cell(8.5, 0.675, ucwords(strtolower($d['nmsiswa'])), 'LB', 0, 'L');
                $this->Cell(1.5, 0.675, $d['gender'], 'LB', 0, 'C');
                $this->Cell(1.5, 0.675, $d['nmrombel'], 'LB', 0, 'C');
                $this->Cell(3.0, 0.675, '', 'LBR', 0, 'L');
                $this->Ln();
            }
            $this->IsiKeterangan($r);
        } else {
            if ($hal == 1) {
                $opset = 0;
                $i = 0;
                $this->JudulKolom();
                $qisi = "SELECT ps.nmsiswa, ps.nmpeserta, ps.passwd, r.nmrombel, su.idsesi FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t ON t.idthpel=r.idthpel INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbruang ru USING(idruang) WHERE su.idsesi='$s' AND ps.idruang='$r' AND t.aktif='1' GROUP BY su.idsesi, ps.nmpeserta LIMIT 25 OFFSET $opset";
                $dt = vquery($qisi);
                foreach ($dt as $d) {
                    $i++;
                    $this->Cell(1, 0.675, $i . '.', 'LB', 0, 'C');
                    $this->Cell(3.25, 0.675, substr($d['nmpeserta'], 3, 2) . '-' . substr($d['nmpeserta'], 5, 4) . '-' . substr($d['nmpeserta'], 9, 4) . '-' . substr($d['nmpeserta'], -1), 'LB', 0, 'C');
                    $this->Cell(8.5, 0.675, ucwords(strtolower($d['nmsiswa'])), 'LB', 0, 'L');
                    $this->Cell(1.5, 0.675, $d['gender'], 'LB', 0, 'C');
                    $this->Cell(1.5, 0.675, $d['nmrombel'], 'LB', 0, 'C');
                    $this->Cell(3.0, 0.675, '', 'LBR', 0, 'L');
                    $this->Ln();
                }
            } else {
                $i = 25;
                $opset = 25;
                $this->JudulKolom();
                $qisi = "SELECT ps.nmsiswa, ps.nmpeserta, ps.passwd, r.nmrombel, su.idsesi FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t ON t.idthpel=r.idthpel INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbruang ru USING(idruang) WHERE su.idsesi='$s' AND ps.idruang='$r' AND t.aktif='1' GROUP BY su.idsesi, ps.nmpeserta LIMIT 25 OFFSET $opset";
                $dt = vquery($qisi);
                $this->SetFont('Times', '', '11');
                foreach ($dt as $d) {
                    $i++;
                    $this->Cell(1, 0.675, $i . '.', 'LB', 0, 'C');
                    $this->Cell(3.25, 0.675, substr($d['nmpeserta'], 3, 2) . '-' . substr($d['nmpeserta'], 5, 4) . '-' . substr($d['nmpeserta'], 9, 4) . '-' . substr($d['nmpeserta'], -1), 'LB', 0, 'C');
                    $this->Cell(8.5, 0.675, ucwords(strtolower($d['nmsiswa'])), 'LB', 0, 'L');
                    $this->Cell(1.5, 0.675, $d['gender'], 'LB', 0, 'C');
                    $this->Cell(1.5, 0.675, $d['nmrombel'], 'LB', 0, 'C');
                    $this->Cell(3.0, 0.675, '', 'LBR', 0, 'L');
                    $this->Ln();
                }
                $this->IsiKeterangan($r);
            }
        }
    }

    function IsiKeterangan($r)
    {
        $qpst = "SELECT SUM(CASE WHEN ps.gender = 'L' THEN 1 ELSE 0 END) as laki, SUM(CASE WHEN ps.gender = 'P' THEN 1 ELSE 0 END) as prmp, COUNT(*) as semua FROM tbpeserta ps WHERE ps.idruang='$r'";
        $ps = vquery($qpst)[0];
        $this->SetFont('Times', 'BI', '11');
        $this->Cell(5, 1, "Keterangan :", 0, 0, 'L');
        $this->Ln();
        $this->SetFont('Times', '', '11');
        $this->Cell(7, 0.675, " Jumlah Laki-laki", 'LT', 0, 'L');
        $this->Cell(3, 0.675, " : " . $ps['laki'] . " orang", 'TR', 0, 'L');
        $this->Ln();
        $this->Cell(7, 0.675, " Jumlah Perempuan", 'L', 0, 'L');
        $this->Cell(3, 0.675, " : " . $ps['prmp'] . " orang", 'R', 0, 'L');
        $this->Ln();
        $this->Cell(7, 0.675, " Jumlah Seluruhnya", 'LB', 0, 'L');
        $this->Cell(3, 0.675,  " : " . $ps['semua'] . " orang", 'BR', 0, 'L');
        $this->Ln();
    }

    function Cetak($r, $s)
    {
        $qisi = "SELECT ps.nmsiswa, ps.nmpeserta, ps.passwd, r.nmrombel, su.idsesi FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t ON t.idthpel=r.idthpel INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbruang ru USING(idruang) WHERE su.idsesi='$s' AND ps.idruang='$r' AND t.aktif='1' GROUP BY su.idsesi, ps.nmpeserta";
        $nsiswa = cquery($qisi);
        if ($nsiswa > 25) {
            $hal = ceil($nsiswa / 25);
            for ($i = 1; $i <= $hal; $i++) {
                $this->AddPage();
                $this->Judul($r, $s);
                $this->IsiData($r, $s, $i);
            }
        } else {
            $this->AddPage();
            $this->Judul($r, $s);
            $this->IsiData($r, $s);
        }
    }
}

$pdf = new PDF('P', 'cm', 'A4');
$pdf->AliasNbPages();
$pdf->SetMargins(1.15, 0.625, 0.75);
$saiki = date('Y-m-d');
$sql = "SELECT ru.idruang, su.idsesi FROM tbpeserta ps INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbruang ru USING(idruang)  GROUP BY ps.idruang, su.idsesi ORDER BY ps.idruang, su.idsesi";
$ds = vquery($sql);
foreach ($ds as $s) {
    $pdf->Cetak($s['idruang'], $s['idsesi']);
}
$pdf->Output();
