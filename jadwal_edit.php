<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	$sql=$conn->query("SELECT*FROM tbjadwal WHERE kdjadwal='$_POST[id]'");
	$j=$sql->fetch_array();
	echo json_encode($j);
?>