<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
$saiki = date('Y-m-d H:i:s');
if (isset($_POST['aksi']) && $_POST['aksi'] == 'reset') {
	if (isset($_POST['id'])) {
		$sql = "SELECT COUNT(*) as jawab,su.jumsoal, jd.durasi FROM tbjawaban jw INNER JOIN tbsetingujian su USING(idset) INNER JOIN tbjadwal jd USING(idjadwal) WHERE jw.idsiswa='$_POST[id]' AND su.idjadwal='$_POST[jd]' AND jw.jwbbenar is NULL GROUP BY jw.idsiswa, jw.idset";

		$qcekjwb = $conn->query($sql);
		$cek = $qcekjwb->fetch_array();
		$kosong = $cek['jawab'];
		$jmlsoal = $cek['jumsoal'];
		$durasi = $cek['durasi'] * 60;
		if ($kosong == $jumsoal) {
			$conn->query("UPDATE tblogpeserta SET status='0', sisawaktu='$durasi', logmulai='$saiki', logakhir='$saiki' WHERE status='1' AND idjadwal='$_POST[jd]' AND idsiswa='$_POST[id]' AND sisawaktu>0");
		} else {
			$conn->query("UPDATE tblogpeserta SET status='0' WHERE status='1' AND idjadwal='$_POST[jd]' AND idsiswa='$_POST[id]'");
		}
		$conn->query("UPDATE tbpeserta SET aktif='1' WHERE idsiswa='$_POST[id]'");
	} else {
		$sql = "SELECT COUNT(*) as jawab,su.jumsoal, jd.durasi FROM tbjawaban jw INNER JOIN tbsetingujian su USING(idset) INNER JOIN tbjadwal jd USING(idjadwal) WHERE su.idjadwal='$_POST[jd]' AND jw.jwbbenar is NULL GROUP BY jw.idsiswa, jw.idset";
		$qcekjwb = $conn->query($sql);
		$cek = $qcekjwb->fetch_array();
		$kosong = $cek['jawab'];
		$jmlsoal = $cek['jumsoal'];
		$durasi = $cek['durasi'] * 60;
		if ($kosong == $jumsoal) {
			$conn->query("UPDATE tblogpeserta SET status='0', sisawaktu='$durasi', logmulai='$saiki', logakhir='$saiki' WHERE status='1' AND idjadwal='$_POST[jd]' AND sisawaktu>0");
		} else {
			$conn->query("UPDATE tblogpeserta SET status='0', durasi='$durasi' WHERE status='1' AND idjadwal='$_POST[jd]'");
		}
	}

	echo 'Reset Peserta Berhasil!';
}

if (isset($_POST['aksi']) && $_POST['aksi'] == 'logout') {
	if (isset($_POST['id'])) {
		$qwk = "SELECT TIME_TO_SEC(timediff('$saiki', logmulai)) as habis, jd.durasi FROM tblogpeserta lp INNER JOIN tbjadwal jd USING(idjadwal) WHERE lp.idjadwal= '$_POST[jd]' AND idsiswa='$_POST[id]'";
		$wk = vquery($qwk)[0];
		$durasi = intval($wk['durasi']) * 60;
		$habis = $wk['habis'];
		$selisih = $durasi - $habis;
		if ($selisih < 0) $sisawaktu = 0;
		else $sisawaktu = $selisih;
		$key = array(
			'idsiswa' => $_POST['id'],
			'idjadwal' => $_POST['jd']
		);
		$data = array(
			'status' => '1',
			'sisawaktu' => $sisawaktu
		);
		$row = editdata('tblogpeserta', $data, '', $key);
		if ($row > 0) {
			editdata('tbpeserta', array('aktif' => '0'), '', array('idsiswa' => $_POST['id']));
			echo 1;
		}
	} else {
		$qlp = viewdata('tblogpeserta', array('idjadwal' => $_POST['jd']));
		$sukses = 0;
		foreach ($qlp as $lp) {
			$qwk = "SELECT TIME_TO_SEC(timediff('$saiki', logmulai)) as habis, jd.durasi FROM tblogpeserta lp INNER JOIN tbjadwal jd USING(idjadwal) WHERE lp.idjadwal= '$_POST[jd]' AND idsiswa='$lp[idsiswa]'";
			$wk = vquery($qwk)[0];
			$durasi = intval($wk['durasi']) * 60;
			$habis = $wk['habis'];
			$selisih = $durasi - $habis;
			if ($selisih < 0) $sisawaktu = 0;
			else $sisawaktu = $selisih;
			$key = array(
				'idsiswa' => $lp['idsiswa'],
				'idjadwal' => $_POST['jd']
			);
			$data = array(
				'status' => '1',
				'sisawaktu' => $sisawaktu
			);
			$rows = editdata('tblogpeserta', $data, '', $key);
			if ($rows > 0) {
				editdata('tbpeserta', array('aktif' => '0'), '', array('idsiswa' => $lp['idsiswa']));
				$sukses++;
			}
		}
		echo 1;
	}
}
