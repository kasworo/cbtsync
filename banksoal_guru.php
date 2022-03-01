<?php
	define("BASEPATH", dirname(__FILE__));
    include "../config/konfigurasi.php";
	function getuser(){
		global $conn;
		$quser=$conn->query("SELECT username, `level`,foto FROM tbuser WHERE username='$_COOKIE[id]'"); 
		$u=$quser->fetch_assoc();
		$username=hash('sha256',$u['username']);
		$users=array(
			'user'=>$u['username'],
			'level'=>$u['level']
		);
		return $users;
	}
	$du=getuser();
	$level=$du['level'];
	$useraktif=$du['username'];
    if (!empty($_GET['k'])){
		
		if($level=='1'){
			$query = $conn->query("SELECT idmapel, nmmapel FROM tbpengampu p INNER JOIN tbmapel m USING(idmapel) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbkelas k USING(idkelas) INNER JOIN tbthpel t ON p.idthpel=t.idthpel INNER JOIN tbuser u ON p.username=u.username WHERE r.idkelas='$_GET[k]' GROUP BY p.idmapel");
		}
		else {
			$query = $conn->query("SELECT idmapel, nmmapel FROM tbpengampu p INNER JOIN tbmapel m USING(idmapel) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbkelas k USING(idkelas) INNER JOIN tbthpel t ON p.idthpel=t.idthpel INNER JOIN tbuser u ON p.username=u.username WHERE r.idkelas='$_GET[k]' AND p.username='$useraktif' GROUP BY p.idmapel");
		
		}
		echo "<option selected value=''>..Pilih..</option>";
		while($d = $query->fetch_array())
		{
			echo "<option value='$d[idmapel]&l=$_GET[k]'>$d[nmmapel]</option>";
		}		
	}
	if(!empty($_GET['m'])){
		if($level=='1'){
			$query = $conn->query("SELECT u.username, u.nama FROM tbpengampu p INNER JOIN tbmapel m USING(idmapel) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbkelas k USING(idkelas) INNER JOIN tbthpel t ON p.idthpel=t.idthpel INNER JOIN tbuser u ON p.username=u.username WHERE p.idmapel='$_GET[m]' AND r.idkelas='$_GET[l]' GROUP BY p.idmapel, p.username");
		}
		else {
			$query = $conn->query("SELECT u.username, u.nama FROM tbpengampu p INNER JOIN tbmapel m USING(idmapel) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbkelas k USING(idkelas) INNER JOIN tbthpel t ON p.idthpel=t.idthpel INNER JOIN tbuser u ON p.username=u.username WHERE p.idmapel='$_GET[m]' AND r.idkelas='$_GET[l]' AND p.username='$useraktif' GROUP BY p.idmapel, p.username");
		}
        echo "<option selected value=''>..Pilih..</option>";
		while($d = $query->fetch_array())
		{
			echo "<option value='$d[username]'>$d[nama]</option>";
		}
	}
?>