<?php
session_start();
include "dbfunction.php";
if (isset($_COOKIE['pst'])) {
    $key = array(
        'idsiswa' => $_COOKIE['pst'],
        'status' => '0'
    );
    $data = array('status' => '1');
    $selesai = editdata('tblogpeserta', $data, '', $key);
    if ($selesai > 0) {
        editdata('tbpeserta', array('aktif' => '0'), '', array('idsiswa' => $_COOKIE['pst']));
        $_SESSION = [];
        session_unset();
        session_destroy();
        setcookie('pst', '');
        setcookie('key', '');
        setcookie('uji', '');
        unset($_COOKIE['pst']);
        unset($_COOKIE['key']);
        unset($_COOKIE['uji']);
        header('location:login.php');
    } else {
        header('location:index.php?p=end');
    }
}

?>
<script>
    function disableBackButton() {
        window.history.forward();
    }
    setTimeout("disableBackButton()", 0);
</script>