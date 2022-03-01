<?php
  if(!isset($_COOKIE['c_user'])){header("Location: login.php");}
?>
<div class="modal fade" id="myAddRuang" aria-modal="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
					<h5 class="modal-title">Tambah Data Ruang</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-sm-12">
						<div class="form-group row mb-2">			
							<label class="col-sm-5 offset-sm-1">Kode Ruang</label>
							<input type="text" class="form-control form-control-sm col-sm-5" id="akruang" name="akruang">
						</div>
				  		<div class="form-group row mb-2">			
							<label class="col-sm-5 offset-sm-1">Nama Ruang</label>
							<input type="text" class="form-control form-control-sm col-sm-5" id="nmruang" name="nmruang">
						</div>
						<div class="form-group row mb-2">			
							<label class="col-sm-5 offset-sm-1">Kapasitas Maksimum</label>
							<input type="number" class="form-control form-control-sm col-sm-5" id="isiruang" name="isiruang">
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-primary btn-sm col-4" id="simpan">
						<i class="fas fa-save"></i> Simpan
					</button>
					<button type="button" class="btn btn-danger btn-sm col-4" data-dismiss="modal">
						<i class="fas fa-power-off"></i> Tutup
					</button>
				</div>
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Data Ruang</h4>
			<div class="card-tools">
			  <button class="btn btn-success btn-sm" id="btnTambah" data-toggle="modal" data-target="#myAddRuang">
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
			  <table id="tb_ruang" class="table table-bordered table-striped table-sm">
				<thead>
					<tr>
						<th style="text-align: center;width:2.5%">No.</th>
						<th style="text-align: center;width:15%">Kode</th>
						<th style="text-align: center">Ruang</th>
						<th style="text-align: center">Kapasitas</th>
						<th style="text-align: center;width:20%">Status</th>
						<th style="text-align: center;width:20%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$qk=$conn->query("SELECT*FROM tbruang");
						$no=0;
						while($m=$qk->fetch_array()):
						$no++;
						if($m['status']=='1'){$status='Aktif';} else {$status='Non Aktif';}
					?>
					<tr>
						<td style="text-align:center"><?php echo $no.'.';?></td>
						<td style="text-align:center"><?php echo $m['kdruang'];?></td>
						<td style="text-align:center"><?php echo $m['nmruang'];?></td>
						<td style="text-align:center"><?php echo $m['isi'].' Peserta';?></td>
						<td style="text-align:center"><?php echo $status;?></td>
						<td style="text-align:center">
							<a href="#myAddRuang" data-toggle="modal" data-id="<?php echo $m['idruang'];?>" class="col-4 btn btn-xs btn-success btnUpdate">
								<i class="fas fa-edit"></i>&nbsp;Edit
							</a>
							<button data-id="<?php echo $m['idruang'];?>" class="col-4 btn btn-xs btn-danger btnHapus">
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
	$(document).ready(function(){
		$("#myAddRuang").on('hidden.bs.modal', function () {
			window.location.reload();
		})
	})
	$("#btnTambah").click(function(){
		$(".modal-title").html("Tambah Data Ruang");
		$("#simpan").html("<i class='fas fa-save'></i> Simpan");
		$("#nmruang").val('');
		$("#akruang").val('');
	})
	
	$("#simpan").click(function(){
		var ak=$("#akruang").val();
		var nm=$("#nmruang").val();
		var isi=$("#isiruang").val();
		$.ajax({
			url:"ruang_simpan.php",
			type:'POST',
			data:"aksi=1&nm="+nm+"&ak="+ak+"&isi="+isi,
			success:function(data){
				toastr.success(data);
			}
		})
	})
	$(".btnUpdate").click(function(){
		$(".modal-title").html("Ubah Data Ruang");
		$("#simpan").html("<i class='fas fa-save'></i> Update");
		var id=$(this).data('id');	  
		$.ajax({
			url:'ruang_edit.php',
			type:'post',
			dataType:'json',
			data:'id='+id,
			success:function(data)
			{
				$("#nmruang").val(data.nmruang);
				$("#akruang").val(data.kdruang);
				$("#isiruang").val(data.isi);
			}
		})
	})
	$(".btnHapus").click(function(){
		var id=$(this).data('id');
		Swal.fire({
		title: 'Anda Yakin?',
		text: "Menghapus Ruang",
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
				url:"ruang_simpan.php",
				data: "aksi=3&id="+id,
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
		text: "Menghapus Ruang",
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
				url:"ruang_simpan.php",
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