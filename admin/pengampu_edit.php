<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
$qamp = "SELECT a.*, r.idkelas FROM tbpengampu a INNER JOIN tbmapel m  USING(idmapel) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t USING(idthpel) WHERE a.idampu='$_POST[id]' AND t.aktif='1'";
$am = vquery($qamp)[0];
echo json_encode($am);
