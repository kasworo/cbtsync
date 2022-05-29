<?php
define("BASEPATH", dirname(__FILE__));
require_once("../assets/library/phpqrcode/qrlib.php");
require('../assets/library/fpdf/fpdf.php');
include "dbfunction.php";

function getDataSkul()
{
    $qsk = "SELECT*FROM tbskul";
    $ad = vquery($qsk)[0];
    $skul = array(
        'nama' => $ad['nmskul'],
        'alamat' => $ad['alamat'],
        'desa' => $ad['desa'],
        'logoskul' => $ad['logoskul'],
        'logoskpd' => $ad['logoskpd']
    );
    return $skul;
}

function GetDataUji($id)
{
    $quji = "SELECT t.nmtes, tp.desthpel FROM tbujian u INNER JOIN tbtes t USING(idtes) INNER JOIN tbthpel tp USING(idthpel) WHERE idujian='$id'";
    $du = vquery($quji)[0];
    $uji = array(
        'nama' => $du['nmtes'],
        'tahun' => $du['desthpel']
    );
    return $uji;
}

function GetDataBank($id, $bs)
{
    $sql = "SELECT bs.nmbank, rb.nmrombel, mp.nmmapel, th.nmthpel FROM tbsetingujian su INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbujian u  USING(idujian) INNER JOIN tbthpel th ON rb.idthpel=th.idthpel AND u.idthpel=th.idthpel AND u.idthpel=rb.idthpel WHERE su.idrombel='$id' AND su.idbank='$bs'";
    return vquery($sql)[0];
}

function GetNomorSoal($b, $l)
{
    $sqlso = "SELECT idbutir FROM tbsoal so INNER JOIN tbstimulus st USING(idstimulus) INNER JOIN tbbanksoal bs USING(idbank) WHERE bs.idbank='$b' LIMIT 15 OFFSET $l";
    return vquery($sqlso);
}

