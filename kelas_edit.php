<?php
    define("BASEPATH", dirname(__FILE__));
    include "../config/konfigurasi.php";
    $qm=$conn->query("SELECT*FROM tbrombel WHERE idrombel='$_POST[id]'");
    $m=$qm->fetch_array();
    echo json_encode($m);
?>