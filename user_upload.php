<?php
	define("BASEPATH", dirname(__FILE__));
	if(!isset($_COOKIE['c_user'])){header("Location: login.php");}
	require_once "../assets/library/PHPExcel.php";
	require_once "../assets/library/excel_reader.php";
	include "../config/konfigurasi.php";
	include "user_getid.php";
	if(empty($_FILES['tmpuser']['tmp_name'])){ 
?>
	<script type="text/javascript">
		$(function() {
			const Toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 2000
			});
			toastr.error("File Kosong Bro");
		})
	</script>	
<?php } else {
	$data = new Spreadsheet_Excel_Reader($_FILES['tmpuser']['tmp_name']);	
	$baris = $data->rowcount($sheet_index=0);
	$tgl=date('Y-m-d');
	$isidata=$baris-5;
	$sukses = 0;
	$gagal = 0;
	$update=0;
	$no=0;
	for ($i=6; $i<=$baris; $i++)
	{
		$no++;
		$xuser=getuserid();	
		$xnama= addslashes($data->val($i,2));
		$xtmplhr = $data->val($i,3); 
		$xtgllhr = $data->val($i,4); 
		$xjekel = $data->val($i,5); 
		$nmagama = $data->val($i,6);
		$xalmt = $data->val($i,7);
        $pass=str_replace("-","",$xtgllhr);
		if(strlen($nmagama)==1){$xagama=$nmagama;}
		else {
			switch ($nmagama) {
			case 'Islam':{ $xagama='A';break;}
			case 'Kristen':{ $xagama='B';break;}
			case 'Katholik':{ $xagama='C';break;}
			case 'Hindu':{ $xagama='D';break;}
			case 'Buddha':{ $xagama='E';break;}
			case 'Konghucu':{ $xagama='F';break;}
			default: {$xagama='';break;}
			}
		}		
		if($xuser==''){
			$pesan='Cek Baris Ke '.$no.'Kolom Username mungkin kosong!';
			$jns='error';
			$gagal++;
		}
		else if(strlen($xnama)<1 || $xnama==''){
			$pesan='Cek Baris Ke '.$no.' Kolom Nama Lengkap, kosong atau tidak wajar!';
			$jns='error';
			$gagal++;
		}
		else if(strlen($xtmplhr)<1 || $xtmplhr==''){
			$pesan='Cek Baris Ke '.$no.' Kolom Tempat Lahir, kosong atau tidak wajar!';
			$jns='error';
			$gagal++;
		}
		else if(strlen($xtgllhr)<1 || $xtgllhr==''){
			$pesan='Cek Baris Ke '.$no. 'Kolom Tanggal Lahir, kosong atau tidak wajar!';
			$jns='error';
			$gagal++;
		}
		else if(strlen($xjekel)>1 || $xjekel==''){
			$pesan='Cek Baris Ke '.$no.' Kolom Gender, kosong atau tidak wajar!';
			$jns='error';
			$gagal++;
		}
		else if($xagama==''){
			$pesan='Cek Baris Ke '.$no. 'Kolom Agama kosong!';
			$jns='error';
			$gagal++;
		}
		else {
			$qpd=$conn->query("SELECT*FROM tbuser WHERE username='$xuser'");
			$ceksiswa=$qpd->num_rows;
			if($ceksiswa>0){
				$query=$conn->query("UPDATE tbuser SET nama='$xnama', tmplahir='$xtmplhr',tgllahir='$xtgllhr', gender='$xjekel', agama='$xagama', alamat='$xalmt' WHERE username='$xuser' AND idskul='$_COOKIE[c_skul]'");
				$pesan='Update Data Sukses!';
				$jns='success';
				$update++;
			} 
			else {
				$query=$conn->query("INSERT INTO tbuser (idskul, username, nama, tmplahir, tgllahir, gender, agama, alamat, aktif, level, passwd) VALUES('$_COOKIE[c_skul]','$xuser', '$xnama','$xtmplhr', '$xtgllhr', '$xjekel', '$xagama', '$xalmt','1','2', PASSWORD('$pass'))");
				$pesan='Simpan Data Sukses!';
				$jns='success';
				$sukses++;
			}
	}
?>
<script type="text/javascript">
	$(function() {
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000
		});
		toastr.<?php echo $jns;?>("<?php echo $pesan;?>");
		return false();
	})
</script>
<?php }flush();?>
<script type="text/javascript">
	$(function() {
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 2000
		});
		toastr.info("Ada <?php echo $gagal;?> gagal ditambahkan, <?php echo $update;?> data berhasil diupdate dan <?php echo $sukses;?> sukses ditambahkan");
		return false();
	})
</script>
<?php } ?>