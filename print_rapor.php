<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	include "../config/konfigurasi.php";
	include "../config/fungsi_tgl.php";
	require_once("../assets/library/phpqrcode/qrlib.php");
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
		else{
			$nmtmp=strtoupper($dt['nmskpd'].' kecamatan '.$dt['kec']);
		}
		$this->SetFont('Times','','10');
		$this->SetY(-1.675,5);
		$this->Rect(1,28.0,0.65,0.75);
		$this->Rect(2,28.0,17.0,0.75);
		$this->Rect(19.25,28.0,0.65,0.75); 
		$this->Cell(19,0.65,$nmtmp,0,0,'C');
		$this->Cell(0.65,0.65,'',0,0,'C');
		} 
	}
	$pdf = new PDF('P','cm','A4');
	$pdf->SetMargins(1,1.25,1);
	$pdf->SetAutoPageBreak('true',2.5);
	$pdf->AliasNbPages();
    $qsk = $conn->query("SELECT*FROM tbskul");
	$ad = $qsk->fetch_array();
	$namsek = strtoupper($ad['nmskul']);
    $logsek = $ad['logoskul'];
    $alsek=$ad['alamat'];
	$dssek=$ad['desa'];

	$quji = $conn->query("SELECT idujian, nmujian, nmtes, nmthpel, desthpel FROM tbujian u INNER JOIN tbtes t USING(idtes) INNER JOIN tbthpel th USING(idthpel) WHERE status='1'");
	$uji = $quji->fetch_array();
	$nmuji = strtoupper($uji['nmtes']);
	$kduji=$uji['nmujian'];
	$sem=substr($uji['nmthpel'],-1);
	if($sem=='1'){$semester='I (Satu)';} else {$semester='II (Genap)';}
	$thpel=substr($uji['desthpel'],0,9);
	$u=getuser();
	$level=$u['level'];
	if(isset($_GET['id'])){
		$sql="SELECT s.idsiswa, s.nis, s.nisn, s.nmsiswa, s.nmpeserta, SUM(n.nilai) as jml, AVG(n.nilai) as rata, rb.idkelas, rb.nmrombel, us.nama, us.nip FROM tbnilai n INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbpeserta s USING(idsiswa) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbuser us ON rb.username=us.username INNER JOIN tbujian u ON s.idujian=u.idujian AND u.idujian=n.idujian INNER JOIN tbthpel t ON t.idthpel=rb.idthpel AND t.idthpel=u.idthpel WHERE t.aktif='1' AND u.status='1' AND s.nmpeserta='$_GET[id]' GROUP BY n.idsiswa, n.idujian";
	}
	else{
		if($level=='1'){
			$sql="SELECT s.idsiswa, s.nis, s.nisn, s.nmsiswa, s.nmpeserta, SUM(n.nilai) as jml, AVG(n.nilai) as rata, rb.idkelas, rb.nmrombel, us.nama, us.nip FROM tbnilai n INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbpeserta s USING(idsiswa) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbuser us ON rb.username=us.username INNER JOIN tbujian u ON s.idujian=u.idujian AND u.idujian=n.idujian INNER JOIN tbthpel t ON t.idthpel=rb.idthpel AND t.idthpel=u.idthpel WHERE t.aktif='1' AND u.status='1' GROUP BY n.idsiswa ORDER BY rs.idrombel, s.idsiswa";
		}
		else {
			$sql="SELECT s.idsiswa, s.nis, s.nisn, s.nmsiswa, s.nmpeserta, SUM(n.nilai) as jml, AVG(n.nilai) as rata, rb.idkelas, rb.nmrombel, us.nama, us.nip FROM tbnilai n INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbpeserta s USING(idsiswa) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbuser us ON rb.username=us.username INNER JOIN tbujian u ON s.idujian=u.idujian INNER JOIN tbthpel t ON t.idthpel=rb.idthpel AND t.idthpel=u.idthpel WHERE t.aktif='1' AND rb.username='$_COOKIE[c_user]' AND u.status='1' GROUP BY n.idsiswa ORDER BY rs.idrombel, s.idsiswa";
		}
	}
	$qsiswa=$conn->query($sql);
	while($row = $qsiswa->fetch_array()){
		$pdf->AddPage();
		$pdf->SetFont('Times','','11');
		$pdf->Cell(3.25,0.5,'Nama Sekolah',0,0,'L');
		$pdf->Cell(0.25,0.5,':',0,0,'L');
		$pdf->Cell(9.25,0.5,$namsek,0,0,'L');
		$pdf->Cell(1,0.5,'',0,0,'L');
		$pdf->Cell(2.75,0.5,'Kelas',0,0,'L');
		$pdf->Cell(0.25,0.5,':',0,0,'L');
		$pdf->Cell(5,0.5,str_replace('Kelas ','', $row['nmrombel']),0,0,'L');
		$pdf->Ln(0.5);
		$pdf->Cell(3.25,0.5,'Alamat Sekolah',0,0,'L');
		$pdf->Cell(0.25,0.5,':',0,0,'L');
		$pdf->Cell(9.25,0.5,$alsek.', '.$dssek,0,0,'L');
		$pdf->Cell(1,0.5,'',0,0,'L');
		$pdf->Cell(2.75,0.5,'Semester',0,0,'L');
		$pdf->Cell(0.25,0.5,':',0,0,'L');
		$pdf->Cell(5,0.5,$semester,0,0,'L');
		$pdf->Ln(0.5);
		$pdf->Cell(3.25,0.5,'Nama Peserta Didik',0,0,'L');
		$pdf->Cell(0.25,0.5,':',0,0,'L');
		$pdf->Cell(9.25,0.5,strtoupper($row['nmsiswa']),0,0,'L');
		$pdf->Cell(1,0.5,'',0,0,'L');
		$pdf->Cell(2.75,0.5,'Tahun Pelajaran',0,0,'L');
		$pdf->Cell(0.25,0.5,':',0,0,'L');
		$pdf->Cell(5,0.5,$thpel,0,0,'L');
		$pdf->Ln(0.5);
		$pdf->Cell(3.25,0.5,'No. Induk / N I S N',0,0,'L');
		$pdf->Cell(0.25,0.5,':',0,0,'L');
		$pdf->Cell(9.25,0.5,$row['nis'].' / '.$row['nisn'],0,0,'L');
		$pdf->Ln(0.5);
		$pdf->SetLineWidth(0.035);
		$pdf->Line(1,3.5,20,3.5);
		$pdf->Ln();
		$pdf->SetFont('Times','B','12');
		$pdf->Cell(19.0,0.6,'LAPORAN HASIL '.strtoupper($nmuji),0,0,'C');
		$pdf->Ln(1.0);
		$pdf->SetFont('Times','B','11');
		$pdf->SetLineWidth(0.025);
		$pdf->Cell(1,1.2,'No.','LTB',0,'C');
		$pdf->Cell(8.5,1.2,'Mata Pelajaran','LTB',0,'C');
		$pdf->Cell(1.5,1.2,'KKM','LTB',0,'C');
		$pdf->Cell(3.3,0.6,'Nilai','LTB',0,'C');
		$pdf->Cell(4.7,1.2,'Keterangan','LTBR',0,'C');
		$pdf->Ln(0.6);
		$pdf->Cell(11.0,0.6);
		$pdf->Cell(1.5,0.6,'Angka','LB',0,'C');
		$pdf->Cell(1.8,0.6,'Predikat','LB',0,'C');
		$pdf->Ln();
		$pdf->SetFont('Times','','11');
		$pdf->Image('../assets/img/tandaair.png',5.75,7.675,10.765);
// 		$qmapel=$conn->query("SELECT mp.nmmapel, kkm.kkm, bs.idbank, COUNT(*) as semua, SUM(jw.skor) as benar FROM tbpeserta ps INNER JOIN tbjawaban jw USING(idsiswa) INNER JOIN tbsoal so USING(idbutir) INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbkkm kkm USING(idmapel, idkelas) INNER JOIN tbujian u ON ps.idujian=u.idujian AND bs.idujian=u.idujian WHERE ps.idsiswa='$row[idsiswa]' GROUP BY jw.idsiswa, bs.idbank ORDER BY mp.idmapel");

	$qmapel=$conn->query("SELECT mp.nmmapel, kkm.kkm, bs.idbank, n.nilai FROM tbpeserta ps INNER JOIN tbnilai n using(idsiswa) INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbkkm kkm USING(idmapel, idkelas) INNER JOIN tbujian u ON ps.idujian=u.idujian AND bs.idujian=u.idujian WHERE ps.idsiswa='$row[idsiswa]' GROUP BY bs.idbank ORDER BY mp.idmapel");
		$i=0;
		while($m=$qmapel->fetch_array())
		{	
			$cell[$i][1]=$m['nmmapel'];
    		//$nilai=$m['benar']/$m['semua']*100;
    		$nilai=$m['nilai'];
			$cell[$i][2]=$m['kkm'];
			$cell[$i][3]=$nilai;
			$rtg =floor((100-$m['kkm'])/3);
			$bb=100-2*$rtg;
			$bt=100-$rtg;
			if($nilai>$m['kkm']){$pred='Terlampaui';}
			else if($nilai==$m['kkm']){$pred='Tuntas';}
			else {$pred='Tidak Tuntas';}
			if ($nilai>=$bt){
				$cell[$i][4]='A';
				$cell[$i][5]='Amat Baik, '.$pred;	
			}
			else if($nilai>=$bb){
				$cell[$i][4]='B';
				$cell[$i][5]='Baik, '.$pred;;
			}
			else if($nilai>=$m['kkm']){
				$cell[$i][4]='C';
				$cell[$i][5]='Cukup, '.$pred;
			}
			else {
				$cell[$i][4]='D';
				$cell[$i][5]='Kurang, '.$pred;
			}
			$i++;
		}
		$jml=0;
		$rata=0;
		for($j=0;$j<$i;$j++)
		{
			$joz=$j+1;
		
			$pdf->Cell(1,0.6, " $joz.",'LB',0,'C');
			$pdf->Cell(8.5,0.6,' '.$cell[$j][1],'LB',0,'L');	  
			$pdf->Cell(1.5,0.6,$cell[$j][2],'LB',0,'C');
			$cek=$cell[$j][2];
			$cek0=$cell[$j][3];			
			if($cek0>=$cek){
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Times','BI','11');
			} 
			else {
			$pdf->SetTextColor(255,10,10);
				$pdf->SetFont('Times','BI','11');
			}
			$jml=$jml+$cek0;
			$pdf->Cell(1.5,0.6,number_format($cek0,2,',','.'),'LB',0,'C');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Times','','11');
			$pdf->Cell(1.8,0.6,$cell[$j][4],'LB',0,'C');
			$pdf->Cell(4.7,0.6,' '.$cell[$j][5],'LBR',0,'L');
			$pdf->Ln();				
			
		}
		$rata=$jml/$joz;
		$pdf->Cell(11,0.6, 'Jumlah Nilai','LB',0,'C');
		$pdf->Cell(1.5,0.6, number_format($jml,2,',','.'),'LB',0,'C');
		$pdf->Cell(6.5,0.6, '','LBR',0,'C');
		$pdf->Ln();
		$pdf->Cell(11,0.6, 'Rata-rata Nilai','LB',0,'C');
		$pdf->Cell(1.5,0.6, number_format($rata,2,',','.'),'LB',0,'C');
		$pdf->Cell(6.5,0.6, '','LBR',0,'C');
		$pdf->Ln(1.0);
		$pdf->SetFont('Times','B','11');
		$pdf->Cell(19.0,0.6,"CATATAN WALI KELAS",'LBTR',0,'C');
		$pdf->Ln();
		$pdf->SetFont('Times','','11');
		$pdf->MultiCell(19.0,0.6,' Laporan hasil tes ini merupakan nilai murni pada '.ucwords(strtolower($nmuji)),'LR','L');
		$pdf->MultiCell(19.0,0.6,' Mohon kerjasama Bapak/Ibu untuk memberikan dorongan kepada peserta didik agar lebih giat dalam belajar','LBR','L');
		$pdf->Ln(0.25);
		$pdf->SetFont('Times','B','11');
		$pdf->Cell(19.0,0.6,"TANGGAPAN ORANG TUA / WALI PESERTA DIDIK",'LBTR',0,'C');
		$pdf->Ln();
		$pdf->Cell(19.0,1.5,'','LBR',0,'C');
		$pdf->Ln(1.75);
		$pdf->SetFont('Times','','11');
		$pdf->Cell(12.0,0.6);
		$pdf->Cell(2.5,0.6,'Diberikan di');
		$pdf->Cell(0.5,0.6,':');
		$pdf->Cell(4.0,0.6,$dssek);
		$pdf->Ln();
		$pdf->Cell(12.0,0.6);
		$pdf->Cell(2.5,0.6,'Tanggal');
		$pdf->Cell(0.5,0.6,':');
		$pdf->Cell(4.0,0.6,indonesian_date(date('Y-m-d')));
		$pdf->Ln(0.5);
		$pdf->Cell(1.0,0.5);
		$pdf->Cell(8.0,0.5,'Mengetahui:',0,0,'C');
		$pdf->Cell(2.0,0.5);
		$pdf->Cell(8.0,0.5);
		$pdf->Ln();
		$pdf->Cell(1.0,0.5);
		$pdf->Cell(8.0,0.5,'Kepala Sekolah,',0,0,'C');
		$pdf->Cell(2.0,0.5);
		$pdf->Cell(8.0,0.5,'Wali Kelas,',0,0,'C');
		$pdf->Ln(1.5);
		$pdf->Cell(1.0,0.5);
		$pdf->Cell(8.0,0.5,'Muhamad, S.Pt',0,0,'C');
		$pdf->Cell(2.0,0.5);
		$pdf->Cell(8.0,0.5,$row['nama'],0,0,'C');
		$pdf->Ln();
		$pdf->Cell(1.0,0.5);
		$pdf->Cell(8.0,0.5,'NIP. 197407302007011003',0,0,'C');
		$pdf->Cell(2.0,0.5);
		if($row['nip']=='Non PNS' || $row['nip']==''){
		$pdf->Cell(8.0,0.5,'',0,0,'C');}
		else{
			$pdf->Cell(8.0,0.5,'NIP. '.$row['nip'],0,0,'C');
		}
		$pdf->Ln(1.0);
		$pdf->Cell(19.0,0.5,'Mengetahui:',0,0,'C');
		$pdf->Ln();
		$pdf->Cell(19.0,0.5,'Orang Tua / Wali,',0,0,'C');
		$pdf->Ln(1.5);
		$pdf->Cell(19.0,0.5,'_________________________',0,0,'C');
		QRcode::png($_SERVER['HTTP_HOST'].'/downloadrapor.php?id='.$row['idsiswa'].'&uji='.$kduji,"../qr_rp/".$row['nisn']."_".$kduji.".png");
		$pdf->Image("../qr_rp/".$row['nisn']."_".$kduji.".png",2.75,25.10,2.5,'png');
	}
	$pdf->Output();
?>