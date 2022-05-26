<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
$key = array('idujian' => $_POST['id']);
if (cekdata('tbsetrapor', $key) > 0) {
    $uj = viewdata('tbsetrapor', $key)[0];
    $data = array(
        'tombol' => '<i class="fas fa-save"></i>&nbsp;Update',
        'judul' => 'Ubah Setting Cetak Rapor',
        'idsetrapor' => $uj['idsetrapor'],
        'tmpterbit' => $uj['tmpterbit'],
        'tglterbit' => $uj['tglterbit']
    );
} else {
    $data = array(
        'tombol' => '<i class="fas fa-save"></i>&nbsp;Simpan',
        'judul' => 'Tambah Setting Cetak Rapor',
        'idsetrapor' => '',
        'tmpterbit' => '',
        'tglterbit' => ''
    );
}
echo json_encode($data);
