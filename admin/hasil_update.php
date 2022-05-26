<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
$qmapel = "SELECT ps.idsiswa, ps.nmpeserta, bs.idbank, mp.idmapel, SUM(so.skormaks) as semua, SUM(jw.skor) as benar FROM tbpeserta ps INNER JOIN tbjawaban jw USING(idsiswa) INNER JOIN tbsoal so USING(idbutir) INNER JOIN tbstimulus st USING(idstimulus) INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) WHERE bs.idujian='$_POST[u]' GROUP BY jw.idsiswa, jw.idset ORDER BY mp.idmapel, ps.idsiswa";
$mapel = vquery($qmapel);
$baru = 0;
$update = 0;
foreach ($mapel as $mp) {
	$salah = $mp['semua'] - $mp['benar'];
	$nilai = $mp['benar'] / $mp['semua'] * 100;
	$qcek = "SELECT*FROM tbnilai WHERE idujian='$_POST[u]' AND idsiswa='$mp[idsiswa]' AND idmapel='$mp[idmapel]'";
	$cek = cquery($qcek);
	if ($cek == 0) {
		$data = array(
			'idujian' => $_POST['u'],
			'idmapel' => $mp['idmapel'],
			'idsiswa' => $mp['idsiswa'],
			'jmlsoal' => $mp['semua'],
			'benar' => $mp['benar'],
			'salah' => $salah,
			'nilai' => $nilai
		);
		if (adddata('tbnilai', $data) > 0) {
			$baru++;
		}
	} else {
		$sql = $conn->query("UPDATE tbnilai SET jmlsoal='$mp[semua]', benar='$mp[benar]', salah='$salah', nilai='$nilai' WHERE idujian='$_POST[u]' AND idsiswa='$mp[idsiswa]' AND idmapel='$mp[idmapel]");
		$update++;
	}
}
/*
$qskor = "SELECT SUM(so.skormaks) as skormaksimum FROM tbjawaban jb INNER JOIN tbsoal so USING(idbutir) WHERE jb.idset='$_POST[idset]' AND jb.idsiswa='$_COOKIE[pst]' GROUP BY jb.idsiswa,jb.idset";
	$sk = vquery($qskor)[0];
	$skormaksimum = $sk['skormaksimum'];
	$sqlsta = "SELECT COUNT(*) as semua, SUM(skor) as benar FROM tbjawaban j INNER JOIN tbsoal s USING(idbutir) WHERE idsiswa='$_COOKIE[pst]' AND j.idset='$_POST[idset]' GROUP BY j.idsiswa,j.idset";
	$cek = vquery($sqlsta)[0];
	$semua = $cek['semua'];
	$benar = $cek['benar'];
	$salah = $skormaksimum - $benar;
	$nilai = $benar / $skormaksimum * 100;
	$keynilai = array(
		'idsiswa' => $_COOKIE['pst'],
		'idmapel' => $idmapel,
		'idujian' => $idujian
	);

	$ceknilai = cekdata('tbnilai', $keynilai);
	if ($ceknilai > 0) {
		$datanilai = array(
			'jmlsoal' => $semua,
			'benar' => $benar,
			'salah' => $salah,
			'nilai' => $nilai
		);
		echo editdata('tbnilai', $datanilai, '', $keynilai) > 0 ? '2' : '0';
	} else {
		$datanilai = array(
			'idsiswa' => $_COOKIE['pst'],
			'idmapel' => $idmapel,
			'idujian' => $idujian,
			'jmlsoal' => $semua,
			'benar' => $benar,
			'salah' => $salah,
			'nilai' => $nilai
		);
		echo adddata('tbnilai', $datanilai) > 0 ? '1' : '0';
	}
    */
echo "Ada " . $baru . " Data Nilai Ditambahkan, dan " . $update . " Diupdate";
