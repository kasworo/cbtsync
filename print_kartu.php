<?php
	define("BASEPATH", dirname(__FILE__));
	require('../assets/library/fpdf/fpdf.php'); 
	include "../config/konfigurasi.php";
	include "../config/fungsi_tgl.php";
	function getuser(){
		global $conn;
		$quser=$conn->query("SELECT username, `level`,foto FROM tbuser WHERE username='$_COOKIE[id]'");
		$u=$quser->fetch_assoc();
		$users=array(
			'user'=>$u['username'],
			'level'=>$u['level']
		);
		return $users;
	}
	class PDF extends FPDF{
		function Header(){
			global $conn;
			$qsk = $conn->query("SELECT*FROM tbskul");
			$ad = $qsk->fetch_array();
			$namsek = strtoupper($ad['nmskul']);
			$logsek = $ad['logoskul'];

			$qth=$conn->query("SELECT*FROM tbthpel WHERE aktif='1'");
			$setid=$qth->fetch_array();
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
			$this->Image($logo,1.0,0.75,1.35);			
			$this->SetTextColor(0,0,0);
			$this->SetFont('Times','B','12');
			$this->Cell(1.75,0.5,'');
			$this->Cell(16.25,0.5,'KARTU TANDA PESERTA',0,0,'C',0);
			$this->Ln();
			$this->SetFont('Times','B','11');
			$this->Cell(1.75,0.5,'');
			$this->Cell(16.25,0.5,strtoupper($nmuji),0,0,'C',0);
			$this->Ln();
			$this->Cell(1.75,0.5,'');
			$this->Cell(16.25,0.5,strtoupper('Tahun Pelajaran ').$thpel,0,0,'C',0);		
			$this->SetLineWidth(0.05);		
			$this->Line(1.0,2.5,20.0,2.5);
			$this->Ln(1.25); 
		}

		function Footer(){
			global $conn;
			$sqlad0=$conn->query( "SELECT a.idjenjang, j.akjenjang, a.nmskpd, a.kec, r.nmrayon, p.nmprov FROM tbskul a INNER JOIN tbjenjang j ON j.idjenjang=a.idjenjang INNER JOIN tbrayon r ON a.idrayon=r.idrayon INNER JOIN tbprov p ON r.idprov=p.idprov");
			$dt=mysqli_fetch_array($sqlad0);
			$nmjenjang=$dt['akjenjang'];
			if($nmjenjang=='SMA' || $nmjenjang=='SMK')
			{
			$nmtmp=strtoupper($dt['nmskpd'].' provinsi '.$dt['nmprov']); 
			}
			elseif($nmjenjang=='SMP')
			{
			$nmtmp=strtoupper($dt['nmskpd'].' '.$dt['nmrayon']);
			}
			else
			{
			$nmtmp=strtoupper($dt['nmskpd'].' kecamatan '.$dt['kec']);
			}
			$this->SetFont('Times','','10');
			$this->SetY(-1.45,5);
			$this->Rect(1,13.40,0.55,0.55);
			$this->Rect(1.75,13.40,17.25,0.55);
			$this->Rect(19.25,13.40,0.55,0.55); 
			$this->Cell(19.0,0.55,$nmtmp,0,0,'C');
			} 
		}
		
		$pdf = new PDF('L','cm','A5');
		$pdf->AliasNbPages();
		$pdf->SetMargins(1.75,0.75,1.0);
		$qth=$conn->query("SELECT*FROM tbthpel WHERE aktif='1'");
		$setid=$qth->fetch_array();
		$sem=substr($setid['nmthpel'],-1);
		if($sem=='1'){$semester='I (Satu)';} else {$semester='II (Dua)';}
		$du=getuser();
		$level=$du['level'];
		$useraktif=$du['username'];
		if($level){
			$sql = $conn->query("SELECT ps.idsiswa, ps.nmsiswa, ps.tgllahir, ps.tmplahir, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian, r1.kdruang, r1.nmruang FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t USING(idthpel) INNER JOIN tbujian u USING(idujian) INNER JOIN tbruang r1 USING(idruang) WHERE u.status='1' AND t.aktif='1' GROUP BY ps.idsiswa ORDER BY r.idrombel DESC, ps.nis");
		}
		else{
			$sql= $conn->query("SELECT ps.idsiswa, ps.nmsiswa, ps.tgllahir, ps.tmplahir, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian, r1.kdruang, r1.nmruang FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t USING(idthpel) INNER JOIN tbpengampu p USING(idrombel) INNER JOIN tbujian u USING(idujian) INNER JOIN tbruang r1 USING(idruang) WHERE u.status='1' AND t.aktif='1' AND p.username='$useraktif' GROUP BY ps.idsiswa ORDER BY ps.nmpeserta ASC");
		}
		while($d=$sql->fetch_array())
		{
			$pdf->AddPage();
			$pdf->SetFont('Times','B','11');
			$pdf->Cell(4.5,0.5,"Nama Peserta ",0,0,'L');
			$pdf->SetFont('Times','','11');
			$pdf->Cell(10.0,0.5,": ".strtoupper($d['nmsiswa']),0,0,'L');
			$pdf->Ln();
			$pdf->SetFont('Times','B','11');
			// $pdf->Cell(4.5,0.5,"Nomor Peserta",0,0,'L');
			// $pdf->SetFont('Times','','11');
			// $pdf->Cell(8.0,0.5,": ".substr($d['nmpeserta'],3,2).'-'.substr($d['nmpeserta'],5,4).'-'.substr($d['nmpeserta'],9,4).'-'.substr($d['nmpeserta'],-1),0,0,'L');
			$pdf->Cell(4.5,0.5,"Username / Password",0,0,'L');
			$pdf->SetFont('Times','','11');
			$pdf->Cell(8.0,0.5,": ".$d['nmpeserta'].' / '.$d['passwd'],0,0,'L');
			$pdf->SetFont('Times','B','11');
			$pdf->Cell(3.0,0.5,"Ruang Ujian",0,0,'L');
			$pdf->SetFont('Times','','11');
			$pdf->Cell(6.0,0.5,": ".$d['kdruang'].' - '.$d['nmruang'] ,0,0,'L');
			$pdf->Ln();
			$pdf->SetFont('Times','B','11');
			$pdf->Cell(4.5,0.5,"Tempat / Tanggal Lahir ",0,0,'L');
			$pdf->SetFont('Times','','11');
			$pdf->Cell(8.0,0.5,": ".ucwords(strtolower($d['tmplahir'])).', '.indonesian_date($d['tgllahir']),0,0,'L');
			$pdf->SetFont('Times','B','11');
			$pdf->Cell(3.0,0.5,"Kelas / Semester",0,0,'L');
			$pdf->SetFont('Times','','11');
			$pdf->Cell(6.0,0.5,": ".$d['nmrombel']." / ".$semester,0,0,'L'); 	
				
			$pdf->Ln(0.75);
			$pdf->SetFont('Times','BU','11');
			$pdf->Cell(16.5,0.5,"JADWAL PELAKSANAAN UJIAN",'',0,'C');
			$pdf->Ln(0.75);
			$pdf->SetFont('Times','','11');
			$qjd=$conn->query( "SELECT jd.* FROM tbjadwal jd INNER JOIN tbujian u USING(idujian) WHERE u.status='1'");
			$cekjd=$qjd->num_rows;
			$i=0;
			while($jd=$qjd->fetch_array())
			{
				$i++;
				$qses=$conn->query("SELECT se.mulai, se.selesai FROM tbsesiujian su INNER JOIN tbsesi se USING(idsesi) WHERE su.idjadwal='$jd[idjadwal]' AND su.idsiswa='$d[idsiswa]'");
				$ses=mysqli_fetch_array($qses);
				$cell[$i][0]=$i.'.';
				$cell[$i][1]=$jd['tglujian'];
				$cell[$i][2]=$jd['matauji'];
				$cell[$i][3]=substr($ses['mulai'],0,5).'-'.substr($ses['selesai'],0,5);
			}

			if($cekjd>6){
				$batas=ceil($cekjd/2);
				$pdf->Cell(0.75,0.55,'No','LTBR',0,'C');
				$pdf->Cell(2.25,0.55,'Tanggal','LTBR',0,'C');
				$pdf->Cell(3.75,0.55,'Mata Pelajaran','LTBR',0,'C');
				$pdf->Cell(2.25,0.55,'Jam','LTBR',0,'C');
				$pdf->Cell(0.25,0.55);
				$pdf->Cell(0.75,0.55,'No','LTBR',0,'C');
				$pdf->Cell(2.25,0.55,'Tanggal','LTBR',0,'C');
				$pdf->Cell(3.75,0.55,'Mata Pelajaran','LTBR',0,'C');
				$pdf->Cell(2.25,0.55,'Jam','LTBR',0,'C');
				$pdf->Ln();
				for($j=1;$j<=$batas;$j++)
				{
					$pdf->Cell(0.75,0.55,$cell[$j][0],'LBTR',0,'C');
					$pdf->Cell(2.25,0.55,date('d-m-Y',strtotime($cell[$j][1])),'LTBR',0,'C');
					$pdf->Cell(3.75,0.55,$cell[$j][2],'LTBR',0,'L');
					$pdf->Cell(2.25,0.55,$cell[$j][3],'LTBR',0,'C');
					$pdf->Cell(0.25,0.55);
					$pdf->Cell(0.75,0.55,$cell[$j+$batas][0],'LBTR',0,'C');
					$pdf->Cell(2.25,0.55,date('d-m-Y',strtotime($cell[$j+$batas][1])),'LTBR',0,'C');
					$pdf->Cell(3.75,0.55,$cell[$j+$batas][2],'LTBR',0,'L');
					$pdf->Cell(2.25,0.55,$cell[$j+$batas][3],'LTBR',0,'C');
					$pdf->Ln();
				}
			}
			else {
				$batas=$cekjd;
				$pdf->Cell(1,0.55,'No','LTBR',0,'C');
				$pdf->Cell(4.5,0.55,'Tanggal Ujian','LTBR',0,'C');
				$pdf->Cell(6.5,0.55,'Mata Pelajaran','LTBR',0,'C');
				$pdf->Cell(5.5,0.55,'Jam','LTBR',0,'C');
				$pdf->Ln();
				for ($j=1;$j<=$batas;$j++)
				{
					
					$pdf->Cell(1,0.55,$cell[$j][0],'LBTR',0,'C');
					$pdf->Cell(4.5,0.55,indonesian_date($cell[$j][1]),'LTBR',0,'L');
					$pdf->Cell(6.5,0.55,$cell[$j][2],'LTBR',0,'L');
					$pdf->Cell(5.5,0.55,$cell[$j][3].' WIB','LTBR',0,'C');
					$pdf->Ln();
				}				
			}
			$pdf->Ln(0.5);
			$pdf->SetFont('Times','BI','11');
			$pdf->Cell(16.25,0.5,"Petunjuk:",'',0,'L');
			$pdf->Ln(0.5);
			$pdf->SetFont('Times','','11');
			$pdf->Cell(0.75,0.5,'1. ','',0,'L');
			$pdf->Cell(15.25,0.5,"Nonaktifkan paket data seluler, dan pembaharuan pada ponsel yang akan digunakan.",'',0,'L');
			$pdf->Ln();
			$pdf->Cell(0.75,0.5,'2. ','',0,'L');
			$pdf->Cell(15.25,0.5,"Aktifkan wifi pada perangkat android anda, hubungkan ke SSID sesuai dengan petunjuk pengawas ujian.",'',0,'L');
			$pdf->Ln();
			$pdf->Cell(0.75,0.5,'3. ','',0,'L');
			$pdf->Cell(15.25,0.5,"Buka aplikasi CBT Lipat, hingga halaman login terbuka.",'',0,'L');
			$pdf->Ln();
			$pdf->Cell(0.75,0.5,'4. ','',0,'L');
			$pdf->Cell(15.25,0.5,"Ketikkan username dan password yang terdapat di kartu ujian, klik tombol login.",'',0,'L');
			$pdf->Ln();
			$pdf->Cell(0.75,0.5,'5. ','',0,'L');
			$pdf->Cell(15.25,0.5,"Pastikan data anda benar pada halaman konfirmasi, selanjutnya ikuti petunjuk dari pengawas ujian.",'',0,'L');
			$pdf->Ln();
  	}	
 	$pdf->Output();