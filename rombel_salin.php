<?php
    define("BASEPATH", dirname(__FILE__));
    include "../config/konfigurasi.php";
    $quser=$conn->query("SELECT username, nama, `level`,foto FROM tbuser");
	$u=$quser->fetch_assoc();
	$username=hash('sha256',$u['username']);
	if($_COOKIE['id']==$username){
		$useraktif=$u['username'];
		$nmuser=$u['nama'];
		$level=$u['level'];
    }
    $qthn=$conn->query("SELECT idthpel FROM tbthpel WHERE aktif='1'");
    $th=$qthn->fetch_array();
    if (isset($_GET['k'])){
		if($level=='1'){
            $idth=$th['idthpel']-1;
            $query = $conn->query("SELECT idkelas,idrombel, nmrombel FROM tbrombel r INNER JOIN tbthpel t ON t.idthpel=r.idthpel WHERE r.idkelas='$_GET[k]' AND r.idthpel='$idth'");            
		}
		else {
            $query = $conn->query("SELECT idkelas,idrombel, nmrombel FROM tbrombel r INNER JOIN tbthpel t ON t.idthpel=r.idthpel INNER JOIN tbpengampu p USING(idrombel) WHERE r.idkelas='$_GET[k]'  AND r.idthpel='$idth' AND p.username='$useraktif' GROUP BY p.idrombel");            
        }
        echo "<option selected value=''>..Pilih..</option>";
            while($d = $query->fetch_array())
            {
                echo "<option value='$d[idrombel]&kls=$d[idkelas]'>$d[nmrombel]</option>";
            }
        
    }

    if (isset($_GET['kls'])){
		if($level=='1'){
            $idth=$th['idthpel'];
            $query = $conn->query("SELECT idrombel, nmrombel FROM tbrombel r INNER JOIN tbthpel t ON t.idthpel=r.idthpel WHERE r.idkelas='$_GET[kls]' AND r.idthpel='$idth'");
            
		}
		else {
            $query = $conn->query("SELECT idrombel, nmrombel FROM tbrombel r INNER JOIN tbthpel t ON t.idthpel=r.idthpel INNER JOIN tbpengampu p USING(idrombel) WHERE r.idkelas='$_GET[kls]'  AND r.idthpel='$idth' AND p.username='$_COOKIE[c_user]' GROUP BY p.idrombel");
            
        }
        echo "<option selected value=''>..Pilih..</option>";
            while($d = $query->fetch_array())
            {
                echo "<option value='$d[idrombel]'>$d[nmrombel]</option>";
            }
    }

?>