<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
if ($_POST['aksi'] == 'edit') {
    $keyt = array('idtes' => $_POST['id']);
    $m = viewdata('tbtes', $keyt)[0];
    echo json_encode($m);
}

if ($_POST['aksi'] == 'aktif') {
    $keyu = array(
        'idtes' => $_POST['id'],
        'idthpel' => $_POST['th']
    );
    if (cekdata('tbujian', $keyu) > 0) {
        $m = viewdata('tbujian', $keyu)[0];
        if ($m['status'] == '1') {
            $btn = "btn btn-secondary btn-sm col-4";
            $ikn = "<i class='fas fa-power-off'></i>&nbsp;Nonaktifkan";
        } else {
            $btn = "btn btn-primary btn-sm col-4";
            $ikn = "<i class='far fa-check-square'></i>&nbsp;Aktifkan";
        }
        $data = array(
            'idthpel' => $m['idthpel'],
            'idtes' => $m['idtes'],
            'kls' => $btn,
            'btn' => $ikn
        );
    } else {
        $th = viewdata('tbthpel', array('aktif' => '1'))[0];
        $idthpel = $th['idthpel'];
        $ts = viewdata('tbtes', array('idtes' => $_POST['id']))[0];
        $idtes = $ts['idtes'];
        $btn = "btn btn-success btn-sm col-4";
        $ikn = "<i class='far fa-check-square'></i>&nbsp;Aktifkan";
        $data = array(
            'idthpel' => $idthpel,
            'idtes' => $idtes,
            'kls' => $btn,
            'btn' => $ikn
        );
    }
    echo json_encode($data);
}
