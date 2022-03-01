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
		function Header()
		{
			global $conn;	
			$qsk = $conn->query("SELECT*FROM tbskul");
			$ad = $qsk->fetch_array();
			$namsek = strtoupper($ad['nmskul']);
			$logsek = $ad['logoskul'];
			$quji = $conn->query("SELECT u.idthpel, u.idujian, t.nmtes, th.nmthpel, th.desthpel FROM tbujian u INNER JOIN tbtes t USING(idtes) INNER JOIN tbthpel th USING(idthpel) WHERE u.status='1'");
			$uji = $quji->fetch_array();
            $nmuji = strtoupper($uji['nmtes']);
			$thpel=substr($uji['desthpel'],0,9);
			if ($logsek=='') {$logo='images/tutwuri.jpg';} 
			else {
				if(file_exists('../images/'.$logsek)){
					$logo ='../images/'.$logsek; 
				}
				else {
				    $logo='images/tutwuri.jpg';  
				}
			}
			$this->Image($logo,1.0,0.65,1.35);			
			$this->SetTextColor(0,0,0);
			$this->SetFont('Times','B','12');
			$this->Cell(1.75,0.5,'');
			$this->Cell(29.25,0.5,'REKAPITULASI HASIL UJIAN',0,0,'C',0);
			$this->Ln();
			$this->SetFont('Times','B','11');
			$this->Cell(1.75,0.5,'');
			$this->Cell(29.25,0.5,strtoupper($nmuji),0,0,'C',0);
			$this->Ln();
			$this->Cell(1.75,0.5,'');
			$this->Cell(29.25,0.5,strtoupper('Tahun Pelajaran ').$thpel,0,0,'C',0);	
			$this->SetLineWidth(0.05);
			$this->Line(1.0,2.5,32.0,2.5);
			$this->SetY(3.75); 			
			$this->SetLineWidth(0.015);
            $this->SetFont('Times','B','11');
            $this->Cell(0.75,0.75,'No.','LTB',0,'C');
            $this->Cell(3.25,0.75,'Nomor Peserta','LTB',0,'C');
            $this->Cell(7.25,0.75,'Nama Peserta','LTB',0,'C');
            $qmp=$conn->query("SELECT*FROM tbmapel");
            $i=0;
            while($mp=$qmp->fetch_array()){
                $i++;
                $this->Cell(1.35,0.75,$mp['akmapel'],'LTB',0,'C');
			}
			$this->Cell(1.75,0.75,'Jumlah','LTB',0,'C');
			$this->Cell(1.75,0.75,'Rerata','LTB',0,'C');
			$this->Cell(2.5,0.75,'Keterangan','LTBR',0,'C');
			$this->Ln();
		}
        function Judul($id)
        {
			global $conn; 
			$qrm=$conn->query("SELECT nmrombel FROM tbrombel WHERE idrombel='$id'");
            $rm=$qrm->fetch_array();
			$this->SetFont('Times','','11');
			$this->SetY(3.0);
            $this->Cell(3.25,0.5,'Kelas','',0,'L');
            $this->Cell(0.25,0.5,':',0,0,'C');
            $this->Cell(6,0.5,$rm['nmrombel'],'',0,'L');
            $this->Cell(14.5,0.5);
            $this->Cell(3.0,0.5,'Semester','',0,'L');
			$this->Cell(0.25,0.5,':',0,0,'C');
			$quji = $conn->query("SELECT u.idthpel, u.idujian, t.nmtes, th.nmthpel, th.desthpel FROM tbujian u INNER JOIN tbtes t USING(idtes) INNER JOIN tbthpel th USING(idthpel) WHERE u.status='1'");
			$uji = $quji->fetch_array();
            $nmuji = strtoupper($uji['nmtes']);
            $sem=substr($uji['nmthpel'],-1);
			if($sem=='1'){$semester='I (Satu)';} else {$semester='II (Genap)';}
			$thpel=substr($uji['desthpel'],0,9);
            $this->Cell(3.25,0.5,$semester,0,0,'L');		
			$this->Ln(0.75); 			          
		}

		function Footer(){
			global $conn;
			$sqlad0=$conn->query( "SELECT a.idjenjang, j.akjenjang, a.nmskpd, a.kec, r.nmrayon, p.nmprov FROM tbskul a INNER JOIN tbjenjang j ON j.idjenjang=a.idjenjang INNER JOIN tbrayon r ON a.idrayon=r.idrayon INNER JOIN tbprov p ON r.idprov=p.idprov");
			$dt=mysqli_fetch_array($sqlad0);
			$nmjenjang=$dt['akjenjang'];
			if($nmjenjang=='SMA' || $nmjenjang=='SMK'){
			    $nmtmp=strtoupper($dt['nmskpd'].' provinsi '.$dt['nmprov']); 
			}
			elseif($nmjenjang=='SMP'){
			    $nmtmp=strtoupper($dt['nmskpd'].' '.$dt['nmrayon']);
			}
			else
			{
			$nmtmp=strtoupper($dt['nmskpd'].' kecamatan '.$dt['kec']);
			}
			$this->SetFont('Times','','11');
			$this->SetY(-1.65,5);
			$this->Rect(1,19.86,0.65,0.65);
			$this->Rect(1.75,19.86,29.375,0.65);
			$this->Rect(31.25,19.86,0.65,0.65); 
			$this->Cell(30.125,0.65,$nmtmp,0,0,'C');
			$this->Cell(0.65,0.65,$this->PageNo(),0,0,'C');
        }

        function IsiData($id){
            global $conn;        
			$qisi=$conn->query("SELECT s.idsiswa, s.nmsiswa, s.nmpeserta, s.passwd, r.nmrombel, u.nmujian, u.idujian FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbujian u USING(idujian) INNER JOIN tbthpel t ON t.idthpel=r.idthpel WHERE u.status='1' AND r.idrombel='$id' AND t.aktif='1' GROUP BY s.idsiswa ORDER BY s.nmsiswa");
			$i=0;
            while($d=$qisi->fetch_array())
            {
                $cell[$i][0]=$i;
				$cell[$i][1]=substr($d['nmpeserta'],3,2).'-'.substr($d['nmpeserta'],5,4).'-'.substr($d['nmpeserta'],9,4).'-'.substr($d['nmpeserta'],-1); 
                $cell[$i][2]=ucwords(strtolower($d['nmsiswa']));	
                $qmp=$conn->query("SELECT*FROM tbmapel");
                $j=4;
                while($mp=$qmp->fetch_array()){
                    $j++;
                    $qnilai=$conn->query("SELECT nilai FROM tbnilai n INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) WHERE n.idsiswa='$d[idsiswa]' AND bs.idmapel='$mp[idmapel]' AND bs.idujian='$d[idujian]'");
					$nil=$qnilai->fetch_array();
                    if($nil['nilai']==0){
                        $cell[$i][$j]= '';
                    }
                    else {
                        $cell[$i][$j]= number_format($nil['nilai'],2,',','.');
                    }
				}
				$qstnil=$conn->query("SELECT SUM(nilai) as jml, AVG(nilai) as rata FROM tbnilai n INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbujian u ON u.idujian=n.idujian AND u.idujian=bs.idujian WHERE n.idsiswa='$d[idsiswa]' AND u.status='1' GROUP BY n.idsiswa, bs.idujian");
				$snil=$qstnil->fetch_array();
				if($snil['jml']==0){ 
					$cell[$i][$j+1]='';
				} else {
					$cell[$i][$j+1] = number_format($snil['jml'],1,',','.');
				}
				if($snil['rata']==0){ 
					$cell[$i][$j+2]='';
				} else {
					$cell[$i][$j+2] = number_format($snil['rata'],2,',','.');
				}
				$i++;				
			}
			$this->SetY(4.5);
			for($k=0;$k<$i;$k++)
			{
				$joz=$k+1;
				$this->SetFont('Times','','11');			
				$this->Cell(0.75,0.65," $joz.",'LB',0,'C'); //No
				$this->Cell(3.25,0.65,$cell[$k][1],'LB',0,'C');   //Username
				$this->Cell(7.25,0.65,$cell[$k][2],'LBR',0,'L');
				$qmp=$conn->query("SELECT*FROM tbmapel");
                $l=4;
                while($mp=$qmp->fetch_array()){
					$l++;
					//$qkkm=$conn->query("SELECT kkm FROM tbkkm INNER JOIN ")
					$this->Cell(1.35,0.65,$cell[$k][$l],'BR',0,'C');
				}
				$this->Cell(1.75,0.65,$cell[$k][$l+1],'LBR',0,'C');
				$this->Cell(1.75,0.65,$cell[$k][$l+2],'LBR',0,'C');
				$this->Cell(2.5,0.65,'','LBR',0,'C');				
				$this->Ln(); 
			}

        } 

        function Cetak($id){
            $this->AddPage();
            $this->Judul($id);
            $this->IsiData($id);
        }
	}
	$pdf = new PDF('L','cm',array(21.5,33.0));
	$pdf->AliasNbPages();
	$pdf->SetMargins(1.15,0.75,0.75);
	$pdf->SetAutoPageBreak('true',3.75);
	$us=getuser();
	$level=$us['level'];
	if($level=='1'){
		$sql = $conn->query("SELECT r.idrombel, r.nmrombel FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbujian u USING(idujian) INNER JOIN tbthpel t ON t.idthpel=r.idthpel WHERE u.status='1' AND t.aktif='1' GROUP BY r.idrombel");
	}
	else{
		$sql = $conn->query("SELECT r.idrombel, r.nmrombel FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbujian u USING(idujian) INNER JOIN tbthpel t ON t.idthpel=r.idthpel WHERE u.status='1' AND t.aktif='1' AND r.username='$_COOKIE[id]' GROUP BY r.idrombel");
	}
	while ($s=$sql->fetch_array())
	{
		$pdf->Cetak($s['idrombel']);
    }
	$pdf->Output();
?>