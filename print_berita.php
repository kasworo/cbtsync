<?php
	define("BASEPATH", dirname(__FILE__));
	require('../assets/library/fpdf/fpdf.php'); 
	class PDF extends FPDF{
		function Header(){
			include "../config/konfigurasi.php";
			$qsk = $conn->query("SELECT*FROM tbskul");
			$ad = $qsk->fetch_array();
			$namsek = strtoupper($ad['nmskul']);
			$logsek = $ad['logoskul'];

			$qth=$conn->query("SELECT*FROM tbthpel WHERE aktif='1'");
			$setid=$qth->fetch_array();
			$sem=substr($setid['nmthpel'],-1);
			if($sem=='1'){$semester='I (Satu)';} else {$semester='II (Genap)';}
			$thpel=substr($setid['desthpel'],0,9);

			$quji = $conn->query("SELECT idujian, nmtes FROM tbujian u INNER JOIN tbtes t USING(idtes) WHERE status='1'");
			$uji = $quji->fetch_array();
			$nmuji = strtoupper($uji['nmtes']);
			
			if ($logsek=='') {$logo='images/tutwuri.jpg';} 
			else
			{
				if(file_exists('../images/'.$logsek))
				{
					$logo ='../images/'.$logsek; 
				}
				else
				{
				$logo='images/tutwuri.jpg';  
				}
			}
			$this->Image($logo,1.75,1.35,1.35);			
			$this->SetTextColor(0,0,0);
			$this->SetFont('Times','B','12');
			$this->Cell(1.75,0.5,'');
			$this->Cell(16.25,0.5,'BERITA ACARA PELAKSANAAN',0,0,'C',0);
			$this->Ln();
			$this->SetFont('Times','B','11');
			$this->Cell(1.75,0.5,'');
			$this->Cell(16.25,0.5,strtoupper($nmuji),0,0,'C',0);
			$this->Ln();
			$this->Cell(1.75,0.5,'');
			$this->Cell(16.25,0.5,strtoupper('Tahun Pelajaran ').$thpel,0,0,'C',0);		
			$this->Ln(0.75);
		}

		function Footer(){
			include "../config/konfigurasi.php";
			$sqlad0=$conn->query( "SELECT a.idjenjang, j.akjenjang, a.nmskpd, a.kec, r.nmrayon, p.nmprov FROM tbskul a INNER JOIN tbjenjang j ON j.idjenjang=a.idjenjang INNER JOIN tbrayon r ON a.idrayon=r.idrayon INNER JOIN tbprov p ON r.idprov=p.idprov");
			$dt=mysqli_fetch_array($sqlad0);
			$nmjenjang=$dt['akjenjang'];
			if($nmjenjang=='SMA' || $nmjenjang=='SMK'){
				$nmtmp=strtoupper($dt['nmskpd'].' provinsi '.$dt['nmprov']); 
			}
			elseif($nmjenjang=='SMP'){
				$nmtmp=strtoupper($dt['nmskpd'].' '.$dt['nmrayon']);
			}
			else{
				$nmtmp=strtoupper($dt['nmskpd'].' kecamatan '.$dt['kec']);
			}
			$this->SetFont('Times','','10');
			$this->SetY(-1.675,5);
			$this->Rect(1,28.0,0.75,0.75);
			$this->Rect(2,28.0,17.0,0.75);
			$this->Rect(19.25,28.0,0.75,0.75); 
			$this->Cell(19,0.75,$nmtmp,0,0,'C');
			$this->Cell(0.75,0.75,'',0,0,'C');
		}
		
		function IsiData($ru,$jd,$se){
			include "../config/konfigurasi.php";
			$qsk = $conn->query("SELECT*FROM tbskul");
			$ad = $qsk->fetch_array();
			$kdsek=$ad['kdskul'];
			$namsek = $ad['nmskul'];
			$desa=$ad['desa'];
			$prov=$ad['prov'];

			$quji = $conn->query("SELECT u.idujian, t.nmtes, u.idthpel FROM tbujian u INNER JOIN tbtes t USING(idtes) INNER JOIN tbthpel th USING(idthpel) WHERE u.status='1' AND u.idthpel='$_COOKIE[c_tahun]'");
			$uji = $quji->fetch_array();
			$nmuji = $uji['nmtes'];
			$thpel=substr($uji['idthpel'],-1);
			if($thpel=='1'){$semester='I (Ganjil)';} else {$semester='II (Genap)';}

			$qjd = $conn->query("SELECT ru.nmruang, jd.tglujian, jd.matauji, s1.nmsesi, s1.mulai, s1.selesai FROM tbpeserta ps INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbruang ru USING(idruang) INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbsesi s1 USING(idsesi) WHERE ru.idruang= '$ru' AND jd.idjadwal='$jd' AND s1.idsesi='$se' GROUP BY ps.idruang, jd.idjadwal, su.idsesi");
			$d=$qjd->fetch_array();
			$nmruang=$d['nmruang'];
			$namsesi=$d['nmsesi'];
			$tgluji=$d['tglujian'];
			$matauji=$d['matauji'];
			$mulai=date('H:i', strtotime($d['mulai']));
			$selesai=date('H:i', strtotime($d['selesai']));
			$hari=date('l', strtotime($tgluji));
			switch ($hari)
			{
				case 'Sunday': {$haritmp="Minggu";break;}
				case 'Monday': {$haritmp="Senin";break;}
				case 'Tuesday': {$haritmp="Selasa";break;}
				case 'Wednesday': {$haritmp="Rabu";break;}
				case 'Thursday': {$haritmp="Kamis";break;}
				case 'Friday': {$haritmp="Jumat";break;}
				case 'Saturday': {$haritmp="Sabtu";break;}
			}
			$tglasli = date('d', strtotime($tgluji));
			switch ($tglasli)
			{
				case '01':{$tgl='1';break;} case '02':{$tgl='2';break;}
				case '03':{$tgl='3';break;} case '04':{$tgl='4';break;}
				case '05':{$tgl='5';break;} case '06':{$tgl='6';break;}
				case '07':{$tgl='7';break;} case '08':{$tgl='8';break;}
				case '09':{$tgl='9';break;} default:{$tgl=$tglasli;break;}
			}
			$tgltmp=terbilang($tgl);
			$bln = date('m', strtotime($tgluji));
			switch ($bln)
			{
				case '01':{$blntmp='Januari';break;}
				case '02':{$blntmp='Februari';break;}
				case '03':{$blntmp='Maret';break;}
				case '04':{$blntmp='April';break;}
				case '05':{$blntmp='Mei';break;}
				case '06':{$blntmp='Juni';break;}
				case '07':{$blntmp='Juli';break;}
				case '08':{$blntmp='Agustus';break;}
				case '09':{$blntmp='September';break;}
				case '10':{$blntmp='Oktober';break;}
				case '11':{$blntmp='November';break;}
				case '12':{$blntmp='Desember';break;}
			}
			$thn = date('Y', strtotime($tgluji));
			$thntmp=terbilang($thn);
			$this->SetFont('Times','','12');	 
			$this->Ln(0.75);
			if(strpos($nmuji,"Tengah Semester")){
				$this->MultiCell(17.5,0.75,"Pada hari ini ".$haritmp." tanggal ".$tgltmp." bulan ".$blntmp." tahun ".$thntmp." telah diselenggarakan ".$nmuji." ".$semester." untuk mata pelajaran ".$matauji." dari pukul ".$mulai." WIB sampai dengan pukul ".$selesai." WIB.",0,'J');
			}
			else{
				$this->MultiCell(17.5,0.75,"Pada hari ini ".$haritmp." tanggal ".$tgltmp." bulan ".$blntmp." tahun ".$thntmp." telah diselenggarakan ".$nmuji." untuk mata pelajaran ".$matauji." dari pukul ".$mulai." WIB sampai dengan pukul ".$selesai." WIB.",0,'J');
			}
			$this->Cell(0.75,0.75,"1.");
			$this->Cell(5,0.75,"Kode Sekolah");
			$this->Cell(0.25,0.75,":");
			$this->Cell(11.5,0.75,$kdsek);
			$this->Ln();
			$this->Cell(0.75,0.75,"");
			$this->Cell(5,0.75,"Satuan Pendidikan");
			$this->Cell(0.25,0.75,":");
			$this->Cell(11.5,0.75,$namsek);
			$this->Ln();

			$this->Cell(0.75,0.75,"");
			$this->Cell(5,0.75,"Ruang Ujian");
			$this->Cell(0.25,0.75,":");
			$this->Cell(11.5,0.75,$nmruang,"");
			$this->Ln();
			$this->Cell(0.75,0.75,"");
			$this->Cell(5,0.75,"Sesi Ujian");
			$this->Cell(0.25,0.75,":");
			$this->Cell(11.5,0.75,$namsesi);
			$this->Ln();
			$this->Cell(0.75,0.75,"");
			$this->Cell(5,0.75,"Jumlah Peserta Seharusnya");
			$this->Cell(0.25,0.75,":");
			$this->Cell(2,0.75,' ................ ');
			$this->Cell(9.5,0.75,"Siswa");;
			$this->Ln();
			$this->Cell(0.75,0.75,"");
			$this->Cell(5,0.75,"Jumlah Hadir");
			$this->Cell(0.25,0.75,":");
			$this->Cell(2,0.75,' ................ ');
			$this->Cell(9.5,0.75,"Siswa");
			$this->Ln();
			$this->Cell(0.75,0.75,"");
			$this->Cell(5,0.75,"Jumlah Tidak Hadir");
			$this->Cell(0.25,0.75,":");
			$this->Cell(2,0.75,' ................ ');
			$this->Cell(9.5,0.75,"Siswa");
			$this->Ln();
			$this->Cell(0.75,0.75,"");
			$this->Cell(5,0.75,"Nomor Yang Tidak Hadir");
			$this->Cell(0.25,0.75,":");
			$this->Cell(11,0.75,' ......................................................................................................... ');
			$this->Ln();
			$this->Cell(6,0.75);
			$this->Cell(11,0.75,' ......................................................................................................... ');
			$this->Ln();
			$this->Cell(6,0.75);
			$this->Cell(11,0.75,' ......................................................................................................... ');
			$this->Ln();
			$this->Cell(0.75,0.75,"2.");
			$this->Cell(4,0.75,"Catatan Selama Pelaksanaan ");
			$this->Ln(0.75);
			$this->Cell(0.85,0.75,"");
			$this->Cell(16.5,4,'','LTBR',0,'C');
			$this->Ln(4.5);
			$this->Cell(17.5,0.75,"Demikian berita acara ini dibuat dengan sesungguhnya, untuk dapat dipergunakan sebagaimana mestinya.");
			$this->Ln(1.5);
			$this->Cell(10.75,0.75,"");
			$this->Cell(2,0.75,"Dibuat di",'',0,'L');
			$this->Cell(0.25,0.75,":",'',0,'L');
			$this->Cell(6.75,0.75,$desa,'',0,'L');
			$this->Ln(0.75);

			$this->Cell(10.75,0.75,"");
			$this->Cell(2,0.75,"Tanggal",'',0,'L');
			$this->Cell(0.25,0.75,":");
			$this->Cell(6.75,0.75,indonesian_date($tgluji));
			$this->Ln(0.75);

			$this->Cell(10.75,0.75,"");
			$this->Cell(7,0.75,"Pengawas Ujian,",'',0,'L');
			$this->Ln(1.8);

			$this->Cell(10.75,0.75);
			$this->Cell(7,0.75,'........................................................','',0,'L');
			$this->Ln(0.75);
			$this->Cell(10.75,0.75);
			$this->Cell(7,0.75,"NIP. ",'',0,'L');
			$this->Ln(1.5);
			$this->SetFont('Times','BI','11');
			$this->Cell(14,0.75,"Keterangan: ",0,'L');
			$this->Ln(0.75);
			$this->SetFont('Times','','11');
			$this->Cell(14,0.75,"Harap dibuat rangkap 2 (Dua), masing-masing untuk Guru Bidang Studi, dan Panitia",'');
			$this->Ln(0.75);			
		}
		function Cetak($ru,$jd, $se){
			$this->AddPage();
			$this->IsiData($ru,$jd, $se);
		} 
	}

	include "../config/konfigurasi.php";
	include "../config/fungsi_tgl.php";
	include "../config/fungsi_huruf.php";
	$pdf = new PDF('P','cm','A4');
	$pdf->AliasNbPages();
	$pdf->SetMargins(1.75,1.25,0.75);
    $saiki=date('Y-m-d');
	$sql = $conn->query("SELECT ru.idruang, jd.idjadwal, s1.idsesi FROM tbpeserta ps INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbruang ru USING(idruang) INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbsesi s1 USING(idsesi) INNER JOIN tbtoken tk USING(idjadwal, idsesi) WHERE jd.tglujian='$saiki' AND tk.status='1' GROUP BY ps.idruang, jd.idjadwal, su.idsesi ORDER BY ps.idruang, jd.idjadwal");
	$cek=$sql->num_rows;
	if($cek>0){
		while($s=$sql->fetch_array())
		{
			$pdf->Cetak($s['idruang'], $s['idjadwal'],$s['idsesi']);		
		}
	}
	else {
		$sql = $conn->query("SELECT ru.idruang, jd.idjadwal, s1.idsesi FROM tbpeserta ps INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbruang ru USING(idruang) INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbsesi s1 USING(idsesi) GROUP BY ps.idruang, jd.idjadwal, su.idsesi ORDER BY ps.idruang, jd.idjadwal");
		while($s=$sql->fetch_array())
		{
			$pdf->Cetak($s['idruang'], $s['idjadwal'],$s['idsesi']);	
		}
	}
	$pdf->Output();
?>