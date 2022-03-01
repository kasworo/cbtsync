<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	$sqthn=$conn->query("SELECT idthpel FROM tbthpel WHERE aktif='1'");
	$thn=$sqthn->fetch_array();
	$idthpel=$thn['idthpel'];
	if($_POST['aksi']=='simpan'){
		if($_POST['rm']=='0'){
			$qrmb=$conn->query("SELECT idrombel FROM tbrombel r INNER JOIN tbthpel t USING(idthpel) WHERE t.aktif='1' AND idkelas='$_POST[kl]'");
			$tmb=0;
			$upd=0;
			while($r=$qrmb->fetch_array()){
				$qcek=$conn->query("SELECT a.*FROM tbpengampu a INNER JOIN tbthpel t ON t.idthpel=a.idthpel WHERE a.idmapel='$_POST[mp]' AND a.idrombel='$r[idrombel]' AND idthpel='$idthpel'");
				$cek=$qcek->num_rows;	
				if($cek==0){
					$sql=$conn->query("INSERT INTO tbpengampu(idrombel, idmapel, username, idthpel) VALUES ('$r[idrombel]','$_POST[mp]','$_POST[gr]','$idthpel')");
					$tmb++;
				}
				else {
					$sql=$conn->query("UPDATE tbpengampu SET idrombel='$r[idmapel]', idmapel='$_POST[mp]', username='$_POST[gr]' WHERE idmapel='$_POST[mp]' AND  idrombel='$s[idrombel]' AND idthpel='$idthpel'");
					$upd++;
				}
			}
			if($tmb>0 && $upd==0){
				echo 'Ada '.$tmb. ' Data Pengampu Berhasil Ditambahkan';
			}
			else if($tmb==0 && $upd>0){
				echo 'Ada '.$upd. ' Data Pengampu Berhasil Diupdate';
			}
			else { 
				echo 'Ada '.$tmb. ' Data Berhasil Ditambahkan, '.$upd.' Diupdate';
			}
		}
		else{
			$qcek=$conn->query("SELECT*FROM tbpengampu WHERE idampu='$_POST[id]' OR (idmapel='$_POST[mp]' AND idrombel='$_POST[rm]' AND idthpel='$idthpel')");
			$cek=$qcek->num_rows;
			
			if($cek==0){
				$sql=$conn->query("INSERT INTO tbpengampu(idrombel, idmapel, username, idthpel) VALUES ('$_POST[rm]','$_POST[mp]','$_POST[gr]','$idthpel')");
				echo 'Simpan Data Pembelajaran Berhasil!';
			}
			else {
				$sql=$conn->query("UPDATE tbpengampu SET idrombel='$_POST[rm]', idmapel='$_POST[mp]', username='$_POST[gr]' WHERE idampu='$_POST[id]' OR (idmapel='$_POST[mp]' AND idrombel='$_POST[rm]' AND idthpel='$idthpel')");
				echo 'Update Data Pengampu Berhasil!';
			}
		}
	}

	if($_POST['aksi']=='salin'){
		$qcek=$conn->query("SELECT*FROM tbpengampu WHERE idthpel='$idthpel' AND idrombel='$_POST[idra]'");
		$cek=$qcek->num_rows;
		if($cek>0){
			while($sa=$qcek->fetch_array())
			{
				$sql=$conn->query("INSERT INTO tbpengampu(idrombel, idmapel, username, idthpel) VALUES ('$_POST[idrt]','$sa[idmapel]','$sa[username]','$sa[idthpel]')");
			}
			echo 'Simpan Data Pembelajaran Berhasil!';
		}
		else {
			echo "Data Pembelajaran Asal Belum Ada";
		}
	}

	if($_POST['aksi']=='hapus'){
		$sql=$conn->query("DELETE FROM tbpengampu WHERE idampu='$_POST[id]'");
		echo 'Hapus Data Pembelajaran Berhasil!';
	}

	if($_POST['aksi']=='kosong'){
		$sql=$conn->query("DELETE FROM tbpengampu WHERE idthpel='$idthpel'");
		echo 'Hapus Semua Data Pembelajaran Berhasil!';
	}	
?>