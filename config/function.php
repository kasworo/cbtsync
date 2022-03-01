<?php
    include "konfigurasi.php";
    function getskul(){
        global $conn;
        $sql=$conn->query("SELECT*FROM tbskul");
        $rows=[];
        while($row=$sql->fetch_assoc()){
            $rows=$row;
        }
        return $rows;
    }

    function getthn(){
        global $conn;
        $sql=$conn->query("SELECT*FROM tbthpel WHERE");
        $rows=[];
        while($row=$sql->fetch_assoc()){
            $rows=$row;
        }
        return $rows;
    }

    function getmapel(){
        global $conn;
        $sql=$conn->query("SELECT idmapel, nmmapel FROM tbmapel");
        $rows=[];
        while($row=$sql->fetch_assoc()){
            $rows=$row;
        }
        return $rows;
    }