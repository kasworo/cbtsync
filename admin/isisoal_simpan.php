<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
function GetOpsine($key)
{
	$idbutir = GetIdButir($key);
	$sql = "SELECT idopsi FROM tbopsi op WHERE idbutir='$idbutir'";
	$rows = vquery($sql);
	$opsi = [];
	foreach ($rows as $row) {
		$opsi[] = $row['idopsi'];
	}
	return $opsi;
}

function GetIdButir($key)
{
	$row = viewdata('tbsoal', $key)[0];
	return $row['idbutir'];
}

if ($_POST['aksi'] == 'simpan') {
	$asline = str_replace("&hellip;", "...", $conn->real_escape_string($_POST['bt']));
	$asline = str_replace(".&hellip;", "...", $asline);
	$asline = str_replace("...", " .... ", $asline);
	$asline = str_replace("<br />", "<br/>", $asline);
	$arr = explode("<br/>", $asline);
	$jml = count($arr);
	$soal = $arr[0];
	$bs = explode("#", $soal);
	$key = array(
		'idstimulus' => $_POST['ids'],
		'nomersoal' => $_POST['nm']
	);
	if (count($bs) > 1) {
		$butir = $bs[0];
		$header = $bs[1];
		if (cekdata('tbsoal', $key) > 0) {
			$data = array(
				'jnssoal' => $_POST['js'],
				'modeopsi' => $_POST['mo'],
				'tksukar' => $_POST['tk'],
				'butirsoal' => $butir,
				'skormaks' => $_POST['sk'],
				'headeropsi' => $header
			);
			$row = editdata('tbsoal', $data, '', $key);
		} else {
			$data = array(
				'idstimulus' => $_POST['ids'],
				'jnssoal' => $_POST['js'],
				'modeopsi' => $_POST['mo'],
				'tksukar' => $_POST['tk'],
				'butirsoal' => $soal,
				'skormaks' => $_POST['sk'],
				'nomersoal' => $_POST['nm'],
				'headeropsi' => $header
			);
			$row = adddata('tbsoal', $data);
		}
	} else {
		$butir = $bs[0];
		if (cekdata('tbsoal', $key) > 0) {
			$data = array(
				'jnssoal' => $_POST['js'],
				'modeopsi' => $_POST['mo'],
				'tksukar' => $_POST['tk'],
				'butirsoal' => $butir,
				'skormaks' => $_POST['sk']
			);
			$row = editdata('tbsoal', $data, '', $key);
		} else {
			$data = array(
				'idstimulus' => $_POST['ids'],
				'jnssoal' => $_POST['js'],
				'modeopsi' => $_POST['mo'],
				'tksukar' => $_POST['tk'],
				'butirsoal' => $butir,
				'skormaks' => $_POST['sk'],
				'nomersoal' => $_POST['nm']
			);
			$row = adddata('tbsoal', $data);
		}
	}

	if ($row == 1) {
		for ($i = 1; $i < $jml; $i++) {
			$isiopsi = GetOpsine($key);
			$opsine = array(
				'idbutir' => GetIdButir($key),
				'opsi' => $arr[$i],
				'benar' => '0',
				'skor' => '0'
			);
			adddata('tbopsi', $opsine);
		}
	}
}

