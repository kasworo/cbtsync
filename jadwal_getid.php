<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	$quji=$conn->query("SELECT nmujian FROM tbujian WHERE idujian='$_POST[id]'");
	$u=$quji->fetch_array();
	$kduji=$u['nmujian'];

	$sql=$conn->query("SELECT count(*) as jml FROM tbjadwal j WHERE j.idujian='$_POST[id]'");
	$d=$sql->fetch_array();
	$urut=$d['jml']+1;
	if($urut>9)
	{
		$urt=substr('00'.$urut,1,3);
	}
	else
	{
		$urt=substr('00'.$urut,0,3);	
	}
	echo $kduji.$urt;
?>