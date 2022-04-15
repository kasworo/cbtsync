<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
$thn = viewdata('tbthpel', array('aktif' => '1'))[0];
$idthpel = $thn['idthpel'];
if ($_POST['aksi'] == 'simpan') {
	if ($_POST['id'] == '' || $_POST['id'] == NULL) {
		$data = array(
			'idkur' => $_POST['kur'],
			'idkelas' => $_POST['kls'],
			'nmrombel' => $_POST['nama'],
			'idgtk' => $_POST['walas'],
			'idthpel' => $idthpel
		);
		if (adddata('tbrombel', $data) > 0) {
			echo '1';
		}
	} else {
		$key = array(
			'idrombel' => $_POST['id']
		);
		$data = array(
			'idkur' => $_POST['kur'],
			'idkelas' => $_POST['kls'],
			'nmrombel' => $_POST['nama'],
			'idgtk' => $_POST['walas'],
			'idthpel' => $idthpel
		);
		if (editdata('tbrombel', $data, '', $key) > 0) {
			echo '1';
		}
	}
}
if ($_POST['aksi'] == '2') {
	$sql = $conn->query("DELETE FROM tbrombel WHERE idrombel='$_POST[id]'");
	echo 'Hapus Rombongan Belajar Berhasil!';
}
if ($_POST['aksi'] == '3') {
	$sql = $conn->query("TRUNCATE tbrombel");
	echo 'Hapus Rombongan Belajar Berhasil!';
}
