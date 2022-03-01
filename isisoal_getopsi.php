<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	$sql=$conn->query("SELECT so.jnssoal, so.modeopsi, op.* FROM tbopsi op INNER JOIN tbsoal so WHERE idopsi='$_POST[id]'");
	$j=$sql->fetch_array();
	echo json_encode($j);
?>