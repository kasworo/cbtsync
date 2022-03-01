<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	include "token_function.php";
	$jam=date('Y-m-d H:i:s');
	$sql=$conn->query("UPDATE tbtoken SET status='0' WHERE idjadwal<>'$_POST[jdw]' OR idsesi<>'$_POST[sesi]'");
	$sql=$conn->query("UPDATE tblogpeserta as lp INNER JOIN tbsesiujian as su ON lp.idsiswa=su.idsiswa SET lp.status='1' WHERE su.idjadwal='$_POST[jdw]' AND su.idsesi<>'$_POST[sesi]'");		
	$qcek=$conn->query("SELECT*FROM tbtoken WHERE idjadwal='$_POST[jdw]' AND idsesi='$_POST[sesi]' AND status='1'");
	$cek=$qcek->num_rows;
	if($cek==0){
		if($_POST['token']==''){$token=gettoken(6);} else { $token=$_POST['token'];}
		$sql=$conn->query("INSERT INTO tbtoken (idjadwal, idsesi, token,jamrilis, status) VALUES ('$_POST[jdw]','$_POST[sesi]','$token','$jam','1')");
		$pesan='Simpan Token Sukses';
	}
	else {
		$token=gettoken(6);
		$sql=$conn->query("UPDATE tbtoken SET token='$token', jamrilis='$jam' WHERE idjadwal='$_POST[jdw]' AND idsesi='$_POST[sesi]' AND status='1'");
		$pesan= 'Update Token Sukses';
	}

	$qaktif=$conn->query("UPDATE tbpeserta as ps INNER JOIN tbsesiujian as su USING(idsiswa) INNER JOIN tbujian as u USING(idujian) SET ps.aktif='1' WHERE su.idjadwal='$_POST[jdw]' AND su.idsesi='$_POST[sesi]' AND u.status='1'");
	echo $pesan;
?>