<?php
    define("BASEPATH", dirname(__FILE__));
    if(!isset($_COOKIE['c_user'])){header("Location: login.php");}
    include "../config/konfigurasi.php";
    $qm=$conn->query("SELECT p.nmpeserta, s.nmsiswa FROM tbpeserta p INNER JOIN tbujian u USING(idujian) INNER JOIN tbsiswa s USING(idsiswa) WHERE p.nmpeserta='$_POST[idpes]'");
    $m=$qm->fetch_array();
    echo json_encode($m);
?>