<?php
    define("BASEPATH", dirname(__FILE__));
    include "../config/konfigurasi.php";
    $sql="SELECT r.idkelas, rs.idrombel, rs.idsiswa, s.nmsiswa FROM tbpeserta s INNER JOIN tbrombelsiswa rs ON rs.idsiswa=s.idsiswa INNER JOIN tbrombel r ON r.idrombel=rs.idrombel INNER JOIN tbthpel tp ON r.idthpel=tp.idthpel WHERE rs.idsiswa='$_POST[id]' AND tp.aktif='1'";
    
    $qcek=$conn->query($sql);
    $cek=$qcek->num_rows;
    if($cek>0){
        $judul='Edit Pengaturan Rombel';
        $tmb="<i class='fas fa-save'></i> Update";
        $m=$qcek->fetch_array();
        $data = array(
            'idsiswa'=>$m['idsiswa'],
            'nmsiswa'=>ucwords(strtolower($m['nmsiswa'])),
            'kelas'=>$m['idkelas'],
            'rombel'=>$m['idrombel'],
            'judul'=>$judul,
            'tmb'=>$tmb
        );
    }
    else {
        $judul='Buat Pengaturan Rombel';
        $tmb="<i class='fas fa-save'></i> Simpan";
        $qm=$conn->query("SELECT s.idsiswa, s.nmsiswa FROM tbpeserta s WHERE s.deleted='0' AND s.idsiswa='$_POST[id]'");        
        $m=$qm->fetch_array();
        $data = array(
            'idsiswa'=>$m['idsiswa'],
            'nmsiswa'=>ucwords(strtolower($m['nmsiswa'])),
            'kelas'=>'',
            'rombel'=>'',
            'judul'=>$judul,
            'tmb'=>$tmb
        );
    }
    echo json_encode($data);
?>