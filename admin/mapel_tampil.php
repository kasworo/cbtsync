<?php
if ($level == '1') :
?>
	<div class="modal fade" id="myAddMapel" aria-modal="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Tambah Data Mata Pelajaran</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-sm-12">
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Kurikulum</label>
							<select class="form-control form-control-sm col-sm-6" id="kdkur" name="kdkur">
								<option value="">..Pilih..</option>
								<?php
								$sqkur = "SELECT*FROM tbkurikulum WHERE aktif='1'";
								$qkur = vquery($sqkur);
								foreach ($qkur as $ku) :
								?>
									<option value="<?php echo $ku['idkur']; ?>"><?php echo $ku['nmkur']; ?></option>
								<?php endforeach ?>
							</select>
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Mata Pelajaran</label>
							<input type="hidden" class="form-control form-control-sm col-sm-6" id="idmapel" name="idmapel">
							<input type="text" class="form-control form-control-sm col-sm-6" id="nmmapel" name="nmmapel">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Kode</label>
							<input type="text" class="form-control form-control-sm col-sm-6" id="akmapel" name="akmapel">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Jenis</label>
							<select class="form-control form-control-sm col-sm-6" id="jmapel" name="jmapel">
								<option value="">..Pilih..</option>
								<?php
								if ($jenjang == '1' || $jenjang == '2') :
								?>
									<option value="0">Umum</option>
									<option value="1">Agama</option>
								<?php elseif ($jenjang == '3') : ?>
									<option value="0">Umum</option>
									<option value="1">Agama</option>
									<option value="2">Peminatan</option>
								<?php else : ?>
									<option value="0">Umum</option>
									<option value="1">Agama</option>
									<option value="2">Kejuruan</option>
								<?php endif ?>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-primary btn-sm col-3" id="simpan">
						<i class="fas fa-save"></i> Simpan
					</button>
					<button type="button" class="btn btn-danger btn-sm col-3" data-dismiss="modal">
						<i class="fas fa-power-off"></i> Tutup
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Data Mata Pelajaran</h4>
			<div class="card-tools">
				<button class="btn btn-success btn-sm" id="btnTambah" data-toggle="modal" data-target="#myAddMapel">
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
				<table id="tb_mapel" class="table table-bordered table-striped table-sm">
					<thead>
						<tr>
							<th style="text-align: center;width:2.5%">No.</th>
							<th style="text-align: center;width:10%">Kode</th>
							<th style="text-align: center">Mata Pelajaran</th>
							<th style="text-align: center;width:20%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$qk = $conn->query("SELECT*FROM tbmapel");
						$no = 0;
						while ($m = $qk->fetch_array()) {
							$no++;
						?>
							<tr>
								<td style="text-align:center"><?php echo $no . '.'; ?></td>
								<td style="text-align:center"><?php echo $m['akmapel']; ?></td>
								<td><?php echo $m['nmmapel']; ?></td>
								<td style="text-align: center">
									<a href="#myAddMapel" data-toggle="modal" data-id="<?php echo $m['idmapel']; ?>" class="col-4 btn btn-xs btn-success btnUpdate">
										<i class="fas fa-edit"></i>&nbsp;Edit
									</a>
									<button data-id="<?php echo $m['idmapel']; ?>" class="col-4 btn btn-xs btn-danger btnHapus">
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

	<script type="text/javascript">
		$(document).ready(function() {
			$("#myAddMapel").on('hidden.bs.modal', function() {
				window.location.reload();
			})
		})
		$("#btnTambah").click(function() {
			$(".modal-title").html("Tambah Data Mata Pelajaran");
			$("#simpan").html("<i class='fas fa-save'></i> Simpan");
			$("#idkur").val('');
			$("#nmmapel").val('');
			$("#akmapel").val('');
		})
		$("#simpan").click(function() {
			let idkur = $("#kdkur").val();
			let nmmapel = $("#nmmapel").val();
			let kdmapel = $("#akmapel").val();
			let jnsmapel = $("#jmapel").val();
			if (idkur == '') {
				toastr.error("Pilih Kurikulum Dulu", "Maaf!");
			} else if (nmmapel == '') {
				toastr.error("Nama Mata Pelajaran Tidak Boleh Kosong", "Maaf!");
			} else if (kdmapel == '') {
				toastr.error("Kode Mata Pelajaran Tidak Boleh Kosong", "Maaf!");
			} else if (jnsmapel == '') {
				toastr.error("Pilihan Jenis Mata Pelajaran Tidak Boleh Kosong", "Maaf!");
			} else {
				data = new FormData();
				data.append('idm', $("#idmapel").val());
				data.append('idk', idkur);
				data.append('nama', nmmapel);
				data.append('kode', kdmapel);
				data.append('jenis', jnsmapel);
				data.append('aksi', 'simpan');
				$.ajax({
					type: "POST",
					url: "mapel_simpan.php",
					data: data,
					processData: false,
					contentType: false,
					cache: false,
					timeout: 8000,
					success: function(respons) {
						if (respons == 1) {
							$(function() {
								toastr.success('Mata Pelajaran Berhasil Ditambah!!', 'Terima Kasih', {
									timeOut: 3000,
									fadeOut: 3000,
									onHidden: function() {
										$("#myAddMapel").modal('hide');
									}
								});
							});
						}
						if (respons == 2) {
							$(function() {
								toastr.info('Mata Pelajaran Berhasil Diupdate!!', 'Informasi', {
									timeOut: 3000,
									fadeOut: 3000,
									onHidden: function() {
										$("#myAddMapel").modal('hide');
									}
								});
							});
						}
						if (respons == 0) {
							$(function() {
								toastr.error('Gagal Update atau Tambah Data!!', 'Mohon Maaf', {
									timeOut: 3000,
									fadeOut: 3000,
									onHidden: function() {
										$("#myAddMapel").modal('hide');
									}
								});
							});
						}
					}
				});
			}
		})

		$(".btnUpdate").click(function() {
			$(".modal-title").html("Ubah Data Mata Pelajaran");
			$("#simpan").html("<i class='fas fa-save'></i> Update");
			let id = $(this).data('id');
			$.ajax({
				url: 'mapel_edit.php',
				type: 'post',
				dataType: 'json',
				data: 'id=' + id,
				success: function(data) {
					$("#idmapel").val(data.idmapel);
					$("#kdkur").val(data.idkur);
					$("#nmmapel").val(data.nmmapel);
					$("#akmapel").val(data.akmapel);
					$("#jmapel").val(data.jenis);
				}
			})
		})

		$(".btnHapus").click(function() {
			let id = $(this).data('id');
			Swal.fire({
				title: 'Anda Yakin?',
				text: "Menghapus Mata Pelajaran",
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Hapus',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						type: "POST",
						url: "mapel_simpan.php",
						data: "aksi=hapus&id=" + id,
						success: function(data) {
							toastr.success(data);
						}
					})
					window.location.reload();
				}
			})
		})
		$("#hapusall").click(function() {
			Swal.fire({
				title: 'Anda Yakin?',
				text: "Menghapus Mata Pelajaran",
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Hapus',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						type: "POST",
						url: "mapel_simpan.php",
						data: "aksi=kosong",
						success: function(data) {
							toastr.success(data);
						}
					})
				}
			})
		})
		$("#btnrefresh").click(function() {
			window.location.reload();
		})
	</script>
