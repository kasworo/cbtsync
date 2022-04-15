<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
if ($_POST['aksi'] == 'aktif') {
	$sta = viewdata('tbpeserta', array('idsiswa' => $_POST['id']))[0];
	$status = $sta['aktif'];
	if ($status == '1') {
		$ubah = '0';
		$jdl = 'Dinonaktifkan!';
	} else {
		$ubah = '1';
		$jdl = 'Diaktifkan!';
	}
	$row = editdata('tbpeserta', array('aktif' => $ubah), '', array('idsiswa' => $_POST['id']));
	if ($row > 0) {
		echo $ubah;
	}
}

if ($_POST['aksi'] == 'hapus') {
	$id = $_POST['id'];
	$sql = $conn->query("UPDATE tbpeserta SET deleted='1', aktif='0' WHERE idsiswa='$id'");
	echo 'Hapus Peserta Didik Berhasil!';
}

if ($_POST['aksi'] == 'kosong') {
	$sql = $conn->query("TRUNCATE tbpeserta");
	echo 'Hapus Peserta Didik Berhasil!';
}
