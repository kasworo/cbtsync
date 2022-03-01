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
		}
		$pdf = new PDF('P','cm','A4');
		$pdf->AliasNbPages();
		$pdf->SetMargins(0.75,0.75,0.75);

		$qsk = $conn->query("SELECT*FROM tbskul");
		$ad = $qsk->fetch_array();
		$namsek = strtoupper($ad['nmskul']);
		$logsek = $ad['logoskul'];

		$qth=$conn->query("SELECT*FROM tbthpel WHERE aktif='1'");
		$setid=$qth->fetch_array();
		$sem=substr($setid['idthpel'],4);
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
		$du=getuser();	
		if($du['level']=='1'){
			$sql = $conn->query("SELECT ps.nmsiswa, ps.nis, ps.nisn, ps.tgllahir, ps.tmplahir, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian, r1.kdruang FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel)  INNER JOIN tbujian u USING(idujian) INNER JOIN tbruang r1 USING(idruang) INNER JOIN tbthpel tp ON tp.idthpel=r.idthpel WHERE u.status='1' AND tp.aktif='1' GROUP BY ps.idsiswa ORDER BY ps.nmpeserta ASC");
		}
		else{
			$sql = $conn->query("SELECT ps.nmsiswa, ps.nis, ps.nisn, ps.tgllahir, ps.tmplahir, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian, r1.kdruang FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel)  INNER JOIN tbujian u USING(idujian) INNER JOIN tbruang r1 USING(idruang) INNER JOIN tbthpel tp ON tp.idthpel=r.idthpel WHERE u.status='1' AND tp.aktif='1' AND r.username='$_COOKIE[id]'GROUP BY ps.idsiswa ORDER BY ps.nmpeserta ASC");
		}
		$ceksiswa=$sql->num_rows;
		$perhal=10;
		$hal=ceil($ceksiswa/$perhal);
		$i=0;
		while($d=$sql->fetch_array())
		{	
			$i++;
			if(strlen($d['nmsiswa'])>30){
				$nmsiswa=substr($d['nmsiswa'],0,29).'.';				
			}
			else
			{
				$nmsiswa=$d['nmsiswa'];
			}
			$cell[$i][0]=$d['nmpeserta'].' / '.$d['passwd'];
			$cell[$i][1]=$nmsiswa;
			$cell[$i][2]=$d['nis'].' / '.$d['nisn'];
			$cell[$i][3]=ucwords(strtolower($d['tmplahir'])).', '.indonesian_date($d['tgllahir']);
			$cell[$i][4]=$d['nmrombel'].' / '.$semester;
			$cell[$i][5]=$d['kdruang'];			
		}
		
		for($j=1;$j<=$hal;$j++){
			$pdf->AddPage();
			for($k=1;$k<=$perhal/2;$k++){
				$pdf->Image($logo,1.0,($k-1)*5.1+0.95,1.0);
				$pdf->SetFont('Times','B','10');
				$pdf->Cell(1.0,0.55,'','LT',0,'C');
				$pdf->Cell(7.0,0.55,'KARTU TANDA PESERTA','TR',0,'C');
				$pdf->SetFont('Times','','10');
				$pdf->Cell(1.5,0.55,'Ruang','TR',0,'C');				
				$pdf->Cell(0.5,0.55);
				$pdf->Image($logo,11.0,($k-1)*5.1+0.95,1.0);
				$pdf->SetFont('Times','B','10');
				$pdf->Cell(1.0,0.55,'','LT',0,'C');
				$pdf->Cell(7.0,0.55,'KARTU TANDA PESERTA','TR',0,'C');
				$pdf->SetFont('Times','','10');
				$pdf->Cell(1.5,0.55,'Ruang','TR',0,'C');
				$pdf->Ln();
				$pdf->SetFont('Times','B','10');
				$pdf->Cell(1.0,0.55,'','L',0,'C');
				$pdf->Cell(7.0,0.55,strtoupper($nmuji),'R',0,'C');
				$pdf->SetFont('Times','B','18');
				$pdf->Cell(1.5,1.1,$cell[($j-1)*$perhal+$k][5],'BR',0,'C');
				$pdf->Cell(0.5,0.55);
				$pdf->SetFont('Times','B','10');
				$pdf->Cell(1.0,0.55,'','L',0,'C');
				$pdf->Cell(7.0,0.55,strtoupper($nmuji),'R',0,'C');
				$pdf->SetFont('Times','B','18');
				$pdf->Cell(1.5,1.1,$cell[($j-1)*$perhal+$k+$perhal/2][5],'BR',0,'C');
				$pdf->Cell(2.0,0.55);
				$pdf->Ln();
				$pdf->SetFont('Times','B','10');
				$pdf->Cell(1.0,0.55,'','LB',0,'C');
				$pdf->Cell(7.0,0.55,'TAHUN PELAJARAN '.$thpel,'BR',0,'C');
				$pdf->Cell(1.5,0.55);
				$pdf->Cell(0.5,0.55);
				$pdf->Cell(1.0,0.55,'','LB',0,'C');
				$pdf->Cell(7.0,0.55,'TAHUN PELAJARAN '.$thpel,'BR',0,'C');
				$pdf->Cell(1.5,0.55);
				$pdf->Ln(0.60);
				$pdf->SetFont('Times','','10');
				$pdf->Cell(0.25,0.60,'','LT');
				$pdf->Cell(3.5,0.60,'Username / Password','T');
				$pdf->Cell(0.25,0.60,':','T');
				$pdf->Cell(5.5,0.60,$cell[($j-1)*$perhal+$k][0],'TR');
				$pdf->Cell(0.5,0.60);
				$pdf->Cell(0.25,0.60,'','LT');
				$pdf->Cell(3.50,0.60,'Username / Password', 'T');
				$pdf->Cell(0.25,0.60,':','T');
				$pdf->Cell(5.5,0.60,$cell[($j-1)*$perhal+$k+$perhal/2][0],'TR');
				$pdf->Ln(); 
				$pdf->Cell(0.25,0.60,'','L');
				$pdf->Cell(3.5,0.60,'Nama Peserta');
				$pdf->Cell(0.25,0.60,':');
				$pdf->Cell(5.5,0.60,$cell[($j-1)*$perhal+$k][1],'R');
				$pdf->Cell(0.5,0.60);
				$pdf->Cell(0.25,0.60,'','L');
				$pdf->Cell(3.5,0.60,'Nama Peserta');
				$pdf->Cell(0.25,0.60,':');
				$pdf->Cell(5.5,0.60,$cell[($j-1)*$perhal+$k+$perhal/2][1],'R');
				$pdf->Ln();
				$pdf->Cell(0.25,0.60,'','L');
				$pdf->Cell(3.5,0.60,'Nomor Induk');
				$pdf->Cell(0.25,0.60,':');
				$pdf->Cell(5.5,0.60,$cell[($j-1)*$perhal+$k][2],'R');
				$pdf->Cell(0.5,0.60);
				$pdf->Cell(0.25,0.60,'','L');
				$pdf->Cell(3.5,0.60,'Nomor Induk');
				$pdf->Cell(0.25,0.60,':');
				$pdf->Cell(5.5,0.60,$cell[($j-1)*$perhal+$k+$perhal/2][2],'R');
				$pdf->Ln();
				$pdf->Cell(0.25,0.60,'','L');
				$pdf->Cell(3.5,0.60,'Tempat / Tgl. Lahir');
				$pdf->Cell(0.25,0.60,':');
				$pdf->Cell(5.5,0.60,$cell[($j-1)*$perhal+$k][3],'R');
				$pdf->Cell(0.5,0.60);
				$pdf->Cell(0.25,0.60,'','L');
				$pdf->Cell(3.5,0.60,'Tempat / Tgl. Lahir');
				$pdf->Cell(0.25,0.60,':');
				$pdf->Cell(5.5,0.60,$cell[($j-1)*$perhal+$k+$perhal/2][3],'R');
				$pdf->Ln();
				$pdf->Cell(0.25,0.60,'','LB');
				$pdf->Cell(3.5,0.60,'Kelas / Semester', 'B');
				$pdf->Cell(0.25,0.60,':','B');
				$pdf->Cell(5.5,0.60,$cell[($j-1)*$perhal+$k][4],'BR');
				$pdf->Cell(0.5,0.60);
				$pdf->Cell(0.25,0.60,'','LB');
				$pdf->Cell(3.5,0.60,'Kelas / Semester', 'B');
				$pdf->Cell(0.25,0.60,':','B');
				$pdf->Cell(5.5,0.60,$cell[($j-1)*$perhal+$k+$perhal/2][4],'BR');
				
				$pdf->Ln(1.0);
			}
		}
 	$pdf->Output();
?>
