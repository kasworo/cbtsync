<div class="modal fade" id="mySesi" aria-modal="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Pengaturan Sesi</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-sm-12">
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Nama Sesi</label>
						<input type="hidden" class="form-control form-control-sm col-sm-6" id="idsesi" name="idsesi">
						<input type="text" class="form-control form-control-sm col-sm-6" id="nmsesi" name="nmsesi">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Mulai</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="strsesi" name="strsesi">
					</div>
					<div class="form-group row -2">
						<label class="col-sm-4 offset-sm-1">Selesai</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="endsesi" name="endsesi">
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-success btn-sm col-4" id="simpan">
					<i class="fas fa-save"></i>&nbsp;Simpan
				</button>
				<button type="button" class="btn btn-danger btn-sm col-4" data-dismiss="modal">
					<i class="fas fa-power-off"></i> Tutup
				</button>
			</div>
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="card card-danger card-outline">
		<div class="card-header">
			<h4 class="card-title">Sesi Ujian</h4>
			<div class="card-tools">
				<button class="btn btn-success btn-sm" id="btnTambah" data-toggle="modal" data-target="#mySesi">
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
				<table id="tb_tes" class="table table-bordered table-striped table-sm">
					<thead>
						<tr>
						<th style="text-align: center;width:2.5%">No.</th>
						<th style="text-align: center;width:25%">Sesi</th>
						<th style="text-align: center;width:20%">Mulai</th>
						<th style="text-align: center;width:20%">Selesai</th>
						<th style="text-align: center;width:17.5%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$qk=$conn->query("SELECT*FROM tbsesi");
							$no=0;
							while($m=$qk->fetch_array())
							{
								$no++;
						?>
						<tr>
							<td style="text-align:center"><?php echo $no.'.';?></td>
							<td style="text-align:center"><?php echo $m['nmsesi'];?></td>
							<td style="text-align:center"><?php echo $m['mulai'].' WIB';?></td>
							<td style="text-align:center"><?php echo $m['selesai'].' WIB';?></td>
							<td style="text-align: center">
								<a href="#mySesi" data-toggle="modal" data-id="<?php echo $m['idsesi'];?>" class="col-4 btn btn-xs btn-success btnUpdate">
									<i class="fas fa-edit"></i>&nbsp;Edit
								</a>
								<button data-id="<?php echo $m['idsesi'];?>" class="col-4 btn btn-xs btn-danger btnHapus">
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
		$("#strsesi").datetimepicker({
			timepicker:true,
			datepicker:false,
			format: 'H:i'
		});
		$("#endsesi").datetimepicker({
			timepicker:true,
			datepicker:false,
			format: 'H:i'
		});
		$("#mySesi").on('hidden.bs.modal', function () {
			window.location.reload();
		})
	}) 
  
	$("#simpan").click(function(){
	  var id=$("#idsesi").val();
	  var nm=$("#nmsesi").val();
	  var ml=$("#strsesi").val();
	  var ak=$("#endsesi").val();
	  $.ajax({
		url:"sesi_simpan.php",
		type:'POST',
		data:"aksi=1&id="+id+"&nm="+nm+"&ml="+ml+"&ak="+ak,
		success:function(data){
		  toastr.success(data);
		}
	  })
  })
  
  $(".btnUpdate").click(function(){
	  $(".modal-title").html("Ubah Data Jenis Tes");
	  $("#simpan").html("<i class='fas fa-save'></i> Update");
	  var id=$(this).data('id');	  
	  $.ajax({
		url:'sesi_edit.php',
		type:'post',
		dataType:'json',
		data:'id='+id,
		success:function(data)
		{
		  $("#idsesi").val(data.idsesi);
		  $("#nmsesi").val(data.nmsesi);
		  $("#strsesi").val(data.mulai);
		  $("#endsesi").val(data.selesai);
		}
	  })
  })
  $(".btnHapus").click(function(){
	var id=$(this).data('id');
	Swal.fire({
	  title: 'Anda Yakin?',
	  text: "Menghapus Jenis Tes",
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
			url:"sesi_simpan.php",
			data: "aksi=2&id="+id,
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
	  text: "Menghapus Jenis Tes",
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
			url:"sesi_simpan.php",
			data: "aksi=3",
			success: function(data){					
			toastr.success(data);
			}
		})
	  }
	})
	})
  $("#btnRefresh").click(function(){
	window.location.reload();
  })
</script>