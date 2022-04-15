<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
if ($_POST['ids'] == '') {
    $judul = 'Tambah Stimulus Soal';
    $data = array(
        'idstimulus' => '',
        'stimulus' => '',
        'gambar' => '',
        'judul' => $judul
    );
} else {

    $stm = viewdata('tbstimulus', array('idstimulus' => $_POST['ids']))[0];
    $judul = 'Update Stimulus Soal';
    $data = array(
        'idstimulus' => $stm['idstimulus'],
        'stimulus' => $stm['stimulus'],
        'gambar' => $stm['gambar'],
        'judul' => $judul
    );
}
echo json_encode($data);
