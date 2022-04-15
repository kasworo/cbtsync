<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
function getnamaujian($tahun, $tes)
{
	$dth = viewdata('tbthpel', array('idthpel' => $tahun))[0];
	$dts = viewdata('tbtes', array('idtes' => $tes))[0];
	return $dts['aktes'] . $dth['nmthpel'];
}

if ($_POST['aksi'] == 'simpan') {
	if ($_POST['id'] == '') {
		$keyt = array('aktes' => $_POST['kd']);
		if (cekdata('tbtes', $keyt) > 0) {
			$data = array('nmtes' => $_POST['nm']);
			if (editdata('tbtes', $data, '', $keyt) > 0) {
				echo '2';
			} else {
				echo '0';
			}
		} else {
			$data = array('aktes' => $_POST['kd'], 'nmtes' => $_POST['nm']);
			if (adddata('tbtes', $data) > 0) {
				echo '1';
			} else {
				echo '0';
			}
		}
	} else {
		$keyt = array('idtes' => $_POST['id']);
		$data = array(
			'aktes' => $_POST['kd'],
			'nmtes' => $_POST['nm']
		);
		if (editdata('tbtes', $data, '', $keyt) > 0) {
			echo '2';
		} else {
			echo '0';
		}
	}
}

if ($_POST['aksi'] == 'aktif') {
	$keyu = array(
		'idthpel' => $_POST['th'],
		'idtes' => $_POST['id']
	);
	$cek = cekdata('tbujian', $keyu);
	if ($cek == 0) {
		$data = array(
			'idtes' => $_POST['id'],
			'idthpel' => $_POST['th'],
			'status' => '1',
			'nmujian' => getnamaujian($_POST['th'], $_POST['id'])
		);
		if (adddata('tbujian', $data) > 0) {
			$sql = "UPDATE tbujian SET status='0' WHERE idtes<>'$_POST[id]' AND status='1'";
			equery($sql);
			echo '1';
		}
	} else {
		$ak = viewdata('tbujian', $keyu)[0];
		$aktif = $ak['status'];
		if ($aktif == '1') {
			$data = array('status' => '0');
			if (editdata('tbujian', $data, '', $keyu) > 0) {
				echo '0';
			}
		} else {
			$data = array('status' => '1');
			if (editdata('tbujian', $data, '', $keyu) > 0) {
				$sql = "UPDATE tbujian SET status='0' WHERE idtes<>'$_POST[id]' AND status='1'";
				equery($sql);
				echo '2';
			}
		}
	}
}

if ($_POST['aksi'] == 'kosong') {
	$sql = $conn->query("TRUNCATE tbtes");
	echo 'Hapus Jenis Tes Berhasil!';
}
if ($_POST['aksi'] == 'hapus') {
	$sql = $conn->query("DELETE FROM tbtes WHERE idtes='$_POST[id]'");
	echo 'Hapus Jenis Tes Berhasil!';
}
