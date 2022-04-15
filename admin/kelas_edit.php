<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
$m = viewdata('tbrombel', array('idrombel' => $_POST['id']))[0];
echo json_encode($m);
