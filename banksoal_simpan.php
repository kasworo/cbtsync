<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	include "../config/function.php";
	$sk=getskul();
	$idskul=$sk['idskul'];
	if($_POST['aksi']=='1'){
		$siki=date('Y-m-d');
		$qcek=$conn->query("SELECT*FROM tbbanksoal WHERE nmbank='$_POST[bnk]'");
		$cek=$qcek->num_rows;
		if($cek==0){
			$sql="INSERT INTO tbbanksoal (idskul, idkelas, idmapel , idujian, nmbank, tglbuat, username) VALUES ('$idskul','$_POST[kls]','$_POST[map]','$_POST[tes]','$_POST[bnk]','$siki','$_POST[usr]')";
			$query=$conn->query($sql);			
			echo 'Simpan Bank Soal Berhasil!';
		}
		else
		{
			echo 'Bank Soal Sudah Ada!';
		}
	}
	
	if($_POST['aksi']=='2'){
		$qcek=$conn->query("SELECT*FROM tbsetingujian WHERE idbank='$_POST[id]' AND idrombel='$_POST[rmb]' AND idjadwal='$_POST[jdw]'");
		$cek=$qcek->num_rows;
		if($cek==0){
			$sql=$conn->query("INSERT INTO tbsetingujian (idbank, idrombel, idjadwal, jumsoal, acaksoal, acakopsi) VALUES ('$_POST[id]','$_POST[rmb]','$_POST[jdw]','$_POST[soal]','$_POST[mode]','$_POST[opsi]')");
			echo 'Seting Ujian Berhasil Disimpan!';
		}
		else {
			$sql=$conn->query("UPDATE tbsetingujian SET jumsoal='$_POST[soal]', acaksoal='$_POST[mode]', acakopsi='$_POST[opsi]' WHERE idbank='$_POST[id]' AND  idrombel='$_POST[rmb]' AND idjadwal='$_POST[jdw]'");
			echo 'Update Seting Ujian Berhasil!';
		}

	}

	if($_POST['aksi']=='3'){
		$sql=$conn->query("DELETE FROM tbsetingujian WHERE idbank='$_POST[id]' AND idjadwal='$_POST[jd]'");
		echo 'Seting Ujian Berhasil Dihapus!';
	}

	if($_POST['aksi']=='4'){
		$sql=$conn->query("UPDATE tbbanksoal SET deleted='1' WHERE idbank='$_POST[id]'");
		echo 'Bank Soal Berhasil Dihapus!';
	}

	if($_POST['aksi']=='5'){
		$sql=$conn->query("UPDATE tbbanksoal SET deleted='1' WHERE idujian='$_POST[id]'");
		echo 'Bank Soal Berhasil Dihapus!';
	}
?>