<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	$saiki=date('Y-m-d H:i:s');
	if($_POST['aksi']=='1'){
		if(isset($_POST['id'])){
			$sql="SELECT COUNT(*) as jawab,su.jumsoal, jd.durasi FROM tbjawaban jw INNER JOIN tbsetingujian su USING(idset) INNER JOIN tbjadwal jd USING(idjadwal) WHERE jw.idsiswa='$_POST[id]' AND su.idjadwal='$_POST[jd]' AND jw.jwbbenar is NULL GROUP BY jw.idsiswa, jw.idset";
			
			$qcekjwb=$conn->query($sql);
			$cek=$qcekjwb->fetch_array();
			$kosong=$cek['jawab'];
			$jmlsoal=$cek['jumsoal'];
			$durasi=$cek['durasi']*60;
			if($kosong==$jumsoal){
				$conn->query("UPDATE tblogpeserta SET status='0', sisawaktu='$durasi', logmulai='$saiki', logakhir='$saiki' WHERE status='1' AND idjadwal='$_POST[jd]' AND idsiswa='$_POST[id]' AND sisawaktu>0");
				
			}
			else {
				$conn->query("UPDATE tblogpeserta SET status='0' WHERE status='1' AND idjadwal='$_POST[jd]' AND idsiswa='$_POST[id]'");
			}
			$conn->query("UPDATE tbpeserta SET aktif='1' WHERE idsiswa='$_POST[id]'");
		}
		else {
			$sql="SELECT COUNT(*) as jawab,su.jumsoal, jd.durasi FROM tbjawaban jw INNER JOIN tbsetingujian su USING(idset) INNER JOIN tbjadwal jd USING(idjadwal) WHERE su.idjadwal='$_POST[jd]' AND jw.jwbbenar is NULL GROUP BY jw.idsiswa, jw.idset";
			
			$qcekjwb=$conn->query($sql);
			$cek=$qcekjwb->fetch_array();
			$kosong=$cek['jawab'];
			$jmlsoal=$cek['jumsoal'];
			$durasi=$cek['durasi']*60;
			if($kosong==$jumsoal){
				$conn->query("UPDATE tblogpeserta SET status='0', sisawaktu='$durasi', logmulai='$saiki', logakhir='$saiki' WHERE status='1' AND idjadwal='$_POST[jd]' AND sisawaktu>0");
				
			}
			else {
				$conn->query("UPDATE tblogpeserta SET status='0', durasi='$durasi' WHERE status='1' AND idjadwal='$_POST[jd]'");
			}
		}
		
		echo 'Reset Peserta Berhasil!';
	}    
    if($_POST['aksi']=='2'){
		if(isset($_POST['id'])){
			$qupd=$conn->query("UPDATE tblogpeserta lp SET lp.status='1', lp.sisawaktu='0' WHERE lp.idjadwal='$_POST[jd]' OR lp.status='0' AND lp.idsiswa='$_POST[id]'");
			$qpst=$conn->query("UPDATE tbpeserta SET aktif='0' WHERE idsiswa='$_POST[id]'");
		}
		else{
			$qupd=$conn->query("UPDATE tblogpeserta lp SET lp.status='1', lp.sisawaktu='0' WHERE lp.idjadwal='$_POST[jd]' OR lp.status='0'");
			$qpst=$conn->query("UPDATE tbpeserta s INNER JOIN tbujian u USING(idujian) SET s.aktif='0' WHERE u.status='1'");
		}
		echo 'Logout Peserta Berhasil!';
	}
?>