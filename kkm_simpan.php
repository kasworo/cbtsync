<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	$sqthn=$conn->query("SELECT idthpel FROM tbthpel WHERE aktif='1'");
	$thn=$sqthn->fetch_array();
	$idthpel=$thn['idthpel'];
	if($_POST['aksi']=='simpan'){
		$qcek=$conn->query("SELECT*FROM tbkkm WHERE idmapel='$_POST[id]' AND idkelas='$_POST[kls]' AND idthpel='$idthpel'");
		$cek=mysqli_num_rows($qcek);
		if($cek==0){
			$sql=$conn->query("INSERT INTO tbkkm (idmapel, idkelas, idthpel,kkm) VALUES ('$_POST[id]','$_POST[kls]','$idthpel','$_POST[kkm]')");
			echo 'Simpan KKM Berhasil!';
		}
		else{
			$sql=$conn->query("UPDATE tbkkm SET kkm='$_POST[kkm]' WHERE idmapel='$_POST[id]' AND idkelas='$_POST[kls]' AND idthpel='$idthpel'");
			echo 'Update KKM Berhasil!';
		}
	}
	
	if($_POST['aksi']=='salin'){
		$sql=$conn->query("REPLACE INTO tbkkm (idmapel, idkelas, kkm, idthpel) SELECT idmapel, idkelas, kkm, '$_POST[tuju]' FROM tbkkm WHERE idthpel='$_POST[asal]'");
		echo 'Salin KKM Berhasil!';
	}

	if($_POST['aksi']=='kosong'){
		$sql=$conn->query("DELETE FROM tbkkm WHERE idthpel='$idthpel'");
		echo 'Hapus Data KKM Berhasil!';
	}
?>