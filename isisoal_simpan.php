<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	if($_POST['aksi']=='1'){
		$qcek=$conn->query("SELECT*FROM tbsoal WHERE idbank='$_POST[ib]' AND nomersoal='$_POST[nm]'");
		$butir=addslashes($_POST['bt']);
		$cek=$qcek->num_rows;
		if($cek==0){
			$sql=$conn->query("INSERT INTO tbsoal (idbank,nomersoal, jnssoal, modeopsi, tksukar, skormaks, butirsoal) VALUES ('$_POST[ib]','$_POST[nm]','$_POST[js]','$_POST[mo]','$_POST[tk]','$_POST[sk]','$butir')");			
			echo 'Simpan Butir Soal Berhasil!';
		}
		else {
			$sql=$conn->query("UPDATE tbsoal SET idbank = '$_POST[ib]',nomersoal='$_POST[nm]', jnssoal ='$_POST[js]', modeopsi='$_POST[mo]', tksukar = '$_POST[tk]', butirsoal='$butir', skormaks='$_POST[sk]' WHERE idbutir='$_POST[id]'");
			echo 'Update Butir Soal Berhasil!';
		}
	}
	
	if($_POST['aksi']=='2'){
		$qjns=$conn->query("SELECT jnssoal, modeopsi FROM tbsoal WHERE idbutir='$_POST[soal]'");
		$js=$qjns->fetch_array();

		$qcek=$conn->query("SELECT*FROM tbopsi WHERE idopsi='$_POST[id]'");
		$cek=$qcek->num_rows;

		if($js['modeopsi']=='1' && ($js['jnssoal']=='1' || $js['jnssoal']=='2')){
			if($_POST['skr']=='1') {
				$skor=1;
				$b=1;
			} else {
				$skor=0;
				$b=0;
			}
			$opsi=addslashes($_POST['ops']);
			$opsia=addslashes($_POST['ops2']);	
			if($cek==0){			
				$sql=$conn->query("INSERT INTO tbopsi (idbutir,opsi, opsialt, benar, skor) VALUES ('$_POST[soal]','$opsi','$opsia','$b','$skor')");
				echo 'Simpan Opsi Jawaban Berhasil!';
			}
			else {
				$sql=$conn->query("UPDATE tbopsi SET opsi='$opsi', opsialt='$opsia', benar='$b',skor='$skor' WHERE idopsi='$_POST[id]'");
				echo 'Update Opsi Jawaban Berhasil!';
			}
		}
		else {
			if($js['jnssoal']=='1' || $js['jnssoal']=='2'){
				if($_POST['skr']=='1') {
					$skor=1;
					$b=1;
				} else {
					$skor=0;
					$b=0;
				}
				$opsi=addslashes($_POST['ops']);	
				if($cek==0){			
					$sql=$conn->query("INSERT INTO tbopsi (idbutir, opsi, benar, skor) VALUES ('$_POST[soal]','$opsi', '$b','$skor')");
					echo 'Simpan Opsi Jawaban Berhasil!';
				}
				else {
					$sql=$conn->query("UPDATE tbopsi SET opsi='$opsi', benar='$b', skor='$skor' WHERE idopsi='$_POST[id]'");
					echo 'Update Opsi Jawaban Berhasil!';
				}
			}
			else if($js['jnssoal']=='3'){
				if($_POST['skr']=='1') {
					$skor=1;
					$b=1;
				} else {
					$skor=1;
					$b=0;
				}
				$opsi=addslashes($_POST['ops']);
				$opsia=addslashes($_POST['ops2']);	
				if($cek==0){			
					$sql=$conn->query("INSERT INTO tbopsi (idbutir,opsi, opsialt, benar, skor) VALUES ('$_POST[soal]','$opsi','$opsia','$b','$skor')");
					echo 'Simpan Opsi Jawaban Berhasil!';
				}
				else {
					$sql=$conn->query("UPDATE tbopsi SET opsi='$opsi', opsialt='$opsia', benar='$b',skor='$skor' WHERE idopsi='$_POST[id]'");
					echo 'Update Opsi Jawaban Berhasil!';
				}
			}
			else if($js['jnssoal']=='4')
			{
				if($_POST['skr']=='1') {
					$skor=1;
					$b=1;
				} else {
					$skor=0;
					$b=0;
				}
				$opsi=addslashes($_POST['ops']);
				$opsia=addslashes($_POST['ops2']);	
				if($cek==0){			
					$sql=$conn->query("INSERT INTO tbopsi (idbutir,opsi, opsialt, benar, skor) VALUES ('$_POST[soal]','$opsi','$opsia','$b','$skor')");
					echo 'Simpan Opsi Jawaban Berhasil!';
				}
				else {
					$sql="UPDATE tbopsi SET opsi='$opsi', opsialt='$opsia', benar='$b',skor='$skor' WHERE idopsi='$_POST[id]'";
					$conn->query($sql);
					echo 'Update Opsi Jawaban Berhasil!';
				}
			}
			else {
				if($_POST['skr']=='1') {
					$skor=1;
					$b=1;
				} else {
					$skor=0;
					$b=0;
				}
				$opsi=addslashes($_POST['ops']);	
				if($cek==0){			
					$sql=$conn->query("INSERT INTO tbopsi (idbutir, opsi, benar, skor) VALUES ('$_POST[soal]','$opsi', '$b','$skor')");
					echo 'Simpan Opsi Jawaban Berhasil!';
				}
				else {
					$sql=$conn->query("UPDATE tbopsi SET opsi='$opsi', benar='$b', skor='$skor' WHERE idopsi='$_POST[id]'");
					echo 'Update Opsi Jawaban Berhasil!';
				}
			}			
		}		
	}

	if($_POST['aksi']=='3'){
		$sql=$conn->query("DELETE FROM tbsoal WHERE idbutir='$_POST[id]'");
		echo 'Butir Soal Berhasil Hapus!';
	}
	if($_POST['aksi']=='4'){
		$sql=$conn->query("DELETE FROM tbsoal WHERE idbank='$_POST[ib]'");
		echo 'Butir Soal Berhasil Dikosongkan!';
	}

	if($_POST['aksi']=='5'){
		$sql=$conn->query("DELETE FROM tbopsi WHERE idopsi='$_POST[id]'");
		echo 'Opsi Jawaban Berhasil Hapus!';
	}

	if($_POST['aksi']=='6'){
		$qcek=$conn->query("SELECT jnssoal FROM tbsoal WHERE idbutir='$_POST[ib]'");
		$cj=$qcek->fetch_array();
		if($cj['jnssoal']=='1'){
			$sql=$conn->query("UPDATE tbopsi SET benar='1', skor='1' WHERE idopsi='$_POST[id]' AND idbutir='$_POST[ib]'");
			$sql=$conn->query("UPDATE tbopsi SET benar='0', skor='0' WHERE idopsi<>'$_POST[id]' AND idbutir='$_POST[ib]'");

			$upd=$conn->query("UPDATE tbjawaban SET skor='1' WHERE jwbbenar='$_POST[id]' AND idbutir='$_POST[ib]'");
			$upd=$conn->query("UPDATE tbjawaban SET skor='0' WHERE jwbbenar<>'$_POST[id]' AND idbutir='$_POST[ib]'");	
		}
		elseif($cj['jnssoal']=='2'){
			if($_POST['nil']==1){$skor=1;} else {$skor=0;}
			$sql=$conn->query("UPDATE tbopsi SET benar='$_POST[nil]', skor='$skor' WHERE idopsi='$_POST[id]' AND idbutir='$_POST[ib]'");
			
			$qskops=$conn->query("SELECT GROUP_CONCAT(idopsi) as opsibenar, COUNT(*) as jumlahe FROM tbopsi WHERE idbutir='$_POST[ib]' AND benar='1' GROUP BY idbutir");
			$ops=$qskops->fetch_array();
			$kunci=explode(",",$ops['opsibenar']);

			$qsalah=$conn->query("SELECT  COUNT(*) as salahe FROM tbopsi WHERE idbutir='$_POST[ib]' AND benar='0' GROUP BY idbutir");
			$s=$qsalah->fetch_array();
			$jmls=$s['salahe'];

			$qops=$conn->query("SELECT jwbbenar FROM tbjawaban WHERE idbutir='$_POST[ib]'");
			while($ops=$qops->fetch_array()){
				$jwbbenar=explode(",",$ops['jwbbenar']);
				$val=array_search($idopsi, $kunci,true);
			}

		}
		elseif($cj['jnssoal']=='3'){
			$sql=$conn->query("UPDATE tbopsi SET benar='$_POST[nil]', skor='1' WHERE idopsi='$_POST[id]' AND idbutir='$_POST[ib]'");
			
			// $qskops=$conn->query("SELECT GROUP_CONCAT(idopsi) as opsibenar, COUNT(*) as jumlahe FROM tbopsi WHERE idbutir='$_POST[ib]' AND benar='1' GROUP BY idbutir");
			// $ops=$qskops->fetch_array();
			// $kunci=explode(",",$ops['opsibenar']);
			// $jmlb=$ops['jumlahe'];

			// $qsalah=$conn->query("SELECT  COUNT(*) as salahe FROM tbopsi WHERE idbutir='$_POST[ib]' AND benar='0' GROUP BY idbutir");
			// $s=$qsalah->fetch_array();
			// $jmls=$s['salahe'];

			// $qops=$conn->query("SELECT jwbbenar FROM tbjawaban WHERE idbutir='$_POST[ib]'");
			// while($ops=$qops->fetch_array()){
			// 	$jwbbenar=explode(",",$ops['jwbbenar']);
			// 	$val=array_search($idopsi, $cekops,true);
			// }				
		}
		elseif($cj['jnssoal']=='4'){
			$sql=$conn->query("UPDATE tbopsi SET benar='$_POST[nil]', skor='1' WHERE idopsi='$_POST[id]' AND idbutir='$_POST[ib]'");				
		}
		else{
			$sql=$conn->query("UPDATE tbopsi SET benar='$_POST[nil]', skor='1' WHERE idopsi='$_POST[id]' AND idbutir='$_POST[ib]'");				
		}
		echo "Update Kunci Jawaban Berhasil!";		
	}
	if($_POST['aksi']=='7'){
		$qch=$conn->query("SELECT*FROM tbheaderopsi WHERE idbutir='$_POST[id]'");
		$cekh=$qch->num_rows;
		if($cekh==0){
			$sql=$conn->query("INSERT INTO tbheaderopsi(idbutir, header1, header2) VALUES ('$_POST[id]','$_POST[hd1]','$_POST[hd2]')");			
			echo 'Simpan Header Opsi Berhasil!';
		}
		else {
			$sql=$conn->query("UPDATE tbheader SET header1 = '$_POST[hd1]',header2='$_POST[hd2]' WHERE idbutir='$_POST[id]'");
			echo 'Update Header Opsi Berhasil!';
		}
	}
?>