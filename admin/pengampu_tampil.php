<script type="text/javascript" src="js/pilihampu.js"></script>
<div class="modal fade" id="myAddAmpu" aria-modal="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Pengampu</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-sm-12">
					<input type="hidden" class="form-control form-control-sm col-sm-6" id="idampu" name="idampu">
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Kelas</label>
						<select class="form-control form-control-sm col-sm-6" id="idkelas" name="idkelas" onchange="pilkelas(this.value)">
							<option value="">..Pilih..</option>
							<?php
							$qkls = tampilKelas();
							foreach ($qkls as $kl) :
							?>
								<option value="<?php echo $kl['idkelas']; ?>"><?php echo $kl['nmkelas']; ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Rombel</label>
						<select class="form-control form-control-sm col-sm-6" id="idrombel" name="idrombel">
							<option value="">..Pilih..</option>
							<?php
							$sqrb = "SELECT r.* FROM tbrombel r INNER JOIN tbthpel t USING(idthpel) WHERE t.aktif='1'";
							$qrb = vquery($sqrb);
							foreach ($qrb as $rb) :
							?>
								<option value="<?php echo $rb['idrombel']; ?>"><?php echo $rb['nmrombel']; ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Mata Pelajaran</label>
						<select class="form-control form-control-sm col-sm-6" id="idmapel" name="idmapel">
							<option value="">..Pilih..</option>
							<?php
							$qmp = viewdata('tbmapel');
							foreach ($qmp as $mp) :
							?>
								<option value="<?php echo $mp['idmapel']; ?>"><?php echo $mp['nmmapel']; ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Guru Bidang Studi</label>
						<select class="form-control form-control-sm col-sm-6" id="idguru" name="idguru">
							<option value="">..Pilih..</option>
							<?php
							$qus = viewdata('tbgtk', array('deleted' => '0'));
							foreach ($qus as $us) :
							?>
								<option value="<?php echo $us['idgtk']; ?>"><?php echo $us['nama']; ?></option>
							<?php endforeach ?>
						</select>
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
<div class="modal fade" id="mySalinAmpu" aria-modal="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Salin Pengampu</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-sm-12">
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Pilih Kelas</label>
						<select class="form-control form-control-sm col-sm-6" id="idklsasl" name="idklsasl" onchange="getkelas(this.value)">
							<option value="">..Pilih..</option>
							<?php
							$qkla = $conn->query("SELECT idkelas,nmkelas FROM tbkelas INNER JOIN tbskul USING (idjenjang)");
							while ($kla = $qkla->fetch_array()) :
							?>
								<option value="<?php echo $kla['idkelas']; ?>"><?php echo $kla['nmkelas']; ?></option>
							<?php endwhile ?>
						</select>
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Rombel Asal</label>
						<select class="form-control form-control-sm col-sm-6" id="idrombasl" name="idrombasl">
							<option value="">..Pilih..</option>
						</select>
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Rombel Tujuan</label>
						<select class="form-control form-control-sm col-sm-6" id="idrombtjn" name="idrombtjn">
							<option value="">..Pilih..</option>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-primary btn-sm col-4" id="salin">
					<i class="fas fa-copy"></i> Salin
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
			<h4 class="card-title">Data Pengampu</h4>
			<div class="card-tools">
				<button class="btn btn-success btn-sm" id="btnTambah" data-toggle="modal" data-target="#myAddAmpu">
					<i class="fas fa-plus-circle"></i>&nbsp;Tambah
				</button>
				<button class="btn btn-default btn-sm" id="btnSalin" data-toggle="modal" data-target="#mySalinAmpu">
					<i class="fas fa-copy"></i>&nbsp;Salin
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
				<table id="tb_pengampu" class="table table-bordered table-striped table-sm">
					<thead>
						<tr>
							<th style="text-align: center;width:2.5%">No.</th>
							<th style="text-align: center;width:10.5%">Rombel</th>
							<th style="text-align: center;width:35%">Mata Pelajaran</th>
							<th style="text-align: center">Guru Bidang Studi</th>
							<th style="text-align: center;width:12.5%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$sqk = "SELECT a.idampu, m.nmmapel, r.nmrombel, g.nama FROM tbpengampu a LEFT JOIN tbrombel r USING(idrombel) LEFT JOIN tbthpel t USING(idthpel) LEFT JOIN tbmapel m USING(idmapel) LEFT JOIN tbgtk g ON a.idgtk=g.idgtk WHERE t.aktif='1' ORDER BY idrombel, idmapel";
						$qk = vquery($sqk);
						$no = 0;
						foreach ($qk as $m) :
							$no++;
						?>
							<tr>
								<td style="text-align:center"><?php echo $no . '.'; ?></td>
								<td style="text-align:center"><?php echo $m['nmrombel']; ?></td>
								<td><?php echo $m['nmmapel']; ?></td>
								<td><?php echo $m['nama']; ?></td>
								<td style="text-align: center">
									<a href="#myAddAmpu" data-toggle="modal" data-id="<?php echo $m['idampu']; ?>" class="btn btn-xs btn-success btnUpdate">
										<i class="fas fa-edit"></i>&nbsp;Edit
									</a>
									<button data-id="<?php echo $m['idampu']; ?>" class="btn btn-xs btn-danger btnHapus">
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
	$(document).ready(function() {
		$("#myAddAmpu").on('hidden.bs.modal', function() {
			window.location.reload();
		})
		$("#mySalinAmpu").on('hidden.bs.modal', function() {
			window.location.reload();
		})
	})
