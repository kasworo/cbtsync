<?php
define("BASEPATH", dirname(__FILE__));
if(isset($_POST['user'], $_POST['pass'])) 
{
	include "../config/konfigurasi.php";
	require("../config/fungsi_thn.php");
	$tgl=date('Y-m-d H:i:s');		
	$user = $conn->real_escape_string($_POST['user']);
	$passz = $conn->real_escape_string($_POST['pass']);
	$pass = sha1(md5($passz));
	
	$qadm = $conn->query("SELECT*FROM tbuser WHERE username = '$user' AND passwd = PASSWORD('$passz')");
	$cek=$qadm->num_rows;
	if($cek>0)
	{
		$row=$qadm->fetch_array();
		$userz = $row['username'];
		$level=$row['level'];
		setcookie('c_user',$userz);
		setcookie('c_login',$loginz);

		$qth=$conn->query("SELECT idthpel FROM tbthpel WHERE aktif='1'");
		$th=$qth->fetch_array();
		setcookie('c_tahun',$th['idthpel']);

		$qskul=$conn->query("SELECT idskul FROM tbskul");
		$sk=$qskul->fetch_array();
		setcookie('c_skul',$sk['idskul']);
		$sql = $conn->query( "UPDATE tbuser SET xlog='$tgl' WHERE username='$userz'");
		echo 1;	
	}
	else { 
		$qskul = $conn->query("SELECT*FROM tbskul WHERE kdskul = '$user' AND npsn = '$passz'");
		$ceks=$qskul->num_rows;
		if($ceks>0){
			$sk=$qskul->fetch_array();
			setcookie('c_user',$sk['kdskul']);
			setcookie('c_skul',$sk['idskul']);
			setcookie('c_login','3');
			$qth=$conn->query("SELECT idthpel FROM tbthpel WHERE aktif='1'");
			$th=$qth->fetch_array();
			setcookie('c_tahun',$th['idthpel']);
			echo 1;	
		}
		else{
		echo 0;
		}
	}			
}
else {header("Location: login.php");}
?>