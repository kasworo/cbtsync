<?php
include "dbfunction.php";
$qpst = "SELECT s.nmsiswa, s.nmpeserta, s.nis, s.nisn, s.tmplahir, s.tgllahir, s.agama, s.gender, k.nmkelas, rs.idrombel, r.nmrombel, jd.idjadwal, jd.tglujian, jd.matauji, jd.durasi,  jd.susulan, su.idset, tk.token, tk.tampil FROM tbpeserta s INNER JOIN tbujian u USING(idujian) INNER JOIN tbjadwal jd USING(idujian) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbkelas k USING(idkelas) INNER JOIN tbsetingujian su ON su.idrombel=rs.idrombel AND su.idrombel=r.idrombel INNER JOIN tbtoken tk ON tk.idjadwal=jd.idjadwal AND tk.idjadwal=su.idjadwal WHERE u.status='1' AND tk.status='1' AND s.idsiswa='$_POST[pst]'";
if (cquery($qpst) > 0) {
    $pst = vquery($qpst)[0];
    $keystatus = array(
        'idjadwal' => $pst['idjadwal'],
        'status' => '1',
        'idsiswa' => $_COOKIE['pst']
    );
    $cekstatus = cekdata('tblogpeserta', $keystatus);
    if ($cekstatus > 0) {
        $data = array(
            'nama' => ucwords(strtolower($pst['nmsiswa'])),
            'nopes' => $pst['nmpeserta'],
            'noinduk' =>  $pst['nis'] . ' / ' . $pst['nisn'],
            'kelahiran' =>  $pst['tmplahir'] . ', ' . indonesian_date($pst['tgllahir']),
            'agama' => getagama($pst['agama']),
            'gender' => getgender($pst['gender']),
            'tgluji' => 'Tidak Ada Jadwal Ujian',
            'matauji' => 'Tidak Ada Mata Ujian',
            'durasi' => 'Tidak Ada Jadwal Ujian',
            'petunjuk' => '<b>Mohon Maaf<b/><br/><small style="color:red;font-weight:bold;">Anda Sudah Selesai</small>',
            'tipe' => 'd-none'
        );
    } else {
        if ($pst['tampil'] == '1') {
            $data = array(
                'token' => '1',
                'nama' => ucwords(strtolower($pst['nmsiswa'])),
                'nopes' => $pst['nmpeserta'],
                'noinduk' =>  $pst['nis'] . ' / ' . $pst['nisn'],
                'kelahiran' =>  $pst['tmplahir'] . ', ' . indonesian_date($pst['tgllahir']),
                'agama' => getagama($pst['agama']),
                'gender' => getgender($pst['gender']),
                'tgluji' => indonesian_date($pst['tglujian']),
                'matauji' => ($pst['susulan'] == '0') ?
                    $pst['matauji'] . " - Utama" :  $pst['matauji'] . " - Susulan",
                'durasi' => $pst['durasi'] . ' menit',
                'petunjuk' => '<b>Masukkan </b>Token&nbsp;<span style="color:red;font-weight:bold;font-size:14pt">' . $pst['token'] . '</span>',
                'tipe' => 'd-block'
            );
        } else {
            $data = array(
                'token' => '0',
                'nama' => ucwords(strtolower($pst['nmsiswa'])),
                'nopes' => $pst['nmpeserta'],
                'noinduk' =>  $pst['nis'] . ' / ' . $pst['nisn'],
                'kelahiran' =>  $pst['tmplahir'] . ', ' . indonesian_date($pst['tgllahir']),
                'agama' => getagama($pst['agama']),
                'gender' => getgender($pst['gender']),
                'tgluji' => indonesian_date($pst['tglujian']),
                'matauji' => ($pst['susulan'] == '0') ?
                    $pst['matauji'] . " - Utama" :  $pst['matauji'] . " - Susulan",
                'durasi' => $pst['durasi'] . ' menit',
                'petunjuk' => '<b>Masukkan Token<b/>&nbsp;<small>(minta dari pengawas)</small>',
                'tipe' => 'd-block'
            );
        }
    }
} else {
    $qsiswa = "SELECT s.nmsiswa, s.nmpeserta, s.nis, s.nisn, s.tmplahir, s.tgllahir, s.agama, s.gender, k.nmkelas, rs.idrombel, r.nmrombel, FROM tbpeserta s INNER JOIN tbujian u USING(idujian) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbkelas k USING(idkelas) WHERE u.status='1' AND s.idsiswa='$_POST[pst]'";
    $pst = vquery($qsiswa)[0];
    $data = array(
        'nama' => ucwords(strtolower($pst['nmsiswa'])),
        'nopes' => $pst['nmpeserta'],
        'noinduk' =>  $pst['nis'] . ' / ' . $pst['nisn'],
        'kelahiran' =>  $pst['tmplahir'] . ', ' . indonesian_date($pst['tgllahir']),
        'agama' => getagama($pst['agama']),
        'gender' => getgender($pst['gender']),
        'tgluji' => 'Tidak Ada Jadwal Ujian',
        'matauji' => 'Tidak Ada Mata Ujian',
        'durasi' => 'Tidak Ada Jadwal Ujian',
        'petunjuk' => '<b>Mohon Maaf<b/>&nbsp;<small style="color:red;font-weight:bold;>Anda Sudah Selesai</small>'
    );
}
echo json_encode($data);
