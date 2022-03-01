<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	if($_POST['aksi']=='1'){
		$qcek=$conn->query("SELECT*FROM tbsesi WHERE idsesi='$_POST[id]'");
		$cek=mysqli_num_rows($qcek);
		if($cek==0)
		{
			$sql=$conn->query("INSERT INTO tbsesi (nmsesi, mulai, selesai) VALUES ('$_POST[nm]','$_POST[ml]','$_POST[ak]')");
			echo 'Simpan Sesi Berhasil!';
		}
		else
		{
			$sql= $conn->query("UPDATE tbsesi SET nmsesi='$_POST[nm]', mulai= '$_POST[ml]', selesai='$_POST[ak]' WHERE idsesi='$_POST[id]'");
			echo 'Update Jadwal Berhasil!';
		}
    }
    
    if($_POST['aksi']=='2'){
        $sql= $conn->query("DELETE FROM tbsesi WHERE idsesi='$_POST[id]'");
		echo 'Hapus Sesi Berhasil!';
    }
    
    if($_POST['aksi']=='3'){
        $sql= $conn->query("TRUNCATE tbsesi");
		echo 'Tabel Sesi Berhasil Dikosongkan!';
	}
?>