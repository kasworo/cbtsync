<?php
    define("BASEPATH", dirname(__FILE__));
    include "../config/konfigurasi.php";    
    $qmapel=$conn->query("SELECT ps.idsiswa, ps.nmpeserta, bs.idbank, mp.idmapel, SUM(so.skormaks) as semua, SUM(jw.skor) as benar FROM tbpeserta ps INNER JOIN tbjawaban jw USING(idsiswa) INNER JOIN tbsoal so USING(idbutir) INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) WHERE bs.idujian='$_POST[u]' GROUP BY jw.idsiswa,bs.idbank ORDER BY mp.idmapel, ps.idsiswa");
    $baru=0;
    $update=0;
    while($mp=$qmapel->fetch_array()){
        $salah=$mp['semua']-$mp['benar'];
        $nilai=$mp['benar']/$mp['semua']*100;
        $qcek=$conn->query("SELECT*FROM tbnilai WHERE idbank='$mp[idbank]' AND idsiswa='$mp[idsiswa]'");
        $cek=$qcek->num_rows;
        if($cek==0){
            $sql=$conn->query("INSERT INTO tbnilai (idbank, idujian, idsiswa, jmlsoal, benar, salah, nilai) VALUES ('$mp[idbank]','$_POST[u]','$mp[idsiswa]','$mp[semua]','$mp[benar]','$salah','$nilai')");
            $baru++;
        }
        else{
            $sql=$conn->query("UPDATE tbnilai SET jmlsoal='$mp[semua]', benar='$mp[benar]',salah='$salah', nilai='$nilai' WHERE idbank='$mp[idbank]' AND idujian='$_POST[u]' AND idsiswa='$mp[idsiswa]'");
            $update++;
        }
    }
    echo "Ada ".$baru." Data Nilai Ditambahkan, dan ".$update." Diupdate";
?>
