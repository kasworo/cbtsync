<?php
if (isset($_GET['idb'])) {
	$ds = viewdata('tbsoal', array('idbutir' => $_GET['idb']))[0];
	$idbutir = $_GET['idb'];
	$nomor = $ds['nomersoal'];
	$idstm = $ds['idstimulus'];
	$jnssoal = $ds['jnssoal'];
} else {
	$sqlnum = "SELECT MAX(nomersoal) as nomor FROM tbsoal WHERE idstimulus='$_GET[ids]'";
	$num = vquery($sqlnum)[0];
	$nomor = $num['nomor'] + 1;
	$idstm = $_GET['ids'];
	$idbutir = '';
}
$bnk = viewdata('tbstimulus', array('idstimulus' => $idstm))[0];
$idbank = $bnk['idbank'];
?>
<script type="text/javascript" src="../assets/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector: "textarea",
		plugins: ["formula advlist lists charmap anchor", "code fullscreen", "table contextmenu paste jbimages"],
		toolbar: "undo redo | bold italic underline subscript superscript | alignleft aligncenter alignright alignjustify | bullist numlist | jbimages table formula code",
		menubar: false,
		relative_urls: false,
		forced_root_block: "",
		force_br_newlines: true,
		force_p_newlines: false,
	});
</script>
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#savesoal").click(function() {
			let js = $("#kategori").val();
			let mo = $("#modeopsi").val();
			let tk = $("#kesulitan").val();
			let sk = $("#skormaks").val();
			let bt = tinymce.get("tanyasoal").getContent();
			if (js == '') {
				toastr.error('Pilih Jenis Soal!!', 'Mohon Maaf!');
			} else if (mo == '') {
				toastr.error('Pilih Mode Opsi!!', 'Mohon Maaf!');
			} else if (tk == '') {
				toastr.error('Pilih Tingkat Kesukaran!!', 'Mohon Maaf!');
			} else if (sk == '') {
				toastr.error('Skor Maksimum Tidak Boleh Kosong!!', 'Mohon Maaf!');
			} else {
				let data = new FormData();
				data.append('ids', "<?php echo $idstm; ?>");
				data.append('js', js);
				data.append('mo', mo);
				data.append('tk', tk);
				data.append('sk', sk);
				data.append('bt', bt);
				data.append('nm', "<?php echo $nomor; ?>");
				data.append('aksi', 'simpan');
				$.ajax({
					url: "isisoal_simpan.php",
					type: 'POST',
					data: data,
					processData: false,
					contentType: false,
					cache: false,
					timeout: 8000,
					success: function(respons) {
						if (respons == 1) {
							$(function() {
								toastr.success('Butir Soal Berhasil Ditambah!!', 'Terima Kasih', {
									timeOut: 3000,
									fadeOut: 3000,
									onHidden: function() {
										let ids = "<?php echo $idstm; ?>";
										let nm = "<?php echo $nomor; ?>";
										$.ajax({
											url: 'isisoal_editbutir.php',
											type: 'post',
											dataType: 'json',
											data: 'ids=' + ids + '&nm=' + nm,
											success: function(e) {
												window.location.href = "index.php?p=tambahsoal&idb=" + e.idbutir
											}
										})
									}
								});
							});
						}
						if (respons == 2) {
							$(function() {
								toastr.info('Butir Soal Berhasil Diupdate!!', 'Informasi', {
									timeOut: 3000,
									fadeOut: 3000,
									onHidden: function() {
										window.location.reload();
									}
								});
							});
						}
						if (respons == 0) {
							toastr.error("Tambah Atau Update Butir Soal Gagal!", "Mohon Maaf");
						}
					}
				})
			}
		})
	})
</script>
<div class="callout callout-danger">
	<label>Petunjuk:</label>
	<ol>
		<li class="text-sm">Lengkapi pilihan jenis soal hingga skor maksimum sebelum butir soal diketikkan.</li>
		<li class="text-sm">Isikan opsi jawaban, tersedia maksimum 6 opsi jawaban.</li>
	</ol>
</div>
<div class="card card-danger card-outline" id="butirsoal">
	<div class="card-header">
		<h3 class="card-title" id="judul">Tambah Butir Soal</h3>
		<div class="card-tools">
			<a href="index.php?p=isisoal&id=<?php echo $idbank; ?>" class="btn btn-default btn-sm ml-1 mb-2">
				<i class="fas fa-arrow-circle-left"></i> Kembali
			</a>
			<button class="btn btn-success btn-sm ml-1 mb-2" id="savesoal" name="savesoal">
				<i class="fas fa-save"></i> Simpan
			</button>
		</div>
	</div>
	<div class="card-body">
		<div class="form-group row mb-2">
			<div class="col-sm-6">
				<div class="form-group row mb-2">
					<div class="col-sm-4">
						<label>Jenis Soal</label>
					</div>
					<div class="col-sm-6">
						<select id="kategori" name="kategori" class="form-control form-control-sm">
							<option value="">..Pilih..</option>
							<option value="1">Pilihan Ganda Biasa</option>
							<option value="2">Pilihan Ganda Kompleks</option>
							<option value="3">Benar / Salah</option>
							<option value="4">Menjodohkan</option>
							<option value="5">Isian Singkat</option>
						</select>
					</div>
				</div>
				<div class="form-group row mb-2">
					<div class="col-sm-4">
						<label>Mode Opsi</label>
					</div>
					<div class="col-sm-6">
						<select id="modeopsi" name="modeopsi" class="form-control form-control-sm">
							<option value="">..Pilih..</option>
							<option value="1">Tanpa Kolom</option>
							<option value="2">Tabel 2 Kolom</option>
							<option value="3">Tabel 3 Kolom</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group row mb-2">
					<div class="col-sm-4">
						<label>Tingkat Kesulitan</label>
					</div>
					<div class="col-sm-6">
						<select id="kesulitan" name="kesulitan" class="form-control form-control-sm">
							<option value="">..Pilih..</option>
							<option value="1">Mudah</option>
							<option value="2">Sedang</option>
							<option value="3">Sulit</option>
						</select>
					</div>
				</div>
				<div class="form-group row mb-2">
					<div class="col-sm-4">
						<label>Skor Maksimum</label>
					</div>
					<div class="col-sm-6">
						<input class="form-control form-control-sm" id="skormaks" name="skormaks">
					</div>
				</div>
			</div>
		</div>
		<hr />
		<div class="form-group row mb-2">
			<div class="col-sm-12">
				<label>Butir Soal</label>
			</div>
		</div>
		<div class="form-group row mb-2">
			<div class="col-sm-12">
				<textarea class="form-control form-control-sm" name="tanyasoal" id="tanyasoal" style="font-size:14pt; width:100%; height:200px;padding:5px"></textarea>
			</div>
		</div>
	</div>
</div>