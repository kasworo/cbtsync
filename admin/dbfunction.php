<?php
define('setclient', 'c:/xampp/htdocs/newcbt/');
$host = "localhost";
$user = "root";
$pwd = "";
$db = "dbcbtnew";
$conn = new mysqli($host, $user, $pwd, $db);
if (mysqli_connect_errno()) {
	echo "Error: Could not connect to database. ";
	exit;
}

function indonesian_date($date)
{
	$indonesian_month = array(
		"Januari", "Februari", "Maret",
		"April", "Mei", "Juni",
		"Juli", "Agustus", "September",
		"Oktober", "November", "Desember"
	);
	$year = substr($date, 0, 4);
	$month = substr($date, 5, 2);
	$currentdate = substr($date, 8, 2);
	if ($month >= 1) {
		$result = $currentdate . " " . $indonesian_month[(int) $month - 1] . " " . $year;
	} else {
		$result = '';
	}
	return $result;
}

function isithpel()
{
	global $conn;
	$tahun = date('Y');
	$bulan = date('m');
	if ($bulan <= 12) {
		if ($bulan > 6) {
			$tahun = $tahun;
			$semester = '1';
			$nmsemester = 'Ganjil';
			$tgl = strtotime("07/01" . $tahun);
			$awal = date('Y-m-d', $tgl);
		} else {
			$tahun = $tahun - 1;
			$semester = '2';
			$nmsemester = 'Genap';
			$tgl = strtotime("01/01" . $tahun);
			$awal = date('Y-m-d', $tgl);
		}
	}
	$tahun1 = $tahun + 1;
	$ay = $tahun . $semester;
	$nama = $tahun . '/' . $tahun1 . '-' . $nmsemester;
	$cek = cekdata('tbthpel', array('nmthpel' => $ay));
	if ($cek == 0) {
		$data = array(
			'nmthpel' => $ay,
			'desthpel' => $nama,
			'awal' => $awal,
			'aktif' => '1'
		);
		editdata('tbthpel', array('aktif' => '0'));
		adddata('tbthpel', $data);
	}
}

function terbilang($x)
{
	$angka = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
	if ($x < 12)
		return " " . $angka[$x];
	elseif ($x < 20)
		return terbilang($x - 10) . " Belas";
	elseif ($x < 100)
		return terbilang($x / 10) . " Puluh" . terbilang($x % 10);
	elseif ($x < 200)
		return "Seratus" . terbilang($x - 100);
	elseif ($x < 1000)
		return terbilang($x / 100) . " Ratus" . terbilang($x % 100);
	elseif ($x < 2000)
		return "Seribu" . terbilang($x - 1000);
	elseif ($x < 1000000)
		return terbilang($x / 1000) . " Ribu" . terbilang($x % 1000);
	elseif ($x < 1000000000)
		return terbilang($x / 1000000) . " Juta" . terbilang($x % 1000000);
}

function getgender($id)
{
	if ($id == 'L') {
		$jk = 'Laki-laki';
	} else {
		$jk = 'Perempuan';
	}
	return $jk;
}

function KonversiRomawi($angka)
{
	switch ($angka) {
		case '1': {
				$romawi = 'i';
				break;
			}
		case '2': {
				$romawi = 'ii';
				break;
			}
		case '3': {
				$romawi = 'iii';
				break;
			}
		case '4': {
				$romawi = 'iv';
				break;
			}
		case '5': {
				$romawi = 'v';
				break;
			}
		case '6': {
				$romawi = 'vi';
				break;
			}
		case '7': {
				$romawi = 'vii';
				break;
			}
		case '8': {
				$romawi = 'viii';
				break;
			}
		case '9': {
				$romawi = 'ix';
				break;
			}
	}
	return strtoupper($romawi);
}
function getskul()
{
	$data = viewdata('tbskul')[0];
	return $data['idskul'];
}

function tampilKelas()
{
	$sqk = "SELECT*FROM tbkelas INNER JOIN tbskul USING(idjenjang)";
	return vquery($sqk);
}

function getidsiswa($nis, $nisn)
{
	$sql = "SELECT idsiswa FROM tbpeserta WHERE nis='$nis' OR nisn='$nisn'";
	$data = vquery($sql)[0];
	return $data['idsiswa'];
}

function vquery($sql)
{
	global $conn;
	$rows = [];
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$rows[] = $row;
	}
	return $rows;
}