</script>
<script type="text/javascript">
	$(function() {
		$('#tb_pengampu').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": false,
			"responsive": true,
		});
	})

	$("#btnTambah").click(function() {
		$(".modal-title").html("Tambah Data Pengampu");
		$("#simpan").html("<i class='fas fa-save'></i> Simpan");
	})

	$("#simpan").click(function() {
		let ida = $("#idampu").val();
		let kelas = $("#idkelas").val();
		let rombel = $("#idrombel").val();
		let mapel = $("#idmapel").val();
		let guru = $("#idguru").val();
		if (kelas == '') {
			toastr.error("Kelas Tidak Boleh Kosong", "Maaf!");
		} else if (rombel == '') {
			toastr.error("Rombel Tidak Boleh Kosong", "Maaf!");
		} else if (mapel == '') {
			toastr.error("Mata Pelajaran Tidak Boleh Kosong", "Maaf!");
		} else if (guru == '') {
			toastr.error("Guru Tidak Boleh Kosong", "Maaf!");
		} else {
			let data = new FormData();
			data.append('id', ida);
			data.append('mp', mapel);
			data.append('rmb', rombel);
			data.append('gtk', guru);
			data.append('aksi', 'simpan')
			$.ajax({
				url: "pengampu_simpan.php",
				type: 'POST',
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 8000,
				success: function(respons) {
					if (respons == 1) {
						$(function() {
							toastr.success('Simpan Pengampu Berhasil!!', 'Terima Kasih', {
								timeOut: 3000,
								fadeOut: 3000,
								onHidden: function() {
									$("#myAddAmpu").modal('hide');
								}
							});
						});
					}
					if (respons == 2) {
						$(function() {
							toastr.info('Update Pengampu Berhasil!!', 'Informasi', {
								timeOut: 3000,
								fadeOut: 3000,
								onHidden: function() {
									$("#myAddAmpu").modal('hide');
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
									$("#myAddAmpu").modal('hide');
								}
							});
						});
					}
				}
			})
		}

	})
	$("#salin").click(function() {
		let kls = $("#idklsasl").val();
		let idrasl = $("#idrombasl").val();
		let idrtjn = $("#idrombtjn").val();
		$.ajax({
			url: "pengampu_simpan.php",
			type: 'POST',
			data: "aksi=salin&idra=" + idrasl + "&idrt=" + idrtjn,
			success: function(data) {
				toastr.success(data);
			}
		})
	})
	$(".btnUpdate").click(function() {
		$(".modal-title").html("Ubah Data Pengampu");
		$("#simpan").html("<i class='fas fa-save'></i> Update");
		let id = $(this).data('id');
		$.ajax({
			url: 'pengampu_edit.php',
			type: 'post',
			dataType: 'json',
			data: 'id=' + id,
			success: function(data) {
				$("#idampu").val(data.idampu);
				$("#idkelas").val(data.idkelas);
				$("#idrombel").val(data.idrombel);
				$("#idmapel").val(data.idmapel);
				$("#idguru").val(data.idgtk);
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
					url: "pengampu_simpan.php",
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
			text: "Menghapus Seluruh Pembelajaran",
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
					url: "pengampu_simpan.php",
					data: "aksi=kosong",
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