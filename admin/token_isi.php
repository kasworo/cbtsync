<?php
include "dbfunction.php";
include "token_function.php";

$skrg = date('Y-m-d');
$jam = date('H:i:s');
$sql = "SELECT jd.* FROM tbjadwal jd INNER JOIN tbsetingujian su USING(idjadwal) INNER JOIN tbujian u USING(idujian) WHERE jd.tglujian='$skrg' AND jd.mulai<='$jam' AND u.status='1' GROUP BY jd.idjadwal LIMIT 1";
if (cquery($sql) > 0) {
    $jd = vquery($sql)[0];
    $qcek = "SELECT TIME_TO_SEC(timediff('$jam', t.jamrilis)) AS waktu, t.jamrilis, t.token, t.idjadwal, t.idsesi, t.tampil FROM tbtoken t INNER JOIN tbjadwal jd USING(idjadwal) WHERE t.idjadwal='$jd[idjadwal]' AND t.status='1'";

    if (cquery($qcek) > 0) {
        $d = vquery($qcek)[0];
        $sesi = $d['idsesi'];
        $jadwal = $d['idjadwal'];
        $dtjmtoken = $d['jamrilis'];
        $dttoken = $d['token'];
        $selisih = $d['waktu'];
        $tampil = $d['tampil'];
        if ($selisih >= 900) {
            $key = array('status' => '1');
            $tokenbaru = array(
                'token' => getToken(6),
                'jamrilis' => $jam
            );
            $edit = editdata('tbtoken', $tokenbaru, '', $key);
            if ($edit > 0) {
                $tk = viewdata('tbtoken', $key)[0];
                $data = array(
                    'aktif' => '1',
                    'status' => '<font style="color:green;font-style: bold;font-size: 12pt">AKTIF</font>',
                    'jadwal' => $tk['idjadwal'],
                    'sesi' => $tk['idsesi'],
                    'pesan' => $tk['token'] . ' (Update Terakhir ' . $tk['jamrilis'] . ')',
                    'tampil' => $tk['tampil']
                );
            }
        } else {
            $data = array(
                'aktif' => '1',
                'status' => '<font style="color:green;font-style: bold;font-size: 12pt">AKTIF</font>',
                'jadwal' => $jadwal,
                'sesi' => $sesi,
                'pesan' => $dttoken . ' (Update Terakhir ' . $dtjmtoken . ')',
                'tampil' => $tampil
            );
        }
    } else {
        $data = array(
            'aktif' => '1',
            'status' => '<font style="color:blue;font-style: bold;font-size: 12pt">STAND BY</font>',
            'sesi' => '',
            'jadwal' => '',
            'pesan' => 'Token Belum Diaktifkan',
            'tampil' => ''
        );
    }
} else {
    $data = array(
        'aktif' => '0',
        'status' => '<font style="color:red;font-style: bold;font-size: 12pt">NON AKTIF</font>',
        'jadwal' => '',
        'sesi' => '',
        'pesan' => 'Belum Ada Jadwal Aktif',
        'tampil' => ''
    );
}
echo json_encode($data);
