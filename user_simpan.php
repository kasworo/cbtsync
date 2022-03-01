<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	if($_POST['aksi']=='simpan')
	{
		$sqlcek =$conn->query("SELECT*FROM tbuser WHERE username='$_POST[id]'");
		$cek=$sqlcek->num_rows;
		if($cek>0)
		{
			$sql = $conn->query("UPDATE tbuser SET nama='$_POST[nama]', nip='$_POST[nip]', tmplahir='$_POST[tmplahir]', tgllahir='$_POST[tgllahir]', gender='$_POST[gender]', agama='$_POST[agama]',alamat='$_POST[almt]' WHERE username='$_POST[id]'");
			echo "Update Data Penggguna Sukses!";
		} 
		else
		{
			$pwd=str_replace('-','',$_POST['tgllahir']);			
			$sql = $conn->query("INSERT INTO tbuser (idskul, username, nama, nip, tmplahir, tgllahir, gender, agama, alamat, passwd, level, aktif) VALUES ('$_COOKIE[c_skul]','$_POST[id]','$_POST[nama]','$_POST[nip]','$_POST[tmplahir]','$_POST[tgllahir]','$_POST[gender]','$_POST[agama]','$_POST[almt]',PASSWORD('$pwd'),'2','1')");
			echo "Tambah Data Pengguna Berhasil!";
		}
	}
	
	if($_POST['aksi']=='aktif'){
		$id=base64_decode($_POST['id']);
		$sqlcek=$conn->query("SELECT aktif FROM tbuser WHERE username='$id'");
		$sta=$sqlcek->fetch_array();
		$status=$sta['aktif'];
		if($status=='1'){$ubah='0';$jdl='Dinonaktifkan!';} else{$ubah='1';$jdl='Diaktifkan!';}
		$sql=$conn->query("UPDATE tbuser SET aktif='$ubah' WHERE username='$id'");
		echo "Pengguna Berhasil ".$jdl;
	}
	
	if($_REQUEST['aksi']=='reset')	{
		$id=base64_decode($_POST['id']);
		$sqlcek=$conn->query("SELECT tgllahir FROM tbuser WHERE username='$id'");
		$sta=$sqlcek->fetch_array();
		$tgl=$sta['tgllahir'];
		$password=password_hash($tgl, PASSWORD_DEFAULT);
		$sqlpasaif=$conn->query("UPDATE tbuser SET passwd='$password' WHERE username='$id'");
		echo "Password Berhasil Direset!";
	}

	if($_POST['aksi']=='hapus'){
		$id=base64_decode($_POST['id']);
		$sql=$conn->query("UPDATE tbuser SET deleted='1', aktif='0' WHERE username='$id'");
		echo "Data Pengguna Berhasil Dihapus!";
	}

	if($_POST['aksi']=='pass'){
		$pwd=password_hash($_POST['passbaru'], PASSWORD_DEFAULT);
		$sql=$conn->query("UPDATE tbuser SET passwd='$pwd', tglupdate='$tgl' WHERE username='$_POST[id]'");
	   echo "Password Berhasil Diganti!";
	}
?>