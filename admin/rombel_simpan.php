<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
if ($_POST['aksi'] == 'simpan') {
	$qcek = "SELECT*FROM tbrombelsiswa rs INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t USING(idthpel) WHERE rs.idsiswa='$_POST[ids]' AND t.aktif='1'";
	$cek = cquery($qcek);
	if ($cek == 0) {
		$data = array(
			'idsiswa' => $_POST['ids'],
			'idrombel' => $_POST['idr']
		);
		if (adddata('tbrombelsiswa', $data) > 0) {
			echo '1';
		} else {
			echo '0';
		}
	} else {
		$key = array(
			'idsiswa' => $_POST['ids'],
			'aktif' => '1'
		);
		$data = array(
			'idrombel' => $_POST['idr']
		);
		$join = array(
			'tbrombel' => 'idrombel',
			'tbthpel' => 'idthpel'
		);
		if (editdata('tbrombelsiswa', $data, $join, $key) > 0) {
			echo '2';
		} else {
			echo '0';
		}
	}
}

if (isset($_POST['aksi']) && $_POST['aksi'] == '2') {
	$sql = $conn->query("REPLACE INTO tbrombelsiswa (idsiswa, idrombel) SELECT idsiswa, '$_POST[rb]' FROM tbrombelsiswa rs INNER JOIN tbsiswa s USING(idsiswa) WHERE idrombel='$_POST[ra]' AND s.deleted='0'");
	echo 'Salin Anggota Rombel Berhasil!';
}
