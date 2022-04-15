<?php
require_once "../assets/library/PHPExcel.php";
require_once "../assets/library/excel_reader.php";
if (isset($_POST['upload'])) {
	$output = '';
	if ($_FILES['zip_file']['name'] != '') {
		$file_name = $_FILES['zip_file']['name'];
		$name = strtolower(end(explode(".", $file_name)));
		if ($ext == 'zip') {
			$path = '../foto/';
			$location = $path . $file_name;
			if (move_uploaded_file($_FILES['zip_file']['tmp_name'], $location)) {
				$zip = new ZipArchive;
				if ($zip->open($location)) {
					$zip->extractTo($path);
					$zip->close();
				}
				$files = scandir($path);
				$sukses = 0;
				$gagal = 0;
				foreach ($files as $file) {
					$allowed_ext = array('jpg', 'jpeg', 'png');
					$file_ext = end(explode(".", $file));
					if (in_array($file_ext, $allowed_ext)) {
						$tmp = explode(".", $file);
						$nama = $tmp[0];
						$data = array('fotosiswa' => $file);
						$key = array('nisn' => $nama);
						$row = editdata('tbpeserta', $data, '', $key);
						if ($row > 0) $sukses++;
						else $gagal++;
					}
				}
				unlink($location);
				if ($sukses > 0) {
					$pesan = "Ada " . $sukses . " File Foto  Berhasil Diupload!";
					$jns = "info";
				}
			}
		} else {
			$pesan = "Bukan File *.zip";
			$jns = "error";
		}
	} else {
		$pesan = 'Tidak Ada File Yang Diupload!';
		$jns = "error";
	}
}

