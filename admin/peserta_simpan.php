<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
function getiduji()
{
	$quji = "SELECT idthpel, idujian, nmtes FROM tbujian u INNER JOIN tbtes t USING(idtes) WHERE u.status='1'";
	$uji = vquery($quji)[0];
	return $uji;
}
function getnopes($uji)
{
	$s = viewdata('tbskul')[0];
	$kdskul = $s['kdskul'];
	$sql = "SELECT COUNT(*) as jml FROM tbpeserta WHERE idujian='$uji'";
	$d = vquery($sql)[0];
	$id = $d['jml'] + 1;
	if ($id < 8) {
		$cekdigit = 10 - ($id % 9 + 1);
	} else {
		$cekdigit = 10 - ($id % 8 + 1);
	}
	return $kdskul . substr('0000' . $id, -4) . $cekdigit;
}

function getpasswd($hrf)
{
	$kar = '1234567890';
	$jkar = strlen($kar);
	$jkar--;
	$token = NULL;
	for ($x = 1; $x <= $hrf; $x++) {
		$pos = rand(0, $jkar);
		$token .= substr($kar, $pos, 1);
	}
	return $token;
}


if ($_POST['aksi'] == 'isi') {
	$qkls = $conn->query("SELECT MAX(idkelas) as maksid, MIN(idkelas) as minid FROM tbkelas k INNER JOIN tbskul s USING(idjenjang)");
	$kl = $qkls->fetch_array();
	$maksid = $kl['maksid'];
	$minid = $kl['minid'];

	$du = getiduji();
	$iduji = $du['idujian'];
	$nmtes = $du['nmtes'];
	$idthpel = $du['idthpel'];

	if (strpos($nmtes, "Akhir Sekolah") !== false || strpos($nmtes, "Ujian Akhir") !== false) {
		$sql = "SELECT s.*, nmrombel FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t  WHERE t.aktif='1' AND r.idkelas='$maksid' AND s.deleted='0' ORDER BY RAND(),r.idrombel ASC";
	} else if (strpos($nmtes, "Akhir Tahun") !== false) {
		$sql = "SELECT s.*, nmrombel FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) WHERE r.idthpel='$idthpel' AND r.idkelas<'$maksid' AND s.deleted='0' ORDER BY RAND()";
	} else if (strpos($nmtes, "Asesmen Kompetensi Minimum") !== false) {
		$sql = "SELECT s.*, nmrombel FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) WHERE r.idthpel='$idthpel' AND r.idkelas BETWEEN '$minid' AND '$maksid' AND s.deleted='0' ORDER BY RAND()";
	} else if (strpos($nmtes, "Seleksi Masuk") !== false || strpos($nmtes, "Tes Masuk") !== false) {
		$sql = "SELECT s.*, nmrombel FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) WHERE r.idthpel='$idthpel' AND r.idkelas='$minid' AND s.deleted='0' ORDER BY RAND()";
	} else {
		$sql = "SELECT s.*, nmrombel FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) WHERE r.idthpel='$idthpel' AND s.deleted='0' ORDER BY RAND(), r.idkelas ASC";
	}
	$jumlah = 0;
	$gagal = 0;
	$qs = vquery($sql);
	foreach ($qs as $nu) {
		$data = array(
			'idujian' => $iduji,
			'nmpeserta' => getnopes($iduji),
			'passwd' => getpasswd(6) . '*',
			'aktif' => '0'
		);
		if (editdata('tbpeserta', $data, '', array('idsiswa' => $nu['idsiswa'])) > 0) {
			$jumlah++;
		} else {
			$gagal++;
		}
	}

	$offset = 0;
	$qru = viewdata('tbruang', array('status' => '1'));
	foreach ($qru as $ru) {
		$isi = $ru['isi'];
		$ruange = $ru['idruang'];
		$sqlpst = "SELECT nmpeserta FROM tbpeserta WHERE nmpeserta<>'0' ORDER BY nmpeserta ASC LIMIT $isi OFFSET $offset";
		$qps = vquery($sqlpst);
		foreach ($qps as $ps) {
			editdata('tbpeserta', array('idruang' => $ruange), '', array('nmpeserta' => $ps['nmpeserta']));
		}
		$offset += $isi;
	}

	echo "Ada " . $jumlah . " Sukses, " . $gagal . " Gagal Ditambahkan!";
}


if ($_POST['aksi'] == 'hapus') {
	$qlp = $conn->query("TRUNCATE tblogpeserta");
	$uji = getiduji();
	$key = array('deleted' => '0');
	$ubah = array(
		'nmpeserta' => '0',
		'idujian' => NULL,
		'idruang' => NULL,
		'passwd' => NULL
	);
	if (editdata('tbpeserta', $ubah, '', $key) > 0) {
		$sql = "UPDATE tbpeserta SET nmpeserta=NULL";
		echo '1';
	}
}

if ($_POST['aksi'] == '3') {
	$sql = $conn->query("UPDATE tbpeserta SET idruang='$_POST[ruang]' WHERE nmpeserta='$_POST[idpes]'");
	echo "Pengaturan Ruang Ujian Berhasil!";
}

if ($_POST['aksi'] == 'delsesi') {
	$uji = getiduji();
	$key = array(
		'idujian' => $uji['idujian'],
		'idsiswa' => $_POST['id']
	);
	$join = array(
		'tbjadwal' => 'idjadwal',
		'tbujian' => 'idujian'
	);
	$key = array('idsiswa' => $_POST['id']);
	if (deldata('tbsesiujian', $key, $join) > 0) {
		echo '1';
	}
}
