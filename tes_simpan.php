<?php

	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	if($_POST['aksi']=='simpan'){
		$qcek=$conn->query("SELECT*FROM tbtes WHERE idtes='$_POST[id]'");
		$cek=$qcek->num_rows;
		if($cek==0)
		{
			$sql=$conn->query("INSERT INTO tbtes (nmtes, aktes) VALUES ('$_POST[nmtes]','$_POST[aktes]')");
				echo 'Simpan Jenis Tes Berhasil!';
		}
		else
		{
			$sql=$conn->query("UPDATE tbtes SET nmtes= '$_POST[nmtes]', aktes='$_POST[aktes]' WHERE idtes='$_POST[id]'");
			echo 'Update Jenis Tes Berhasil!';
		}
	}

	if($_POST['aksi']=='aktif'){
		$quji=$conn->query("SELECT*FROM tbujian WHERE idthpel='$_POST[th]' AND idtes='$_POST[id]'");
		$cek=$quji->num_rows;
		if($cek==0){
			$qtp=$conn->query("SELECT nmthpel FROM tbthpel WHERE idthpel='$_POST[th]'");
			$tp=$qtp->fetch_array();
			$thpel=$tp['nmthpel'];
			$qts=$conn->query("SELECT aktes FROM tbtes WHERE idtes='$_POST[id]'");
			$ts=$qts->fetch_array();
			$nmtes=$ts['aktes'];
			$nmujian=$nmtes.$thpel;
			$sql=$conn->query("UPDATE tbujian SET status='0'");
			$sql=$conn->query("INSERT INTO tbujian (idtes, nmujian, status, idthpel) VALUES ('$_POST[id]','$nmujian','1', '$_POST[th]')");
			echo 'Aktivasi Tes Berhasil!';			
		}
		else {
			$ak=$quji->fetch_array();
			$aktif=$ak['status'];
			if($aktif=='1'){
				$sql=$conn->query("UPDATE tbujian SET status='0' WHERE idthpel='$_POST[th]' AND idtes='$_POST[id]'");
				echo 'Tes Berhasil Dinonaktifkan!';
			}
			else{
				$sql=$conn->query("UPDATE tbujian SET status='1' WHERE idthpel='$_POST[th]' AND idtes='$_POST[id]'");
				echo 'Aktivasi Tes Berhasil!';
			}
		}		
	}

    if($_POST['aksi']=='kosong'){
		$sql=$conn->query("TRUNCATE tbtes");
		echo 'Hapus Jenis Tes Berhasil!';
	}
	if($_POST['aksi']=='hapus'){
        $sql=$conn->query("DELETE FROM tbtes WHERE idtes='$_POST[id]'");
		echo 'Hapus Jenis Tes Berhasil!';
	}
?>