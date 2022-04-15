<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
if ($_POST['aksi'] == 'simpan') {
	$key = array('kdruang' => $_POST['ak']);
	if (cekdata('tbruang', $key) == 0) {
		$data = array(
			'nmruang' => $_POST['nm'],
			'isi' => $_POST['isi'],
			'kdruang' => $_POST['ak'],
			'status' => '1'
		);
		if (adddata('tbruang', $data) > 0) {
			echo '1';
		} else {
			echo '0';
		}
	} else {
		$data = array(
			'nmruang' => $_POST['nm'],
			'isi' => $_POST['isi'],
			'status' => '1'
		);
		if (editdata('tbruang', $data, '', $key) > 0) {
			echo '2';
		} else {
			echo '0';
		}
	}
}
if (isset($_POST['aksi']) && $_POST['aksi'] == 'aktif') {
	$key = array('idruang' => $_POST['id']);
	$data = viewdata('tbruang', $key)[0];
	if ($data['status'] == '1') {
		$status = array('status' => '0');
		$aksi = '1';
	}
	if ($data['status'] == '0') {
		$status = array('status' => '1');
		$aksi = '2';
	}
	if (editdata('tbruang', $status, '', $key) > 0) {
		echo $aksi;
	} else {
		echo '0';
	}
}
if (isset($_POST['aksi']) && $_POST['aksi'] == '3') {
	$sql = $conn->query("DELETE FROM tbruang WHERE idruang='$_POST[id]'");
	echo 'Hapus Ruang Ujian Berhasil!';
}
