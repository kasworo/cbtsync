<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	$sqthn=$conn->query("SELECT idthpel FROM tbthpel WHERE aktif='1'");
	$thn=$sqthn->fetch_array();
	$idthpel=$thn['idthpel'];
	if($_POST['aksi']=='1'){
		$qcek=$conn->query("SELECT*FROM tbrombel WHERE idrombel='$_POST[id]'");
		$cek=mysqli_num_rows($qcek);
		if($cek==0){
			$sql="INSERT INTO tbrombel (idkelas, nmrombel, idkur, username, idthpel) VALUES ('$_POST[kdkls]', '$_POST[nmrombel]', '$_POST[idkur]', '$_POST[walas]','$idthpel')";
		}
		else{
			$sql="UPDATE tbrombel SET idkur='$_POST[idkur]', username='$_POST[walas]', nmrombel= '$_POST[nmrombel]' WHERE idrombel='$_POST[id]'";
		}
		$conn->query($sql);
		$result=$conn->affected_rows;
		echo $result;
	}	
	if($_POST['aksi']=='2'){
		$sql=$conn->query("DELETE FROM tbrombel WHERE idrombel='$_POST[id]'");
		echo 'Hapus Rombongan Belajar Berhasil!';
	}
	if($_POST['aksi']=='3'){
		$sql=$conn->query("TRUNCATE tbrombel");
		echo 'Hapus Rombongan Belajar Berhasil!';
	}
?>