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
					$sqlrg = "SELECT*FROM tbruang";
					$no = 0;
					$qru = vquery($sqlrg);
					foreach ($qru as $m) :
						$no++;
						if ($m['status'] == '1') {
							$stat = 'Aktif';
							$btn = 'btn-success';
						} else {
							$stat = 'Non Aktif';
							$btn = 'btn-secondary';
						}
					?>
						<tr>
							<td style="text-align:center"><?php echo $no . '.'; ?></td>
							<td style="text-align:center"><?php echo $m['kdruang']; ?></td>
							<td style="text-align:center"><?php echo $m['nmruang']; ?></td>
							<td style="text-align:center"><?php echo $m['isi'] . ' Peserta'; ?></td>
							<td style="text-align:center">
								<input data-id="<?php echo $m['idruang']; ?>" type="button" class="col-4 btn <?php echo $btn; ?> btn-xs btnAktif" value="<?php echo $stat; ?>">
							</td>
							<td style="text-align:center">
								<a href="#myAddRuang" data-toggle="modal" data-id="<?php echo $m['idruang']; ?>" class="col-4 btn btn-xs btn-primary btnUpdate">
									<i class="fas fa-edit"></i>&nbsp;Edit
								</a>
								<button data-id="<?php echo $m['idruang']; ?>" class="col-4 btn btn-xs btn-danger btnHapus">
									<i class="fas fa-trash-alt"></i>&nbsp;Hapus
								</button>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#myAddRuang").on('hidden.bs.modal', function() {
			window.location.reload();
		})
	})
	$("#btnTambah").click(function() {
		$(".modal-title").html("Tambah Data Ruang");
		$("#simpan").html("<i class='fas fa-save'></i> Simpan");
		$("#nmruang").val('');
		$("#akruang").val('');
	})

	$(".btnAktif").click(function() {
		let data = new FormData()
		data.append('id', $(this).data('id'))
		data.append('aksi', 'aktif')
		$.ajax({
			url: "ruang_simpan.php",
			type: 'POST',
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 8000,
			success: function(respons) {
				if (respons == 1) {
					$(function() {
						toastr.info('Ruangan Berhasil Dinonaktifkan!!', 'Informasi', {
							timeOut: 3000,
							fadeOut: 3000,
							onHidden: function() {
								window.location.reload()
							}
						});
					});
				}
				if (respons == 2) {
					$(function() {
						toastr.info('Ruangan Berhasil Diaktifkan!!', 'Informasi', {
							timeOut: 3000,
							fadeOut: 3000,
							onHidden: function() {
								window.location.reload()
							}
						});
					});
				}
				if (respons == 0) {
					$(function() {
						toastr.error('Gagal Update atau Simpan Data!!', 'Mohon Maaf', {
							timeOut: 3000,
							fadeOut: 3000,
							onHidden: function() {
								window.location.reload()
							}
						});
					});
				}
			}
		})
	})

	$("#simpan").click(function() {
		let ak = $("#akruang").val();
		let nm = $("#nmruang").val();
		let isi = $("#isiruang").val();
		if (ak == '') {
			toastr.error("Pilihan Tidak Boleh Kosong", "Maaf!");
		} else if (nm == '') {
			toastr.error("Pilihan Tidak Boleh Kosong", "Maaf!");
		} else if (isi == '') {
			toastr.error("Pilihan Tidak Boleh Kosong", "Maaf!");
		} else {
			let data = new FormData();
			data.append('nm', nm);
			data.append('ak', ak);
			data.append('isi', isi);
			data.append('aksi', 'simpan')
			$.ajax({
				url: "ruang_simpan.php",
				type: 'POST',
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 8000,
				success: function(respons) {
					if (respons == 1) {
						$(function() {
							toastr.success('Simpan Data Berhasil!!', 'Terima Kasih', {
								timeOut: 3000,
								fadeOut: 3000,
								onHidden: function() {
									$("#myAddRuang").modal('hide');
								}
							});
						});
					}
					if (respons == 2) {
						$(function() {
							toastr.info('Update Data Berhasil!!', 'Informasi', {
								timeOut: 3000,
								fadeOut: 3000,
								onHidden: function() {
									$("#myAddRuang").modal('hide');
								}
							});
						});
					}
					if (respons == 0) {
						$(function() {
							toastr.error('Gagal Update atau Simpan Data!!', 'Mohon Maaf', {
								timeOut: 3000,
								fadeOut: 3000,
								onHidden: function() {
									$("#myAddRuang").modal('hide');
								}
							});
						});
					}
				}
			})
		}
	})
	$(".btnUpdate").click(function() {
		$(".modal-title").html("Ubah Data Ruang");
		$("#simpan").html("<i class='fas fa-save'></i> Update");
		let id = $(this).data('id');
		$.ajax({
			url: 'ruang_edit.php',
			type: 'post',
			dataType: 'json',
			data: 'id=' + id,
			success: function(data) {
				$("#nmruang").val(data.nmruang);
				$("#akruang").val(data.kdruang);
				$("#isiruang").val(data.isi);
			}
		})
	})
	$(".btnHapus").click(function() {
		let id = $(this).data('id');
		Swal.fire({
			title: 'Anda Yakin?',
			text: "Menghapus Ruang",
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
					url: "ruang_simpan.php",
					data: "aksi=3&id=" + id,
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
			text: "Menghapus Ruang",
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
					url: "ruang_simpan.php",
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