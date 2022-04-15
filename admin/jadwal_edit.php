<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
$j = viewdata('tbjadwal', array('idjadwal' => $_POST['id']))[0];
echo json_encode($j);
