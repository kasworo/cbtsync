<?php
	 define("BASEPATH", dirname(__FILE__));
	 include "../config/konfigurasi.php";
	if(isset($_POST['aksi']) && $_POST['aksi']=='1'){
		$qcek=$conn->query("SELECT*FROM tbrombelsiswa rs INNER JOIN tbrombel r USING(idrombel) WHERE rs.idsiswa='$_POST[id]' AND r.idthpel='$_COOKIE[c_tahun]'");
		$cek=$qcek->num_rows;
		if($cek==0){
			$sql=$conn->query("INSERT INTO tbrombelsiswa(idsiswa, idrombel) VALUES ('$_POST[id]','$_POST[rm]')");
			echo 'Simpan Anggota Rombel Berhasil!';
		}
		else {
			$sql=$conn->query("UPDATE tbrombelsiswa rs INNER JOIN tbrombel r USING(idrombel) SET rs.idrombel='$_POST[rm]' WHERE rs.idsiswa='$_POST[id]' AND r.idthpel='$_COOKIE[c_tahun]'");
			echo 'Update Anggota Rombel Berhasil!';
		}
	}

	if(isset($_POST['aksi']) && $_POST['aksi']=='2'){
		$sql=$conn->query("REPLACE INTO tbrombelsiswa (idsiswa, idrombel) SELECT idsiswa, '$_POST[rb]' FROM tbrombelsiswa rs INNER JOIN tbsiswa s USING(idsiswa) WHERE idrombel='$_POST[ra]' AND s.deleted='0'");
		echo 'Salin Anggota Rombel Berhasil!';
	}
?>