<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
$uj = viewdata('tbsetrapor', array('idujian' => $_POST['id']))[0];
echo json_encode($uj);
