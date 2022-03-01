<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	include "../config/fungsi_tgl.php";
	require('../assets/library/fpdf/fpdf.php');
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
			
			
			$quji = $conn->query("SELECT u.idujian, t.nmtes, tp.nmthpel, tp.desthpel FROM tbujian u INNER JOIN tbtes t USING(idtes) INNER JOIN tbthpel tp USING(idthpel) WHERE u.status='1' AND tp.aktif='1'");
			$uji = $quji->fetch_array();
			$sem=substr($uji['nmthpel'],-1);
			if($sem=='1'){$semester='I (Satu)';} else {$semester='II (Genap)';}
			$thpel=substr($uji['desthpel'],0,9);
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
			$this->Image($logo,1.0,0.60,1.35);			
			$this->SetTextColor(0,0,0);
			$this->SetFont('Times','B','12');
			$this->Cell(1.75,0.5,'');
			$this->Cell(16.25,0.5,'LAPORAN HASIL TES',0,0,'C',0);
			$this->Ln();
			$this->SetFont('Times','B','11');
			$this->Cell(1.75,0.5,'');
			$this->Cell(16.25,0.5,strtoupper($nmuji),0,0,'C',0);
			$this->Ln();
			$this->Cell(1.75,0.5,'');
			$this->Cell(16.25,0.5,strtoupper('Tahun Pelajaran ').$thpel,0,0,'C',0);		
			$this->SetLineWidth(0.05);		
			$this->Line(1.0,2.5,20.0,2.5);
			$this->Ln(1.15);
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
			$this->Rect(1,28.0,0.60,0.75);
			$this->Rect(2,28.0,17.0,0.75);
			$this->Rect(19.25,28.0,0.60,0.75); 
			$this->Cell(19,0.60,$nmtmp,0,0,'C');
			$this->Cell(0.60,0.60,'',0,0,'C');
		} 
	}

	
	$pdf = new PDF('P','cm','A4');
	$pdf->AliasNbPages();
	$pdf->SetMargins(1.15,0.75,0.75);
	$qth=$conn->query("SELECT tp.* FROM tbthpel tp INNER JOIN tbujian u USING(idthpel) WHERE u.status='1'");
	$uji=$qth->fetch_array();
	$sem=substr($uji['nmthpel'],-1);
	if($sem=='1'){$semester='I (Satu)';} else {$semester='II (Genap)';}
	$thpel=substr($uji['desthpel'],0,9);
	if(isset($_GET['r'])){
		$sql="SELECT rb.idrombel, bs.idmapel, m.nmmapel, us.nama, rb.nmrombel, k.kkm FROM tbrombel rb INNER JOIN tbthpel t USING(idthpel) INNER JOIN tbbanksoal bs USING(idkelas) INNER JOIN tbmapel m USING(idmapel) INNER JOIN tbpengampu p USING(idrombel,idmapel) INNER JOIN tbuser us ON us.username=p.username INNER JOIN tbkkm k USING(idkelas, idmapel) WHERE bs.idbank='$_GET[id]' AND rb.idrombel='$_GET[r]'  AND t.aktif='1' GROUP BY bs.idbank, p.idrombel";
	}
	else{
		$sql = "SELECT rb.idrombel, bs.idmapel, m.nmmapel, us.nama, rb.nmrombel, k.kkm FROM tbrombel rb INNER JOIN tbthpel t USING(idthpel) INNER JOIN tbbanksoal bs USING(idkelas) INNER JOIN tbmapel m USING(idmapel) INNER JOIN tbpengampu p USING(idrombel,idmapel) INNER JOIN tbuser us ON us.username=p.username INNER JOIN tbkkm k USING(idkelas, idmapel) WHERE bs.idbank='$_GET[id]' AND t.aktif='1' GROUP BY bs.idbank, p.idrombel";
	}
	$qhsl = $conn->query($sql);
	while ($s=$qhsl->fetch_array())
	{
		$pdf->AddPage();
		$pdf->SetFont('Times','','11');
		$pdf->Cell(3.25,0.5,'Mata Pelajaran','',0,'L');
		$pdf->Cell(0.25,0.5,':',0,0,'C');
		$pdf->Cell(6,0.5,$s['nmmapel'],'',0,'L');
		$pdf->Cell(2.5,0.5);
		$pdf->Cell(3.0,0.5,'KKM','',0,'L');
		$pdf->Cell(0.25,0.5,':',0,0,'C');
		$pdf->Cell(3.25,0.5,$s['kkm'],0,0,'L');
		$pdf->Ln();
		$pdf->Cell(3.25,0.5,'Guru Bidang Studi','',0,'L');
		$pdf->Cell(0.25,0.5,':',0,0,'C');
		$pdf->Cell(6,0.5,$s['nama'],'',0,'L');
		$pdf->Cell(2.5,0.5);
		$pdf->Cell(3.0,0.5,'Kelas / Semester','',0,'L');
		$pdf->Cell(0.25,0.5,':',0,0,'C');
		$pdf->Cell(3.25,0.5,$s['nmrombel'].' / '.$semester,0,0,'L');
		$pdf->Ln(0.75);
		$pdf->SetLineWidth(0.015);
		$pdf->SetFont('Times','B','11');
		$pdf->Cell(1,0.60,'No.','LTB',0,'C');
		$pdf->Cell(3.25,0.60,'Nomor Peserta','LTB',0,'C');
		$pdf->Cell(7.375,0.60,'Nama Peserta','LTB',0,'C');
		$pdf->Cell(1.5,0.60,'Benar','LTBR',0,'C');
		$pdf->Cell(1.5,0.60,'Salah','LTBR',0,'C');
		$pdf->Cell(1.5,0.60,'Nilai','LTBR',0,'C');
		$pdf->Cell(2.75,0.60,'Keterangan','LTBR',0,'C');
		$pdf->Ln();
		$qisi=$conn->query("SELECT ps.nmsiswa, ps.nmpeserta,rb.idrombel, rb.nmrombel, bs.idbank, COUNT(*) as semua, SUM(jw.skor) as benar FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa)INNER JOIN tbrombel rb USING(idrombel) LEFT JOIN tbjawaban jw USING(idsiswa) INNER JOIN tbsoal so USING(idbutir) INNER JOIN tbbanksoal bs USING(idbank) WHERE bs.idbank='$_GET[id]' AND rb.idrombel='$s[idrombel]' GROUP BY jw.idsiswa, bs.idbank ORDER BY ps.nmsiswa");
		$i=0;
		$pdf->SetFont('Times','','11');
		while($d=$qisi->fetch_array())
		{
			$cell[$i][0]=$i;
			$cell[$i][1]=substr($d['nmpeserta'],3,2).'-'.substr($d['nmpeserta'],5,4).'-'.substr($d['nmpeserta'],9,4).'-'.substr($d['nmpeserta'],-1); 
			$cell[$i][2]=ucwords(strtolower($d['nmsiswa']));
			$cell[$i][3]=number_format($d['benar'],2,',','.');
			$cell[$i][4]=number_format($d['semua']-$d['benar'],2,',','.');
			$nilai=($d['benar']/$d['semua'])*100;
			$cell[$i][5]=number_format($nilai,2,',','.');
			if($nilai>=strval($s['kkm']) || $nilai==100)
			{
				$ket='Tuntas';
			} else 
			{
				$ket='Tidak Tuntas';
			}
			$cell[$i][6]=$ket;
			$i++;
		}
		for($j=0;$j<$i;$j++)
		{
			$joz=$j+1;
			$pdf->SetFont('Times','','11');			
			$pdf->Cell(1,0.60," $joz.",'LB',0,'C');
			$pdf->Cell(3.25,0.60,$cell[$j][1],'LB',0,'C');
			$pdf->Cell(7.375,0.60,$cell[$j][2],'LB',0,'L');
			$pdf->Cell(1.5,0.60,$cell[$j][3],'LB',0,'C');
			$pdf->Cell(1.5,0.60,$cell[$j][4],'LB',0,'C');
			$nilai=number_format(($cell[$j][5]),2,'.',',');
			if(strval($nilai)>=strval($s['kkm']) || $nilai==100)
			{
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Times','BI','11');
			} else 
			{
				$pdf->SetTextColor(255,10,10);
				$pdf->SetFont('Times','BI','11');
			}
			$pdf->Cell(1.5,0.60,$cell[$j][5],'LB',0,'C');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Times','','11');
			$pdf->Cell(2.75,0.60,$cell[$j][6],'LBR',0);	
			$pdf->Ln(); 
		}
		$qsk = $conn->query("SELECT*FROM tbskul");
		$sk = $qsk->fetch_array();
		$pdf->Ln(0.2);
		$pdf->Cell(10.0,0.5);
		$pdf->Cell(10.0,0.5, 'Kabupaten '.$sk['kab'].', '.indonesian_date(date('Y-m-d')),'',0,'C');
		$pdf->Ln();
		$pdf->Cell(10.0,0.5);
		$pdf->Cell(10.0,0.5, 'Administrator,','',0,'C');
		$qad = $conn->query("SELECT nama, nip FROM tbuser WHERE level='1'");
		$ad = $qad->fetch_array();
		$pdf->Ln(1.5);
		$pdf->Cell(10.0,0.5);
		$pdf->Cell(10.0,0.5, $ad['nama'],'',0,'C');	
	}
	$pdf->Output();
?>