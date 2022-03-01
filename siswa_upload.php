<?php
	define("BASEPATH", dirname(__FILE__));
	require_once "../assets/library/PHPExcel.php";
	require_once "../assets/library/excel_reader.php";
	include "../config/konfigurasi.php";

	if(empty($_FILES['filepd']['tmp_name'])){ 
		echo "<script>
			$(function() {
				toastr.error('File Template Peserta Ujian Kosong!','Mohon Maaf!',{
					timeOut:1000,
					fadeOut:1000
				});
			});
		</script>";	
	} 
	else{
		$data = new Spreadsheet_Excel_Reader($_FILES['filepd']['tmp_name']);	
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
			$xkdskul=$data->val($i,2);		
			$xnis=$data->val($i,3);
			$xnisn=$data->val($i,4);
			$xnama= addslashes($data->val($i,5));
			$xtmplhr = $data->val($i,6); 
			$xtgllhr = $data->val($i,7); 
			$xjekel = $data->val($i,8); 
			$nmagama = $data->val($i,9);
			$xalmt = $data->val($i,10);
			$del=$data->val($i,11);
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
			
			if($xnis==''){
				echo "<script>
						$(function() {
							toastr.error('Cek Kolom NIS a.n ".$xnama."','Mohon Maaf!',{
								timeOut:10000,
								fadeOut:10000
							});
						});
					</script>";
			}
			else if(strlen($xnisn)<>10 || $xnisn==''){
				echo "<script>
						$(function() {
							toastr.error('Cek Kolom NISN a.n ".$xnama."','Mohon Maaf!',{
								timeOut:10000,
								fadeOut:10000
							});
						});
					</script>";
			}
			else if(strlen($xnama)<1 || $xnama==''){
				echo "<script>
						$(function() {
							toastr.error('Data Pada Kolom Nama Peserta Tidak Wajar!','Mohon Maaf!',{
								timeOut:10000,
								fadeOut:10000
							});
						});
					</script>";
			}
			else if(strlen($xtmplhr)<1 || $xtmplhr==''){
				echo "<script>
						$(function() {
							toastr.error('Cek Kolom Tempat Lahir a.n ".$xnama."','Mohon Maaf!',{
								timeOut:10000,
								fadeOut:10000
							});
						});
					</script>";
			}
			else if(strlen($xtgllhr)<1 || $xtgllhr==''){
				echo "<script>
						$(function() {
							toastr.error('Cek Kolom Tanggal Lahir a.n ".$xnama."','Mohon Maaf!',{
								timeOut:10000,
								fadeOut:10000
							});
						});
					</script>";
			}
			else if(strlen($xjekel)>1 || $xjekel==''){
				echo "<script>
						$(function() {
							toastr.error('Cek Kolom Jenis Kelamin a.n ".$xnama."','Mohon Maaf!',{
								timeOut:10000,
								fadeOut:10000
							});
						});
					</script>";
			}
			else if($xagama==''){
				echo "<script>
						$(function() {
							toastr.error('Cek Kolom Agama a.n ".$xnama."','Mohon Maaf!',{
								timeOut:10000,
								fadeOut:10000
							});
						});
					</script>";
			}
			else {
				$qpd=$conn->query("SELECT*FROM tbpeserta WHERE nis='$xnis' AND nisn='$xnisn'");
				$ceksiswa=$qpd->num_rows;
				if($ceksiswa>0){
					$sql="UPDATE tbpeserta SET nmsiswa='$xnama', tmplahir='$xtmplhr',tgllahir='$xtgllhr', gender='$xjekel', idagama='$xagama', alamat='$xalmt', deleted='$del' WHERE nis='$xnis' AND nisn='$xnisn'";
					$conn->query($sql);
					$edit=$conn->affected_rows;
					if($edit>0){
						echo "<script>
							$(function() {
								toastr.success('Update Data Peserta a.n ".$xnama." Berhasil!','Terima Kasih',{
									timeOut:10000,
									fadeOut:10000
								});
							});
						</script>";
						$update++;
					}
					else {
						echo "<script>
								$(function() {
									toastr.error('Tidak Ada Perubahan Data a.n ".$xnama."!','Mohon Maaf',{
										timeOut:3000,
										fadeOut:3000
									});
								});
							</script>";
					}
				} 
				else {
					$sql="INSERT INTO tbpeserta (idskul, nisn, nis, nmsiswa, tmplahir, tgllahir, gender, idagama, alamat, aktif, deleted) VALUES('$xkdskul', '$xnisn', '$xnis', '$xnama','$xtmplhr', '$xtgllhr', '$xjekel', '$xagama', '$xalmt','0','$del')";
					$conn->query($sql);
					$simpan=$conn->affected_rows;
					if($simpan>0){
						echo "<script>
							$(function() {
								toastr.success('Simpan Data Peserta a.n ".$xnama." Berhasil!','Terima Kasih',{
									timeOut:10000,
									fadeOut:10000
								});
							});
						</script>";
						$sukses++;
					}
					else {
						echo "<script>
								$(function() {
									toastr.error('Simpan Data Peserta a.n ".$xnama." Gagal!','Mohon Maaf',{
										timeOut:3000,
										fadeOut:3000
									});
								});
							</script>";
						$gagal++;
					}
				}
			}
			echo "<script>
					$(function() {
						toastr.info('Ada ".$sukses." data ditambahkan, ".$update." data diupdate, ".$gagal." data gagal ditambahkan!','Terima Kasih',{
						timeOut:2000,
						fadeOut:2000
					});
				});
			</script>";
			flush();
		}
	}
?>