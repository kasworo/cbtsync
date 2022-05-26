<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";

function DistribusiSoal($idb, $jml)
{
	$sqlstm = "SELECT COUNT(*) as soalstim, idstimulus FROM (SELECT so.idbutir, st.idstimulus FROM tbstimulus st INNER JOIN tbsoal so USING(idstimulus) WHERE st.idbank='$idb' ORDER BY RAND() LIMIT $jml) tbstims GROUP BY idstimulus";
	$dstm = vquery($sqlstm);
	$keluar = 0;
	$datasoal = [];
	foreach ($dstm as $stm) {
		$nsoal = $stm['soalstim'];
		$datasoal[] = array(
			'idstimulus' => $stm['idstimulus'],
			'limit' => $nsoal
		);
		$keluar += $nsoal;
		if ($keluar === $jml) break;
	}
	return $datasoal;
}


function BagiSoal($rmb, $jdw)
{
	$sql = "SELECT idset, jumsoal, idbank FROM tbsetingujian su INNER JOIN tbjadwal jd USING(idjadwal) WHERE su.idrombel='$rmb' AND jd.idjadwal='$jdw'";
	$set = vquery($sql)[0];
	$idset = $set['idset'];
	$drmb = viewdata('tbrombelsiswa', array('idrombel' => $rmb));
	foreach ($drmb as $rm) {
		$qso = DistribusiSoal($set['idbank'], $set['jumsoal']);
		foreach ($qso as $so) {
			$idstim = $so['idstimulus'];
			$limite = $so['limit'];
			$qbtsoal = "SELECT*FROM tbsoal WHERE idstimulus='$idstim' ORDER BY RAND() LIMIT $limite";
			$bts = vquery($qbtsoal);
			foreach ($bts as $bt) {
				//Pilihan Ganda Biasa
				if ($bt['jnssoal'] == '1') {
					$qopsi = "SELECT GROUP_CONCAT(idopsi ORDER BY RAND()) as viewopsi FROM tbopsi WHERE idbutir='$bt[idbutir]'";
					$vo = vquery($qopsi)[0];
					$vopsi = $vo['viewopsi'];
					$vopsialt = $vopsi;
				}
				//Pilihan Ganda Kompleks
				if ($bt['jnssoal'] == '2') {
					$qopsi = "SELECT GROUP_CONCAT(idopsi ORDER BY RAND()) as viewopsi FROM tbopsi WHERE idbutir='$bt[idbutir]'";
					$vo = vquery($qopsi)[0];
					$vopsi = $vo['viewopsi'];
					$vopsialt = $vopsi;
				}
				//Benar Atau Salah
				if ($bt['jnssoal'] == '3') {
					$qopsi = "SELECT GROUP_CONCAT(idopsi ORDER BY RAND()) as viewopsi FROM tbopsi WHERE idbutir='$bt[idbutir]'";
					$vo = vquery($qopsi)[0];
					$vopsi = $vo['viewopsi'];

					$cekopsi = explode(",", $vo['viewopsi']);
					$skr = [];
					foreach ($cekopsi as $idopsi) {
						$qskor = "SELECT benar FROM tbopsi WHERE idopsi='$idopsi'";
						$sk = vquery($qskor)[0];
						$skr[] = $sk['benar'];
						$vopsialt = implode(",", $skr);
					}
				}
				//Menjodohkan
				if ($bt['jnssoal'] == '4') {
					$qopsi = "SELECT GROUP_CONCAT(idopsi) as viewopsi FROM tbopsi WHERE idbutir='$bt[idbutir]' AND opsi IS NOT NULL";
					$vo = vquery($qopsi)[0];
					$vopsi = $vo['viewopsi'];

					$qopsialt = "SELECT GROUP_CONCAT(idopsi ORDER BY RAND()) as viewopsialt FROM tbopsi WHERE idbutir='$bt[idbutir]'";
					$va = vquery($qopsialt)[0];
					$vopsialt = $va['viewopsialt'];
				}
				//Isian Singkat
				if ($bt['jnssoal'] == '5') {
					$qopsi = "SELECT opsi FROM tbopsi WHERE idbutir='$bt[idbutir]'";
					$vo = vquery($qopsi)[0];
					$vopsi = $vo['opsi'];
					$vopsialt = $vopsi;
				}

				$keyjwb = array(
					'idsiswa' => $rm['idsiswa'],
					'idset' => $idset,
					'idbutir' => $bt['idbutir']
				);
				$cekisijawab = cekdata('tbjawaban', $keyjwb);
				if ($cekisijawab == 0) {
					$datajawab = array(
						'idsiswa' => $rm['idsiswa'],
						'idset' => $idset,
						'idbutir' => $bt['idbutir'],
						'viewopsi' => $vopsi,
						'viewopsialt' => $vopsialt
					);
					adddata('tbjawaban', $datajawab);
				} else {
					$datajawab = array(
						'viewopsi' => $vopsi,
						'viewopsialt' => $vopsialt
					);
					editdata('tbjawaban', $datajawab, '', $keyjwb);
				}
			}
		}
	}
	return 1;
}

