<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	$quser=$conn->query("SELECT `level` FROM tbuser WHERE username='$_COOKIE[id]'");
	$u=$quser->fetch_assoc();
	$level=$u['level'];
	if (isset($_GET['k'])){
		if($level=='1'){
			$query = $conn->query("SELECT idrombel, nmrombel FROM tbrombel r INNER JOIN tbthpel t ON t.idthpel=r.idthpel WHERE r.idkelas='$_GET[k]' AND t.aktif='1'");
			echo "<option selected value=''>..Pilih..</option>";
			while($d = $query->fetch_array())
			{
				echo "<option value='$d[idrombel]'>$d[nmrombel]</option>";
			}
		}
		else {
			$query = $conn->query("SELECT idrombel, nmrombel FROM tbrombel r INNER JOIN tbthpel t ON t.idthpel=r.idthpel INNER JOIN tbpengampu p USING(idrombel) WHERE r.idkelas='$_GET[k]' AND p.username='$useraktif' AND tp.aktif='1' GROUP BY p.idrombel");
			echo "<option selected value=''>..Pilih..</option>";
			while($d = $query->fetch_array())
			{
				echo "<option value='$d[idrombel]'>$d[nmrombel]</option>";
			}
		}
		
	}
?>