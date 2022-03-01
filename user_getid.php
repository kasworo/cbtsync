<?php
	define("BASEPATH", dirname(__FILE__));
	if (!function_exists('getuserid')) {
		function getuserid()  {
			include "../config/konfigurasi.php";
			$qskul=$conn->query("SELECT kdskul FROM tbskul WHERE idskul='$_COOKIE[c_skul]'");
			$cek=$qskul->fetch_array();
			$kdskul=$cek['kdskul'];
			$sql=$conn->query("SELECT COUNT(*) as juser FROM tbuser WHERE level='2' AND idskul='$_COOKIE[c_skul]'");
			$row=$sql->fetch_array();
			$id=$row['juser']+1;
			if($id<8)
			{
				$cekdigit=10-($id%9+1);
			}
			else
			{
				$cekdigit=10-($id%8+1);
			}
			$iduser='G'.substr($kdskul,-8).substr('00'.$id,-3);
		   return $iduser;
		}
	}
?>