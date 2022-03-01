<?php
	define("BASEPATH", dirname(__FILE__));
	require('../assets/library/fpdf/fpdf.php'); 
	include "../config/konfigurasi.php";
	include "../config/fungsi_tgl.php";
	
	class PDF extends FPDF{
        function Header(){
			global $conn;
			$qsk = $conn->query("SELECT*FROM tbskul");
			$ad = $qsk->fetch_assoc();
			$namsek = strtoupper($ad['nmskul']);
			$logsek = $ad['logoskul'];

			$qth=$conn->query("SELECT*FROM tbthpel WHERE aktif='1'");
			$setid=$qth->fetch_assoc();
			$thpel=substr($setid['desthpel'],0,9);

			$quji = $conn->query("SELECT idujian, nmtes FROM tbujian u INNER JOIN tbtes t USING(idtes) WHERE status='1'");
			$uji = $quji->fetch_assoc();
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
			$this->Image($logo,1.0,0.625,1.35);			
			$this->SetTextColor(0,0,0);
			$this->SetFont('Times','B','12');
			$this->Cell(1.75,0.5,'');
			$this->Cell(16.25,0.5,'DAFTAR HADIR PESERTA',0,0,'C',0);
			$this->Ln();
			$this->SetFont('Times','B','11');
			$this->Cell(1.75,0.5,'');
			$this->Cell(16.25,0.5,strtoupper($nmuji),0,0,'C',0);
			$this->Ln();
			$this->Cell(1.75,0.5,'');
			$this->Cell(16.25,0.5,strtoupper('Tahun Pelajaran ').$thpel,0,0,'C',0);		
			$this->SetLineWidth(0.05);		
			$this->Line(1.0,2.5,20.0,2.5);
			$this->Ln(1.0);
			$this->SetLineWidth(0.015);
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
			$this->SetY(-1.675,5);
			$this->Rect(1,28.0,0.625,0.75);
			$this->Rect(2,28.0,17.0,0.75);
			$this->Rect(19.25,28.0,0.625,0.75); 
			$this->Cell(19,0.625,$nmtmp,0,0,'C');
			$this->Cell(0.625,0.625,'',0,0,'C');
		} 
        function IsiData($j,$r,$s){  
            global $conn;
            $qth=$conn->query("SELECT*FROM tbthpel WHERE aktif='1'");
			$setid=$qth->fetch_assoc();
			$sem=substr($setid['nmthpel'],-1);
			if($sem=='1'){$semester='I (Satu)';} else {$semester='II (Genap)';}

            $qruang=$conn->query("SELECT kdruang, nmruang FROM tbruang WHERE idruang='$r'");
            $ru=$qruang->fetch_assoc();
            $nmruang=$ru['kdruang'].' ('.$ru['nmruang'].')';
            
			$qjd = $conn->query("SELECT*FROM tbjadwal WHERE idjadwal='$j'");
            $jd=$qjd->fetch_assoc();
			$matauji=$jd['matauji'];
			$tgluji=$jd['tglujian'];

			$qsesi=$conn->query("SELECT*FROM tbsesi WHERE idsesi='$s'");
			$ses=$qsesi->fetch_assoc();
			$ceksesi=$s['idsesi'];
			switch ($ceksesi) 
			{
				case '1':{$namsesi='1 (Satu)';break;}
				case '2':{$namsesi='2 (Dua)';break;}
				case '3':{$namsesi='3 (Tiga)';break;}
				case '4':{$namsesi='4 (Empat)';break;}
				default : {$namsesi = '...................................';break;}
			}
			$mulai=$ses['mulai'];
			$akhir=$ses['selesai'];

			$this->SetFont('Times','','11');
			$this->Cell(2.5,0.625,'Mata Pelajaran','',0,'L');
			$this->Cell(0.25,0.625,':',0,0,'C');
			$this->Cell(7,0.625,$matauji,'',0,'L');
			$this->Cell(2.0,0.625);
			$this->Cell(2.5,0.625,'Tanggal Ujian','',0,'L');
			$this->Cell(0.25,0.625,':',0,0,'C');
			$this->Cell(3,0.625,indonesian_date($tgluji),'',0,'L');
			$this->Ln(0.625);
			$this->Cell(2.5,0.625,'Semester','',0,'L');
			$this->Cell(0.25,0.625,':',0,0,'C');
			$this->Cell(7,0.625,$semester,'',0,'L');
			$this->Cell(2.0,0.625);
			$this->Cell(2.5,0.625,'Waktu','',0,'L');
			$this->Cell(0.25,0.625,':',0,0,'C');
			$this->Cell(3,0.625,substr($mulai,0,5). ' s/d '.substr($akhir,0,5).' WIB','',0,'L');
			$this->Ln(0.625);
			$this->Cell(2.5,0.625,'Sesi Ujian','',0,'L');
			$this->Cell(0.25,0.625,':',0,0,'C');
			$this->Cell(7,0.625, $namsesi,'',0,'L');
			$this->Cell(2.0,0.625);   
			$this->Cell(2.5,0.625,'Ruang','',0,'L');
			$this->Cell(0.25,0.625,':',0,0,'C');
            $this->Cell(7,0.625,$nmruang,'',0,'L');
			$this->Ln(1.0);

			$this->SetFont('Times','B','11');
			$this->Cell(1,0.625,'No.','LTB',0,'C');
			$this->Cell(3.25,0.625,'Nomor Peserta','LTB',0,'C');
			$this->Cell(7.375,0.625,'Nama Peserta','LTB',0,'C');
			$this->Cell(1.375,0.625,'Kelas','LTBR',0,'C');
			$this->Cell(6.0,0.625,'Tanda Tangan','LTBR',0,'C'); 
			$this->Ln();
			$qisi=$conn->query("SELECT ps.nmsiswa, ps.nmpeserta, ps.passwd, r.nmrombel FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t ON t.idthpel=r.idthpel INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbruang ru USING(idruang) INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbsesi s1 USING(idsesi) WHERE jd.idjadwal='$j' AND su.idsesi='$s' AND ps.idruang='$r' AND t.aktif='1' GROUP BY jd.idjadwal, ps.nmpeserta");
			$i=0;
			while($d=$qisi->fetch_assoc())
			{
				$cell[$i][0]=$i;
				$cell[$i][1]=substr($d['nmpeserta'],3,2).'-'.substr($d['nmpeserta'],5,4).'-'.substr($d['nmpeserta'],9,4).'-'.substr($d['nmpeserta'],-1); 
				$cell[$i][2]=ucwords(strtolower($d['nmsiswa']));
				$cell[$i][3]=str_replace("Kelas","",$d['nmrombel']);
				$i++;
			}
			$this->SetFont('Times','','11');
			for($j=0;$j<$i;$j++)
			{
				$joz=$j+1;
				$this->Cell(1,0.625," $joz.",'LTB',0,'C');
				$this->Cell(3.25,0.625,$cell[$j][1],'LTB',0,'C');
				$this->Cell(7.375,0.625,$cell[$j][2],'LTB',0,'L');
				$this->Cell(1.375,0.625,$cell[$j][3],'LTB',0,'C');
				if ($j % 2 == 0) {	
					$this->Cell(3.0,0.625,"$joz.",'LTB',0,'L');	 
					$this->Cell(3.0,0.625,"",'TBR',0,'L');
				} else {
					$this->Cell(3.0,0.625,"",'LTB',0,'L');
					$this->Cell(3.0,0.625,"$joz.",'TBR',0,'L');
				}	
				$this->Ln();
			}
           
            $this->SetFont('Times','BI','11');
            $this->Cell(5,1,"Keterangan :",0,0,'L');
            $this->Ln(0.5);
            $this->SetFont('Times','','10');
            $this->Cell(17.5,1,"1. Daftar hadir dibuat rangkap 2 (dua), masing-masing  untuk panitia, dan guru bidang studi.",0,'L');
            $this->Ln(0.5);
            $this->Cell(17.5,1,"2. Pengawas ruang menyilang Nama dan Nomor Peserta yang tidak hadir.",0,'L');
            $this->Ln(1);
            $this->Cell(7,0.625," Jumlah Peserta Seharusnya",'TL',0,'L');
            $this->Cell(3,0.625," : _____ orang",'TR',0,'L');
            $this->Cell(2,0.625);
            $this->Cell(9,0.625,"Pengawas Ujian,",0,0,'L');
            $this->Ln();
            $this->Cell(7,0.625," Jumlah Peserta Tidak Hadir",'LB',0,'L');
            $this->Cell(3,0.625," : _____ orang",'BR',0,'L');
            $this->Cell(2,0.625);
            $this->Ln();
            $this->Cell(7,0.625," Jumlah Peserta Hadir",'LB',0,'L');
            $this->Cell(3,0.625," : _____ orang",'BR',0,'L');
            $this->Cell(2,0.625);
            $this->Ln(0.5);
            $this->Cell(12,0.625);
            $this->Cell(9,0.5,"..............................................................",0,0,'L');
            $this->Ln();
            $this->Cell(12,0.5,"",0,0,'C');
            $this->Cell(9,0.5,"NIP. ");
        }
        function Cetak($j,$r,$s){
            global $conn;
            $qisi=$conn->query("SELECT ps.nmsiswa, ps.nmpeserta, ps.passwd, r.nmrombel FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbruang ru USING(idruang) INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbsesi s1 USING(idsesi) WHERE jd.idjadwal='$j' AND su.idsesi='$s' AND ps.idruang='$r' AND r.idthpel='$_COOKIE[c_tahun]'");
            $cek=$qisi->num_rows;
            if($cek>=25){
                $this->SetAutoPageBreak('true',6.0); 
            }
            else {
                $this->SetAutoPageBreak('true',3.0);
            }
            $this->AddPage();
            $this->IsiData($j,$r,$s);
        }
	}

	$pdf = new PDF('P','cm','A4');
	$pdf->AliasNbPages();
	$pdf->SetMargins(1.15,0.625,0.75);
    $saiki=date('Y-m-d');
	$jam=date('H:i:s');
	$sql = "SELECT ru.idruang, jd.idjadwal, s1.idsesi FROM tbpeserta ps INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbruang ru USING(idruang) INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbsesi s1 USING(idsesi) INNER JOIN tbtoken tk USING(idjadwal, idsesi) WHERE jd.tglujian='$saiki' AND tk.status='1' GROUP BY ps.idruang, jd.idjadwal, su.idsesi ORDER BY ps.idruang, jd.idjadwal";
	$query = $conn->query($sql);	
	$cek=$query->num_rows;
	if($cek>0){
		while($s=$query->fetch_assoc())
		{
			$pdf->Cetak($s['idjadwal'],$s['idruang'],$s['idsesi']);		
		}
	}
	else {
		$sql = "SELECT ru.idruang, jd.idjadwal, s1.idsesi FROM tbpeserta ps INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbruang ru USING(idruang) INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbsesi s1 USING(idsesi) GROUP BY ps.idruang, jd.idjadwal, su.idsesi ORDER BY ps.idruang, jd.idjadwal";
		$query=$conn->query($sql);
		while($s=$query->fetch_assoc())
		{
			$pdf->Cetak($s['idjadwal'],$s['idruang'],$s['idsesi']);		
		}
	}	
	$pdf->Output();
?>