if ($_POST['aksi'] == 'opsi') {
	$ds = viewdata('tbsoal', array('idbutir' => $_POST['idbutir']))[0];
	$modeopsi = $ds['modeopsi'];
	$jnssoal = $ds['jnssoal'];

	if ($modeopsi == '1' && ($jnssoal == '1' || $jnssoal == '2')) {
		if ($_POST['benar'] == '1') {
			$skor = 1;
		} else {
			$skor = 0;
		}
		if (isset($_POST['idops'])) {
			if (isset($_FILES)) {
				$data = array(
					'opsi' => $conn->real_escape_string($_POST['opsi']),
					'benar' => $_POST['benar'],
					'skor' => $skor
				);
			} else {
				$data = array(
					'opsi' => $conn->real_escape_string($_POST['opsi']),
					'benar' => $_POST['benar'],
					'skor' => $skor,
					'gambaropsi' => uploadfile($file)
				);
			}
			if (editdata('tbopsi', $data, '', array('idopsi' => $_POST['idops'])) > 0) {
				echo '2';
			} else {
				echo '0';
			}
		} else {
			if (empty($_FILES)) {
				$data = array(
					'idbutir' => $_POST['idbutir'],
					'opsi' => $conn->real_escape_string($_POST['opsi']),
					'benar' => $_POST['benar'],
					'skor' => $skor
				);
			} else {
				$data = array(
					'idbutir' => $_POST['idbutir'],
					'opsi' => $conn->real_escape_string($_POST['opsi']),
					'benar' => $_POST['benar'],
					'skor' => $skor,
					'gambaropsi' => uploadfile($_FILES['file'])
				);
			}
			if (adddata('tbopsi', $data) > 0) {
				echo '1';
			} else {
				echo '0';
			}
		}
	} else {
		if ($jnssoal == '1' || $jnssoal == '2') {
			if ($_POST['benar'] == '1') {
				$skor = 1;
				$b = 1;
			} else {
				$skor = 0;
				$b = 0;
			}
			if (isset($_POST['idops'])) {
				if (isset($_FILES)) {
					$data = array(
						'opsi' => $conn->real_escape_string($_POST['opsi']),
						'benar' => $_POST['benar'],
						'skor' => $skor
					);
				} else {
					$data = array(
						'opsi' => $conn->real_escape_string($_POST['opsi']),
						'benar' => $_POST['benar'],
						'skor' => $skor,
						'gambaropsi' => uploadfile($file)
					);
				}
				if (editdata('tbopsi', $data, '', array('idopsi' => $_POST['idops'])) > 0) {
					echo '2';
				} else {
					echo '0';
				}
			} else {
				if (empty($_FILES)) {
					$data = array(
						'idbutir' => $_POST['idbutir'],
						'opsi' => $conn->real_escape_string($_POST['opsi']),
						'benar' => $_POST['benar'],
						'skor' => $skor
					);
				} else {
					$data = array(
						'idbutir' => $_POST['idbutir'],
						'opsi' => $conn->real_escape_string($_POST['opsi']),
						'benar' => $_POST['benar'],
						'skor' => $skor,
						'gambaropsi' => uploadfile($_FILES['file'])
					);
				}

				if (adddata('tbopsi', $data) > 0) {
					echo '1';
				} else {
					echo '0';
				}
			}
		}
		if ($jnssoal == '3') {
			if ($_POST['benar'] == '1') {
				$skor = 1;
				$b = 1;
			} else {
				$skor = 1;
				$b = 0;
			}
			if (isset($_POST['idops'])) {
				if (isset($_FILES)) {
					$data = array(
						'opsi' => $conn->real_escape_string($_POST['opsi']),
						'benar' => $_POST['benar'],
						'skor' => $skor
					);
				} else {
					$data = array(
						'opsi' => $conn->real_escape_string($_POST['opsi']),
						'benar' => $_POST['benar'],
						'skor' => $skor,
						'gambaropsi' => uploadfile($file)
					);
				}
				if (editdata('tbopsi', $data, '', array('idopsi' => $_POST['idops'])) > 0) {
					echo '2';
				} else {
					echo '0';
				}
			} else {
				if (empty($_FILES)) {
					$data = array(
						'idbutir' => $_POST['idbutir'],
						'opsi' => $conn->real_escape_string($_POST['opsi']),
						'benar' => $_POST['benar'],
						'skor' => $skor
					);
				} else {
					$data = array(
						'idbutir' => $_POST['idbutir'],
						'opsi' => $conn->real_escape_string($_POST['opsi']),
						'benar' => $_POST['benar'],
						'skor' => $skor,
						'gambaropsi' => uploadfile($_FILES['file'])
					);
				}

				if (adddata('tbopsi', $data) > 0) {
					echo '1';
				} else {
					echo '0';
				}
			}
		}
		if ($jnssoal == '4') {
			if ($_POST['benar'] == '1') {
				$skor = 1;
				$b = 1;
			} else {
				$skor = 0;
				$b = 0;
			}
			if (isset($_POST['idops'])) {
				if (isset($_FILES)) {
					$data = array(
						'opsi' => $conn->real_escape_string($_POST['opsi']),
						'opsialt' => $conn->real_escape_string($_POST['opsia']),
						'benar' => $_POST['benar'],
						'skor' => $skor
					);
				} else {
					$data = array(
						'opsi' => $conn->real_escape_string($_POST['opsi']),
						'opsialt' => $conn->real_escape_string($_POST['opsia']),
						'benar' => $_POST['benar'],
						'skor' => $skor,
						'gambaropsi' => uploadfile($file)
					);
				}
				if (editdata('tbopsi', $data, '', array('idopsi' => $_POST['idops'])) > 0) {
					echo '2';
				} else {
					echo '0';
				}
			} else {
				if (empty($_FILES)) {
					$data = array(
						'idbutir' => $_POST['idbutir'],
						'opsi' => $conn->real_escape_string($_POST['opsi']),
						'opsialt' => $conn->real_escape_string($_POST['opsia']),
						'benar' => $_POST['benar'],
						'skor' => $skor
					);
				} else {
					$data = array(
						'idbutir' => $_POST['idbutir'],
						'opsi' => $conn->real_escape_string($_POST['opsi']),
						'opsialt' => $conn->real_escape_string($_POST['opsia']),
						'benar' => $_POST['benar'],
						'skor' => $skor,
						'gambaropsi' => uploadfile($_FILES['file'])
					);
				}

				if (adddata('tbopsi', $data) > 0) {
					echo '1';
				} else {
					echo '0';
				}
			}
		}
	}
}
if ($_POST['aksi'] == 'isikunci') {
	$ds = viewdata('tbsoal', array('idbutir' => $_POST['idsoal']))[0];
	$modeopsi = $ds['modeopsi'];
	$jnssoal = $ds['jnssoal'];
	$sukses = 0;
	$gagal = 0;
	// Pilihan Ganda Biasa
	if ($jnssoal == '1') {
		$key = array(
			'idbutir' => $_POST['idsoal'],
			'idopsi' => $_POST['idopsi']
		);
		$benar = array(
			'benar' => '1',
			'skor' => '1'
		);
		$row = editdata('tbopsi', $benar, '', $key);
		if ($row > 0) {
			$sql = "UPDATE tbopsi SET benar='0', skor='0' WHERE idbutir= '$_POST[idsoal]' AND idopsi<>'$_POST[idopsi]'";
			equery($sql);
			$sukses++;
		} else {
			$gagal++;
		}
	}
	// Pilihan Ganda Kompleks
	if ($jnssoal == '2') {
		$sql = "UPDATE tbopsi SET benar='0', skor='0' WHERE idbutir= '$_POST[idsoal]'";
		equery($sql);
		$butir = $_POST['idsoal'];
		$opsi = explode(",", $_POST['idopsi']);
		foreach ($opsi as $id) {
			$key = array(
				'idopsi' => $id,
				'idbutir' => $butir
			);
			$benar = array(
				'benar' => '1',
				'skor' => '1'
			);
			$row = editdata('tbopsi', $benar, '', $key);
			if ($row > 0) {
				$sukses++;
			} else {
				$gagal++;
			}
		}
	}
	//Benar atau Salah
	if ($jnssoal == '3') {
		// $sql = "UPDATE tbopsi SET benar='', skor='0' WHERE idbutir= '$_POST[idsoal]'";
		// equery($sql);
		$butir = $_POST['idsoal'];
		$opsi = explode(",", $_POST['idopsi']);
		foreach ($opsi as $id) {
			$key = array(
				'idopsi' => $id,
				'idbutir' => $butir
			);
			$benar = array(
				'benar' => $_POST['benar'],
				'skor' => '1'
			);
			$row = editdata('tbopsi', $benar, '', $key);
			if ($row > 0) {
				$sukses++;
			} else {
				$gagal++;
			}
		}
	}

	//Isian Singkat
	if ($jnssoal == '5') {
		var_dump($_POST);
	}
	if ($sukses > 0) {
		echo "Isi Kunci Jawaban Sukses!";
	}
	if ($gagal > 0) {
		echo "Isi Kunci Jawaban Gagal!";
	}
}
if ($_POST['aksi'] == 'delopsi') {
	$sql = "DELETE ops, mt FROM tbopsi ops INNER JOIN tbmatching mt USING(idopsi) WHERE ops.idopsi='$_POST[idops]'";
	echo 'Opsi Jawaban Berhasil Hapus!';
}
if ($_POST['aksi'] == '2') {
	$qjns = $conn->query("SELECT jnssoal, modeopsi FROM tbsoal WHERE idbutir='$_POST[soal]'");
	$js = $qjns->fetch_array();

	$qcek = $conn->query("SELECT*FROM tbopsi WHERE idopsi='$_POST[id]'");
	$cek = $qcek->num_rows;

	if ($js['modeopsi'] == '1' && ($jnssoal == '1' || $jnssoal == '2')) {
		if ($_POST['benar'] == '1') {
			$skor = 1;
			$b = 1;
		} else {
			$skor = 0;
			$b = 0;
		}
		$opsi = addslashes($_POST['ops']);
		$opsia = addslashes($_POST['ops2']);
		if ($cek == 0) {
			$sql = $conn->query("INSERT INTO tbopsi (idbutir,opsi, opsialt, benar, skor) VALUES ('$_POST[soal]','$opsi','$opsia','$b','$skor')");
			echo 'Simpan Opsi Jawaban Berhasil!';
		} else {
			$sql = $conn->query("UPDATE tbopsi SET opsi='$opsi', opsialt='$opsia', benar='$b',skor='$skor' WHERE idopsi='$_POST[id]'");
			echo 'Update Opsi Jawaban Berhasil!';
		}
	} else {
		if ($jnssoal == '1' || $jnssoal == '2') {
			if ($_POST['benar'] == '1') {
				$skor = 1;
				$b = 1;
			} else {
				$skor = 0;
				$b = 0;
			}
			$opsi = addslashes($_POST['ops']);
			if ($cek == 0) {
				$sql = $conn->query("INSERT INTO tbopsi (idbutir, opsi, benar, skor) VALUES ('$_POST[soal]','$opsi', '$b','$skor')");
				echo 'Simpan Opsi Jawaban Berhasil!';
			} else {
				$sql = $conn->query("UPDATE tbopsi SET opsi='$opsi', benar='$b', skor='$skor' WHERE idopsi='$_POST[id]'");
				echo 'Update Opsi Jawaban Berhasil!';
			}
		} else if ($jnssoal == '3') {
			if ($_POST['benar'] == '1') {
				$skor = 1;
				$b = 1;
			} else {
				$skor = 1;
				$b = 0;
			}
			$opsi = addslashes($_POST['ops']);
			$opsia = addslashes($_POST['ops2']);
			if ($cek == 0) {
				$sql = $conn->query("INSERT INTO tbopsi (idbutir,opsi, opsialt, benar, skor) VALUES ('$_POST[soal]','$opsi','$opsia','$b','$skor')");
				echo 'Simpan Opsi Jawaban Berhasil!';
			} else {
				$sql = $conn->query("UPDATE tbopsi SET opsi='$opsi', opsialt='$opsia', benar='$b',skor='$skor' WHERE idopsi='$_POST[id]'");
				echo 'Update Opsi Jawaban Berhasil!';
			}
		} else if ($jnssoal == '4') {
			if ($_POST['benar'] == '1') {
				$skor = 1;
				$b = 1;
			} else {
				$skor = 0;
				$b = 0;
			}
			$opsi = addslashes($_POST['ops']);
			$opsia = addslashes($_POST['ops2']);
			if ($cek == 0) {
				$sql = $conn->query("INSERT INTO tbopsi (idbutir,opsi, opsialt, benar, skor) VALUES ('$_POST[soal]','$opsi','$opsia','$b','$skor')");
				echo 'Simpan Opsi Jawaban Berhasil!';
			} else {
				$sql = "UPDATE tbopsi SET opsi='$opsi', opsialt='$opsia', benar='$b',skor='$skor' WHERE idopsi='$_POST[id]'";
				$conn->query($sql);
				echo 'Update Opsi Jawaban Berhasil!';
			}
		} else {
			if ($_POST['benar'] == '1') {
				$skor = 1;
				$b = 1;
			} else {
				$skor = 0;
				$b = 0;
			}
			$opsi = addslashes($_POST['ops']);
			if ($cek == 0) {
				$sql = $conn->query("INSERT INTO tbopsi (idbutir, opsi, benar, skor) VALUES ('$_POST[soal]','$opsi', '$b','$skor')");
				echo 'Simpan Opsi Jawaban Berhasil!';
			} else {
				$sql = $conn->query("UPDATE tbopsi SET opsi='$opsi', benar='$b', skor='$skor' WHERE idopsi='$_POST[id]'");
				echo 'Update Opsi Jawaban Berhasil!';
			}
		}
	}
}

