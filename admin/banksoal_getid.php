<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
$kdkls = $_REQUEST['kls'];
$idmapel = str_replace("&", "", substr($_REQUEST['map'], 0, 2));

$qmp = $conn->query("SELECT akmapel FROM tbmapel WHERE idmapel='$idmapel'");
$mp = $qmp->fetch_array();
$kdmapel = strtoupper($mp['akmapel']);

$quji = $conn->query("SELECT idujian FROM tbujian WHERE status='1'");
$uj = $quji->fetch_array();
$kduji = $uj['idujian'];

$sql = $conn->query("SELECT COUNT(*) as id FROM tbbanksoal p INNER JOIN tbujian u USING(idujian)  WHERE u.status='1'");
$row = $sql->fetch_array();
$id = $row['id'] + 1;

$idpaket = $kdmapel . $kduji . substr('0' . $kdkls . '00' . $id, -5);;
echo $idpaket;
