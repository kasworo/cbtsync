<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
if (isset($_GET['k'])) {
	$sql = "SELECT idmapel, nmmapel FROM tbpengampu p INNER JOIN tbmapel m USING(idmapel) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbkelas k USING(idkelas) INNER JOIN tbthpel t ON r.idthpel=t.idthpel INNER JOIN tbgtk u ON p.idgtk=u.idgtk WHERE r.idkelas='$_GET[k]' GROUP BY p.idmapel";
	$dkls = vquery($sql);
	echo "<option selected value=''>..Pilih..</option>";
	foreach ($dkls as $kl) {
		echo "<option value='$kl[idmapel]&l=$_GET[k]'>$kl[nmmapel]</option>";
	}
}


if (isset($_GET['m'])) {
	$sql = "SELECT u.idgtk, u.nama  FROM tbpengampu p INNER JOIN tbmapel m USING(idmapel) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbkelas k USING(idkelas) INNER JOIN tbthpel t ON r.idthpel=t.idthpel INNER JOIN tbgtk u ON p.idgtk=u.idgtk WHERE p.idmapel='$_GET[m]' AND r.idkelas='$_GET[l]' GROUP BY p.idmapel";
	$dk = vquery($sql);
	echo "<option selected value=''>..Pilih..</option>";
	foreach ($dk as $d) {
		echo "<option value='$d[idgtk]'>$d[nama]</option>";
	}
}
