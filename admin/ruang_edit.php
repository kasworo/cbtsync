<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
$sql = $conn->query("SELECT*FROM tbruang WHERE idruang='$_POST[id]'");
$j = $sql->fetch_array();
echo json_encode($j);