if ($_POST['aksi'] == '3') {
	$sql = $conn->query("DELETE FROM tbsoal WHERE idbutir='$_POST[id]'");
	echo 'Butir Soal Berhasil Hapus!';
}
if ($_POST['aksi'] == '4') {
	$sql = $conn->query("DELETE FROM tbsoal WHERE idbank='$_POST[ib]'");
	echo 'Butir Soal Berhasil Dikosongkan!';
}

if ($_POST['aksi'] == '5') {
	$sql = $conn->query("DELETE FROM tbopsi WHERE idopsi='$_POST[id]'");
	echo 'Opsi Jawaban Berhasil Hapus!';
}

if ($_POST['aksi'] == '6') {
	$qcek = $conn->query("SELECT jnssoal FROM tbsoal WHERE idbutir='$_POST[ib]'");
	$cj = $qcek->fetch_array();
	if ($cj['jnssoal'] == '1') {
		$sql = $conn->query("UPDATE tbopsi SET benar='1', skor='1' WHERE idopsi='$_POST[id]' AND idbutir='$_POST[ib]'");
		$sql = $conn->query("UPDATE tbopsi SET benar='0', skor='0' WHERE idopsi<>'$_POST[id]' AND idbutir='$_POST[ib]'");

		$upd = $conn->query("UPDATE tbjawaban SET skor='1' WHERE jwbbenar='$_POST[id]' AND idbutir='$_POST[ib]'");
		$upd = $conn->query("UPDATE tbjawaban SET skor='0' WHERE jwbbenar<>'$_POST[id]' AND idbutir='$_POST[ib]'");
	} elseif ($cj['jnssoal'] == '2') {
		if ($_POST['nil'] == 1) {
			$skor = 1;
		} else {
			$skor = 0;
		}
		$sql = $conn->query("UPDATE tbopsi SET benar='$_POST[nil]', skor='$skor' WHERE idopsi='$_POST[id]' AND idbutir='$_POST[ib]'");

		$qskops = $conn->query("SELECT GROUP_CONCAT(idopsi) as opsibenar, COUNT(*) as jumlahe FROM tbopsi WHERE idbutir='$_POST[ib]' AND benar='1' GROUP BY idbutir");
		$ops = $qskops->fetch_array();
		$kunci = explode(",", $ops['opsibenar']);

		$qsalah = $conn->query("SELECT  COUNT(*) as salahe FROM tbopsi WHERE idbutir='$_POST[ib]' AND benar='0' GROUP BY idbutir");
		$s = $qsalah->fetch_array();
		$jmls = $s['salahe'];

		$qops = $conn->query("SELECT jwbbenar FROM tbjawaban WHERE idbutir='$_POST[ib]'");
		while ($ops = $qops->fetch_array()) {
			$jwbbenar = explode(",", $ops['jwbbenar']);
			$val = array_search($idopsi, $kunci, true);
		}
	} elseif ($cj['jnssoal'] == '3') {
		$sql = $conn->query("UPDATE tbopsi SET benar='$_POST[nil]', skor='1' WHERE idopsi='$_POST[id]' AND idbutir='$_POST[ib]'");

		// $qskops=$conn->query("SELECT GROUP_CONCAT(idopsi) as opsibenar, COUNT(*) as jumlahe FROM tbopsi WHERE idbutir='$_POST[ib]' AND benar='1' GROUP BY idbutir");
		// $ops=$qskops->fetch_array();
		// $kunci=explode(",",$ops['opsibenar']);
		// $jmlb=$ops['jumlahe'];

		// $qsalah=$conn->query("SELECT  COUNT(*) as salahe FROM tbopsi WHERE idbutir='$_POST[ib]' AND benar='0' GROUP BY idbutir");
		// $s=$qsalah->fetch_array();
		// $jmls=$s['salahe'];

		// $qops=$conn->query("SELECT jwbbenar FROM tbjawaban WHERE idbutir='$_POST[ib]'");
		// while($ops=$qops->fetch_array()){
		// 	$jwbbenar=explode(",",$ops['jwbbenar']);
		// 	$val=array_search($idopsi, $cekops,true);
		// }				
	} elseif ($cj['jnssoal'] == '4') {
		$sql = $conn->query("UPDATE tbopsi SET benar='$_POST[nil]', skor='1' WHERE idopsi='$_POST[id]' AND idbutir='$_POST[ib]'");
	} else {
		$sql = $conn->query("UPDATE tbopsi SET benar='$_POST[nil]', skor='1' WHERE idopsi='$_POST[id]' AND idbutir='$_POST[ib]'");
	}
	echo "Update Kunci Jawaban Berhasil!";
}
if ($_POST['aksi'] == '7') {
	$qch = $conn->query("SELECT*FROM tbheaderopsi WHERE idbutir='$_POST[id]'");
	$cekh = $qch->num_rows;
	if ($cekh == 0) {
		$sql = $conn->query("INSERT INTO tbheaderopsi(idbutir, header1, header2) VALUES ('$_POST[id]','$_POST[hd1]','$_POST[hd2]')");
		echo 'Simpan Header Opsi Berhasil!';
	} else {
		$sql = $conn->query("UPDATE tbheader SET header1 = '$_POST[hd1]',header2='$_POST[hd2]' WHERE idbutir='$_POST[id]'");
		echo 'Update Header Opsi Berhasil!';
	}
}
