<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	
	function getnopes($uji){
		global $conn;
		$qsk=$conn->query("SELECT kdskul FROM tbskul");
		$s=$qsk->fetch_array();
		$kdskul=$s['kdskul'];
		$sql=$conn->query("SELECT COUNT(*) as jml FROM tbpeserta WHERE idujian='$uji'");
		$d=$sql->fetch_array();
		$id=$d['jml']+1;
		if($id<8)
		{
			$cekdigit=10-($id%9+1);
		}
		else
		{
			$cekdigit=10-($id%8+1);
		}
		return $kdskul.substr('0000'.$id,-4).$cekdigit;
	}

	function getpasswd($hrf){ 
		$kar= '1234567890'; 
		$jkar= strlen($kar); 
		$jkar--; 
		$token=NULL; 
		for($x=1;$x<=$hrf;$x++){ 
			$pos = rand(0,$jkar); 
			$token .= substr($kar,$pos,1); 
		}
		return $token; 
	}
	
	
	if($_POST['aksi']=='1'){		
		$qkls=$conn->query("SELECT MAX(idkelas) as maksid, MIN(idkelas) as minid FROM tbkelas k INNER JOIN tbskul s ON s.idjenjang=k.idjenjang");
		$kl=$qkls->fetch_array();
		$maksid=$kl['maksid'];
		$minid=$kl['minid'];
		$quji=$conn->query("SELECT idthpel, idujian, nmtes FROM tbujian u INNER JOIN tbtes t USING(idtes) WHERE u.status='1'");
		$u=$quji->fetch_array();
		$iduji=$u['idujian'];
		$idthpel=$u['idthpel'];
		$nmtes=$u['nmtes'];
		if(strpos($nmtes,"Akhir Sekolah")!==false){
			$sql="SELECT s.*, nmrombel FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) WHERE r.idthpel='$idthpel' AND r.idkelas='$maksid' AND s.deleted='0' ORDER BY RAND()";
		}
		else if(strpos($nmtes,"Akhir Tahun")!==false){
			$sql="SELECT s.*, nmrombel FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) WHERE r.idthpel='$idthpel' AND r.idkelas<'$maksid' AND s.deleted='0' ORDER BY RAND()";
		}
		else if(strpos($nmtes,"Asesmen Kompetensi Minimum")!==false){
			$sql="SELECT s.*, nmrombel FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) WHERE r.idthpel='$idthpel' AND r.idkelas BETWEEN '$minid' AND '$maksid' AND s.deleted='0' ORDER BY RAND()";
		}
		else if(strpos($nmtes,"Seleksi Masuk")!==false || strpos($nmtes,"Tes Masuk")!==false){
			$sql="SELECT s.*, nmrombel FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) WHERE r.idthpel='$idthpel' AND r.idkelas='$minid' AND s.deleted='0' ORDER BY RAND()";
		}
		else {
			$sql="SELECT s.*, nmrombel FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) WHERE r.idthpel='$idthpel' AND s.deleted='0' ORDER BY RAND(),r.idkelas ASC";
		}
		$jumlah=0;
	
		$qs=$conn->query($sql);					
		while($nu=$qs->fetch_array()){
			$nopes=getnopes($iduji);
			$passwd=getpasswd(6).'*';
			$qcekdulu=$conn->query("SELECT nmpeserta FROM tbpeserta WHERE idsiswa='$nu[idsiswa]' AND idujian='$iduji' OR nmpeserta=NULL");
			$cekdulu=$qcekdulu->num_rows;
			if($cekdulu==0){
				$qnm="UPDATE tbpeserta SET idujian = '$iduji', nmpeserta='$nopes', passwd='$passwd' WHERE idsiswa='$nu[idsiswa]'";
				$conn->query($qnm);
				$jumlah++;
			}
			else{
				$gagal++;
			}
		}
		$qruang=$conn->query("SELECT idruang,isi FROM tbruang WHERE status='1'");
		$batasatas=0;			
		$offset=0;
		$i=0;
		while($ru=$qruang->fetch_array()){
			$i++;
			$isi=$ru['isi'];
			$ruange=$ru['idruang'];
			if($i==1){
				$sqlpst="SELECT nmpeserta FROM tbpeserta WHERE nmpeserta IS NOT NULL ORDER BY nmpeserta ASC LIMIT $isi";
			}
			else {
				$offset=$offset+$ru['isi'];
				$sqlpst="SELECT nmpeserta FROM tbpeserta WHERE nmpeserta IS NOT NULL ORDER BY nmpeserta ASC LIMIT $isi OFFSET $offset";						
			}
			$qpst=$conn->query($sqlpst);
			while ($ps=$qpst->fetch_array()){
				$qisiruang=$conn->query("UPDATE tbpeserta SET idruang='$ruange' WHERE nmpeserta='$ps[nmpeserta]'");
			}
		}
		echo "Ada ".$jumlah." Sukses, ".$gagal." Gagal Ditambahkan!";
	}

	if($_POST['aksi']=='2'){
		$qsu=$conn->query("TRUNCATE tbsesiujian");
		$qlp=$conn->query("TRUNCATE tblogpeserta");
		$sql=$conn->query("UPDATE tbpeserta SET nmpeserta=NULL, idujian=NULL, idruang=NULL, passwd=NULL");
		echo "Hapus Peserta Ujian Berhasil!";
	}
	
	if($_POST['aksi']=='3'){
		$sql=$conn->query("UPDATE tbpeserta SET idruang='$_POST[ruang]' WHERE nmpeserta='$_POST[idpes]'");
		echo "Pengaturan Ruang Ujian Berhasil!";
	}

	if($_POST['aksi']=='4'){
		$qcek=$conn->query("SELECT*FROM tbsesi WHERE nmsesi LIKE '%$_POST[sesi]%'");
		$cek=$qcek->num_rows;
		if($cek==0){
			echo "Sesi Tidak Ada";
		}
		else
		{
			$qps=$conn->query("SELECT*FROM tbsesiujian WHERE idsiswa='$_POST[idpes]' AND idjadwal='$_POST[jd]'");
			$cekps=$qps->num_rows;
			if($cekps==0){
				$sql=$conn->query("INSERT INTO tbsesiujian (idsiswa, idjadwal, idsesi) VALUES ('$_POST[idpes]','$_POST[jd]','$_POST[sesi]')");
				echo "Simpan Sesi Ujian Berhasil!";			  
			}
			else {
				$sql=$conn->query("UPDATE tbsesiujian SET idsesi='$_POST[sesi]' WHERE idsiswa='$_POST[idpes]' AND idjadwal = '$_POST[jd]'");
				echo "Update Sesi Ujian Berhasil!";
			}			
		}
	}	
?>