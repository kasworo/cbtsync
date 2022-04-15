<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
function GetNamaJadwal($id)
{
	$uji = viewdata('tbujian', array('idujian' => $id))[0];
	$kduji = $uji['nmujian'];

	$sql = "SELECT count(*) as jml FROM tbjadwal j WHERE j.idujian='$id'";
	$d = vquery($sql)[0];
	$urut = $d['jml'] + 1;
	if ($urut > 9) {
		$urt = substr('00' . $urut, 1, 3);
	} else {
		$urt = substr('00' . $urut, 0, 3);
	}
	return $kduji . $urt;
}

if ($_POST['aksi'] == 'simpan') {
	$key = array(
		'idjadwal' => $_POST['id']
	);
	$cek = cekdata('tbjadwal', $key);
	if ($cek == 0) {
		$data = array(
			'idujian' => $_POST['idtes'],
			'kdjadwal' => GetNamaJadwal($_POST['idtes']),
			'matauji' => $_POST['mtuji'],
			'tglujian' => $_POST['tgl'],
			'durasi' => $_POST['lama'],
			'mulai' => $_POST['mulai'],
			'lambat' => $_POST['lmb'],
			'susulan' => $_POST['utm']
		);
		if (adddata('tbjadwal', $data) > 0) {
			echo '1';
		} else {
			echo '0';
		}
	} else {
		$data = array(
			'matauji' => $_POST['mtuji'],
			'tglujian' => $_POST['tgl'],
			'durasi' => $_POST['lama'],
			'lambat' => $_POST['lmb'],
			'susulan' => $_POST['utm'],
			'mulai' => $_POST['mulai']
		);
		if (editdata('tbjadwal', $data, '', $key) > 0) {
			echo '2';
		} else {
			echo '0';
		}
	}
}


if (isset($_POST['aksi']) && $_POST['aksi'] == 'token') {
	$qcek = $conn->query("SELECT viewtoken FROM tbjadwal WHERE idjadwal='$_POST[jdw]'");
	$ct = $qcek->fetch_array();
	$cektoken = $ct['viewtoken'];
	if ($cektoken == '1') {
		$sql = $conn->query("UPDATE tbjadwal SET viewtoken='0' WHERE idjadwal='$_POST[jdw]'");
		$pesan = 'Token Berhasil Disembunyikan, Mintalah Peserta Untuk Menghubungi Pengawas';
	} else {
		$sql = $conn->query("UPDATE tbjadwal SET viewtoken='1' WHERE idjadwal='$_POST[jdw]'");
		$pesan = 'Token Berhasil Ditampilkan, Mintalah Peserta Melihat Teks Berwarna Merah';
	}
	echo $pesan;
}

if (isset($_POST['aksi']) && $_POST['aksi'] == 'hasil') {
	$qcek = $conn->query("SELECT hasil FROM tbjadwal WHERE idjadwal='$_POST[jdw]'");
	$vh = $qcek->fetch_array();
	$cekhasil = $vh['hasil'];
	if ($cekhasil == '1') {
		$sql = $conn->query("UPDATE tbjadwal SET hasil='0' WHERE idjadwal='$_POST[jdw]'");
		$pesan = 'Hasil Ujian Disembunyikan';
	} else {
		$sql = $conn->query("UPDATE tbjadwal SET hasil='1' WHERE idjadwal='$_POST[jdw]'");
		$pesan = 'Hasil Ujian Ditampilkan';
	}
	echo $pesan;
}

if (isset($_POST['aksi']) && $_POST['aksi'] == '4') {
	$sql = $conn->query("DELETE FROM tbjadwal WHERE kdjadwal='$_POST[id]'");
	echo 'Hapus Jadwal Berhasil!';
}
