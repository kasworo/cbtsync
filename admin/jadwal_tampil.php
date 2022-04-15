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
							$sqts = "SELECT u.idujian, u.nmujian, ts.nmtes FROM tbujian u INNER JOIN tbtes ts USING(idtes) INNER JOIN tbthpel t USING(idthpel) WHERE t.aktif='1' AND status='1'";
							$qts = vquery($sqts);
							foreach ($qts as $ts) :
							?>
								<option value="<?php echo $ts['idujian']; ?>"><?php echo $ts['nmujian'] . ' - ' . $ts['nmtes']; ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-5">Untuk Ujian</label>
						<input type="hidden" class="form-control form-control-sm col-sm-6" id="idjdw">
						<select class="form-control form-control-sm col-sm-6" id="utmtes" name="utmtes" autocomplete="off">
							<option value="">..Pilih..</option>
							<option value="0">Utama</option>
							<option value="1">Susulan</option>
						</select>
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
						<label class="col-sm-5">Dimulai Pukul</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="awaltes" name="awaltes">
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
				<button class="btn btn-primary btn-sm col-4" id="savejdw">
					<i class="fas fa-save"></i> Simpan
				</button>
				<button class="btn btn-danger btn-sm col-4" data-dismiss="modal">
					<i class="fas fa-power-off"></i> Tutup
				</button>
			</div>
		</div>
	</div>
</div>
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
		$sqsk = "SELECT j.* FROM tbjadwal j INNER JOIN tbujian u USING(idujian) WHERE u.status='1' ORDER BY idjadwal";
		$ceksk = cquery($sqsk);
		if ($ceksk > 0) :
		?>
			<div class="form-group mb-2">
				<div class="table-responsive">
					<table width="100%" class="table-sm table-bordered table-striped" id="tbjadwal">
						<thead>
							<th style="text-align:center;width:2.5%">No.</th>
							<th style="text-align:center;">Mata Ujian</th>
							<th style="text-align:center;width:17.5%">Tanggal Tes</th>
							<th style="text-align:center;width:17.5%">Durasi Tes</th>
							<th style="text-align:center;width:17.5%">Dimulai Pukul</th>
							<th style="text-align:center;width:15%">Aksi</th>
						</thead>
						<tbody>
							<?php
							$i = 0;
							$qsk = vquery($sqsk);
							foreach ($qsk as $sk) :
								$i++;
							?>
								<tr>
									<td style="text-align:center"><?php echo $i, '.'; ?></td>
									<td style="text-align:left"><?php echo $sk['matauji']; ?></td>
									<td style="text-align:center"><?php echo indonesian_date($sk['tglujian']); ?></td>
									<td style="text-align:center"><?php echo $sk['durasi'] . ' menit'; ?></td>
									<td style="text-align:center"><?php echo substr($sk['mulai'], 0, 5) . ' WIB'; ?></td>
									<td style="text-align:center">
										<a href="#myJadwal" data-toggle="modal" data-id="<?php echo $sk['idjadwal']; ?>" class="btn btn-xs btn-info editJadwal">
											<i class="fas fa-edit"></i>&nbsp;Edit
										</a>
										<button data-id="<?php echo $sk['kdjadwal']; ?>" class="btn btn-xs btn-danger btnHapus">
											<i class="fas fa-trash-alt"></i>&nbsp;Hapus
										</button>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php else : ?>
			<div class="alert alert-danger">
				<p>Silahkan Tambahkan Jadwal Dulu!</p>
			</div>
		<?php endif ?>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(function() {
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

		$('#tgltes').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});

		$(".editJadwal").click(function() {
			$(".modal-title").html("Ubah Jadwal Ujian");
			$("#simpan").html("<i class='fas fa-save'></i> Update");
			let id = $(this).data('id');
			$.ajax({
				url: 'jadwal_edit.php',
				type: 'post',
				dataType: 'json',
				data: 'id=' + id,
				success: function(data) {
					$("#idjdw").val(data.idjadwal);
					$("#idjtes").val(data.idujian);
					$("#utmtes").val(data.susulan);
					$("#matauji").val(data.matauji);
					$("#tgltes").val(data.tglujian);
					$("#drstes").val(data.durasi);
					$("#awaltes").val(data.mulai);
					$("#lambat").val(data.lambat);
				}
			})
		})
		$("#savejdw").click(function() {
			let id = $("#idjdw").val();;
			let idtes = $("#idjtes").val();
			let utm = $("#utmtes").val();
			let mtuji = $("#matauji").val();
			let tgl = $("#tgltes").val();
			let wktu = $("#drstes").val();
			let mulai = $("#awaltes").val();
			let lmb = $("#lambat").val();
			if (idtes == '') {
				toastr.error("Pilih Jenis Tes Terlebih Dahulu....");
			} else if (utm == '') {
				toastr.error("Jenis Ujian Harus Diisi!");
			} else if (mtuji == '') {
				toastr.error("Mata Pelajaran Tidak Boleh Kosong..");
			} else if (tgl == '') {
				toastr.error("Tanggal Ujian Harus Diisi");
			} else if (wktu == '') {
				toastr.error("Durasi Ujian Harus Diisi");
			} else {
				let data = new FormData();
				data.append('id', id);
				data.append('idtes', idtes);
				data.append('utm', utm);
				data.append('mtuji', mtuji);
				data.append('tgl', tgl);
				data.append('lama', wktu);
				data.append('mulai', mulai);
				data.append('lmb', lmb);
				data.append('aksi', 'simpan');
				$.ajax({
					url: 'jadwal_simpan.php',
					type: 'POST',
					data: data,
					processData: false,
					contentType: false,
					cache: false,
					timeout: 8000,
					success: function(respons) {
						if (respons == 1) {
							toastr.success("Tambah Jadwal Ujian Berhasil!");
						} else if (respons == 2) {
							toastr.info("Update Jadwal Ujian Berhasil!");
						} else {
							toastr.error("Tambah Atau Update Jadwal Ujian Gagal!");
						}
					}
				})
			}
		})

		$(".btnHapus").click(function() {
			let id = $(this).data('id');
			Swal.fire({
				title: 'Anda Yakin?',
				text: "Menghapus Jadwal Ini",
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
						url: "jadwal_simpan.php",
						data: "aksi=3&id=" + id,
						success: function(data) {
							toastr.success(data);
						}
					})
				}
			})
		})

		$("#btnhapus").click(function() {
			Swal.fire({
				title: 'Anda Yakin?',
				text: "Menghapus Semua Jadwal Ujian",
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
						url: "jadwal_simpan.php",
						data: "aksi=4",
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
		$("#myJadwal").on('hidden.bs.modal', function() {
			window.location.reload();
		})
	})
</script>