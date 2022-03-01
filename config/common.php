<?php
    defined("BASEPATH") or exit("No direct access allowed");
    $host="localhost";
    $user="root";
    $pwd="password";
    $db="newcbt_db";
    $conn= new mysqli($host, $user, $pwd, $db);
    if(mysqli_connect_errno()) {
        echo "Error: Could not connect to database.";
        exit;
    }

    function query($tabel){
        global $conn;
        $sql=$conn->query("SELECT*FROM ".$tabel);
        $row=$sql->fetch_assoc();
    }
?>
