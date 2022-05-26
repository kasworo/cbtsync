<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
$saiki = date('Y-m-d H:i:s');
$qwk = "SELECT TIME_TO_SEC(timediff('$saiki', logmulai)) as habis, jd.durasi FROM tblogpeserta lp INNER JOIN tbjadwal jd USING(idjadwal) WHERE lp.idjadwal= '$_POST[jdw]' AND idsiswa='$_COOKIE[pst]'";
$wk = vquery($qwk)[0];
$durasi = intval($wk['durasi']) * 60;
$habis = $wk['habis'];
$selisih = $durasi - $habis;
if ($selisih <= 0) {
	$key = array(
		'idjadwal' => $_POST['jdw'],
		'idsiswa' => $_COOKIE['pst']
	);
	$isilog = array(
		'logakhir' => $saiki,
		'sisawaktu' => '0',
		'status' => '1'
	);
	if (editdata('tblogpeserta', $isilog, '', $key)) {
		echo
		'<script type="text/javascript">
			$(document).ready(function() {
				Swal.fire({
					title: "Mohon Maaf",
					text: "Waktu Ujian Anda Sudah Habis",
					icon: "error",
					showCancelButton: false,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "OK",
				}).then((result) => {
					if (result.value) {
						window.location.href = "index.php?p=end";
					}
				})
			})
		</script>';
	}
} else {
	$key = array(
		'idjadwal' => $_POST['jdw'],
		'idsiswa' => $_COOKIE['pst']
	);
	$isilog = array(
		'logakhir' => $saiki,
		'sisawaktu' => $selisih,
		'status' => '0'
	);
	editdata('tblogpeserta', $isilog, '', $key);
}