if ($_POST['aksi'] == 'simpan') {
	$cek = cekdata('tbbanksoal', array('nmbank' => $_POST['bnk']));
	$mp = explode('&', $_POST['map']);
	if ($cek == 0) {
		$data = array(
			'idskul' => getskul(),
			'idkelas' => $_POST['kls'],
			'idmapel' => $mp[0],
			'idujian' => $_POST['tes'],
			'nmbank' => $_POST['bnk'],
			'tglbuat' => date('Y-m-d'),
			'idgtk' => $_POST['gtk'],
			'deleted' => '0'
		);
		if (adddata('tbbanksoal', $data) > 0) {
			echo '1';
		} else {
			echo '0';
		}
	} else {
		echo '2';
	}
}

if ($_POST['aksi'] == 'aktif') {
	$aktif = editdata('tbjadwal', array('aktif' => '1'), '', array('idjadwal' => $_POST['jdw']));
	if ($aktif > 0) {
		$sql = "UPDATE tbjadwal SET aktif='0' WHERE idjadwal<>'$_POST[jdw]' AND aktif='1'";
		equery($sql);
	}
	$keyu = array(
		'idbank' => $_POST['idb'],
		'idrombel' => $_POST['rmb'],
		'idjadwal' => $_POST['jdw']
	);
	if (cekdata('tbsetingujian', $keyu) > 0) {
		$data = array(
			'hasil' => $_POST['hsl'],
			'jumsoal' => $_POST['soal'],
			'acaksoal' => '1',
			'acakopsi' => '1'
		);
		$row = editdata('tbsetingujian', $data, '', $keyu);
		if ($row > 0) {
			$sql = "DELETE FROM tbjawaban INNER JOIN tbsetingujian su USING(idset) WHERE idbank='$_POST[idb]' AND idrombel='$_POST[rmb]' AND idjadwal='$_POST[jdw]'";
			$hps = equery($sql);
			if ($hps > 0) {
				BagiSoal($_POST['rmb'],  $_POST['jdw']);
				echo '2';
			}
		}
	} else {
		$data = array(
			'idbank' => $_POST['idb'],
			'idrombel' => $_POST['rmb'],
			'idjadwal' => $_POST['jdw'],
			'hasil' => $_POST['hsl'],
			'jumsoal' => $_POST['soal'],
			'acaksoal' => '1',
			'acakopsi' => '1'
		);
		if (adddata('tbsetingujian', $data) > 0) {
			BagiSoal($_POST['rmb'],  $_POST['jdw']);
			echo '1';
		}
	}
}

if ($_POST['aksi'] == '3') {
	$sql = $conn->query("DELETE FROM tbsetingujian WHERE idbank='$_POST[id]' AND idjadwal='$_POST[jd]'");
	echo 'Seting Ujian Berhasil Dihapus!';
}

if ($_POST['aksi'] == '4') {
	$sql = $conn->query("UPDATE tbbanksoal SET deleted='1' WHERE idbank='$_POST[id]'");
	echo 'Bank Soal Berhasil Dihapus!';
}

if ($_POST['aksi'] == '5') {
	$sql = $conn->query("UPDATE tbbanksoal SET deleted='1' WHERE idujian='$_POST[id]'");
	echo 'Bank Soal Berhasil Dihapus!';
}
