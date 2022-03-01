<?php
	require_once "../assets/library/PHPExcel.php";
	require_once "../assets/library/excel_reader.php";
	include "../config/konfigurasi.php";
	$sqthn=$conn->query("SELECT idthpel FROM tbthpel WHERE aktif='1'");
	$thn=$sqthn->fetch_array();
	$idthpel=$thn['idthpel'];
	if(empty($_FILES['tmprombel']['tmp_name'])){ 
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
	$data = new Spreadsheet_Excel_Reader($_FILES['tmprombel']['tmp_name']);	
	$baris = $data->rowcount($sheet_index=0);
	$tgl=date('Y-m-d');
	$isidata=$baris-4;
	$sukses = 0;
	$gagal = 0;
	$update=0;
	for ($i=5; $i<=$baris; $i++)
	{
		$xnis=strval($data->val($i,2));
		$xnisn=strval($data->val($i,3));
		$xrombel = strval($data->val($i,5));
		if($xnis==''){
			$pesan='Cek Baris Ke '.$no.'Kolom NIS mungkin kosong!';
			$jns='error';
			$gagal++;
		}
		else if(strlen($xnisn)<>10 || $xnisn==''){
			$pesan='Cek Baris Ke '.$no.' Kolom NISN kosong atau digit tidak sesuai!';
			$jns='error';
			$gagal++;
		}
		else if($xrombel==''){
			$pesan='Cek Baris Ke '.$no.' Kolom Rombel, kosong atau tidak wajar!';
			$jns='error';
			$gagal++;
		}
		
		else {
			$qrmb=$conn->query("SELECT*FROM tbrombel WHERE idrombel='$xrombel' AND idthpel='$idthpel'");
			$cekrmb=$qrmb->num_rows;
			if($cekrmb==0){
				$pesan='Kode Rombel Tidak Sesuai!';
				$jns='warning';
				$gagal++; 
			}
			else
			{
				$qpd=$conn->query("SELECT*FROM tbpeserta WHERE nis='$xnis' AND nisn='$xnisn'");
				$ceksiswa=$qpd->num_rows;
				if($ceksiswa==0){
					$pesan='Siswa Belum Terdaftar!';
					$jns='warning';
					$gagal++; 
				}
				else {
					$s=$qpd->fetch_array();
					$qrs=$conn->query("SELECT*FROM tbrombelsiswa WHERE idsiswa='$s[idsiswa]' AND idrombel='$xrombel'");
					$cekrs=$qrs->num_rows;
					if($cekrs==0){
						$query=$conn->query("INSERT INTO tbrombelsiswa (idsiswa, idrombel) VALUES ('$s[idsiswa]','$xrombel')");
						$pesan='Simpan Anggota Rombel Sukses!';
						$jns='success';
						$sukses++;
					}
					else
					{
						$pesan ='Siswa Sudah Masuk Rombel!';
						$jns='error';
						$sukses++;
					}
			}
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
		toastr.info("Ada <?php echo $gagal;?> gagal ditambahkan dan <?php echo $sukses;?> sukses ditambahkan");
		return false();
	})
</script>
<?php } ?>