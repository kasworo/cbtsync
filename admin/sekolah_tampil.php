<?php
function upload($files)
{
	$namaFile = $files['name'];
	$tmpFile = $files['tmp_name'];
	$ekstensiValid = array('jpg', 'jpeg', 'png');
	$getEkstensiFile = explode(".", $namaFile);
	$ekstensiFile = strtolower(end($getEkstensiFile));
	if (in_array($ekstensiFile, $ekstensiValid)) {
		$dir = "../images/";
		$namaFileBaru = uniqid() . '.' . $ekstensiFile;
		move_uploaded_file($tmpFile, $dir . $namaFileBaru);
		return $namaFileBaru;
	} else {
		echo "<script>
				$(function() {
					toastr.error('File *." . $ekstensiFile . " Tidak Boleh Diupload!','Mohon Maaf',{
					timeOut:4000,
					fadeOut:3000
					});
				});
			</script>";
		return false;
	}
}

if ($sk['logoskul'] == '' || $sk['logoskul'] == null) {
	$logoskul = '../assets/img/nofile.png';
} else {
	if (file_exists('../images/' . $sk['logoskul'])) {
		$logoskul = '../images/' . $sk['logoskul'];
	} else {
		$logoskul = '../assets/img/tutwuri.png';
	}
}

if (isset($_POST['simpan'])) {
	if ($_FILES['logoskul']['error'] === 4) {
		$logoskulbaru = $_POST['logoskpdnama'];
	} else {
		$logoskulbaru = upload($_FILES['logoskul']);
	}
	if ($_FILES['logoskpd']['error'] === 4) {
		$logoskpdbaru = $_POST['logoskpdnama'];
	} else {
		$logoskpdbaru = upload($_FILES['logoskpd']);
	}
	$key = array('kdskul' => $_POST['kode']);
	if (cekdata('tbskul', $key) > 0) {
		$data = array(
			'nmskul' => $_POST['nama'],
			'npsn' => $_POST['npsn'],
			'nss' => $_POST['nss'],
			'nmskpd' => $_POST['skpd'],
			'alamat' => $_POST['almt'],
			'desa' => $_POST['desa'],
			'kec' => $_POST['kec'],
			'kab' => $_POST['kab'],
			'prov' => $_POST['prov'],
			'kdpos' => $_POST['kdpos'],
			'website' => $_POST['web'],
			'email' => $_POST['imel'],
			'logoskul' => $logoskulbaru,
			'logoskpd' => $logoskpdbaru
		);
		$row = editdata('tbskul', $data, '', $key);
	} else {
		$data = array(
			'idjenjang' => $_POST['idjjg'],
			'kdskul' => $_POST['kode'],
			'nmskul' => $_POST['nama'],
			'npsn' => $_POST['npsn'],
			'nss' => $_POST['nss'],
			'nmskpd' => $_POST['skpd'],
			'alamat' => $_POST['almt'],
			'desa' => $_POST['desa'],
			'kec' => $_POST['kec'],
			'kab' => $_POST['kab'],
			'prov' => $_POST['prov'],
			'kdpos' => $_POST['kdpos'],
			'website' => $_POST['web'],
			'email' => $_POST['imel'],
			'logoskul' => $logoskulbaru,
			'logoskpd' => $logoskpdbaru
		);
		$row = adddata('tbskul', $data);
	}
}

if ($sk['logoskpd'] == '' || $sk['logoskpd'] == null) {
	$logoskpd = 'assets/img/nofile.png';
} else {
	if (file_exists('images/' . $sk['logoskpd'])) {
		$logoskpd = 'images/' . $sk['logoskpd'];
	} else {
		$logoskpd = '../assets/img/tutwuri.png';
	}
}

