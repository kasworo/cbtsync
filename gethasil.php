<?php
include "dbfunction.php";

$sqlset = "SELECT su.idset, su.idjadwal, bs.idmapel, bs.idujian FROM tbsetingujian su INNER JOIN tbrombelsiswa rs USING(idrombel) INNER JOIN tbbanksoal bs USING(idbank) WHERE rs.idsiswa='$_COOKIE[pst]' AND su.idset='$_POST[idset]'";
$ds = vquery($sqlset)[0];
$idmapel = $ds['idmapel'];
$idujian = $ds['idujian'];

$keyjd = array(
	'idjadwal' => $ds['idjadwal'],
	'idsiswa' => $_COOKIE['pst'],
);
$datajd = array(
	'status' => '1'
);
if (editdata('tblogpeserta', $datajd, '', $keyjd) > 0) {
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
} else {
	echo '0';
}
