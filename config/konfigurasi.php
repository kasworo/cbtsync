<?php
    defined("BASEPATH") or exit("No direct access allowed");
    $host="localhost";
    $user="smpnlipa_admin";
    $pwd="Gemini84Ok";
    $db="smpnlipa_elearning";
    $conn= new mysqli($host, $user, $pwd, $db);
    if(mysqli_connect_errno()) {
        echo "Error: Could not connect to database.";
        exit;
    }
?>