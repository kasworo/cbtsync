<div class="modal fade" id="myAddKurikulum" aria-modal="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Data Kurikulum</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-sm-12">
					<div class="form-group row mb-2">			
						 <label class="col-sm-4 offset-sm-1">Nama Kurikulum</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="nmkurikulum" name="nmkurikulum">
					</div>
					<div class="form-group row mb-2">			
						<label class="col-sm-4 offset-sm-1">Kode</label>
						<input type="number" class="form-control form-control-sm col-sm-6" id="akkurikulum" name="akkurikulum">
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-primary btn-md col-4" id="simpan">
					<i class="fas fa-save"></i> Simpan
				</button>
				<button type="button" class="btn btn-danger btn-md col-4" data-dismiss="modal">
					<i class="fas fa-power-off"></i> Tutup
				</button>
			</div>
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Data Kurikulum</h4>
			<div class="card-tools">
				<button class="btn btn-success btn-sm" id="btnTambah" data-toggle="modal" data-target="#myAddKurikulum">
					<i class="fas fa-plus-circle"></i>&nbsp;Tambah
				</button>
				<button class="btn btn-info btn-sm" id="btnRefresh">
					<i class="fas fa-sync-alt"></i>&nbsp;Refresh
				</button>
				<button id="hapusall" class="btn btn-danger btn-sm">
					<i class="fas fa-trash-alt"></i>&nbsp;Hapus
				</button>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
			 <table id="tb_kurikulum" class="table table-bordered table-striped table-sm">
				<thead>
					<tr>
						<th style="text-align: center;width:2.5%">No.</th>
						<th style="text-align: center;width:12.5%">Kode</th>
						<th style="text-align: center">Kurikulum</th>
						<th style="text-align: center">Status</th>
						<th style="text-align: center;width:20%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$qk=$conn->query("SELECT*FROM tbkurikulum");
						$no=0;
						while($m=$qk->fetch_array())
						{
							$no++;
							if($m['aktif']=='1'){$status='Aktif';} else {$status='Non Aktif';}
					?>
					<tr>
						<td style="text-align:center"><?php echo $no.'.';?></td>
						<td style="text-align:center"><?php echo $m['akkur'];?></td>
						<td><?php echo $m['nmkur'];?></td>
						<td><?php echo $status;?></td>
						<td style="text-align: center">
							<a href="#myAddKurikulum" data-toggle="modal" data-id="<?php echo $m['idkur'];?>" class="btn btn-xs btn-success btnUpdate">
								<i class="fas fa-edit"></i>&nbsp;Edit
							</a>
							<button data-id="<?php echo $m['idkur'];?>" class="btn btn-xs btn-danger btnHapus">
								<i class="fas fa-trash-alt"></i>&nbsp;Hapus
							</button>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			 </table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#myAddKurikulum").on('hidden.bs.modal', function () {
			window.location.reload();
		})
	})
	$("#btnTambah").click(function(){
		$(".modal-title").html("Tambah Data Kurikulum");
		$("#simpan").html("<i class='fas fa-save'></i> Simpan");
		$("#idkur").val('');
		$("#nmkurikulum").val('');
		$("#akkurikulum").val('');
	})
 
	$("#simpan").click(function(){
		var nm=$("#nmkurikulum").val();
		var ak=$("#akkurikulum").val();
		$.ajax({
			url:"kurikulum_simpan.php",
			type:'POST',
			data:"aksi=simpan&nmkurikulum="+nm+"&akkurikulum="+ak,
			success:function(data){
				toastr.success(data);
			}
		})
	})
	$(".btnUpdate").click(function(){
		$(".modal-title").html("Ubah Data Kurikulum");
		$("#simpan").html("<i class='fas fa-save'></i> Update");
		var id=$(this).data('id');	 
		$.ajax({
			url:'kurikulum_edit.php',
			type:'post',
			dataType:'json',
			data:'id='+id,
			success:function(data)
			{
				$("#nmkurikulum").val(data.nmkur);
				$("#akkurikulum").val(data.akkur);
			}
		})
	})
	$(".btnHapus").click(function(){
			var id=$(this).data('id');
			Swal.fire({
				title: 'Anda Yakin?',
				text: "Menghapus Kurikulum",
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
					url:"kurikulum_simpan.php",
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
		Swal.fire({
			title: 'Anda Yakin?',
			text: "Menghapus Kurikulum",
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
					url:"kurikulum_simpan.php",
					data: "aksi=kosong",
					success: function(data){					
						toastr.success(data);
					}
				})
			}
		})
	})
	$("#btnrefresh").click(function(){
		window.location.reload();
	})
</script>