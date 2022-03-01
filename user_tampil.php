<?php
if(!isset($_COOKIE['c_user'])){header("Location: login.php");}
if(!empty($_REQUEST['d']) && $_REQUEST['d']=='1'){include "user_upload.php";}?>
<div class="modal fade" id="myImportUser" aria-modal="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="?p=datauser&d=1" method="POST" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title">Import Data Pengguna</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-sm-12">
					<div class="row">
						<label for="tmpuser">Pilih File Template</label>
						<div class="custom-file">
						<input type="file" class="custom-file-input" id="tmpuser" name="tmpuser">
						<label class="custom-file-label" for="tmpuser">Pilih file</label>
						</div>				
						<p style="color:red;margin-top:10px"><em>Hanya mendukung file *.xls (Microsoft Excel 97-2003)</em></p>
					</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<a href="user_template.php" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-download"></i> Download</a>
					<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-upload"></i> Upload</button>
					<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-power-off"></i> Tutup</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Data Pengguna</h4>
			<div class="card-tools">
				<a href="index.php?p=adduser&m=1" class="btn btn-primary btn-sm">
					<i class="fas fa-plus-circle"></i>&nbsp;Tambah
				</a>
				<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myImportUser">
					<i class="fas fa-cloud-upload-alt"></i>&nbsp;Import
				</button>
			</div>
		</div>
		<div class="card-body">			
			<div class="table-responsive">
				<table id="tb_user" class="table table-bordered table-striped table-sm">
					<thead>
						<tr>
							<th style="text-align: center;width:2.5%">No.</th>
							<th style="text-align: center;width:15%">Username</th>
							<th style="text-align: center;width:22.5%">Nama Lengkap</th>
							<th style="text-align: center;">Alamat</th>
							<th style="text-align: center;width:10%">Aktif</th>
							<th style="text-align: center;width:25%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$qs=$conn->query("SELECT*FROM tbuser WHERE level='2' AND aktif='1'");
							$no=0;
							while($s=$qs->fetch_array()):
							$no++;
							if($s['aktif']=='1'){$stat='Aktif';$btn="btn-success";} else {$stat='Non Aktif';$btn="btn-danger";}
						?>
						<tr>
							<td style="text-align:center"><?php echo $no.'.';?></td>
							<td style="text-align:center"><?php echo $s['username'];?></td>
							<td><?php echo $s['nama'];?></td>
							<td><?php echo $s['alamat'];?></td>
							<td style="text-align:center">
								<input data-id="<?php echo base64_encode($s['username']);?>" type="button" class="btn <?php echo $btn;?> btn-xs btnAktif" value="<?php echo $stat;?>">
							</td>
							<td style="text-align: center">
								<button data-id="<?php echo base64_encode($s['username']);?>" class="col-3 btn btn-xs btn-secondary btnReset">
									<i class="fas fa-sync-alt"></i>&nbsp;Reset
								</button>
								<a href="index.php?p=adduser&m=2&id=<?php echo base64_encode($s['username']);?>" class="col-3 btn btn-xs btn-primary">
									<i class="fas fa-edit"></i>&nbsp;Edit
								</a>
								<button data-id="<?php echo base64_encode($s['username']);?>" class="col-3 btn btn-xs btn-danger btnHapus">
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
		$('#tb_user').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": false,
			"responsive": true,
		});
	});

	$(".btnReset").click(function(){
		var id=$(this).data('id');
		$.ajax({
			url:"user_simpan.php",
			type:"POST",
			data:"aksi=reset&id="+id,
			success:function(data){
			toastr.success(data);		 
			}
		})
	})

	$(".btnHapus").click(function(){
		var id=$(this).data('id');
		Swal.fire({
			title: 'Anda Yakin?',
			text: "Menghapus Data Pengguna",
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
						url:"user_simpan.php",
						data: "aksi=hapus&id="+id,
						success: function(data){
							toastr.success(data);
						}
				})
				window.location.reload();
			}
		})
	})

	
	$(".btnAktif").click(function(){
		var id=$(this).data('id');
		$.ajax({
			url:"user_simpan.php",
			type:"POST",
			data:"aksi=aktif&id="+id,
			success:function(data){
				toastr.success(data); 
				window.location.reload();		 
			}
		})
	})
	
</script>