function CekNomorSoal($b, $l)
{
    $sqlso = "SELECT idbutir FROM tbsoal so INNER JOIN tbstimulus st USING(idstimulus) INNER JOIN tbbanksoal bs USING(idbank) WHERE bs.idbank='$b' LIMIT 15 OFFSET $l";
    return cquery($sqlso);
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

class PDF extends FPDF
{
    function Header()
    {
        $ad = getDataSkul();
        $logsek = $ad['logoskul'];
        if ($logsek == '') {
            $logo = '../images/tutwuri.jpg';
        } else {
            if (file_exists('../images/' . $logsek)) {
                $logo = '../images/' . $logsek;
            } else {
                $logo = '../images/tutwuri.jpg';
            }
        }
        $this->Image($logo, 1.0, 0.65, 1.35);
        $uji = GetDataUji($_POST['uji']);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', 'B', '11');
        $this->Cell(1.75, 0.5, '');
        $this->Cell(29.25, 0.5, 'LAPORAN ANALISIS BUTIR SOAL', 0, 0, 'C', 0);
        $this->Ln();
        $this->Cell(1.75, 0.5, '');
        $this->Cell(29.25, 0.5, strtoupper($uji['nama']), 0, 0, 'C', 0);
        $this->Ln();
        $this->Cell(1.75, 0.5, '');
        $this->Cell(29.25, 0.5, strtoupper($ad['nama']), 0, 0, 'C', 0);
        $this->Ln();
        $this->Cell(1.75, 0.5, '');
        $this->Cell(29.25, 0.5, 'TAHUN PELAJARAN ' . substr($uji['tahun'], 0, 9), 0, 0, 'C', 0);
        $this->SetLineWidth(0.05);
        $this->Line(1.0, 2.875, 32.0, 2.875);
    }

    function JudulKolom($j, $b)
    {
        $this->SetY(4.5);
        $this->SetLineWidth(0.015);
        $this->SetFont('Arial', 'B', '10');
        if ($j == 1) {
            $this->Cell(1.0, 1.0, 'No.', 'LTBR', 0, 'C');
            $this->Cell(3.5, 1.0, 'No. Induk / NISN', 'TBR', 0, 'C');
            $this->Cell(7.5, 1.0, 'Nama Peserta', 'TBR', 0, 'C');
            $this->Cell(18.75, 0.5, 'Nomor Soal', 'TBR', 0, 'C');
            $this->Ln(0.5);
            $k = 0;
            $this->SetX(13.15);
            $dso = GetNomorSoal($b, $k);
            foreach ($dso as $so) {
                $k++;
                $this->Cell(1.25, 0.50, $k, 'BR', 0, 'C');
            }
        } else {
            $k = ($j - 1) * 15;
            $ceknomor = CekNomorSoal($b, $k);
            $this->Cell(1.0, 1.0, 'No.', 'LTB', 0, 'C');
            $this->Cell(3.5, 1.0, 'No. Induk / NISN', 'TBR', 0, 'C');
            $this->Cell(7.5, 1.0, 'Nama Peserta', 'TBR', 0, 'C');
            $dso = GetNomorSoal($b, $k);
            if ($ceknomor == 15) {
                $this->SetXY(13.15, 4.5);
                $this->Cell(18.75, 0.5, 'Nomor Soal', 'TBR', 0, 'C');
                //$this->Ln(0.5);
                $this->SetXY(13.15, 5.0);
                foreach ($dso as $so) {
                    $k++;
                    $this->Cell(1.25, 0.50, $k, 'BR', 0, 'C');
                }
            } else {
                $this->SetXY(13.15, 4.5);
                $this->Cell(15, 0.5, 'Nomor Soal', 'TBR', 0, 'C');
                $this->Cell(1.25, 1.0, 'Benar', 'TBR', 0, 'C');
                $this->Cell(1.25, 1.0, 'Salah', 'TBR', 0, 'C');
                $this->Cell(1.25, 1.0, 'Nilai', 'TBR', 0, 'C');
                $this->SetXY(13.15, 5.0);
                foreach ($dso as $so) {
                    $k++;
                    $this->Cell(1.25, 0.50, $k, 'BR', 0, 'C');
                }
                $m = 45 - $k;
                for ($n = 1; $n <= $m; $n++) {
                    $this->Cell(1.25, 0.50, '', 'BR', 0, 'C');
                }
            }
        }
    }
    function Judul($id, $i, $j, $bs)
    {
        $this->JudulKolom($j, $bs);
        $du = GetDataBank($id, $bs);
        if ($i == 1) {
            $this->SetFont('Arial', '', '10');
            $this->SetY(3.25);
            $this->Cell(3.25, 0.5, 'Kelas', '', 0, 'L');
            $this->Cell(0.25, 0.5, ':', 0, 0, 'C');
            $this->Cell(6, 0.5, $du['nmrombel'], '', 0, 'L');
            $this->Cell(14.5, 0.5);
            $this->Cell(3.0, 0.5, 'Semester', '', 0, 'L');
            $this->Cell(0.25, 0.5, ':', 0, 0, 'C');
            $sem = substr($du['nmthpel'], -1);
            if ($sem == '1') {
                $semester = 'I (Satu)';
            } else {
                $semester = 'II (Genap)';
            }
            $this->Cell(3.25, 0.5, $semester, 0, 0, 'L');
            $this->Ln(0.5);
            $this->Cell(3.25, 0.5, 'Mata Pelajaran', '', 0, 'L');
            $this->Cell(0.25, 0.5, ':', 0, 0, 'C');
            $this->Cell(6, 0.5, $du['nmmapel'], '', 0, 'L');
            $this->Cell(14.5, 0.5);
            $this->Cell(3.0, 0.5, 'Bank Soal', '', 0, 'L');
            $this->Cell(0.25, 0.5, ':', 0, 0, 'C');
            $this->Cell(6, 0.5, $du['nmbank'], '', 0, 'L');
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

    function IsiData($id, $hal, $j, $uji, $bs)
    {
        $this->SetFont('Arial', '', '10');
        if ($hal == 1) {
            $opset = 0;
        } else {
            $opset = 20;
        }
        $qisi = "SELECT s.idsiswa, s.nis, s.nisn, s.nmsiswa, s.nmpeserta, s.passwd, r.nmrombel, u.nmujian, u.idujian FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbujian u USING(idujian) INNER JOIN tbthpel t ON t.idthpel=r.idthpel WHERE u.idujian='$uji' AND r.idrombel='$id' AND t.aktif='1' GROUP BY s.idsiswa ORDER BY s.nmsiswa LIMIT 20 OFFSET $opset";
        $dq = vquery($qisi);
        $i = $opset;
        $this->SetY(5.5);
        foreach ($dq as $d) {
            $i++;
            $this->Cell(1.0, 0.575, $i . '.', 'LBR', 0, 'C');
            $this->Cell(3.5, 0.575, $d['nis'] . ' / ' . $d['nisn'], 'BR', 0, 'C');
            $this->Cell(7.5, 0.575, ucwords(strtolower($d['nmsiswa'])), 'BR', 0, 'L');
            if ($j == 1) {
                $k = 0;
                $this->SetX(13.15);
                $dso = GetNomorSoal($bs, $k);
                foreach ($dso as $so) {
                    $k++;
                    $qjwb = "SELECT skor FROM tbjawaban WHERE idsiswa='$d[idsiswa]' AND idbutir='$so[idbutir]'";
                    $jw = vquery($qjwb)[0];
                    $skor = number_format($jw['skor'], 1, ',', '.');
                    $this->Cell(1.25, 0.575, $skor, 'BR', 0, 'C');
                }
            } else {
                $k = ($j - 1) * 15;
                $ceknomor = CekNomorSoal($bs, $k);
                $this->SetX(13.15);
                $dso = GetNomorSoal($bs, $k);
                if ($ceknomor == 15) {
                    foreach ($dso as $so) {
                        $k++;
                        $qjwb = "SELECT skor FROM tbjawaban WHERE idsiswa='$d[idsiswa]' AND idbutir='$so[idbutir]'";
                        $jw = vquery($qjwb)[0];
                        $skor = number_format($jw['skor'], 2, ',', '.');
                        $this->Cell(1.25, 0.575, $skor, 'BR', 0, 'C');
                    }
                } else {
                    foreach ($dso as $so) {
                        $k++;
                        $qjwb = "SELECT skor FROM tbjawaban WHERE idsiswa='$d[idsiswa]' AND idbutir='$so[idbutir]'";
                        $jw = vquery($qjwb)[0];
                        $skor = number_format($jw['skor'], 2, ',', '.');
                        $this->Cell(1.25, 0.575, $skor, 'BR', 0, 'C');
                    }
                    $m = 42 - $k;
                    for ($n = 1; $n <= $m; $n++) {
                        $this->Cell(1.25, 0.575, '', 'BR', 0, 'C');
                    }
                    $sqlu = "SELECT  ps.idsiswa, bs.idbank, su.idset, COUNT(jwb.idset) as soal, ROUND(SUM(skor),2) as benar FROM tbjawaban jwb INNER JOIN tbpeserta ps USING(idsiswa) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbsetingujian su USING(idset) INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbujian u ON u.idujian=jd.idujian  INNER JOIN tbbanksoal bs USING(idbank) WHERE bs.idbank='$bs' AND bs.idujian='$uji' AND rs.idrombel='$id' AND ps.idsiswa='$d[idsiswa]' GROUP BY jwb.idset, jwb.idsiswa";
                    $qu = vquery($sqlu)[0];
                    $this->Cell(1.25, 0.575, $qu['benar'], 'BR', 0, 'C');
                    $this->Cell(1.25, 0.575, $qu['soal'] - $qu['benar'], 'BR', 0, 'C');
                    $this->Cell(1.25, 0.575, $qu['benar'] * 100 / $qu['soal'], 'BR', 0, 'C');
                }
            }


            $this->Ln(0.575);
        }
    }

    function Cetak($id, $uji, $mp)
    {
        $qrmb = "SELECT rs.idrombel, rb.nmrombel FROM tbrombelsiswa rs INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbthpel tp USING(idthpel) WHERE tp.aktif='1' AND rs.idrombel='$id'";
        $nsiswa = cquery($qrmb);
        $hal = ceil($nsiswa / 20);
        $qsoal = "SELECT COUNT(so.idbutir) as jmlsoal, bs.idbank FROM tbsoal so INNER JOIN tbstimulus USING(idstimulus) INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbsetingujian su USING(idbank) WHERE su.idrombel='$id' AND bs.idujian='$uji' AND bs.idmapel='$mp' GROUP BY bs.idbank";
        $so = vquery($qsoal)[0];
        $jmlsoal = $so['jmlsoal'];
        $bso = $so['idbank'];
        $soal = ceil($jmlsoal / 15);
        var_dump($jmlsoal);
        var_dump($soal);
        die;
        for ($i = 1; $i <= $hal; $i++) {
            for ($j = 1; $j <= $soal; $j++) {
                $this->AddPage();
                $this->Judul($id, $i, $j, $bso);
                $this->IsiData($id, $i, $j, $uji, $bso);
            }
        }
    }
}
$pdf = new PDF('L', 'cm', array(21.5, 33.0));
$pdf->AliasNbPages();
$pdf->SetMargins(1.15, 0.75, 0.75);
$pdf->SetAutoPageBreak('true', 3.75);
$pdf->Cetak($_POST['rmb'], $_POST['uji'], $_POST['map']);
$pdf->Output();
