<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
$qm = viewdata('tbmapel', array('idmapel' => $_POST['id']))[0];
echo json_encode($qm);
