<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
if ($_POST['aksi'] == 'simpan') {
	$th = viewdata('tbthpel', array('aktif' => '1'))[0];
	$key = array(
		'idthpel' => $th['idthpel'],
		'idmapel' => $_POST['id'],
		'idkelas' => $_POST['kls']
	);
	$cek = cekdata('tbkkm', $key);
	if ($cek == 0) {
		$datane = array(
			'idthpel' => $th['idthpel'],
			'idmapel' => $_POST['id'],
			'idkelas' => $_POST['kls'],
			'kkm' => $_POST['kkm']
		);
		if (adddata('tbkkm', $datane) > 0) {
			echo '1';
		} else {
			echo '0';
		}
	} else {
		$datane = array(
			'kkm' => $_POST['kkm']
		);
		if (editdata('tbkkm', $datane, '', $key) > 0) {
			echo '2';
		} else {
			echo '0';
		}
	}
}

if ($_POST['aksi'] == 'salin') {
	$sql = $conn->query("REPLACE INTO tbkkm (idmapel, idkelas, kkm, idthpel) SELECT idmapel, idkelas, kkm, '$_POST[tuju]' FROM tbkkm WHERE idthpel='$_POST[asal]'");
	echo 'Salin KKM Berhasil!';
}

if ($_POST['aksi'] == 'kosong') {
	$sql = $conn->query("DELETE FROM tbkkm WHERE idthpel='$idthpel'");
	echo 'Hapus Data KKM Berhasil!';
}
