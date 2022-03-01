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
    $idthpel=$th['idthpel'];

    if($_REQUEST['aksi']=='edit'){
        $qm=$conn->query("SELECT*FROM tbtes WHERE idtes='$_POST[id]'");
        $m=$qm->fetch_array();
        echo json_encode($m);
    }
    if($_REQUEST['aksi']=='aktif'){
        $quji=$conn->query("SELECT*FROM tbujian WHERE idthpel='$idthpel' AND idtes='$_POST[id]' AND status='1'");
        $cek=$quji->num_rows;
        if($cek>0){
            $qm=$conn->query("SELECT t.* FROM tbtes t INNER JOIN tbujian u ON u.idtes=t.idtes WHERE u.idtes='$_POST[id]'");
            $m=$qm->fetch_array();
            $data=array(
                'idthpel'=>$idthpel,
                'idtes'=>$m['idtes'],
                'kls'=>'btn btn-secondary btn-sm col-4',
                'btn'=>'<i class="fas fa-power-off"></i>&nbsp;Nonaktifkan'

            );
            echo json_encode($data);
        }
        else {
            $qm=$conn->query("SELECT*FROM tbtes WHERE idtes='$_POST[id]'");
            $m=$qm->fetch_array();
            $data=array(
                'idthpel'=>$idthpel,
                'idtes'=>$m['idtes'],
                'kls'=>'btn btn-success btn-sm col-4',
                'btn'=>'<i class="far fa-check-square"></i>&nbsp;Aktifkan'
            );
            echo json_encode($data);
        }
    }

?>