<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
if ($_POST['aksi'] == 'simpan') {
	if ($_POST['id'] == '') {
		$sqamp = "SELECT a.*FROM tbpengampu a INNER JOIN tbrombel USING(idrombel) INNER JOIN tbthpel t USING(idthpel) WHERE a.idmapel='$_POST[mp]' AND a.idrombel='$_POST[rmb]' AND t.aktif='1'";
		if (cquery($sqamp) > 0) {
			$keyp = array(
				'idrombel' => $_POST['rmb'],
				'idmapel' => $_POST['mp'],
				'aktif' => '1'
			);
			$join = array(
				'tbrombel' => 'idrombel',
				'tbthpel' => 'idthpel'
			);
			$data = array(
				'idgtk' => $_POST['gtk']
			);
			if (editdata('tbpengampu', $data, $join, $keyp) > 0) {
				echo '2';
			} else {
				echo '0';
			}
		} else {
			$data = array(
				'idrombel' => $_POST['rmb'],
				'idgtk' => $_POST['gtk'],
				'idmapel' => $_POST['mp']
			);
			if (adddata('tbpengampu', $data) > 0) {
				echo '1';
			} else {
				echo '0';
			}
		}
	} else {
		$keyp = array('idampu' => $_POST['id']);
		$data = array(
			'idrombel' => $_POST['rmb'],
			'idgtk' => $_POST['gtk'],
			'idmapel' => $_POST['mp']
		);
		if (editdata('tbpengampu', $data, '', $keyp) > 0) {
			echo '2';
		} else {
			echo '0';
		}
	}
}
// 	if ($_POST['rm'] == '0') {
// 		$qrmb = $conn->query("SELECT idrombel FROM tbrombel r INNER JOIN tbthpel t USING(idthpel) WHERE t.aktif='1' AND idkelas='$_POST[kl]'");
// 		$tmb = 0;
// 		$upd = 0;
// 		while ($r = $qrmb->fetch_array()) {
// 			$qcek = $conn->query("SELECT a.*FROM tbpengampu a INNER JOIN tbthpel t ON t.idthpel=a.idthpel WHERE a.idmapel='$_POST[mp]' AND a.idrombel='$r[idrombel]' AND idthpel='$idthpel'");
// 			$cek = $qcek->num_rows;
// 			if ($cek == 0) {
// 				$sql = $conn->query("INSERT INTO tbpengampu(idrombel, idmapel, username, idthpel) VALUES ('$r[idrombel]','$_POST[mp]','$_POST[gr]','$idthpel')");
// 				$tmb++;
// 			} else {
// 				$sql = $conn->query("UPDATE tbpengampu SET idrombel='$r[idmapel]', idmapel='$_POST[mp]', username='$_POST[gr]' WHERE idmapel='$_POST[mp]' AND  idrombel='$s[idrombel]' AND idthpel='$idthpel'");
// 				$upd++;
// 			}
// 		}
// 		if ($tmb > 0 && $upd == 0) {
// 			echo 'Ada ' . $tmb . ' Data Pengampu Berhasil Ditambahkan';
// 		} else if ($tmb == 0 && $upd > 0) {
// 			echo 'Ada ' . $upd . ' Data Pengampu Berhasil Diupdate';
// 		} else {
// 			echo 'Ada ' . $tmb . ' Data Berhasil Ditambahkan, ' . $upd . ' Diupdate';
// 		}
// 	} else {
// 		$qcek = $conn->query("SELECT*FROM tbpengampu WHERE idampu='$_POST[id]' OR (idmapel='$_POST[mp]' AND idrombel='$_POST[rm]' AND idthpel='$idthpel')");
// 		$cek = $qcek->num_rows;

// 		if ($cek == 0) {
// 			$sql = $conn->query("INSERT INTO tbpengampu(idrombel, idmapel, username, idthpel) VALUES ('$_POST[rm]','$_POST[mp]','$_POST[gr]','$idthpel')");
// 			echo 'Simpan Data Pembelajaran Berhasil!';
// 		} else {
// 			$sql = $conn->query("UPDATE tbpengampu SET idrombel='$_POST[rm]', idmapel='$_POST[mp]', username='$_POST[gr]' WHERE idampu='$_POST[id]' OR (idmapel='$_POST[mp]' AND idrombel='$_POST[rm]' AND idthpel='$idthpel')");
// 			echo 'Update Data Pengampu Berhasil!';
// 		}
// 	}
// }

if ($_POST['aksi'] == 'salin') {
	$sqamp = "SELECT pg.idmapel, pg.idgtk, tp.idthpel FROM tbpengampu pg INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbthpel tp USING(idthpel) WHERE tp.aktif='1' AND pg.idrombel='$_POST[idra]'";

	if (cquery($sqamp) > 0) {
		$baru = 0;
		$qamp = vquery($sqamp);
		foreach ($qamp as $sa) {
			$sql = "INSERT INTO tbpengampu(idrombel, idmapel, idgtk) VALUES ('$_POST[idrt]','$sa[idmapel]','$sa[idgtk]')";

			if (equery($sql) > 0) {
				$baru++;
			}
		}
		if ($baru > 0) {
			echo 'Salin Data Pembelajaran Berhasil!';
		} else {
			echo 'Salin Pembelajaran Gagal!';
		}
	} else {
		echo "Data Pembelajaran Asal Belum Ada";
	}
}

if ($_POST['aksi'] == 'hapus') {
	$sql = $conn->query("DELETE FROM tbpengampu WHERE idampu='$_POST[id]'");
	echo 'Hapus Data Pembelajaran Berhasil!';
}

if ($_POST['aksi'] == 'kosong') {
	$sql = $conn->query("DELETE FROM tbpengampu WHERE idthpel='$idthpel'");
	echo 'Hapus Semua Data Pembelajaran Berhasil!';
}
