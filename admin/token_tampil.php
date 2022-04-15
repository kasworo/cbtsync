<div class="callout callout-danger">
	<p><strong>Petunjuk:</strong></p>
	<p>
		Pastikan <strong><em>Status Tes</em></strong> sudah <strong style="color:red;">Aktif</strong>, pilih jadwal pada pilihan <strong><em>Daftar Tes</em></strong>.<br />
		Jangan lupa pilih<strong><em> Kelompok Peserta</em></strong> dan pastikan token muncul di bagian <strong><em>Token Aktif</em></strong>, klik tombol <strong><em>Simpan</em></strong>.
		<br />Token akan tergenerate secara otomatis dalam rentang waktu <strong> 15 (limabelas)</strong> menit, klik tombol <strong><em>Refresh</em></strong> jika token terbaru tidak muncul.
	</p>
</div>
<div class="card card-secondary card-outline">
	<div class="card-header">
		<h4 class="card-title">Rilis Token Ujian</h4>
	</div>
	<div class="card-body">
		<div class="col-sm-12">
			<div class="form-group row mb-2">
				<div class="col-sm-3 offset-sm-1">
					<label>Status Tes</label>
				</div>
				<div class="col-sm-4">
					<label id="pesan"></label>
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-sm-3 offset-sm-1">
					<label>Daftar Tes</label>
				</div>
				<div class="col-sm-4">
					<select class="form-control form-control-sm" id="jadwal">
						<option value="">..Pilih..</option>
						<?php
						$skrg = date('Y-m-d');
						$sqljadwal = "SELECT*FROM tbjadwal j INNER JOIN  tbujian u USING(idujian) WHERE u.status='1' AND j.tglujian='$skrg'";
						$qjdw = vquery($sqljadwal);
						foreach ($qjdw as $jd) :
						?>

							<option value="<?php echo $jd['idjadwal']; ?>"><?php echo indonesian_date($jd['tglujian']) . ' - ' . $jd['kdjadwal']; ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-sm-3 offset-sm-1">
					<label>Kelompok Tes</label>
				</div>
				<div class="col-sm-4">
					<select class="form-control form-control-sm" id="sesi">
						<option value="">..Pilih ..</option>
						<?php
						$sqses = "SELECT idsesi FROM tbsesiujian GROUP BY idsesi";
						$qses = vquery($sqses);
						foreach ($qses as $ses) :
						?>
							<option value="<?php echo $ses['idsesi']; ?>"><?php echo "Sesi " . $ses['idsesi']; ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-sm-3 offset-sm-1">
					<label>Token Aktif</label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control form-control-sm text-success" id="token" disabled />
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-sm-3 offset-sm-1">
					<label>Token Tampil</label>
				</div>
				<div class="col-sm-4">
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" id="tmpl1" name="tampil" value="1">
						<label class="form-check-label">Tampil</label>
						<input class="form-check-input ml-5" type="radio" id="tmpl2" name="tampil" value="0">
						<label class="form-check-label">Tidak Tampil</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer">
		<button class="btn btn-primary btn-sm mb-2 ml-2" id="simpan">
			<i class="fas fa-fw fa-save"></i> Simpan
		</button>
		<button class="btn btn-secondary btn-sm mb-2 ml-2" id="refresh">
			<i class="fas fa-fw fa-sync-alt"></i> Refresh
		</button>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		let data = new FormData()
		data.append('aksi', 'tampil')
		$.ajax({
			url: "token_isi.php",
			type: "POST",
			data: data,
			dataType: 'json',
			processData: false,
			contentType: true,
			cache: false,
			success: function(respons) {
				if (respons.aktif == '1') {
					$("#pesan").html(respons.status)
					$("#jadwal").val(respons.jadwal)
					$("#sesi").val(respons.sesi)
					$("#token").val(respons.pesan)
					$("#jadwal").prop('disabled', false)
					$("#sesi").prop('disabled', false)
					if (respons.tampil == '1') {
						$("#tmpl1").prop('checked', true)
						$("#tmpl2").prop('checked', false)
					} else if (respons.tampil == '0') {
						$("#tmpl1").prop('checked', false)
						$("#tmpl2").prop('checked', true)
					} else {
						$("#tmpl1").prop('checked', false)
						$("#tmpl2").prop('checked', false)
					}
				} else {
					$("#pesan").html(respons.status)
					$("#jadwal").val(respons.jadwal)
					$("#sesi").val(respons.sesi)
					$("#token").val(respons.pesan)
					$("#jadwal").prop('disabled', true)
					$("#sesi").prop('disabled', true)
					$("#tmpl1").prop('disabled', true)
					$("#tmpl2").prop('disabled', true)
				}
			}
		})
	})

	$("#sesi").change(function() {
		let jdw = $("#jadwal").val()
		let sesi = $(this).val()
		if (jdw == '' || jdw == null) {
			toastr.error("Pilih Jadwal Dulu!", "Maaf")
		} else if (sesi == '' || sesi == null) {
			toastr.error("Pilih Sesi Dulu!", "Maaf")
		} else {
			let data = new FormData()
			data.append('jdw', jdw)
			data.append('sesi', sesi)
			data.append('aksi', 'isi')
			$.ajax({
				url: "token_getisi.php",
				type: "POST",
				data: data,
				dataType: 'json',
				processData: false,
				contentType: false,
				cache: false,
				timeout: 8000,
				success: function(respons) {
					$("#token").val(respons.pesan)
				}
			})
		}
	})
	$("#simpan").click(function() {
		let vtoken = $("#token").val();
		let token = vtoken.substr(0, 6);
		let tampil = $('input:radio[name = tampil]:checked').val()
		if (vtoken == '') {
			toastr.error("Ujian Tidak Bisa Diaktifkan!", "Mohon Maaf")
		} else if (tampil === undefined) {
			toastr.error("Ujian Tidak Bisa Diaktifkan!", "Mohon Maaf")
		} else {

			let data = new FormData();
			data.append('jdw', $("#jadwal").val())
			data.append('sesi', $("#sesi").val())
			data.append('tampil', tampil)
			data.append('token', token)
			$.ajax({
				url: "token_simpan.php",
				type: "POST",
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 8000,
				success: function(respons) {
					if (respons == 1) {
						$(function() {
							toastr.success('Simpan Token Ujian Berhasil!!', 'Terima Kasih', {
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
							toastr.info('Update Token Ujian Berhasil!!', 'Informasi', {
								timeOut: 3000,
								fadeOut: 3000,
								onHidden: function() {
									window.location.reload()
								}
							})
						})
					}
					if (respons == 0) {
						$(function() {
							toastr.error('Gagal Update atau Simpan Data!!', 'Mohon Maaf', {
								timeOut: 3000,
								fadeOut: 3000,
								onHidden: function() {
									window.location.reload()
								}
							})
						})
					}
				}
			})
		}
	})
	$("#refresh").click(function() {
		// let tampil = $('input:radio[name = tampil]:checked').val()
		// alert(tampil);
		window.location.reload();
	})
</script>