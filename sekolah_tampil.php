<?php
$sk = viewdata('tbskul')[0];
if ($sk['logoskul'] == '' || $sk['logoskul'] == null) {
	$logoskul = '../assets/img/avatar.gif';
} else {
	if (file_exists('../images/' . $sk['logoskul'])) {
		$logoskul = '../images/' . $sk['logoskul'];
	} else {
		$logoskul = '../assets/img/tutwuri.png';
	}
}

if ($sk['logoskpd'] == '' || $sk['logoskpd'] == null) {
	$logoskpd = '../assets/img/tutwuri.png';
} else {
	if (file_exists('../images/' . $sk['logoskpd'])) {
		$logoskpd = '../images/' . $sk['logoskpd'];
	} else {
		$logoskpd = '../assets/img/tutwuri.png';
	}
}
if ($level == '1') :
?>
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Data Satuan Pendidikan</h4>
			<div class="card-tools">
				<button class="btn btn-primary btn-sm" id="simpan">
					<i class="fas fa-fw fa-save"></i>
					<span>&nbsp;Simpan</span>
				</button>
				<a href="?p=dashboard" class="btn btn-danger btn-sm ">
					<i class="fas fa-fw fa-power-off"></i>
					<span>&nbsp;Tutup</span>
				</a>
			</div>
		</div>
		<div class="card-body">
			<div class="form-group row mb-2">
				<div class="col-sm-4">
					<div class="row">
						<div class="col-sm-12">
							<div class="card card-gray-dark">
								<div class="card-header">
									<h4 class="card-title"><b>Logo Sekolah</b></h4>
								</div>
								<div class="card-body">
									<div id="logoskul" style="text-align:center">
										<img class="img img-responsive img-rounded" src="<?php echo $logoskul; ?>" height="180px" />
										<span id="logoskul_status"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="card card-secondary">
								<div class="card-header">
									<h4 class="card-title"><b>Logo SKPD / Yayasan</b></h4>
								</div>
								<div class="card-body">
									<div id="logoskpd" style="text-align:center">
										<img class="img img-responsive img-rounded" src="<?php echo $logoskpd; ?>" height="180px" />
										<span id="logoskpd_status"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Kode Satuan Pendidikan</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="kode_skul" name="kode_skul" placeholder="Kode Satuan Pendidikan" value="<?php echo $sk['kdskul']; ?>">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Nama Satuan Pendidikan</label>
						<input type="text" disabled class="form-control form-control-sm col-sm-6" id="nama_skul" name="nama_skul" placeholder="Nama Satuan Pendidikan" value="<?php echo $sk['nmskul']; ?>">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">NPSN</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="npsn_skul" name="npsn_skul" placeholder="N P S N" value="<?php echo $sk['npsn']; ?>">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">No. Statistik Sekolah</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="nss_skul" name="nss_skul" placeholder="Nomor Statistik Sekolah" value="<?php echo $sk['nss']; ?>">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Nama SKPD</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="skpd_skul" name="skpd_skul" placeholder="Nama SKPD atau Yayasan" value="<?php echo $sk['nmskpd']; ?>">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Alamat</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="almt_skul" name="almt_skul" placeholder="Alamat" value="<?php echo $sk['alamat']; ?>">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Desa</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="desa_skul" name="desa_skul" placeholder="Desa" value="<?php echo $sk['desa']; ?>">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Kecamatan</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="kec_skul" name="kec_skul" placeholder="Kecamatan" value="<?php echo $sk['kec']; ?>">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Kabupaten</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="kab_skul" name="kab_skul" placeholder="Kabupaten" value="<?php echo $sk['kab']; ?>">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Provinsi</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="prov_skul" name="prov_skul" placeholder="Provinsi" value="<?php echo $sk['prov']; ?>">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Kode Pos</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="kpos_skul" name="kpos_skul" placeholder="Kode Pos" value="<?php echo $sk['kdpos']; ?>">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Website</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="web_skul" name="web_skul" placeholder="Website Satuan Pendidikan" value="<?php echo $sk['website']; ?>">
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">E-mail</label>
						<input type="text" class="form-control form-control-sm col-sm-6" id="imel_skul" name="imel_skul" placeholder="Email Satuan Pendidikan" value="<?php echo $sk['email']; ?>">
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(function() {
			var btnUpload = $('#logoskul');
			var status = $('#logoskul_status');
			new AjaxUpload(btnUpload, {
				action: 'sekolah_logo.php',
				name: 'uploadlogo',
				onSubmit: function(filefoto, ext) {
					if (!(ext && /^(jpg|png)$/.test(ext))) {
						toastr.error('Hanya Mendukung File *.jpg atau *.png Saja Bro!!');
						return false;
					}
					status.text('Upload Sedang Berlangsung...');
				},
				onComplete: function(file, response) {
					status.text('');
					if (response === "success") {
						$('#logoskul').html('<img src="../images/' + file + '" height="180px" alt="" />').addClass('success');
						window.location.reload();
					} else {
						toastr.error('Upload Gagal Bro!!');
					}
				}
			});
		});
		$(function() {
			var btnUpload = $('#logoskpd');
			var status = $('#logoskpd_status');
			new AjaxUpload(btnUpload, {
				action: 'sekolah_skpd.php',
				name: 'uploadskpd',
				onSubmit: function(file, ext) {
					if (!(ext && /^(jpg|png)$/.test(ext))) {
						toastr.error('Hanya Mendukung File *.jpg atau *.png Saja Bro!!');
						return false;
					}
					status.text('Upload Sedang Berlangsung...');
				},
				onComplete: function(file, response) {
					status.text('');
					if (response === "success") {
						$('#logoskpd').html('<img src="../images/' + file + '" height="180px" alt="" />').addClass('success');
						//document.getElementById("files").placeholder = file;
						window.location.reload();
					} else {
						toastr.error('Upload Gagal Bro!!');
					}
				}
			});
		});
		$(document).ready(function() {
			$("#simpan").click(function() {
				var kode = $("#kode_skul").val();
				var nama = $("#nama_skul").val();
				var npsn = $("#npsn_skul").val();
				var noss = $("#nss_skul").val();
				var skpd = $("#skpd_skul").val();
				var almt = $("#almt_skul").val();
				var desa = $("#desa_skul").val();
				var kec = $("#kec_skul").val();
				var kab = $("#kab_skul").val();
				var prov = $("#prov_skul").val();
				var kpos = $("#kpos_skul").val();
				var webs = $("#web_skul").val();
				var imel = $("#imel_skul").val();
				$.ajax({
					type: "POST",
					url: "sekolah_simpan.php",
					data: "aksi=simpan&kode=" + kode + "&nama=" + nama + "&npsn=" + npsn + "&noss=" + noss + "&skpd=" + skpd + "&almt=" + almt + "&desa=" + desa + "&kec=" + kec + "&kab=" + kab + "&prov=" + prov + "&kpos=" + kpos + "&webs=" + webs + "&imel=" + imel,
					success: function(data) {
						toastr.success(data);
						return false;
						window.location.reload();
					}
				})
			})
		})
	</script>
