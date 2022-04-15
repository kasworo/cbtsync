<?php
define("BASEPATH", dirname(__FILE__));
session_start();
include "dbfunction.php";
$saiki = date('Y-m-d H:i:s');
if ($_POST['aksi'] == 'simpan') {
	$qjns = "SELECT jnssoal,skormaks FROM tbsoal WHERE idbutir='$_POST[soal]'";
	$jn = vquery($qjns)[0];
	$jns = $jn['jnssoal'];
	$maksk = intval($jn['skormaks']);

	$sqlgetsk = "SELECT SUM(skor) as semua FROM tbopsi WHERE idbutir='$_POST[soal]' GROUP BY idbutir";
	$getsk = vquery($sqlgetsk)[0];
	$skore = $getsk['semua'];

	// Soal Pilihan Ganda Biasa
	if ($jns == '1') {
		$qops = "SELECT viewopsi FROM tbjawaban WHERE idbutir='$_POST[soal]' AND idsiswa='$_COOKIE[pst]'";
		$ops = vquery($qops)[0];
		$cekops = explode(",", $ops['viewopsi']);
		$jwbbenar = $_POST['opsi'];
		$jwbsalah = '';
		$val = array_search($jwbbenar, $cekops, true);
		switch ($val) {
			case '0': {
					$hrf = 'A';
					break;
				}
			case '1': {
					$hrf = 'B';
					break;
				}
			case '2': {
					$hrf = 'C';
					break;
				}
			case '3': {
					$hrf = 'D';
					break;
				}
			case '4': {
					$hrf = 'E';
					break;
				}
		}
		$qskor = "SELECT skor FROM tbopsi WHERE idopsi='$jwbbenar'";
		$sk = vquery($qskor)[0];
		$pskor = $sk['skor'];
	}

	// Soal Pilihan Ganda Kompleks
	if ($jns == '2') {
		$qskops = "SELECT GROUP_CONCAT(CASE WHEN benar='1' THEN idopsi END) as opsibenar, COUNT(CASE WHEN benar='1' THEN 1 END) as jmlbenar, COUNT(CASE WHEN benar='0' THEN 1 END) as jmlsalah, COUNT(*) as kabeh FROM tbopsi WHERE idbutir='$_POST[soal]' GROUP BY idbutir";
		$ops = vquery($qskops)[0];
		$kunci = explode(",", $ops['opsibenar']);
		$jmlb = $ops['jmlbenar'];
		$jmls = $ops['jmlsalah'];
		$jml = $ops['kabeh'];

		$qvops = "SELECT viewopsi FROM tbjawaban WHERE idbutir='$_POST[soal]' AND idsiswa='$_COOKIE[pst]'";
		$vops = vquery($qvops)[0];
		$cekops = explode(",", $vops['viewopsi']);

		$jwbbenar = $_POST['opsi'];
		$cekopsi = explode(",", $jwbbenar);
		$jwbsalah = '';
		$skor = 0;
		$b = 0;
		$s = 0;
		foreach ($cekopsi as $idopsi) {
			$val = array_search($idopsi, $cekops, true);
			switch ($val) {
				case '0': {
						$t = 'A';
						break;
					}
				case '1': {
						$t = 'B';
						break;
					}
				case '2': {
						$t = 'C';
						break;
					}
				case '3': {
						$t = 'D';
						break;
					}
				case '4': {
						$t = 'E';
						break;
					}
				case '5': {
						$t = 'F';
						break;
					}
				case '6': {
						$t = 'G';
						break;
					}
			}
			$dh[] = $t;
			$hrf = implode(",", $dh);
			$qskor = "SELECT skor FROM tbopsi WHERE idopsi='$idopsi'";
			$sk = vquery($qskor)[0];
			$skr = $sk['skor'];
			if ($skr == '1') $b++;
			else $s++;
			$skor += $skr;
		}
		$k = $b + $s;
		if ($k == $jml) $pskor = 0;
		else if ($s > 0 && $b <= $jmlb) $pskor = 0;
		else $pskor = $skor;
	}
	//Soal Benar atau Salah
	if ($jns == '3') {
		$qops = "SELECT viewopsi FROM tbjawaban WHERE idbutir='$_POST[soal]' AND idsiswa='$_COOKIE[pst]'";
		$ops = vquery($qops)[0];
		$cekops = explode(",", $ops['viewopsi']);

		$qskor = "SELECT skor FROM tbopsi WHERE idopsi='$_POST[opsi]' AND benar='$_POST[jwb]'";
		$cekskor = cquery($qskor);
		if ($cekskor == 0) $skor = 0;
		else {
			$sk = vquery($qskor)[0];
			$skor = $sk['skor'];
		}

		$qmth = "SELECT*FROM tbmatching WHERE idbutir='$_POST[soal]' AND idopsi='$_POST[opsi]' AND idsiswa='$_COOKIE[pst]'";
		$cekmth = cquery($qmth);
		if ($cekmth == 0) {
			$qins = "INSERT INTO tbmatching (idsiswa, idbutir, idopsi, jawaban, huruf, skor, logjawab) VALUES ('$_COOKIE[pst]', '$_POST[soal]', '$_POST[opsi]', '$_POST[jwb]','', '$skor','$saiki')";
		} else {
			$qins = "UPDATE tbmatching SET jawaban='$_POST[jwb]', skor='$skor', logjawab='$saiki' WHERE idsiswa='$_COOKIE[pst]' AND idbutir='$_POST[soal]' AND idopsi='$_POST[opsi]'";
		}
		if (equery($qins) > 0) {
			$qbnr = "SELECT GROUP_CONCAT(CASE WHEN jawaban='1' THEN idopsi END ORDER BY idopsi) as jwbbenar, GROUP_CONCAT(CASE WHEN jawaban='0' THEN idopsi END ORDER BY idopsi) as jwbsalah, SUM(skor) as skor FROM tbmatching WHERE idbutir='$_POST[soal]' AND idsiswa='$_COOKIE[pst]'";
			$bnr = vquery($qbnr)[0];
			$jwbbenar = $bnr['jwbbenar'];
			$jwbsalah = $bnr['jwbsalah'];
			$hrf = '';
			$pskor = $bnr['skor'];
		}
	}

	//Soal Menjodohkan (Matching)
	if ($jns == '4') {
		// $qops = "SELECT viewopsialt FROM tbjawaban WHERE idbutir='$_POST[soal]' AND idsiswa='$_COOKIE[pst]'";
		// $ops = vquery($qops)[0];
		// $cekops = explode(",", $ops['viewopsialt']);
		// $pskor = 0;

		// $idbenar = $_POST['jwb'];

		// $val = array_search($idbenar, $cekops, true);
		// switch ($val) {
		// 	case '0': {
		// 			$t = 'A';
		// 			break;
		// 		}
		// 	case '1': {
		// 			$t = 'B';
		// 			break;
		// 		}
		// 	case '2': {
		// 			$t = 'C';
		// 			break;
		// 		}
		// 	case '3': {
		// 			$t = 'D';
		// 			break;
		// 		}
		// 	case '4': {
		// 			$t = 'E';
		// 			break;
		// 		}
		// 	case '5': {
		// 			$t = 'F';
		// 			break;
		// 		}
		// 	case '6': {
		// 			$t = 'G';
		// 			break;
		// 		}
		// 	case '7': {
		// 			$t = 'H';
		// 			break;
		// 		}
		// 	case '8': {
		// 			$t = 'I';
		// 			break;
		// 		}
		// 	case '9': {
		// 			$t = 'J';
		// 			break;
		// 		}
		// }
		// if ($_POST['opsi'] == $_POST['jwb']) {
		// 	$skor = '1';
		// } else {
		// 	$skor = '0';
		// }

		// $qmth = "SELECT*FROM tbmatching WHERE idbutir='$_POST[soal]' AND idopsi='$_POST[opsi]' AND idsiswa='$_COOKIE[pst]'";
		// $cekmth = cquery($qmth);
		// if ($cekmth == 0) {
		// 	$qins = "INSERT INTO tbmatching (idsiswa, idbutir, idopsi, jawaban, huruf, skor, logjawab) VALUES ('$_COOKIE[pst]', '$_POST[soal]', '$_POST[opsi]', '$_POST[jwb]','$t','$skor','$saiki')";
		// } else {
		// 	$qins = "UPDATE tbmatching SET jawaban='$_POST[jwb]', huruf='$t', logjawab='$saiki' WHERE idsiswa='$_COOKIE[pst]' AND idbutir='$_POST[soal]' AND idopsi='$_POST[opsi]'";
		// }
		// if (equery($qins) > 0) {
		// 	$qbenar = "SELECT GROUP_CONCAT(jawaban ORDER BY idopsi) as jwbbenar, GROUP_CONCAT(huruf ORDER BY idopsi) as hrf, COUNT(CASE WHEN idopsi=jawaban THEN 1 END) as skor FROM tbmatching WHERE idbutir='$_POST[soal]' AND idsiswa='$_COOKIE[pst]'";
		// 	$bnr = vquery($qbenar)[0];
		// 	$jwbbenar = $bnr['jwbbenar'];
		// 	$jwbsalah = '';
		// 	$hrf = $bnr['hrf'];
		// 	$pskor = $bnr['skor'];
		// }

		$qops = "SELECT viewopsi, viewopsialt FROM tbjawaban WHERE idbutir='$_POST[soal]' AND idsiswa='$_COOKIE[pst]'";
		$ops = vquery($qops)[0];
		$opsibenar = explode(",", $ops['viewopsi']);
		$opsialt = explode(",", $ops['viewopsialt']);
		$data = json_decode($_POST['jawaban'], true);
		foreach ($data as $row) {
			switch ($row['from']) {
				case 'a': {
						$t = 0;
						break;
					}
				case 'b': {
						$t = 1;
						break;
					}
				case 'c': {
						$t = 2;
						break;
					}
				case 'd': {
						$t = 3;
						break;
					}
				case 'e': {
						$t = 4;
						break;
					}
				case 'f': {
						$t = 5;
						break;
					}
				case 'g': {
						$t = 6;
						break;
					}
				case 'h': {
						$t = 7;
						break;
					}
				case 'i': {
						$t = 8;
						break;
					}
				case 'j': {
						$t = 9;
						break;
					}
			}

			$jwblist = $opsibenar[$t];

			switch ($row['to']) {
				case 'A': {
						$u = 0;
						break;
					}
				case 'B': {
						$u = 1;
						break;
					}
				case 'C': {
						$u = 2;
						break;
					}
				case 'D': {
						$u = 3;
						break;
					}
				case 'E': {
						$u = 4;
						break;
					}
				case 'F': {
						$u = 5;
						break;
					}
				case 'G': {
						$u = 6;
						break;
					}
				case 'H': {
						$u = 7;
						break;
					}
				case 'I': {
						$u = 8;
						break;
					}
				case 'J': {
						$u = 9;
						break;
					}
			}
			$jwbpst = $opsialt[$u];
			if ($jwblist == $jwbpst) {
				$skor = '1';
			} else {
				$skor = '0';
			}
			$qmth = "SELECT*FROM tbmatching WHERE idbutir='$_POST[soal]' AND idopsi='$jwblist' AND idsiswa='$_COOKIE[pst]'";
			$cekmth = cquery($qmth);
			if ($cekmth == 0) {
				$qins = "INSERT INTO tbmatching (idsiswa, idbutir, idopsi, jawaban, dari, huruf,  skor, logjawab) VALUES ('$_COOKIE[pst]', '$_POST[soal]', '$jwblist', '$jwbpst','$row[from]','$row[to]','$skor','$saiki')";
			} else {
				$qins = "UPDATE tbmatching SET jawaban='$jwbpst', skor='$skor', dari='$row[from]', huruf='$row[to]', logjawab='$saiki' WHERE idsiswa='$_COOKIE[pst]' AND idbutir='$_POST[soal]' AND idopsi='$jwblist'";
			}
			if (equery($qins) > 0) {
				$qbenar = "SELECT GROUP_CONCAT(jawaban ORDER BY idopsi) as jwbbenar, GROUP_CONCAT(huruf ORDER BY idopsi) as hrf, COUNT(CASE WHEN idopsi=jawaban THEN 1 END) as tskor FROM tbmatching WHERE idbutir='$_POST[soal]' AND idsiswa='$_COOKIE[pst]'";
				$bnr = vquery($qbenar)[0];
				$jwbbenar = $bnr['jwbbenar'];
				$jwbsalah = '';
				$hrf = $bnr['hrf'];
				$pskor = $bnr['tskor'];
			}
		}
	}
	// Soal Isian Singkat
	if ($jns == '5') {
		$qops = "SELECT viewopsi FROM tbjawaban WHERE idbutir='$_POST[soal]' AND idsiswa='$_COOKIE[pst]'";
		$ops = vquery($qops)[0];
		$kunci = strtolower($ops['viewopsi']);
		$jwbbenar = strtolower(rtrim($_POST['opsi'], " "));
		$hrf = '';
		$jwbsalah = '';
		if ($jwbbenar == $kunci) {
			$pskor = 1;
		} else {
			$pskor = 0;
		}
	}
	$nilaine = $pskor / $skore * $maksk;
	$sqlisi = "UPDATE tbjawaban SET jwbbenar='$jwbbenar', jwbsalah='$jwbsalah', skor='$nilaine', huruf='$hrf', logjawab='$saiki' WHERE idbutir='$_POST[soal]' AND idsiswa='$_COOKIE[pst]'";
	if (equery($sqlisi) > 0) {
		echo 1;
	} else {
		echo 0;
	}
}

if ($_POST['aksi'] == 'edit') {
	$conn->query("DELETE FROM tbmatching WHERE idbutir='$_POST[soal]' AND idsiswa='$_COOKIE[pst]'");
	$conn->query("UPDATE tbjawaban SET jwbbenar=NULL, jwbsalah=NULL, skor=NULL, huruf=NULL, logjawab='$saiki' WHERE idbutir='$_POST[soal]' AND idsiswa='$_COOKIE[pst]'");
}
