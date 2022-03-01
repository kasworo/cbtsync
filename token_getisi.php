<?php 
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	include "token_function.php";	
	if($_POST['jdw']=='' || $_POST['jdw']==null || $_POST['sesi']=='' || $_POST['sesi']==null)
	{
		$data=array(
			'jam'=>'...',
			'token'=>'...',
			'pesan'=>'...'
		);
	}
	else
	{
		$skrg=date('Y-m-d');
        $jam=date('H:i:s');
        $qcek=$conn->query("SELECT TIME_TO_SEC(timediff('$jam',jamrilis)) AS waktu, jamrilis, token FROM tbtoken WHERE idjadwal='$_POST[jdw]' AND idsesi='$_POST[sesi]' AND status='1'");
        $cek=$qcek->num_rows;
        
		if($cek>0)
		{
			$d=$qcek->fetch_array();
			$dtjmtoken=$d['jamrilis'];
			$dttoken=$d['token'];
			$selisih=$d['waktu'];
			$data = array(
				'jam'=>$dtjmtoken,
				'pesan'=>$dttoken.' (Update Terakhir '.$dtjmtoken.')');
		}
		else
		{
			$token=getToken(6);
			$data=array(
				'jam'=>$jam,
				'pesan'=>$token.' (Update Terakhir '.substr($jam,-8).')'
			);
		}
    }
    echo json_encode($data);
?>