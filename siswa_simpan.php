<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	if($_POST['aksi']=='simpan'){
		$nmsiswa=addslashes($_POST['nama']);
		$qcek=$conn->query("SELECT*FROM tbpeserta WHERE idsiswa='$_POST[id]'");
		$cek=mysqli_num_rows($qcek);
		if($cek==0){
			$sql = $conn->query("INSERT INTO tbpeserta (nmsiswa, nis, nisn, tmplahir, tgllahir, gender, idagama, alamat, aktif, deleted, idskul) VALUES ('$nmsiswa','$_POST[nis]','$_POST[nisn]','$_POST[tmplahir]', '$_POST[tgllahir]', '$_POST[gender]','$_POST[agama]','$_POST[almt]','1','0', '$_COOKIE[c_skul]')");
			echo 'Simpan Peserta Didik Berhasil!';
		}
		else{
			$sql=$conn->query("UPDATE tbpeserta SET nmsiswa ='$nmsiswa', nis ='$_POST[nis]', nisn='$_POST[nisn]', tmplahir='$_POST[tmplahir]', tgllahir='$_POST[tgllahir]', gender='$_POST[gender]', idagama='$_POST[agama]', alamat='$_POST[almt]' WHERE idsiswa='$_POST[id]'");
			echo 'Update Peserta Didik Berhasil!';
		}
	}	
	
	if($_POST['aksi']=='aktif'){
		$id=base64_decode($_POST['id']);
		$qcek=$conn->query("SELECT aktif FROM tbpeserta WHERE idsiswa='$id'");
		$sta=$qcek->fetch_array();
		$status=$sta['aktif'];
		if($status=='1'){$ubah='0';$jdl='Dinonaktifkan!';} else{$ubah='1';$jdl='Diaktifkan!';}
		$sql=$conn->query("UPDATE tbpeserta SET aktif='$ubah' WHERE idsiswa='$id'");
		echo "Peserta Didik Berhasil ".$jdl;
	}
	
	if($_POST['aksi']=='hapus'){
		$id=$_POST['id'];
		$sql=$conn->query("UPDATE tbpeserta SET deleted='1', aktif='0' WHERE idsiswa='$id'");
		echo 'Hapus Peserta Didik Berhasil!';
	}

	if($_POST['aksi']=='kosong'){
		$sql=$conn->query("TRUNCATE tbpeserta");
		echo 'Hapus Peserta Didik Berhasil!';
	}
?>