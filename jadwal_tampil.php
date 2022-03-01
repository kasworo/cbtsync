<div class="modal fade" id="myJadwal" aria-modal="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Jadwal Ujian</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-sm-12">
					<div class="form-group row mb-2">
						<label class="col-sm-5">Pilihan Tes</label>
						<select class="form-control form-control-sm col-sm-6" id="idjtes" name="idjtes" autocomplete="off">
							<option value="">..Pilih..</option>
							<?php
								$qts=$conn->query("SELECT u.idujian, u.nmujian, ts.nmtes FROM tbujian u INNER JOIN tbtes ts ON ts.idtes=u.idtes WHERE u.idthpel='$idthpel' AND status='1'");
								while($ts=$qts->fetch_array()){
							?>
							<option value="<?php echo $ts['idujian'];?>"><?php echo $ts['nmujian'].' - '.$ts['nmtes'];?></option>
							<?php }?>
						</select>
					</div>					
					<div class="form-group row mb-2">
						<label class="col-sm-5">Nama Jadwal</label>
						<input type="hidden" class="form-control form-control-sm col-sm-6" id="kdjadwal" name="kdjadwal">
						<input type="text" class="form-control form-control-sm col-sm-6" id="nmjadwal" name="nmjadwal" autocomplete="off">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-5">Mata Ujian</label>
						<input class="form-control form-control-sm col-sm-6" id="matauji" name="matauji" autocomplete="off">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-5">Tanggal Tes</label>
						<input class="form-control form-control-sm col-sm-6" id="tgltes" name="tgltes" autocomplete="off">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-5">Durasi Ujian</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="drstes" name="drstes">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-5">Untuk Ujian</label>
						<select class="form-control form-control-sm col-sm-6" id="utmtes" name="utmtes" autocomplete="off">
							<option value="">..Pilih..</option>
							<option value="0">Utama</option>
							<option value="1">Susulan</option>
						</select>
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-5">Hitung Keterlambatan</label>
						<select class="form-control form-control-sm col-sm-6" id="lambat" name="lambat" autocomplete="off">
							<option value="">..Pilih..</option>
							<option value="0">Tidak</option>
							<option value="1">Ya</option>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button class="btn btn-primary btn-sm col-4" id="simpan">
					<i class="fas fa-save"></i> Simpan
				</button>
				<button class="btn btn-danger btn-sm col-4" data-dismiss="modal">
					<i class="fas fa-power-off"></i> Tutup
				</button>
			</div>
		</div>
	</div>
