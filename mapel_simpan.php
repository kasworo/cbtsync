<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	if($_POST['aksi']=='simpan'){
		$qcek=$conn->query("SELECT*FROM tbmapel WHERE idmapel='$_POST[id]'");
		$cek=$qcek->num_rows;
		if($cek==0){
			$sql=$conn->query("INSERT INTO tbmapel (idkur, nmmapel, akmapel, jenis) VALUES ('$_POST[idkur]','$_POST[nmmapel]','$_POST[akmapel]','$_POST[jmapel]')");
				echo 'Simpan Mata Pelajaran Berhasil!';
		}
		else{
			$sql=$conn->query("UPDATE tbmapel SET idkur='$_POST[idkur]', nmmapel= '$_POST[nmmapel]', akmapel='$_POST[akmapel]', jenis='$_POST[jmapel]' WHERE idmapel='$_POST[id]'");
			echo 'Update Mata Pelajaran Berhasil!';
		}
	}	
	if($_POST['aksi']=='kosong'){
		$sql=$conn->query("TRUNCATE tbmapel");
		echo 'Hapus Mata Pelajaran Berhasil!';
	}
	if($_POST['aksi']=='hapus'){
		$sql=$conn->query("DELETE FROM tbmapel WHERE idmapel='$_POST[id]'");
		echo 'Hapus Mata Pelajaran Berhasil!';
	}
?>