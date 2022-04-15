<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	$list = array("jpg", "png");
	$uploaddir = '../images/'; 
	$namafile = basename($_FILES['uploadskpd']['name']);
	$pecah = explode(".", $namafile);
	$ekstensi = $pecah[1];
	$file = $uploaddir . basename($_FILES['uploadskpd']['name']); 
	if(in_array($ekstensi, $list))
	{
		if (move_uploaded_file($_FILES['uploadskpd']['tmp_name'], $file)) { 
			$sql = $conn->query("UPDATE tbskul SET logoskpd = '$namafile' WHERE idskul='$_COOKIE[c_skul]'");
			echo "success"; 
		}
		else{
			echo "error";
		}
	} 
	else {
		echo "error";
	}
?>