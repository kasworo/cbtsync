<?php
include "dbfunction.php";
$id = $_POST['id'];
$data = cekdata('tbpeserta', array('idsiswa' => $id));
if ($data == 0) {
    $dir = '../assets/img/';
    $foto = 'kotakfoto.png';
    $fotolama = '';
    $rows = array(
        'idsiswa' => '',
        'nmsiswa' => '',
        'nis' => '',
        'nisn' => '',
        'tmplahir' => '',
        'tgllahir' => '',
        'gender' => '',
        'agama' => '',
        'alamat' => '',
        'foto' => $foto,
        'dir' => $dir,
        'judul' => 'Tambah Data Peserta Didik',
        'tmbl' => '<i class="fas fa-save"></i> Simpan'
    );
} else {
    $m = viewdata('tbpeserta', array('idsiswa' => $id))[0];
    if ($m['fotosiswa'] == '') {
        $dir = '../assets/img/';
        $foto = 'kotakfoto.png';
    } else {
        $dir = '../foto/';
        $foto = $m['fotosiswa'];
    }
    $rows = array(
        'idsiswa' => $m['idsiswa'],
        'nmsiswa' => ucwords(strtolower($m['nmsiswa'])),
        'nis' => $m['nis'],
        'nisn' => $m['nisn'],
        'tmplahir' => $m['tmplahir'],
        'tgllahir' => $m['tgllahir'],
        'gender' => $m['gender'],
        'agama' => $m['agama'],
        'alamat' => $m['alamat'],
        'foto' => $foto,
        'dir' => $dir,
        'judul' => 'Update Data Peserta Didik',
        'tmbl' => '<i class="fas fa-save"></i> Update'
    );
}

echo json_encode($rows);