<?php else : ?>
	<div class="col-sm-12">
		<div class="card card-secondary card-outline">
			<div class="card-header">
				<h3 class="card-title"><strong>Data Satuan Pendidikan</strong></h3>
			</div>
			<div class="card-body">
				<div class="form-group row mb-2">
					<div class="col-sm-4">
						<div class="row">
							<div class="col-sm-12">
								<div class="card card-gray-dark">
									<div class="card-header">
										<h4 class="card-title"><b>Logo Sekolah</b></h4>
									</div>
									<div class="card-body">
										<div id="logoskul" style="text-align:center">
											<img class="img img-responsive img-rounded" src="<?php echo $logoskul; ?>" height="180px" />
											<span id="logoskul_status"></span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="card card-secondary">
									<div class="card-header">
										<h4 class="card-title"><b>Logo SKPD / Yayasan</b></h4>
									</div>
									<div class="card-body">
										<div id="logoskpd" style="text-align:center">
											<img class="img img-responsive img-rounded" src="<?php echo $logoskpd; ?>" height="180px" />
											<span id="logoskpd_status"></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-8">
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Kode Satuan Pendidikan</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="kode_skul" name="kode_skul" placeholder="Kode Satuan Pendidikan" value="<?php echo $sk['kdskul']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Nama Satuan Pendidikan</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="nama_skul" name="nama_skul" placeholder="Nama Satuan Pendidikan" value="<?php echo $sk['nmskul']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">NPSN</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="npsn_skul" name="npsn_skul" placeholder="N P S N" value="<?php echo $sk['npsn']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">No. Statistik Sekolah</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="nss_skul" name="nss_skul" placeholder="Nomor Statistik Sekolah" value="<?php echo $sk['nss']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Nama SKPD</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="skpd_skul" name="skpd_skul" placeholder="Nama SKPD atau Yayasan" value="<?php echo $sk['nmskpd']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Alamat</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="almt_skul" name="almt_skul" placeholder="Alamat" value="<?php echo $sk['alamat']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Desa</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="desa_skul" name="desa_skul" placeholder="Desa" value="<?php echo $sk['desa']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Kecamatan</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="kec_skul" name="kec_skul" placeholder="Kecamatan" value="<?php echo $sk['kec']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Kabupaten</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="kab_skul" name="kab_skul" placeholder="Kabupaten" value="<?php echo $sk['kab']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Provinsi</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="prov_skul" name="prov_skul" placeholder="Provinsi" value="<?php echo $sk['prov']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Kode Pos</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="kpos_skul" name="kpos_skul" placeholder="Kode Pos" value="<?php echo $sk['kdpos']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Website</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="web_skul" name="web_skul" placeholder="Website Satuan Pendidikan" value="<?php echo $sk['website']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">E-mail</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="imel_skul" name="imel_skul" placeholder="Email Satuan Pendidikan" value="<?php echo $sk['email']; ?>">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>