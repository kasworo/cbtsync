<script type="text/javascript" src="js/pilihbank.js"></script>
<div class="modal fade" id="myAddBank" aria-modal="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Bank Soal</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-sm-12">
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Penilaian</label>
						<select class="form-control form-control-sm col-sm-6" id="idjtes" name="idjtes">
							<option value="">..Pilih..</option>
							<?php
							$sqtes = "SELECT u.idujian, u.nmujian, ts.nmtes FROM tbujian u INNER JOIN tbtes ts USING(idtes) INNER JOIN tbthpel t USING(idthpel) WHERE t.aktif='1' AND u.status='1'";
							$qtes = vquery($sqtes);
							foreach ($qtes as $ts) :
							?>
								<option value="<?php echo $ts['idujian']; ?>"><?php echo $ts['nmujian'] . ' - ' . $ts['nmtes']; ?></option>
							<?php endforeach ?>
						</select>
					</div>
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
						<label class="col-sm-4 offset-sm-1">Mata Pelajaran</label>
						<select class="form-control form-control-sm col-sm-6" id="idmapel" name="idmapel" onchange="pilmapel(this.value)">
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
							$qgr = viewdata('tbgtk', array('deleted' => '0'));
							foreach ($qgr as $us) :
							?>
								<option value="<?php echo $us['idgtk']; ?>"><?php echo $us['nama']; ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Paket Soal</label>
						<input class="form-control form-control-sm col-sm-6" id="idbank" name="idbank" readonly>
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
			<h4 class="card-title">Data Bank Soal</h4>
			<div class="card-tools">
				<button class="btn btn-success btn-sm" id="btnTambah" data-toggle="modal" data-target="#myAddBank">
					<i class="fas fa-plus-circle"></i>&nbsp;Tambah
				</button>
				<button id="hapusall" class="btn btn-danger btn-sm">
					<i class="fas fa-trash-alt"></i>&nbsp;Hapus
				</button>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tb_banksoal" class="table table-bordered table-striped table-sm">
					<thead>
						<tr>
							<th style="text-align: center;width:2.5%">No.</th>
							<th style="text-align: center;width:12.5%">Paket Soal</th>
							<th style="text-align: center;width:40%">Mata Pelajaran</th>
							<th style="text-align: center;width:27.5%">Guru Bidang Studi</th>
							<th style="text-align: center;">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($level == '1') {
							$sqkm = "SELECT bs.*, mp.nmmapel, g.nama, uj.nmujian FROM tbbanksoal bs INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbujian uj USING(idujian) INNER JOIN tbtes ts USING(idtes) INNER JOIN tbpengampu pg USING(idmapel) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbgtk g ON (pg.idgtk=g.idgtk AND bs.idgtk=g.idgtk AND bs.idgtk=pg.idgtk) INNER JOIN tbuser us USING(username) INNER JOIN tbthpel t ON (rb.idthpel=t.idthpel AND uj.idthpel=t.idthpel)WHERE t.aktif='1' AND uj.status='1' AND bs.deleted='0' GROUP BY bs.idbank ORDER BY bs.idbank ASC";
						}
						if ($level == '2') {
							$sqkm = "SELECT bs.*, mp.nmmapel, g.nama, uj.nmujian FROM tbbanksoal bs INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbujian uj USING(idujian) INNER JOIN tbtes ts USING(idtes) INNER JOIN tbpengampu pg USING(idmapel) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbgtk g ON (pg.idgtk=g.idgtk AND bs.idgtk=g.idgtk AND bs.idgtk=pg.idgtk) INNER JOIN tbuser us USING(username) INNER JOIN tbthpel t ON (rb.idthpel=t.idthpel AND uj.idthpel=t.idthpel) WHERE us.username='$_COOKIE[id]' AND t.aktif='1' AND uj.status='1' AND bs.deleted='0' GROUP BY bs.idbank ORDER BY bs.idbank ASC";
						}
						$no = 0;
						$qkm = vquery($sqkm);
						foreach ($qkm as $m) :
							$no++;
							$keyb = array('idbank' => $m['idbank']);
							$cekset = cekdata('tbsetingujian', $keyb);
							if ($cekset > 0) {
								$status = 'sudah';
								$bdg = 'badge-danger';
							} else {
								$status = 'belum';
								$bdg = 'badge-success';
							}
						?>
							<tr>
								<td style="text-align:center"><?php echo $no . '.'; ?></td>
								<td>
									<?php echo $m['nmbank']; ?>
								</td>
								<td>
									<?php echo $m['nmmapel']; ?>
									<span class="float-right badge <?php echo $bdg; ?>"><?php echo $status; ?></span>
								</td>
								<td><?php echo $m['nama']; ?></td>
								<td style="text-align: center">
									<button data-id="<?php echo $m['idbank']; ?>" class="btn btn-xs btn-secondary btnIsi">
										<i class="fas fa-edit"></i>&nbsp;Soal
									</button>
									<button data-id="<?php echo $m['idbank']; ?>" class="btn btn-xs btn-danger btnHapus">
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
		$("#myAddBank").on('hidden.bs.modal', function() {
			window.location.reload();
		})
	})
	$(function() {
		$('#tb_banksoal').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": false,
			"responsive": true,
		});
	})
	$("#idguru").change(function() {
		let kls = $("#idkelas").val();
		let map = $("#idmapel").val();
		let idguru = $(this).val();
		if (idguru == '') {
			toastr.error("Guru Bidang Studi Tidak Boleh Kosong", "Maaf!");
		} else {
			$.ajax({
				url: "banksoal_getid.php",
				data: "kls=" + kls + "&map=" + map,
				cache: false,
				success: function(msg) {
					$("#idbank").val(msg);
				}
			})
		}
	})

	$("#simpan").click(function() {
		let tes = $("#idjtes").val();
		let kls = $("#idkelas").val();
		let map = $("#idmapel").val();
		let usr = $("#idguru").val();
		let bnk = $("#idbank").val();
		if (tes == '') {
			toastr.error('Tes Tidak Boleh Kosong!!', 'Mohon Maaf!');
			$("#idjtes").focus();
		} else if (kls == '') {
			toastr.error('Kelas Tidak Boleh Kosong!!', 'Mohon Maaf!');
			$("#idkelas").focus();
		} else if (map == '') {
			toastr.error('Mata Pelajaran Tidak Boleh Kosong!!', 'Mohon Maaf!');
			$("#idmapel").focus();
		} else if (usr == '') {
			toastr.error('Guru Bidang Studi Tidak Boleh Kosong!!', 'Mohon Maaf!');
			$("#idguru").focus();
		} else {
			let data = new FormData();
			data.append('tes', tes);
			data.append('kls', kls);
			data.append('map', map);
			data.append('gtk', usr);
			data.append('bnk', bnk);
			data.append('aksi', 'simpan');
			$.ajax({
				url: "banksoal_simpan.php",
				type: 'POST',
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 8000,
				success: function(respons) {
					if (respons == 1) {
						$(function() {
							toastr.success('Bank Soal Berhasil Ditambah!!', 'Terima Kasih', {
								timeOut: 3000,
								fadeOut: 3000,
								onHidden: function() {
									$("#myAddBank").modal('hide');
								}
							})
						})
					}
					if (respons == 2) {
						$(function() {
							toastr.success('Bank Soal Sudah Pernah Dibuat!', 'Informasi', {
								timeOut: 3000,
								fadeOut: 3000,
								onHidden: function() {
									$("#myAddBank").modal('hide');
								}
							})
						})
					}
					if (respons == 0) {
						$(function() {
							toastr.error('Gagal Ditambahkan atau Diupdate!!', 'Mohon Maaf', {
								timeOut: 3000,
								fadeOut: 3000,
								onHidden: function() {
									$("#myAddBank").modal('hide');
								}
							})
						})
					}
				}
			})
		}


	})

	$("#btnAktivasi").click(function() {
		let idb = $("#idsoal").val();
		let rmb = $("#rmbuji").val();
		let jdw = $("#jduji").val();
		let soal = $("#soal").val();
		let mode = $("#mode").val();
		let opsi = $("#opsi").val();
		$.ajax({
			url: "banksoal_simpan.php",
			type: 'POST',
			data: "aksi=2&id=" + idb + "&rmb=" + rmb + "&jdw=" + jdw + "&soal=" + soal + "&mode=" + mode + "&opsi=" + opsi,
			success: function(data) {
				toastr.success(data);
			}
		})
	})
	$(".btnIsi").click(function() {
		let id = $(this).data('id');
		window.location.href = 'index.php?p=isisoal&id=' + id;
	})

	$(".btnUji").click(function() {
		let id = $(this).data('id');
		$.ajax({
			url: 'banksoal_aktif.php',
			type: 'post',
			data: 'id=' + id,
			success: function(data) {
				$(".fetched-data").html(data)
			}
		})
	})

	$(".btnHapus").click(function() {
		let id = $(this).data('id');
		Swal.fire({
			title: 'Anda Yakin',
			text: "Menghapus Bank Soal ini?",
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
					url: "banksoal_simpan.php",
					data: "aksi=4&id=" + id,
					success: function(data) {
						toastr.info(data, 'Terimakasih', {
							timeOut: 1000,
							fadeOut: 1000,
							onHidden: function() {}
						});
					}
				})
			}
		})
	})
	$("#hapusall").click(function() {
		Swal.fire({
			title: 'Anda Yakin?',
			text: "Menghapus Bank Soal",
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
					url: "banksoal_simpan.php",
					data: "aksi=5&id=" + iduji,
					success: function(data) {
						toastr.info(data, 'Terimakasih', {
							timeOut: 1000,
							fadeOut: 1000,
							onHidden: function() {}
						});
					}
				})
			}
		})
	})
	$("#btnRefresh").click(function() {
		window.location.reload();
	})
</script>