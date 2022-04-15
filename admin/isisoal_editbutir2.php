<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
if (isset($_POST['idb'])) {
    $so = viewdata('tbsoal', array('idbutir' => $_POST['idb']))[0];
    $data = array(
        'jnssoal' => $so['jnssoal'],
        'modeopsi' => $so['modeopsi'],
        'tksukar' => $so['tksukar'],
        'butirsoal' => $so['butirsoal'],
        'skormaks' => $so['skormaks'],
        'judul' => 'Update Butir Soal'
    );
} else {
    $key = array(
        'idstimulus' => $_POST['ids'],
        'nomersoal' => $_POST['nm']
    );
    $so = viewdata('tbsoal', $key)[0];
    $data = array(
        'idbutir' => $so['idbutir'],
        'jnssoal' => $so['jnssoal'],
        'modeopsi' => $so['modeopsi'],
        'tksukar' => $so['tksukar'],
        'butirsoal' => $so['butirsoal'],
        'skormaks' => $so['skormaks'],
        'judul' => 'Tambah Butir Soal'
    );
}
echo json_encode($data);
