<?php
include "dbfunction.php";
if ($_POST['aksi'] == 'simpan') {
	if ($_POST['id'] != '' || $_POST['id'] != NULL) {
		$data = array(
			'akkur' => $_POST['akkur'],
			'nmkur' => $_POST['nmkur']
		);
		if (editdata('tbkurikulum', $data, '', array('idkur' => $_POST['id'])) > 0) {
			echo '2';
		} else {
			echo '0';
		}
	} else {
		$data = array(
			'akkur' => $_POST['akkur'],
			'nmkur' => $_POST['nmkur']
		);
		if (adddata('tbkurikulum', $data) > 0) {
			echo '1';
		} else {
			echo '0';
		}
	}
}

if ($_POST['aksi'] == 'kosong') {
	$sql = $conn->query("TRUNCATE tbkurikulum");
	echo 'Hapus Kurikulum Berhasil!';
}
if ($_POST['aksi'] == 'hapus') {
	$sql = $conn->query("DELETE FROM tbkurikulum WHERE idkur='$_POST[id]'");
	echo 'Hapus Kurikulum Berhasil!';
}
