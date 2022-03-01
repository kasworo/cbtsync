<?php
	if(isset($_GET['d']) && $_GET['d']==1){
		include "siswa_upload.php";
	}
	if(isset($_POST['upload'])){
		$output = '';  
		if($_FILES['zip_file']['name'] != '')  
		{  
			$file_name = $_FILES['zip_file']['name'];  
			$array = explode(".", $file_name);  
			$name = $array[0];  
			$ext = $array[1];  
			if($ext == 'zip')  
			{  
				$path = '../foto/';  
				$location = $path . $file_name;  
				if(move_uploaded_file($_FILES['zip_file']['tmp_name'], $location))  
				{  
					$zip = new ZipArchive;  
					if($zip->open($location))  
					{  
						$zip->extractTo($path);  
						$zip->close();  
					}  
					$files = scandir($path);  
					foreach($files as $file)  
					{  
						$allowed_ext = array('jpg', 'png');  
						$file_ext = end(explode(".", $file));  
						if(in_array($file_ext, $allowed_ext))  
						{  
							$tmp = explode(".", $file);
							$nama = $tmp[0];
							$qfoto=$conn->query("UPDATE tbpeserta SET fotosiswa='$file' WHERE nisn='$nama'");  
						}
					}
					unlink($location);
					$pesan="Upload File Foto ".$file." Berhasil!";
					$jns="success";
				}  
			}
			else{
				$pesan='Bukan File *.zip';
				$jns="error";
			}  
		}
		else{
			$pesan='Tidak Ada File Yang Diupload!';
			$jns="error";
		} 
	
?>
<script type="text/javascript">
	$(function() {
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 2000
		});
		toastr.<?php echo $jns;?>("<?php echo $pesan;?>");
	})
</script>
<?php } ?>
<div class="modal fade" id="myImportPD" aria-modal="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="index.php?p=datasiswa&d=1" method="POST" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title">Import Data Peserta Didik</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-sm-12">
						<div class="row">
							<label for="filepd">Pilih File Template</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="filepd" name="filepd">
								<label class="custom-file-label" for="filepd">Pilih file</label>
							</div>							
							<p style="color:red;margin-top:10px"><em>Hanya mendukung file *.xls (Microsoft Excel 97-2003)</em></p>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<a href="siswa_template.php" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-download"></i> Download</a>
					<button class="btn btn-primary btn-sm"><i class="fas fa-upload"></i> Upload</button>
					<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-power-off"></i> Tutup</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="myFotoPD" aria-modal="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="" method="POST" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title">Upload Foto Peserta Didik</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-sm-12">
						<div class="row">
							<label for="filepd">Pilih File Zip</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="fotopd" name="zip_file">
								<label class="custom-file-label" for="fotopd">Pilih file</label>
							</div>							
							<p style="color:red;margin-top:10px"><em>Hanya mendukung file *.zip</em></p>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="submit" class="btn btn-primary btn-sm" name="upload"><i class="fas fa-upload"></i> Upload</button>
					<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-power-off"></i> Tutup</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Data Peserta Didik</h4>
			<div class="card-tools">
				<a href="index.php?p=addsiswa&m=1" class="btn btn-primary btn-sm">
					<i class="fas fa-plus-circle"></i>&nbsp;Tambah
				</a>
				<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myImportPD">
					<i class="fas fa-cloud-upload-alt"></i>&nbsp;Import
				</button>
				<button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#myFotoPD">
					<i class="fas fa-upload"></i>&nbsp;Foto
				</button>
				<button id="hapusall" class="btn btn-danger btn-sm">
					<i class="fas fa-trash-alt"></i>&nbsp;Hapus
				</button>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tb_siswa" class="table table-bordered table-striped table-sm">
					<thead>
						<tr>
							<th style="text-align: center;width:2.5%">No.</th>
							<th style="text-align: center;width:25%">Nama User</th>
							<th style="text-align: center;width:17.5%">NIS / NISN</th>
							<th style="text-align: center;">Alamat</th>
							<th style="text-align: center;width:10%">Status</th>
							<th style="text-align: center;width:20%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$qs=$conn->query("SELECT*FROM tbpeserta WHERE deleted='0'");
							$no=0;
							while($s=$qs->fetch_array()):$no++;
							if($s['aktif']=='1'){$stat='Aktif';$btn="btn-success";} else {$stat='Non Aktif';$btn="btn-danger";}
						?>
						<tr>
							<td style="text-align:center"><?php echo $no.'.';?></td>
							<td title="<?php echo $s['idsiswa'];?>"><?php echo ucwords(strtolower($s['nmsiswa']));?></td>
							<td><?php echo $s['nis'].' / '.$s['nisn'];?></td>
							<td><?php echo $s['alamat'];?></td>
							<td style="text-align:center">
								<input data-id="<?php echo base64_encode($s['idsiswa']);?>" type="button" class="col-10 btn <?php echo $btn;?> btn-xs btnAktif" value="<?php echo $stat;?>">
							</td>
							<td style="text-align: center">
								<a href="index.php?p=addsiswa&m=2&id=<?php echo base64_encode($s['idsiswa']);?>" class="btn btn-xs btn-primary col-4">
									<i class="fas fa-edit"></i>&nbsp;Edit
								</a>
								<button data-id="<?php echo $s['idsiswa'];?>" class="btn btn-xs btn-danger col-4 btnHapus">
									<i class="fas fa-trash-alt"></i>&nbsp;Hapus
								</button>
							</td>
						</tr>
						<?php endwhile ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		$('#tb_siswa').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": false,
			"responsive": true,
		});
	});
	
	$(".btnAktif").click(function(){
		var id=$(this).data('id');
		$.ajax({
			url:"siswa_simpan.php",
			type:"POST",
			data:"aksi=aktif&id="+id,
			success:function(data){
				toastr.success(data); 
			}
		})
	})

	$(".btnHapus").click(function(){
		var id=$(this).data('id');
		Swal.fire({
			title: 'Anda Yakin?',
			text: "Menghapus Data Peserta Didik",
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Hapus',
			cancelButtonText:'Batal'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					type:"POST",
					url:"siswa_simpan.php",
					data: "aksi=hapus&id="+id,
					success: function(data){
						toastr.success(data);
					}
				})
				window.location.reload();
			}
		})
	})

	$("#hapusall").click(function(){
		var id=$(this).data('id');
		Swal.fire({
			title: 'Anda Yakin?',
			text: "Menghapus Seluruh Data Peserta Didik"+id,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Hapus',
			cancelButtonText:'Batal'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					type:"POST",
					url:"siswa_simpan.php",
					data: "aksi=kosong&id="+id,
					success: function(data){					
						toastr.success(data);
					}
				})
				window.location.reload();
			}
		})
	})	
</script>