</div>
<div class="col-sm-12">
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Pengaturan Jadwal Tes</h4>
			<div class="card-tools">
				<button class="btn btn-success btn-sm" id="btnTambah" data-toggle="modal" data-target="#myJadwal">
					<i class="fas fa-plus-circle"></i>&nbsp;Tambah
				</button>
				<button class="btn btn-secondary btn-sm" id="btnrefresh">
					<i class="fas fa-sync-alt"></i>&nbsp;Refresh
				</button>
				<button class="btn btn-danger btn-sm" id="btnhapus">
					<i class="fas fa-trash-alt"></i>&nbsp;Hapus
				</button>
			</div>
		</div>
		<div class="card-body">
			<?php
				if($level=='1'){
					$qsk=$conn->query("SELECT j.* FROM tbjadwal j INNER JOIN tbujian u USING(idujian) WHERE u.status='1' ORDER BY idjadwal");
				}
				elseif ($level=='2'){
					$qsk=$conn->query("SELECT j.* FROM tbjadwal j INNER JOIN tbujian u USING(idujian) INNER JOIN tbsetingujian su USING(idjadwal) INNER JOIN tbpengampu a USING(idrombel) INNER JOIN tbbanksoal bs USING(idmapel,idbank) WHERE u.status='1' AND a.username='$useraktif' GROUP BY j.idjadwal ORDER BY idjadwal");
				}
				else {
					$qsk=$conn->query("SELECT j.* FROM tbjadwal j INNER JOIN tbujian u USING(idujian) WHERE u.status='1' ORDER BY idjadwal");
				}
				$ceksk=$qsk->num_rows;
				if($ceksk>0):
			?>
			<div class="form-group mb-2">
				<div class="table-responsive">
					<table width="100%" class="table-sm table-bordered table-striped" id="tbjadwal">
						<thead>
							<th style="text-align:center;width:2.5%">No.</th>
							<th style="text-align:center;">Jadwal Tes</th>
							<th style="text-align:center;">Mata Uji</th>
							<th style="text-align:center;width:17.5%">Tanggal Tes</th>
							<th style="text-align:center;width:15%">Aksi</th>
						</thead>
						<tbody>
						<?php
							$i=0;
							while($sk=$qsk->fetch_array()):
							$i++;
							if($sk['hasil']=='1'){
								$btn="btn-success";
								$ikn="fa-eye";
								$teks="Tampilkan";
							}
							else {
								$btn="btn-secondary";
								$ikn="fa-eye-slash";
								$teks="Sembunyikan";
							}
						?>
							<tr>
								<td style="text-align:center"><?php echo $i,'.';?></td>
								<td style="text-align:left"><?php echo $sk['nmjadwal'];?></td>
								<td style="text-align:left"><?php echo $sk['matauji'];?></td>
								<td style="text-align:center"><?php echo indonesian_date($sk['tglujian']);?></td>
								<td style="text-align:center">
									<a href="#myJadwal" data-toggle="modal" data-id="<?php echo $sk['kdjadwal'];?>" class="btn btn-xs btn-info editJadwal">
										<i class="fas fa-edit"></i>&nbsp;Edit
									</a>
									<button data-id="<?php echo $sk['kdjadwal'];?>" class="btn btn-xs btn-danger btnHapus">
										<i class="fas fa-trash-alt"></i>&nbsp;Hapus
									</button>
								</td>
							</tr>			
						<?php endwhile ?>
						</tbody>
					</table>
				</div>				
			</div>
		<?php else: ?>
		<div class="alert alert-danger">
			<p>Silahkan Tambahkan Jadwal Dulu!</p>
		</div> 
		<?php endif?>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(function () {
			$('#tbjadwal').DataTable({
				"paging": true,
				"lengthChange": false,
				"searching": true,
				"ordering": false,
				"info": false,
				"autoWidth": false,
				"responsive": true,
			});
		})
		$("#idjtes").change(function(){
			var iduji=$(this).val();
			$.ajax({
				url: "jadwal_getid.php",
				type:"POST",
				data: "id="+iduji,
				cache: false,
				success: function(data){
					$("#kdjadwal").val(data);
				}
			});
		})
		$('#tgltes').datetimepicker({
			timepicker:false,
			format: 'Y-m-d'
		});
		$(".editJadwal").click(function(){
			$(".modal-title").html("Ubah Jadwal Ujian");
			$("#simpan").html("<i class='fas fa-save'></i> Update");
			var id=$(this).data('id');
			$.ajax({
				url:'jadwal_edit.php',
				type:'post',
				dataType:'json',
				data:'id='+id,
				success:function(data)
				{
					$("#idjtes").val(data.idujian);
					$("#kdjadwal").val(data.kdjadwal);
					$("#nmjadwal").val(data.nmjadwal);
					$("#matauji").val(data.matauji);
					$("#tgltes").val(data.tglujian);
					$("#drstes").val(data.durasi);
					$("#utmtes").val(data.susulan);
					$("#lambat").val(data.lambat);
					$("#hasil").val(data.hasil);
				}
			})
		})
		$("#simpan").click(function(){
			var id=$("#idjtes").val();
			var kode=$("#kdjadwal").val();
			var nama =$("#nmjadwal").val();
			var mtuji=$("#matauji").val();
			var tgl =$("#tgltes").val();
			var wktu=$("#drstes").val();
			var utm =$("#utmtes").val();
			var lmb=$("#lambat").val();
			if(id==''){
				toastr.error("Pilih Jenis Tes Terlebih Dahulu....");			
			}
			else if(nama==''){
				toastr.error("Nama Jadwal Tidak Boleh Kosong..");
			}
			else if(mtuji==''){
				toastr.error("Mata Pelajaran Tidak Boleh Kosong..");
			}
			else if (tgl==''){
				toastr.error("Tanggal Kegiatan Harus Diisi");
			}
			else if (wktu==''){
				toastr.error("Durasi Kegiatan Harus Diisi");
			}
			else if (utm==''){
				toastr.error("Jenis Ujian Harus Diisi!");
			}
			else{
				$.ajax({
					url: "jadwal_simpan.php",
					type:"post",
					data: "aksi=1&id="+id+"&kode="+kode+"&nama="+nama+"&mtuji="+mtuji+"&tgl="+tgl+"&wkt="+wktu+"&utm="+utm+"&lmb="+lmb,
					cache: false,
					success: function(data){
						toastr.success(data);
					}
				});
			}
		})
		$(".btnHapus").click(function(){
		var id=$(this).data('id');
		Swal.fire({
			title: 'Anda Yakin?',
			text: "Menghapus Jadwal Ini",
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
				url:"jadwal_simpan.php",
				data: "aksi=3&id="+id,
				success: function(data){					
					toastr.success(data);
				}
			})
		}
		})
	})
	$("#btnhapus").click(function(){
		Swal.fire({
			title: 'Anda Yakin?',
			text: "Menghapus Semua Jadwal Ujian",
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
				url:"jadwal_simpan.php",
				data: "aksi=4",
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
	$("#myJadwal").on('hidden.bs.modal', function () {
		window.location.reload();
	})	
  })  
</script>