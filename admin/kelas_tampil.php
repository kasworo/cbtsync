<div class="modal fade" id="myAddKelas" aria-modal="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Data Rombongan Belajar</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-sm-12">
					<div class="form-group row mb-2">
						<label class="col-sm-5">Kurikulum</label>
						<div class="col-sm-6">
							<select class="form-control form-control-sm" id="kdkur" name="kdkur">
								<option value="">..Pilih..</option>
								<?php
								$qkur = viewdata('tbkurikulum');
								foreach ($qkur as  $ku) :
								?>
									<option value="<?php echo $ku['idkur']; ?>"><?php echo $ku['nmkur']; ?>
									<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-5">Kelas</label>
						<div class="col-sm-6">
							<select class="form-control form-control-sm" id="kdkelas" name="kdkelas">
								<option value="">..Pilih..</option>
								<?php
								$qkls = "SELECT idkelas, nmkelas FROM tbkelas INNER JOIN tbskul USING (idjenjang)";
								$kls = vquery($qkls);
								foreach ($kls as $kl) :
								?>
									<option value="<?php echo $kl['idkelas']; ?>"><?php echo $kl['nmkelas']; ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-5">Rombongan Belajar</label>
						<div class="col-sm-6">
							<input type="hidden" class="form-control form-control-sm" id="idrombel" name="idrombel">
							<input type="text" class="form-control form-control-sm" id="nmrombel" name="nmrombel">
						</div>
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-5">Wali Kelas</label>
						<div class="col-sm-6">
							<select class="form-control form-control-sm" id="idwalas" name="idwalas">
								<option value="">..Pilih..</option>
								<?php
								$sqwls = "SELECT idgtk, nama FROM tbgtk g INNER JOIN tbskul sk USING(idskul) WHERE idskul='$idskul'";
								$qwls = vquery($sqwls);
								foreach ($qwls as $wl) :
								?>
									<option value="<?php echo $wl['idgtk']; ?>"><?php echo $wl['nama']; ?></option>
								<?php endforeach ?>
							</select>
						</div>
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
		<h4 class="card-title">Data Rombongan Belajar <?php echo $tapel; ?></h4>
		<div class="card-tools">
			<button button class="btn btn-success btn-sm" id="btnTambah" data-toggle="modal" data-target="#myAddKelas">
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
		<div class="form-group mb-2">
			<div class="table-responsive">
				<table id="tb_kelas" class="table table-bordered table-striped table-sm">
					<thead>
						<tr>
							<th style="text-align: center;width:2.5%">No.</th>
							<th style="text-align: center;width:15%">Kelas</th>
							<th style="text-align: center;width:15%">Rombel</th>
							<th style="text-align: center">Wali Kelas</th>
							<th style="text-align: center;width:25%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$sqlr = "SELECT rb.*,k.nmkelas, g.nama FROM tbrombel rb INNER JOIN tbkelas k USING(idkelas) INNER JOIN tbthpel tp USING(idthpel) INNER JOIN tbgtk g USING(idgtk) WHERE tp.aktif='1'";
						$qkm = vquery($sqlr);
						$no = 0;
						foreach ($qkm as $m) :
							$no++;
						?>
							<tr>
								<td style="text-align:center"><?php echo $no . '.'; ?></td>
								<td style="text-align:center"><?php echo $m['nmkelas']; ?></td>
								<td style="text-align:center"><?php echo $m['nmrombel']; ?></td>
								<td><?php echo $m['nama']; ?></td>
								<td style="text-align: center">
									<a href="#myAddKelas" data-toggle="modal" data-id="<?php echo $m['idrombel']; ?>" class="col-4 btn btn-xs btn-success btnUpdate">
										<i class="fas fa-edit"></i>&nbsp;Edit
									</a>
									<button data-id="<?php echo $m['idrombel']; ?>" class="col-4 btn btn-xs btn-danger btnHapus">
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
</div>
<script type="text/javascript">
	$(function() {
		$('#tb_kelas').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": false,
			"responsive": true,
		});
	})
	$(document).ready(function() {
		$("#myAddKelas").on('hidden.bs.modal', function() {
			window.location.reload();
		})
	})
	$("#btnTambah").click(function() {
		$(".modal-title").html("Tambah Data Rombongan Belajar");
		$("#simpan").html("<i class='fas fa-save'></i> Simpan");
		$("#idkur").val('');
		$("#kdkelas").val('');
		$("#nmrombel").val('');
	})

	$("#simpan").click(function(e) {
		e.preventDefault();
		let idrombel = $("#idrombel").val();
		let idkur = $("#kdkur").val();
		let kdkls = $("#kdkelas").val();
		let nmrmb = $("#nmrombel").val();
		let walas = $("#idwalas").val();
		if (idkur == '') {
			toastr.error("Pilih Kurikulum Dulu", "Maaf!");
		} else if (kdkls == '') {
			toastr.error("Pilih Kelas Dulu", "Maaf!");
		} else if (nmrmb == '') {
			toastr.error("Nama Rombel Tidak Boleh Kosong", "Maaf!");
		} else if (walas == '') {
			toastr.error("Pilih Wali Kelas Dulu", "Maaf!");
		} else {
			let data = new FormData();
			data.append('id', idrombel);
			data.append('kur', idkur);
			data.append('kls', kdkls);
			data.append('nama', nmrmb);
			data.append('walas', walas);
			data.append('aksi', 'simpan');
			$.ajax({
				url: 'kelas_simpan.php',
				type: 'POST',
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 8000,
				success: function(respons) {
					if (respons == 1) {
						toastr.success("Tambah Atau Update Data Rombel Berhasil!");
					} else {
						toastr.error("Tambah Atau Update Data Rombel Gagal!");
					}

				}
			})
		}
	})
	$(".btnUpdate").click(function() {
		$(".modal-title").html("Ubah Data Rombongan Belajar");
		$("#simpan").html("<i class='fas fa-save'></i> Update");
		let id = $(this).data('id');
		$.ajax({
			url: 'kelas_edit.php',
			type: 'post',
			dataType: 'json',
			data: 'id=' + id,
			success: function(data) {
				$("#idrombel").val(data.idrombel);
				$("#kdkur").val(data.idkur);
				$("#nmrombel").val(data.nmrombel);
				$("#kdkelas").val(data.idkelas);
				$("#idwalas").val(data.username);
			}
		})
	})
	$(".btnHapus").click(function() {
		let id = $(this).data('id');
		Swal.fire({
			title: 'Anda Yakin?',
			text: "Menghapus Rombongan Belajar" + id,
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
					url: "kelas_simpan.php",
					data: "aksi=2&id=" + id,
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
			text: "Menghapus Rombongan Belajar",
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
					url: "kelas_simpan.php",
					data: "aksi=3",
					success: function(data) {
						toastr.success(data);
					}
				})
			}
		})
	})
	$("#btnRefresh").click(function() {
		window.location.reload();
	})
</script>