<?php
function upload($files)
{
	$namaFile = $files['name'];
	$tmpFile = $files['tmp_name'];
	$ekstensiValid = array('jpg', 'jpeg', 'png');
	$getEkstensiFile = explode(".", $namaFile);
	$ekstensiFile = strtolower(end($getEkstensiFile));
	if (in_array($ekstensiFile, $ekstensiValid)) {
		$dir = "../foto/";
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

if (isset($_POST['simpan'])) {
	if (empty($_POST['idsiswa'])) {
		$data = array(
			'idskul' => $idskul,
			'nmsiswa' => addslashes($_POST['nmsiswa']),
			'nis' => $_POST['nis'],
			'nisn' => $_POST['nisn'],
			'tmplahir' => $_POST['tmplahir'],
			'tgllahir' => $_POST['tgllahir'],
			'gender' => $_POST['gender'],
			'agama' => $_POST['agama'],
			'alamat' => $_POST['almt'],
			'deleted' => '0'
		);
		$rows = adddata('tbpeserta', $data);
	} else {
		$data = array(
			'nmsiswa' => addslashes($_POST['nmsiswa']),
			'nis' => $_POST['nis'],
			'nisn' => $_POST['nisn'],
			'tmplahir' => $_POST['tmplahir'],
			'tgllahir' => $_POST['tgllahir'],
			'gender' => $_POST['gender'],
			'agama' => $_POST['agama'],
			'alamat' => $_POST['almt']
		);
		$field = array('idsiswa' => $_POST['idsiswa']);
		$rows = editdata('tbpeserta', $data, '', $field);
	}
	if ($rows > 0) {
		echo "<script>
				$(function() {
					toastr.success('Tambah atau Edit Data Peserta Didik Berhasil!','Terima Kasih',
					{
						timeOut:1000,
						fadeOut:1000,
						onHidden: function(){
							window.location.href='index.php?p=datasiswa';
						}
					});
				});
			</script>";
	} else {
		echo "<script>
				$(function() {
					toastr.error('Data Peserta Didik Gagal Disimpan!','Mohon Maaf',
					{
						timeOut:1000,
						fadeOut:1000,
						onHidden: function(){
							window.location.href='index.php?p=datasiswa';
						}
					});
				});
			</script>";
	}
}
?>
<?php if (isset($_GET['id'])) : ?>
	<script type="text/javascript">
		$(document).ready(function() {
			let id = "<?php echo $_GET['id']; ?>";
			$.ajax({
				url: "siswa_edit.php",
				type: "POST",
				dataType: 'json',
				data: "id=" + id,
				success: function(e) {
					$("#idsiswa").val(e.idsiswa);
					$("#nmsiswa").val(e.nmsiswa);
					$("#nis").val(e.nis);
					$("#nisn").val(e.nisn);
					$("#tmplahir").val(e.tmplahir);
					$("#tgllahir").val(e.tgllahir);
					$("#gender").val(e.gender);
					$("#agama").val(e.agama);
					$("#almt").val(e.alamat);
					$("#judul").html(e.judul);
					$("#fotosiswa").attr("src", e.dir + e.foto);
					$("#simpan").html(e.tmbl);
				}
			})
		})
	</script>
<?php endif ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#tgllahir').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
	})
</script>
<div class="callout callout-warning">
	<p><strong>Petunjuk</strong><br />Silahkan cek kembali data Peserta Didik, lengkapi dan betulkan jika masih terdapat data
		yang masih kurang atau salah, kemudian klik tombol <strong>Update</strong></p>
</div>
<form action="" method="post" enctype="multipart/form-data">
	<div class="card card-primary card-outline">
		<div class="card-header">
			<h5 class="card-title m-0" id="judul">Data Peserta Didik</h5>
			<div class="card-tools">
				<button type="submit" class="btn btn-primary btn-sm" name="simpan" id="simpan">
					<i class="fas fa-fw fa-save"></i> Simpan
				</button>
				<a href="index.php?p=datasiswa" class="btn btn-sm btn-danger">
					<i class="fas fa-fw fa-power-off"></i>&nbsp;Tutup
				</a>
			</div>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-sm-3">
					<div class="text-center mt-2">
						<img class="img img-fluid img-responsive img-rounded" width="50%" id="fotosiswa">
						<span id="fotosiswa_status"></span>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">Nama Peserta Didik</label>
						<div class="col-sm-6">
							<input type="hidden" class="form-control form-control-sm" name="idsiswa" id="idsiswa">
							<input class="form-control form-control-sm" name="nmsiswa" id="nmsiswa" autocomplete="off">
						</div>
					</div>
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">NIS</label>
						<div class="col-sm-6">
							<input class="form-control form-control-sm" name="nis" id="nis" autocomplete="off">
						</div>
					</div>
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">NISN</label>
						<div class="col-sm-6">
							<input class="form-control form-control-sm" name="nisn" id="nisn" autocomplete="off">
						</div>
					</div>
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">Tempat Lahir</label>
						<div class="col-sm-6">
							<input class="form-control form-control-sm" name="tmplahir" id="tmplahir" autocomplete="off">
						</div>
					</div>
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">Tanggal Lahir</label>
						<div class="col-sm-6">
							<input class="form-control form-control-sm" name="tgllahir" id="tgllahir" autocomplete="off">
						</div>
					</div>
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">Jenis Kelamin</label>
						<div class="col-sm-6">
							<select class="form-control form-control-sm" name="gender" id="gender">
								<option value="">..Pilih..</option>
								<option value="L">Laki-laki</option>
								<option value="P">Perempuan</option>
							</select>
						</div>
					</div>
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">Agama</label>
						<div class="col-sm-6">
							<select class="form-control form-control-sm" name="agama" id="agama" autocomplete="off">
								<option value="">..Pilih..</option>
								<option value="A">Islam</option>
								<option value="B">Kristen</option>
								<option value="C">Katholik</option>
								<option value="D">Hindu</option>
								<option value="E">Buddha</option>
								<option value="F">Konghucu</option>
							</select>
						</div>
					</div>
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">Alamat</label>
						<div class="col-sm-6">
							<textarea class="form-control form-control-sm" name="almt" id="almt" autocomplete="off"></textarea>
						</div>
					</div>
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">Foto</label>
						<div class="col-sm-6">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>