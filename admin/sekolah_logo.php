<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	$list = array("jpg", "png");
	$uploaddir = '../images/'; 
	$namafile = basename($_FILES['uploadlogo']['name']);
	$pecah = explode(".", $namafile);
	$ekstensi = $pecah[1];
	$file = $uploaddir . basename($_FILES['uploadlogo']['name']); 
	if(in_array($ekstensi, $list))
	{
		if (move_uploaded_file($_FILES['uploadlogo']['tmp_name'], $file)) { 
			$sql = $conn->query("UPDATE tbskul SET logoskul = '$namafile' WHERE idskul='$_COOKIE[c_skul]'");
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