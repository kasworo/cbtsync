<?php
include "dbfunction.php";
$data = array(
	'idgtk' => $_POST['id']
);
$cekgtk = cekdata("tbgtk", $data);
if ($cekgtk == 0) {
	$dir = '../assets/img/';
	$foto = 'avatar.gif';
	$fotolama = '';
	$rows = array(
		'idgtk' => '',
		'nama' => '',
		'nik' => '',
		'nip' => '',
		'tmplahir' => '',
		'tgllahir' => '',
		'gender' => '',
		'agama' => '',
		'kepeg' => '',
		'jbtd' => '',
		'email' => '',
		'alamat' => '',
		'desa' => '',
		'kec' => '',
		'kab' => '',
		'prov' => '',
		'kdpos' => '',
		'nohp' => $m['nohp'],
		'foto' => $foto,
		'fotolama' => $fotolama,
		'dir' => $dir
	);
} else {
	$m = viewdata("tbgtk", $data)[0];
	if ($m['foto'] == '') {
		$dirfoto = '../assets/img/';
		$foto = 'avatar.gif';
		$fotolama = '';
	} else {
		if (file_exists('gambar/' . $m['foto'])) {
			$dirfoto = 'gambar/';
			$foto = $m['foto'];
			$fotolama = $m['foto'];
		} else {
			$dirfoto = '../assets/img/';
			$foto = 'avatar.gif';
			$fotolama = '';
		}
	}
	if ($m['ttd'] == '') {
		$dirttd = '../assets/img/';
		$ttd = 'nofile.png';
		$ttdlama = '';
	} else {
		if (file_exists('gambar/' . $m['ttd'])) {
			$dirttd = 'gambar/';
			$ttd = $m['ttd'];
			$ttdlama = $m['ttd'];
		} else {
			$dirttd = '../assets/img/';
			$ttd = 'nofile.png';
			$ttdlama = '';
		}
	}
	$rows = array(
		'idgtk' => $m['idgtk'],
		'nama' => $m['nama'],
		'nik' => $m['nik'],
		'nip' => $m['nip'],
		'tmplahir' => $m['tmplahir'],
		'tgllahir' => $m['tgllahir'],
		'gender' => $m['gender'],
		'agama' => $m['agama'],
		'kepeg' => $m['kepeg'],
		'jbtd' => $m['jbtdinas'],
		'email' => $m['email'],
		'alamat' => $m['alamat'],
		'desa' => $m['desa'],
		'kec' => $m['kec'],
		'kab' => $m['kab'],
		'prov' => $m['prov'],
		'kdpos' => $m['kdpos'],
		'nohp' => $m['nohp'],
		'foto' => $foto,
		'fotolama' => $fotolama,
		'dirfoto' => $dirfoto,
		'ttd' => $ttd,
		'ttdlama' => $ttdlama,
		'dirttd' => $dirttd
	);
}
echo json_encode($rows);
