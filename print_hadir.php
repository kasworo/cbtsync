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

			$qth=$conn->query("SELECT*FROM tbthpel WHERE idthpel='$_COOKIE[c_tahun]'");
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
			$this->Image($logo,1.0,0.65,1.35);			
			$this->SetTextColor(0,0,0);
			$this->SetFont('Times','B','12');
			$this->Cell(1.75,0.5,'');
			$this->Cell(29.25,0.5,'LAPORAN KEHADIRAN PESERTA',0,0,'C',0);
			$this->Ln();
			$this->Cell(1.75,0.5,'');
			$this->Cell(29.25,0.5,strtoupper($nmuji),0,0,'C',0);
			$this->Ln();
			$this->Cell(1.75,0.5,'');
			$this->Cell(29.25,0.5,strtoupper('Tahun Pelajaran ').$thpel,0,0,'C',0);	
			$this->SetLineWidth(0.05);		
			$this->Line(1.0,2.5,32.0,2.5);
			$this->Ln(1.25);
			$this->SetLineWidth(0.015);
			$this->SetFont('Times','B','11');
			$this->Cell(1.0,1.3,'No.','LTB',0,'C');
			$this->Cell(3.25,1.3,'Nomor Peserta','LTB',0,'C');
			$this->Cell(7.375,1.3,'Nama Peserta','LTB',0,'C');
			$this->Cell(1.375,1.3,'Kelas','LTBR',0,'C');
			$this->Cell(1.5,1.3,'Ruang','LTBR',0,'C');
			$qjd=$conn->query("SELECT jd.* FROM tbjadwal jd INNER JOIN tbsetingujian su USING(idjadwal) INNER JOIN tbujian u USING(idujian)WHERE u.status='1' AND jd.susulan='0' GROUP BY jd.tglujian");
			$this->Cell(13.75,0.65,'Tanggal Ujian','LTBR',0,'C');
			$this->Cell(2.5,1.3,'Keterangan','LTBR',0,'C');
			$this->Ln(0.65);
			$this->Cell(14.5,0.65);
			while($jd=$qjd->fetch_array()){
				$this->Cell(1.375,0.65,date('d',strtotime($jd['tglujian'])).'/'.date('m',strtotime($jd['tglujian'])),'LBR',0,'C');
			}
			$this->Ln();
		}

		function Footer(){
			include "../config/konfigurasi.php";
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
			$this->SetFont('Times','','11');
			$this->SetY(-1.6,5);
			$this->Rect(1,19.86,0.6,0.6);
			$this->Rect(2,19.86,29.0,0.6);
			$this->Rect(31.25,19.86,0.6,0.6); 
			$this->Cell(30.125,0.6,$nmtmp,0,0,'C');
			$this->Cell(0.6,0.6,$this->PageNo(),0,0,'C');
		} 
	}

	include "../config/konfigurasi.php";
	include "../config/fungsi_tgl.php";
	$pdf = new PDF('L','cm',array(21.5,33.0));
	$pdf->AliasNbPages();
	$pdf->SetMargins(1.15,0.65,0.75);
	$qsk = $conn->query("SELECT*FROM tbskul");
	$ad = $qsk->fetch_array();
	$dsskul=$ad['desa'];

	$sql = $conn->query("SELECT ru.* FROM tbsiswa s INNER JOIN tbpeserta ps USING(idsiswa) INNER JOIN tbruang ru USING(idruang) GROUP BY ps.idruang");
	while($s=$sql->fetch_array())
	{
		switch ($s['idruang'])
		{ 
			case '1':{$nmruang='I';break;}
			case '2':{$nmruang='II';break;}
			case '3':{$nmruang='III';break;}
			case '4':{$nmruang='IV';break;}
			case '5':{$nmruang='IV';break;}
			case '6':{$nmruang='VI';break;}
			case '7':{$nmruang='VII';break;}
			}
			$pdf->SetFont('Times','B','11');
			$qisi=$conn->query("SELECT s.nmsiswa, ps.nmpeserta, ps.passwd, ru.nmruang, r.nmrombel FROM tbsiswa s INNER JOIN tbpeserta ps USING(idsiswa) INNER JOIN tbrombelsiswa USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbsesiujian su USING(nmpeserta) INNER JOIN tbruang ru USING(idruang) INNER JOIN tbjadwal jd USING(idjadwal) WHERE ps.idruang='$s[idruang]' GROUP BY ps.nmpeserta, ps.idruang");
			$i=0;
			while($d=$qisi->fetch_array())
			{
				$cell[$i][0]=$i;
				$cell[$i][1]=substr($d['nmpeserta'],3,2).'-'.substr($d['nmpeserta'],5,4).'-'.substr($d['nmpeserta'],9,4).'-'.substr($d['nmpeserta'],-1); 
				$cell[$i][2]=$d['nmsiswa'];
				$cell[$i][3]=str_replace("Kelas","",$d['nmrombel']);
                $cell[$i][4]=$nmruang;
                $qjd=$conn->query("SELECT jd.* FROM tbjadwal jd INNER JOIN tbsetingujian su USING(idjadwal) INNER JOIN tbujian u USING(idujian)WHERE u.status='1' AND jd.susulan='0' GROUP BY jd.tglujian");
                $h=0;
                while($jd=$qjd->fetch_array())
                {
                    $h++;
                    $qlog=$conn->query("SELECT status FROM tblogpeserta WHERE idjadwal='$jd[idjadwal]' AND nmpeserta='$d[nmpeserta]'");
                    $l=$qlog->fetch_array();            
                    if($l['status']==''){$cell[$i][$h+4]=chr(53);} else{$cell[$i][$h+4]=chr(51);}
                }
				$i++;
			}			 
		$pdf->AddPage();
		$pdf->SetFont('Times','','11');
		for($j=0;$j<$i;$j++)
		{
			$joz=$j+1;
			$pdf->Cell(1,0.65," $joz.",'LB',0,'C'); //No
			$pdf->Cell(3.25,0.65,$cell[$j][1],'LB',0,'C');   //Username
			$pdf->Cell(7.375,0.65,$cell[$j][2],'LB',0,'L');
			$pdf->Cell(1.375,0.65,$cell[$j][3],'LB',0,'C');
			$pdf->Cell(1.5,0.65,$cell[$j][4],'LB',0,'C');
			$qjd=$conn->query("SELECT jd.* FROM tbjadwal jd INNER JOIN tbsetingujian su USING(idjadwal) INNER JOIN tbujian u USING(idujian)WHERE u.status='1' AND jd.susulan='0' GROUP BY jd.tglujian");
			$h=0;
            while($jd=$qjd->fetch_array())
            {
                $h++;
                if($cell[$j][$h+4]==chr(53)){
                    $pdf->SetTextColor(255,0,0); 
                }
                else
                {
                    $pdf->SetTextColor(0,0,0);
                }
                $pdf->SetFont('ZapfDingbats', 'B','12'); 
				$pdf->Cell(1.375,0.65,$cell[$j][$h+4],'LB',0,'C');
            }
            $pdf->SetFont('Times', '','11'); 
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(2.5,0.65,'','LBR',0,'C');
		  	$pdf->Ln();
		}

		$qjd=$conn->query("SELECT jd.* FROM tbjadwal jd INNER JOIN tbsetingujian su USING(idjadwal) INNER JOIN tbujian u USING(idujian)WHERE u.status='1' AND jd.susulan='0' GROUP BY jd.tglujian");
    	$a=0;
        while($jd=$qjd->fetch_array())
        {
            $a++;
			$qsemua=$conn->query("SELECT COUNT(idruang) as semua FROM tbpeserta WHERE idruang='$s[idruang]' GROUP BY idruang");
			$sm=$qsemua->fetch_array();
			$semua=$sm['semua'];

			$qlog=$conn->query("SELECT COUNT(*) as hadir FROM tbpeserta ps LEFT JOIN tblogpeserta lp USING(nmpeserta) INNER JOIN tbjadwal jd USING(idjadwal) WHERE lp.idjadwal='$jd[idjadwal]' AND ps.idruang='$s[idruang]'AND jd.susulan='0' GROUP BY ps.idruang");
			$lg=$qlog->fetch_array();
			$hadir=$lg['hadir'];
			$absen=$semua-$hadir;
			$col[1][0]='Jumlah Peserta Seharusnya';
			$col[2][0]='Jumlah Peserta Hadir';
			$col[3][0]='Jumlah Peserta Tidak Hadir';
			$col[1][$a]=$semua;
			$col[2][$a]=$hadir;
			$col[3][$a]=$absen;
		}
		for($k=1;$k<=3;$k++)
		{
			$pdf->Cell(14.5, 0.65,$col[$k][0],'LB',0,'C');
			$qjd=$conn->query("SELECT jd.* FROM tbjadwal jd INNER JOIN tbsetingujian su USING(idjadwal) INNER JOIN tbujian u USING(idujian)WHERE u.status='1' AND jd.susulan='0' GROUP BY jd.tglujian");
			$b=0;
			while($jd=$qjd->fetch_array())
			{
				$b++;
				$pdf->Cell(1.375,0.65,$col[$k][$b],'LB',0,'C');
			}
			$pdf->Cell(2.5,0.65,'','LBR',0,'C');
			$pdf->Ln();
		}
		$pdf->Ln(0.25);
		$pdf->Cell(10.0,0.5);
		$pdf->Cell(12.0,0.5);
		$pdf->Cell(10.0,0.5, $dsskul.', '.indonesian_date(date('Y-m-d')),'',0,'C');
		$pdf->Ln();
		$pdf->Cell(10.0,0.5,'Mengetahui:','',0,'C');
		$pdf->Cell(12.0,0.5);
		$pdf->Cell(10.0,0.5);
		$pdf->Ln();
		$pdf->Cell(10.0,0.5,'Ketua Panitia,','',0,'C');
		$pdf->Cell(12.0,0.5);
		$pdf->Cell(10.0,0.5,'Administrator,','',0,'C');
		$pdf->Ln(1.5);
		$pdf->Cell(10.0,0.5,'.................................................','',0,'C');
		$pdf->Cell(12.0,0.5);
		$pdf->Cell(10.0,0.5,'.................................................','',0,'C');			
	}
	$pdf->Output();
?>