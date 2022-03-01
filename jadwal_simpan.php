<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	if(isset($_POST['aksi']) && $_POST['aksi']=='1'){
		$qcek=$conn->query("SELECT*FROM tbjadwal WHERE kdjadwal='$_POST[kode]'");
		$cek=mysqli_num_rows($qcek);
		if($cek==0)
		{
			$sql=$conn->query("INSERT INTO tbjadwal (idujian, kdjadwal, nmjadwal,matauji, tglujian, durasi, lambat, susulan) VALUES ('$_POST[id]', '$_POST[kode]','$_POST[nama]','$_POST[mtuji]','$_POST[tgl]', '$_POST[wkt]', '$_POST[lmb]','$_POST[utm]')");
			echo 'Simpan Jadwal Berhasil!';
		}
		else
		{
			$sql= $conn->query("UPDATE tbjadwal SET tglujian='$_POST[tgl]', nmjadwal= '$_POST[nama]', matauji='$_POST[mtuji]', durasi='$_POST[wkt]', lambat='$_POST[lmb]', susulan='$_POST[utm]' WHERE kdjadwal='$_POST[kode]' AND idujian='$_POST[id]'");
			echo 'Update Jadwal Berhasil!';
		}
	}


	if(isset($_POST['aksi']) && $_POST['aksi']=='2'){
		$qcek=$conn->query("SELECT viewtoken FROM tbjadwal WHERE idjadwal='$_POST[jdw]'");
		$ct=$qcek->fetch_array();
		$cektoken=$ct['viewtoken'];
		if($cektoken=='1'){
			$sql=$conn->query("UPDATE tbjadwal SET viewtoken='0' WHERE idjadwal='$_POST[jdw]'");
			$pesan='Token Berhasil Disembunyikan, Mintalah Peserta Untuk Menghubungi Pengawas';
		}
		else {
			$sql=$conn->query("UPDATE tbjadwal SET viewtoken='1' WHERE idjadwal='$_POST[jdw]'");
			$pesan='Token Berhasil Ditampilkan, Mintalah Peserta Melihat Teks Berwarna Merah';
		}
		echo $pesan;		
	}

	if(isset($_POST['aksi']) && $_POST['aksi']=='3'){
		$qcek=$conn->query("SELECT hasil FROM tbjadwal WHERE idjadwal='$_POST[jdw]'");
		$vh=$qcek->fetch_array();
		$cekhasil=$vh['hasil'];
		if($cekhasil=='1'){
			$sql=$conn->query("UPDATE tbjadwal SET hasil='0' WHERE idjadwal='$_POST[jdw]'");
			$pesan='Hasil Ujian Disembunyikan';
		}
		else {
			$sql=$conn->query("UPDATE tbjadwal SET hasil='1' WHERE idjadwal='$_POST[jdw]'");
			$pesan='Hasil Ujian Ditampilkan';
		}
		echo $pesan;		
	}

	if(isset($_POST['aksi']) && $_POST['aksi']=='4'){
		$sql=$conn->query("DELETE FROM tbjadwal WHERE kdjadwal='$_POST[id]'");
		echo 'Hapus Jadwal Berhasil!';		
	}

	

?>