if (isset($_POST['import'])) {
	if (empty($_FILES['filepd']['tmp_name'])) {
		echo "<script>
					$(function() {
						toastr.error('File Template Peserta Ujian Kosong!','Mohon Maaf!',{
							timeOut:1000,
							fadeOut:1000
						});
					});
				</script>";
	} else {
		$data = new Spreadsheet_Excel_Reader($_FILES['filepd']['tmp_name']);
		$baris = $data->rowcount($sheet_index = 0);
		var_dump($data);
		$isidata = $baris - 5;
		$sukses = 0;
		$gagal = 0;
		$update = 0;
		$idskul = getskul();
		for ($i = 6; $i <= $baris; $i++) {
			$xnis = $data->val($i, 3);
			$xnisn = $data->val($i, 4);
			$xnama = $conn->real_escape_string($data->val($i, 5));
			$xtmplhr = $data->val($i, 6);
			$xtgllhr = $data->val($i, 7);
			$xjekel = $data->val($i, 8);
			$nmagama = $data->val($i, 9);
			$xalmt = $data->val($i, 10);

			if (strlen($nmagama) == 1) {
				$xagama = $nmagama;
			} else {
				switch ($nmagama) {
					case 'Islam': {
							$xagama = 'A';
							break;
						}
					case 'Kristen': {
							$xagama = 'B';
							break;
						}
					case 'Katholik': {
							$xagama = 'C';
							break;
						}
					case 'Hindu': {
							$xagama = 'D';
							break;
						}
					case 'Buddha': {
							$xagama = 'E';
							break;
						}
					case 'Konghucu': {
							$xagama = 'F';
							break;
						}
					default: {
							$xagama = '';
							break;
						}
				}
			}

			if ($xnis == '') {
				echo "<script>
							$(function() {
								toastr.error('Cek Kolom NIS a.n " . $xnama . "','Mohon Maaf!',{
									timeOut:10000,
									fadeOut:10000
								});
							});
						</script>";
			} else if (strlen($xnisn) <> 10 || $xnisn == '') {
				echo "<script>
							$(function() {
								toastr.error('Cek Kolom NISN a.n " . $xnama . "','Mohon Maaf!',{
									timeOut:10000,
									fadeOut:10000
								});
							});
						</script>";
			} else if (strlen($xnama) < 1 || $xnama == '') {
				echo "<script>
							$(function() {
								toastr.error('Cek Kolom Nama Lengkap a.n " . $xnama . "','Mohon Maaf!',{
									timeOut:1000,
									fadeOut:1000
								});
							});
						</script>";
			} else if (strlen($xtmplhr) < 1 || $xtmplhr == '') {
				echo "<script>
							$(function() {
								toastr.error('Cek Kolom Tempat Lahir a.n " . $xnama . "','Mohon Maaf!',{
									timeOut:1000,
									fadeOut:1000
								});
							});
						</script>";
			} else if (strlen($xtgllhr) < 1 || $xtgllhr == '') {
				echo "<script>
							$(function() {
								toastr.error('Cek Kolom Tanggal Lahir a.n " . $xnama . "','Mohon Maaf!',{
									timeOut:1000,
									fadeOut:1000
								});
							});
						</script>";
			} else if (strlen($xjekel) > 1 || $xjekel == '') {
				echo "<script>
							$(function() {
								toastr.error('Cek Kolom Jenis Kelamin a.n " . $xnama . "','Mohon Maaf!',{
									timeOut:1000,
									fadeOut:1000
								});
							});
						</script>";
			} else if ($xagama == '') {
				echo "<script>
							$(function() {
								toastr.error('Cek Kolom Agama a.n " . $xnama . "','Mohon Maaf!',{
									timeOut:1000,
									fadeOut:1000
								});
							});
						</script>";
			} else {
				$key = array(
					'nisn' => $xnisn,
					'nis' => $xnis
				);
				$ceksiswa = cekdata('tbpeserta', $key);
				if ($ceksiswa > 0) {
					$datasiswa = array(
						'idskul' => $idskul,
						'nmsiswa' => $xnama,
						'tmplahir' => $xtmplhr,
						'tgllahir' => $xtgllhr,
						'gender' => $xjekel,
						'agama' => $xagama,
						'alamat' => $xalmt,
						'deleted' => '0'
					);

					if (editdata('tbpeserta', $datasiswa, '', $key) > 0) {
						echo "<script>
									$(function() {
										toastr.success('Update Data Peserta Didik a.n " . $xnama . " Sukses!','Terima Kasih',{
											timeOut:3000,
											fadeOut:3000
										});
									});
								</script>";
						$update++;
					} else {
						echo "<script>
									$(function() {
										toastr.error('Update Data Peserta Didik a.n " . $xnama . " Gagal!','Terima Kasih',{
											timeOut:3000,
											fadeOut:3000
										});
									});
								</script>";
					}
				} else {
					$datasiswa = array(
						'idskul' => $idskul,
						'nmsiswa' => $xnama,
						'nis' => $xnis,
						'nisn' => $xnisn,
						'tmplahir' => $xtmplhr,
						'tgllahir' => $xtgllhr,
						'gender' => $xjekel,
						'agama' => $xagama,
						'alamat' => $xalmt,
						'deleted' => '0'
					);

					if (adddata('tbpeserta', $datasiswa) > 0) {
						echo "<script>
									$(function() {
										toastr.success('Tambah Data Peserta Didik a.n " . $xnama . " Sukses!','Terima Kasih',{
											timeOut:3000,
											fadeOut:3000
										});
									});
								</script>";
						$sukses++;
					} else {
						echo "<script>
									$(function() {
										toastr.error('Tambah Data Peserta Didik a.n " . $xnama . " Gagal!','Mohon Maaf',{
											timeOut:4000,
											fadeOut:3000
										});
									});
								</script>";
						$gagal++;
					}
				}
			}
		}
		echo "<script>
						$(function() {
							toastr.info('Ada " . $sukses . " data ditambah, " . $update . " data diupdate, " . $gagal . " data gagal ditambahkan!','Terimakasih',{
							timeOut:2000,
							fadeOut:2000
						});
					});
				</script>";
	}
}
?>
<div class="modal fade" id="myImportPD" aria-modal="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form action="" method="POST" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title">Import Data Peserta Didik</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-sm-12">
						<div class="row">
							<label for="filepd">Pilih File Template</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="filepd" name="filepd">
								<label class="custom-file-label" for="filepd">Pilih file</label>
							</div>
							<p style="color:red;margin-top:10px"><em>Hanya mendukung file *.xls (Microsoft Excel 97-2003)</em></p>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<a href="siswa_template.php" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-download"></i> Download</a>
					<button type="submit" class="btn btn-primary btn-sm" name="import"><i class="fas fa-upload"></i> Upload</button>
					<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-power-off"></i> Tutup</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="myFotoPD" aria-modal="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form action="" method="POST" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title">Upload Foto Peserta Didik</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-sm-12">
						<div class="row">
							<label for="filepd">Pilih File Zip</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="fotopd" name="zip_file">
								<label class="custom-file-label" for="fotopd">Pilih file</label>
							</div>
							<p style="color:red;margin-top:10px"><em>Hanya mendukung file *.zip</em></p>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="submit" class="btn btn-primary btn-sm" name="upload"><i class="fas fa-upload"></i> Upload</button>
					<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-power-off"></i> Tutup</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="card card-secondary card-outline">
	<div class="card-header">
		<h4 class="card-title">Data Peserta Didik</h4>
		<div class="card-tools">
			<a href="index.php?p=addsiswa" class="btn btn-primary btn-sm">
				<i class="fas fa-plus-circle"></i>&nbsp;Tambah
			</a>
			<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myImportPD">
				<i class="fas fa-cloud-upload-alt"></i>&nbsp;Import
			</button>
			<button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#myFotoPD">
				<i class="fas fa-upload"></i>&nbsp;Foto
			</button>
			<button id="hapusall" class="btn btn-danger btn-sm">
				<i class="fas fa-trash-alt"></i>&nbsp;Hapus
			</button>
		</div>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table id="tb_siswa" class="table table-bordered table-striped table-sm">
				<thead>
					<tr>
						<th style="text-align: center;width:2.5%">No.</th>
						<th style="text-align: center;width:25%">Nama User</th>
						<th style="text-align: center;width:17.5%">NIS / NISN</th>
						<th style="text-align: center;">Alamat</th>
						<th style="text-align: center;width:10%">Status</th>
						<th style="text-align: center;width:20%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$qs = viewdata('tbpeserta', array('deleted' => '0'));
					$no = 0;
					foreach ($qs as $s) :
						$no++;
						if ($s['aktif'] == '1') {
							$stat = 'Aktif';
							$btn = "btn-success";
						} else {
							$stat = 'Non Aktif';
							$btn = "btn-danger";
						}
					?>
						<tr>
							<td style="text-align:center"><?php echo $no . '.'; ?></td>
							<td title="<?php echo $s['idsiswa']; ?>"><?php echo ucwords(strtolower($s['nmsiswa'])); ?></td>
							<td><?php echo $s['nis'] . ' / ' . $s['nisn']; ?></td>
							<td><?php echo $s['alamat']; ?></td>
							<td style="text-align:center">
								<input data-id="<?php echo $s['idsiswa']; ?>" type="button" class="col-10 btn <?php echo $btn; ?> btn-xs btnAktif" value="<?php echo $stat; ?>">
							</td>
							<td style="text-align: center">
								<a href="index.php?p=addsiswa&id=<?php echo $s['idsiswa']; ?>" class="btn btn-xs btn-primary col-4">
									<i class="fas fa-edit"></i>&nbsp;Edit
								</a>
								<button data-id="<?php echo $s['idsiswa']; ?>" class="btn btn-xs btn-danger col-4 btnHapus">
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
	$(function() {
		$('#tb_siswa').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": false,
			"responsive": true,
		});
	});

	$(".btnAktif").click(function() {
		let data = new FormData();
		data.append('id', $(this).data('id'))
		data.append('aksi', 'aktif')
		$.ajax({
			type: "POST",
			url: "siswa_simpan.php",
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 8000,
			success: function(respons) {
				if (respons == 1) {
					$(function() {
						toastr.info('Peserta Didik Berhasil Diaktifkan!!', 'Informasi', {
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
						toastr.info('Peserta Didik Berhasil Dinonaktifkan!!', 'Informasi', {
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
	})

	$(".btnHapus").click(function() {
		var id = $(this).data('id');
		Swal.fire({
			title: 'Anda Yakin?',
			text: "Menghapus Data Peserta Didik",
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
					url: "siswa_simpan.php",
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
		var id = $(this).data('id');
		Swal.fire({
			title: 'Anda Yakin?',
			text: "Menghapus Seluruh Data Peserta Didik" + id,
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
					url: "siswa_simpan.php",
					data: "aksi=kosong&id=" + id,
					success: function(data) {
						toastr.success(data);
					}
				})
				window.location.reload();
			}
		})
	})
</script>