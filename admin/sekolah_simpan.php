<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	if($_REQUEST['aksi']=='simpan'){
		$sql=$conn->query("UPDATE tbskul SET nmskul='$_REQUEST[nama]', npsn='$_REQUEST[npsn]', nss='$_REQUEST[noss]', nmskpd='$_REQUEST[skpd]',alamat='$_REQUEST[almt]',desa='$_REQUEST[desa]', kec='$_REQUEST[kec]', kab='$_REQUEST[kab]', prov='$_REQUEST[prov]', kdpos='$_REQUEST[kpos]', website='$_REQUEST[webs]', email='$_REQUEST[imel]' WHERE kdskul='$_REQUEST[kode]'");
		echo "Update Data Berhasil";
	}
?>