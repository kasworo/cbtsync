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
						<label class="col-sm-4 offset-sm-1">Kode</label>
						<input type="hidden" class="form-control form-control-sm col-sm-6" id="idkur" name="idkur">
						<input type="number" class="form-control form-control-sm col-sm-6" id="akkur" name="akkur">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Nama Kurikulum</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="nmkur" name="nmkur">
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button class="btn btn-primary btn-md col-4" id="btnSimpan" name="simpan">
					<i class="fas fa-save"></i> Simpan
				</button>
				<a href="#" class="btn btn-danger btn-md col-4" data-dismiss="modal">
					<i class="fas fa-power-off"></i> Tutup
				</a>
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
				<table id="tb_kur" class="table table-bordered table-striped table-sm">
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
						$qk = $conn->query("SELECT*FROM tbkurikulum");
						$no = 0;
						while ($m = $qk->fetch_array()) {
							$no++;
							if ($m['aktif'] == '1') {
								$status = 'Aktif';
							} else {
								$status = 'Non Aktif';
							}
						?>
							<tr>
								<td style="text-align:center"><?php echo $no . '.'; ?></td>
								<td style="text-align:center"><?php echo $m['akkur']; ?></td>
								<td><?php echo $m['nmkur']; ?></td>
								<td><?php echo $status; ?></td>
								<td style="text-align: center">
									<a href="#myAddKurikulum" data-toggle="modal" data-id="<?php echo $m['idkur']; ?>" class="btn btn-xs btn-success btnUpdate">
										<i class="fas fa-edit"></i>&nbsp;Edit
									</a>
									<button data-id="<?php echo $m['idkur']; ?>" class="btn btn-xs btn-danger btnHapus">
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
	$(document).ready(function() {
		$("#myAddKurikulum").on('hidden.bs.modal', function() {
			window.location.reload();
		})
	})
	$("#btnTambah").click(function() {
		$(".modal-title").html("Tambah Data kur");
		$("#simpan").html("<i class='fas fa-save'></i> Simpan");
		$("#idkur").val('');
		$("#nmkur").val('');
		$("#akkur").val('');
	})

	$("#btnSimpan").click(function(e) {
		e.preventDefault();
		let akkur = $("#akkur").val();
		let nmkur = $("#nmkur").val();
		if (akkur == '' || akkur == null) {
			toastr.error('Kode Tidak Boleh Kosong!!', 'Mohon Maaf!');
		} else if (nmkur == '' || nmkur == null) {
			toastr.error('Nama Kurikulum Tidak Boleh Kosong!!', 'Mohon Maaf!');
		} else {
			let data = new FormData();
			data.append('id', $("#idkur").val());
			data.append('akkur', akkur);
			data.append('nmkur', nmkur);
			data.append('aksi', 'simpan');
			$.ajax({
				type: "POST",
				url: "kurikulum_simpan.php",
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 8000,
				success: function(respons) {
					if (respons == 1) {
						$(function() {
							toastr.success('Kurikulum Berhasil Ditambah!!', 'Terima Kasih', {
								timeOut: 3000,
								fadeOut: 3000,
								onHidden: function() {
									$("#myAddKurikulum").modal('hide');
								}
							});
						});
					}
					if (respons == 2) {
						$(function() {
							toastr.success('Kurikulum Berhasil Diupdate!', 'Informasi', {
								timeOut: 3000,
								fadeOut: 3000,
								onHidden: function() {
									$("#myAddKurikulum").modal('hide');
								}
							});
						});
					}
					if (respons == 0) {
						$(function() {
							toastr.error('Gagal Ditambahkan atau Diupdate!!', 'Mohon Maaf', {
								timeOut: 3000,
								fadeOut: 3000,
								onHidden: function() {
									$("#myAddKurikulum").modal('hide');
								}
							});
						});
					}
				}
			});
		}
	})

	$(".btnUpdate").click(function() {
		$(".modal-title").html("Ubah Data kur");
		$("#simpan").html("<i class='fas fa-save'></i> Update");
		let id = $(this).data('id');
		$.ajax({
			url: 'kurikulum_edit.php',
			type: 'post',
			dataType: 'json',
			data: 'id=' + id,
			success: function(data) {
				$("#idkur").val(data.idkur)
				$("#nmkur").val(data.nmkur);
				$("#akkur").val(data.akkur);
			}
		})
	})


	$(".btnHapus").click(function() {
		let id = $(this).data('id');
		Swal.fire({
			title: 'Anda Yakin?',
			text: "Menghapus Kurikulum",
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
					url: "kurikulum_simpan.php",
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
			text: "Menghapus Data Kurikulum",
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
					url: "kurikulum_simpan.php",
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