function cquery($sql)
{
	global $conn;
	$result = $conn->query($sql);
	return $result->num_rows;
}

function equery($sql)
{
	global $conn;
	$conn->query($sql);
	return $conn->affected_rows;
}

function viewdata($tbl, $key = '', $grup = '', $ord = '')
{
	global $conn;
	if ($key == '') {
		if ($grup == '' && $ord == '') {
			$sql = "SELECT*FROM $tbl";
		} else if ($grup == '') {
			$sql = "SELECT*FROM $tbl ORDER BY $ord";
		} else {
			$sql = "SELECT*FROM $tbl GROUP BY $grup";
		}
	} else {
		$where = [];
		foreach ($key as $wh => $nil) {
			$where[] = "$wh = '$nil'";
		}
		if ($grup == '' && $ord == '') {
			$sql = "SELECT*FROM $tbl WHERE " . implode(' AND ', $where);
		} else if ($grup == '') {
			$sql = "SELECT*FROM $tbl WHERE " . implode(' AND ', $where) . " ORDER BY $ord";
		} else {
			$sql = "SELECT*FROM $tbl WHERE " . implode(' AND ', $where) . " GROUP BY $grup";
		}
	}
	// var_dump($sql);
	// die;
	$rows = [];
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$rows[] = $row;
	}
	return $rows;
}

function cekdata($tbl, $key = '', $grup = '', $ord = '')
{
	global $conn;
	if ($key == '') {
		if ($grup == '' && $ord == '') {
			$sql = "SELECT*FROM $tbl";
		} else if ($grup == '') {
			$sql = "SELECT*FROM $tbl ORDER BY $ord";
		} else {
			$sql = "SELECT*FROM $tbl GROUP BY $grup";
		}
	} else {
		$where = [];
		foreach ($key as $wh => $nil) {
			$where[] = "$wh = '$nil'";
		}
		if ($grup == '' && $ord == '') {
			$sql = "SELECT*FROM $tbl WHERE " . implode(' AND ', $where);
		} else if ($grup == '') {
			$sql = "SELECT*FROM $tbl WHERE " . implode(' AND ', $where) . " ORDER BY $ord";
		} else {
			$sql = "SELECT*FROM $tbl WHERE " . implode(' AND ', $where) . " GROUP BY $grup";
		}
	}
	//var_dump($sql);
	$result = $conn->query($sql);
	return $result->num_rows;
}

function adddata($tbl, $data)
{
	global $conn;
	$key = array_keys($data);
	$val = array_values($data);
	$sql = "INSERT INTO $tbl (" . implode(', ', $key) . ") VALUES ('" . implode("', '", $val) . "')";
	// var_dump($sql);
	// die;
	$conn->query($sql);
	return $conn->affected_rows;
}

function editdata($tbl, $data, $join = '', $field = '')
{
	global $conn;
	$cols = [];
	foreach ($data as $key => $val) {
		$cols[] = "$key = '$val'";
	}
	$where = [];
	foreach ($field as $wh => $nil) {
		$where[] = "$wh = '$nil'";
	}

	if ($join == '') {
		$sql = "UPDATE $tbl SET " . implode(', ', $cols) . " WHERE " . implode(' AND ', $where);
	} else {
		$tbjoin = [];
		foreach ($join as $joins => $idjoins) {
			$tbjoin[] = "$joins USING($idjoins)";
		}
		$sql = "UPDATE $tbl INNER JOIN " . implode(' ', $tbjoin) . " SET " . implode(', ', $cols) . " WHERE " . implode(' AND ', $where);
	}
	// var_dump($sql);
	// // die;
	$conn->query($sql);
	return $conn->affected_rows;
}

function deldata($tbl, $field, $join = '', $als = '')
{
	global $conn;
	$where = [];
	foreach ($field as $wh => $nil) {
		$where[] = "$wh = '$nil'";
	}
	if ($join == '' || $als == '') {
		$sql = "DELETE FROM $tbl WHERE " . implode(' AND ', $where);
	} else {
		$tbjoin = [];
		foreach ($join as $joins => $idjoins) {
			$tbjoin[] = "$joins USING($idjoins)";
		}
		$sql = "DELETE $als FROM $tbl INNER JOIN " . implode(' ', $tbjoin) . " WHERE " . implode(' AND ', $where);
	}
	// var_dump($sql);
	// die;
	$conn->query($sql);
	return $conn->affected_rows;
}
