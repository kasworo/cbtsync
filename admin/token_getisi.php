<?php
include "dbfunction.php";
include "token_function.php";
if ($_POST['jdw'] == '' || $_POST['jdw'] == null || $_POST['sesi'] == '' || $_POST['sesi'] == null) {
	$data = array(
		'jam' => '...',
		'token' => '...',
		'pesan' => '...'
	);
} else {
	$skrg = date('Y-m-d');
	$jam = date('H:i:s');
	$qcek = "SELECT TIME_TO_SEC(timediff('$jam', t.jamrilis)) AS waktu, t.jamrilis, t.token FROM tbtoken t WHERE t.idjadwal='$_POST[jdw]' AND t.idsesi='$_POST[sesi]' AND t.status='1'";
	$cek = cquery($qcek);
	if ($cek > 0) {
		$d = vquery($qcek)[0];
		$dtjmtoken = $d['jamrilis'];
		$dttoken = $d['token'];
		$selisih = $d['waktu'];
		$data = array(
			'jam' => $dtjmtoken,
			'pesan' => $dttoken . ' (Update Terakhir ' . $dtjmtoken . ')'
		);
	} else {
		$token = getToken(6);
		$data = array(
			'jam' => $jam,
			'pesan' => $token . ' (Update Terakhir ' . substr($jam, -8) . ')'
		);
	}
}
echo json_encode($data);