<?php else : ?>
	<div class="col-sm-12">
		<div class="card card-secondary card-outline">
			<div class="card-header">
				<h4 class="card-title">Data Mata Pelajaran</h4>
			</div>
			<div class="card-body">
				<div class="form-group row mb-2">
					<div class="alert alert-warning">
						<p><strong>Perhatian:</strong><br />Berikut adalah data Mata Pelajaran. Perubahan data ini hanya bisa dilakukan oleh Administrator.</p>
					</div>
				</div>
				<br />
				<div class="table-responsive">
					<table id="tb_mapel" class="table table-bordered table-striped table-sm">
						<thead>
							<tr>
								<th style="text-align: center;width:2.5%">No.</th>
								<th style="text-align: center;width:15%">Kode Mapel</th>
								<th style="text-align: center">Mata Pelajaran</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$qk = $conn->query("SELECT*FROM tbmapel");
							$no = 0;
							while ($m = $qk->fetch_array()) {
								$no++;
							?>
								<tr>
									<td style="text-align:center"><?php echo $no . '.'; ?></td>
									<td style="text-align:center"><?php echo $m['akmapel']; ?></td>
									<td><?php echo $m['nmmapel']; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>
<script type="text/javascript">
	$(function() {
		$('#tb_mapel').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": false,
			"responsive": true,
		});
	})
</script>