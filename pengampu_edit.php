<?php
    define("BASEPATH", dirname(__FILE__));
    include "../config/konfigurasi.php";
    $qam=$conn->query("SELECT a.*, r.idkelas FROM tbpengampu a INNER JOIN tbmapel m ON m.idmapel=a.idmapel INNER JOIN tbrombel r ON r.idrombel=a.idrombel INNER JOIN tbthpel t ON t.idthpel=a.idthpel WHERE a.idampu='$_POST[id]' AND t.aktif='1'");
    $am=$qam->fetch_array();
    echo json_encode($am);
?>