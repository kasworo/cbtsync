<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
if ($_POST['aksi'] == 'simpan') {
	if ($_POST['idm'] != '' || $_POST['idm'] != NULL) {
		if (cekdata('tbmapel', array('idmapel' => $_POST['idm'])) > 0) {
			$data = array(
				'idkur' => $_POST['idk'],
				'nmmapel' => $_POST['nama'],
				'akmapel' => $_POST['kode'],
				'jenis' => $_POST['jenis']
			);
			if (editdata('tbmapel', $data, '', array('idmapel' => $_POST['idm'])) > 0) {
				echo '2';
			} else {
				echo '0';
			}
		}
	} else {
		$data = array(
			'idkur' => $_POST['idk'],
			'nmmapel' => $_POST['nama'],
			'akmapel' => $_POST['kode'],
			'jenis' => $_POST['jenis']
		);
		if (adddata('tbmapel', $data) > 0) {
			echo '1';
		} else {
			echo '0';
		}
	}
}
if ($_POST['aksi'] == 'kosong') {
	$sql = $conn->query("TRUNCATE tbmapel");
	echo 'Hapus Mata Pelajaran Berhasil!';
}
if ($_POST['aksi'] == 'hapus') {
	$sql = $conn->query("DELETE FROM tbmapel WHERE idmapel='$_POST[id]'");
	echo 'Hapus Mata Pelajaran Berhasil!';
}
