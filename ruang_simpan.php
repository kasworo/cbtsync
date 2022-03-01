<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	if(isset($_POST['aksi']) && $_POST['aksi']=='1'){
		
		$qcek=$conn->query("SELECT*FROM tbruang WHERE kdruang='$_POST[ak]'");
		$cek=$qcek->num_rows;
		if($cek==0){
			$sql="INSERT INTO tbruang (kdruang, nmruang, isi, `status`) VALUES ('$_POST[ak]','$_POST[nm]', '$_POST[isi]', '1')";
			$conn->query($sql);
			$data=$conn->affected_rows;
			if($data>0){
			echo "Simpan Ruang Ujian Berhasil!";
			} else {
				echo mysqli_error();
			}

		}
		else {
			$sql=$conn->query("UPDATE tbruang SET nmruang='$_POST[nm]', isi='$_POST[isi]' WHERE kdruang='$_POST[ak]'");
			echo 'Update Data Ruang Ujian Berhasil!';
		}
	 }
	if(isset($_POST['aksi']) && $_POST['aksi']=='3'){
		$sql=$conn->query("DELETE FROM tbruang WHERE idruang='$_POST[id]'");
		echo 'Hapus Ruang Ujian Berhasil!';
		
	 }
?>