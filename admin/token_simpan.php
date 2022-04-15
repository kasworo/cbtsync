<?php
include "dbfunction.php";
include "token_function.php";
$jam = date('Y-m-d H:i:s');
$keyt = array(
	'idjadwal' => $_POST['jdw'],
	'idsesi' => $_POST['sesi'],
	'status' => '1'
);
if (cekdata('tbtoken', $keyt) == 0) {
	$data = array(
		'idjadwal' => $_POST['jdw'],
		'idsesi' => $_POST['sesi'],
		'token' => $_POST['token'],
		'jamrilis' => $jam,
		'tampil' => $_POST['tampil'],
		'status' => '1'
	);
	if (adddata('tbtoken', $data) > 0) {
		$sqlt = "UPDATE tbtoken SET status='0' WHERE idjadwal<>'$_POST[jdw]' OR idsesi<>'$_POST[sesi]'";
		$sqllp = "UPDATE tblogpeserta as lp INNER JOIN tbsesiujian as su ON lp.idsiswa=su.idsiswa SET lp.status='1' WHERE su.idjadwal='$_POST[jdw]' AND su.idsesi<>'$_POST[sesi]'";
		$sqla = "UPDATE tbpeserta as ps INNER JOIN tbsesiujian as su USING(idsiswa) INNER JOIN tbujian as u USING(idujian) SET ps.aktif='1' WHERE su.idjadwal='$_POST[jdw]' AND su.idsesi='$_POST[sesi]' AND u.status='1' AND ps.aktif='0' OR ps.aktif IS NULL";
		$updtk = equery($sqlt);
		$updlp = equery($sqllp);
		$updps = equery($sqla);
		if ($updtk == 1 && $updlp == 1 && $updps == 1) {
			echo '1';
		} else {
			echo '0';
		}
	}
} else {
	$token = gettoken(6);
	$data = array(
		'tampil' => $_POST['tampil'],
		'token' => gettoken(6),
		'jamrilis' => $jam
	);
	if (editdata('tbtoken', $data, '', $keyt) > 0) {
		echo '2';
	}
}