if ($level == '1') :
?>
	<div class="card card-secondary card-outline">
		<form action="" method="post" enctype="multipart/form-data">
			<div class="card-header">
				<h4 class="card-title">Data Satuan Pendidikan</h4>
				<div class="card-tools">
					<button type="submit" class="btn btn-primary btn-sm" id="simpan" name="simpan">
						<i class="fas fa-fw fa-save"></i>
						<span>&nbsp;Simpan</span>
					</button>
					<a href="index.php?p=dashboard" class="btn btn-danger btn-sm ">
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
							<label class="col-sm-4 offset-sm-1">Jenjang Pendidikan</label>
							<select class="form-control form-control-sm col-sm-6" id="idjjg" name="idjjg">
								<option value="">..Pilih..</option>
								<option value="1" <?php echo ($sk['idjenjang'] == '1') ? "selected" : ""; ?>>SD/MI Sederajat</option>
								<option value="2" <?php echo ($sk['idjenjang'] == '2') ? "selected" : ""; ?>>SMP/MTs Sederajat</option>
								<option value="3" <?php echo ($sk['idjenjang'] == '3') ? "selected" : ""; ?>>SMA/MA Sederajat</option>
								<option value="4" <?php echo ($sk['idjenjang'] == '4') ? "selected" : ""; ?>>SMK/MAK Sederajat</option>
							</select>
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Kode Satuan Pendidikan</label>
							<input type="text" class="form-control form-control-sm col-sm-6" id="kode" name="kode" placeholder="Kode Satuan Pendidikan" value="<?php echo $sk['kdskul']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Nama Satuan Pendidikan</label>
							<input type="text" class="form-control form-control-sm col-sm-6" id="nama" name="nama" placeholder="Nama Satuan Pendidikan" value="<?php echo $sk['nmskul']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">NPSN</label>
							<input type="text" class="form-control form-control-sm col-sm-6" id="npsn" name="npsn" placeholder="N P S N" value="<?php echo $sk['npsn']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">No. Statistik Sekolah</label>
							<input type="text" class="form-control form-control-sm col-sm-6" id="nss" name="nss" placeholder="Nomor Statistik Sekolah" value="<?php echo $sk['nss']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Nama SKPD</label>
							<input type="text" class="form-control form-control-sm col-sm-6" id="skpd" name="skpd" placeholder="Nama SKPD atau Yayasan" value="<?php echo $sk['nmskpd']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Alamat</label>
							<input type="text" class="form-control form-control-sm col-sm-6" id="almt" name="almt" placeholder="Alamat" value="<?php echo $sk['alamat']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Desa</label>
							<input type="text" class="form-control form-control-sm col-sm-6" id="desa" name="desa" placeholder="Desa" value="<?php echo $sk['desa']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Kecamatan</label>
							<input type="text" class="form-control form-control-sm col-sm-6" id="kec" name="kec" placeholder="Kecamatan" value="<?php echo $sk['kec']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Kabupaten</label>
							<input type="text" class="form-control form-control-sm col-sm-6" id="kab" name="kab" placeholder="Kabupaten" value="<?php echo $sk['kab']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Provinsi</label>
							<input type="text" class="form-control form-control-sm col-sm-6" id="prov" name="prov" placeholder="Provinsi" value="<?php echo $sk['prov']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Kode Pos</label>
							<input type="text" class="form-control form-control-sm col-sm-6" id="kdpos" name="kdpos" placeholder="Kode Pos" value="<?php echo $sk['kdpos']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Website</label>
							<input type="text" class="form-control form-control-sm col-sm-6" id="web" name="web" placeholder="Website Satuan Pendidikan" value="<?php echo $sk['website']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">E-mail</label>
							<input type="text" class="form-control form-control-sm col-sm-6" id="imel" name="imel" placeholder="Email Satuan Pendidikan" value="<?php echo $sk['email']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Logo Sekolah</label>
							<input type="file" id="logoskul" name="logoskul">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Logo SKPD / Yayasan</label>
							<input type="file" id="logoskpd" name="logoskpd">
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
<?php else : ?>
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
										<input type="hidden" id="logoskulnama" name="logoskulnama" value="<?php echo $sk['logoskul']; ?>">
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
											<input type="hidden" id="logoskpdnama" name="logoskpdname" value="<?php echo $sk['logoskpd']; ?>">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class=" col-sm-8">
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Kode Satuan Pendidikan</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="kode" name="kode" placeholder="Kode Satuan Pendidikan" value="<?php echo $sk['kdskul']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Nama Satuan Pendidikan</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="nama" name="nama" placeholder="Nama Satuan Pendidikan" value="<?php echo $sk['nmskul']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">NPSN</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="npsn" name="npsn" placeholder="N P S N" value="<?php echo $sk['npsn']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">No. Statistik Sekolah</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="nss" name="nss" placeholder="Nomor Statistik Sekolah" value="<?php echo $sk['nss']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Nama SKPD</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="skpd" name="skpd" placeholder="Nama SKPD atau Yayasan" value="<?php echo $sk['nmskpd']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Alamat</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="almt" name="almt" placeholder="Alamat" value="<?php echo $sk['alamat']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Desa</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="desa" name="desa" placeholder="Desa" value="<?php echo $sk['desa']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Kecamatan</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="kec" name="kec" placeholder="Kecamatan" value="<?php echo $sk['kec']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Kabupaten</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="kab" name="kab" placeholder="Kabupaten" value="<?php echo $sk['kab']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Provinsi</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="prov" name="prov" placeholder="Provinsi" value="<?php echo $sk['prov']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Kode Pos</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="kpos" name="kpos" placeholder="Kode Pos" value="<?php echo $sk['kdpos']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">Website</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="web" name="web" placeholder="Website Satuan Pendidikan" value="<?php echo $sk['website']; ?>">
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-4 offset-sm-1">E-mail</label>
							<input type="text" readonly class="form-control form-control-sm col-sm-6" id="imel" name="imel" placeholder="Email Satuan Pendidikan" value="<?php echo $sk['email']; ?>">
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif ?>