<?php
echo (floor(0.60) . "<br>");
echo (floor(1.40) . "<br>");
echo (ceil(1.40) . "<br>");
echo (ceil(5) . "<br>");
echo (ceil(5.1) . "<br>");
echo (ceil(-5.1) . "<br>");
echo (ceil(-5.9));
?>
<div class="modal fade" id="myUjian" aria-modal="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Pengaturan Ujian</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="fetched-data"></div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-primary btn-sm col-4" id="btnAktivasi">
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
			<h4 class="card-title">Status Aktivasi Bank Soal</h4>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tbstatussoal" class="table table-bordered table-striped table-sm">
					<thead>
						<tr>
							<th style="text-align: center;width:2.5%">No.</th>
							<th style="text-align: center;width:12.5%">Paket Soal</th>
							<th style="text-align: center;width:30%">Mata Pelajaran</th>
							<th style="text-align: center;width:15%">Rombel</th>
							<th style="text-align: center;width:20%">Tanggal</th>
							<th style="text-align: center;">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($level == '1') {
							$sqlbs = "SELECT bs.*, mp.nmmapel, g.nama, uj.nmujian FROM tbbanksoal bs INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbujian uj USING(idujian) INNER JOIN tbtes ts USING(idtes) INNER JOIN tbgtk g USING(idgtk) INNER JOIN tbthpel tp USING(idthpel) WHERE tp.aktif='1' AND uj.status='1' AND bs.deleted='0'";
						}
						if ($level == '2') {
							$sqlbs = "SELECT bs.*, mp.nmmapel, g.nama, uj.nmujian FROM tbbanksoal bs INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbujian uj USING(idujian) INNER JOIN tbtes ts USING(idtes) INNER JOIN tbgtk g USING(idgtk) INNER JOIN tbthpel tp USING(idthpel) INNER JOIN tbuser us USING(username) WHERE us.username='$_COOKIE[id]' AND tp.aktif='1' AND uj.status='1' AND bs.deleted='0'";
						}
						$no = 0;
						$qbs = vquery($sqlbs);
						foreach ($qbs as $m) :
							$no++;
							$qakt = $conn->query("SELECT jd.idjadwal, GROUP_CONCAT(rb.nmrombel SEPARATOR ', ') as rombel, jd.tglujian FROM tbsetingujian su INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbjadwal jd USING(idjadwal) WHERE idbank='$m[idbank]'");
							$ak = $qakt->fetch_array();
							$idjadwal = $ak['idjadwal'];
							$rombel = $ak['rombel'];
							$tglu = indonesian_date($ak['tglujian']);
						?>
							<tr>
								<td style="text-align:center"><?php echo $no . '.'; ?></td>
								<td style="text-align:center"><?php echo $m['nmbank']; ?></td>
								<td><?php echo $m['nmmapel']; ?></td>
								<td>
									<?php echo $rombel . ' ' . $idjadwal; ?>
								</td>
								<td>
									<?php echo $tglu; ?>
								</td>
								<td style="text-align: center">
									<?php
									$qsoal = $conn->query("SELECT so.*FROM tbsoal so INNER JOIN tbstimulus st USING(idstimulus) WHERE st.idbank='$m[idbank]'");
									$ceksoal = $qsoal->num_rows;
									if ($ceksoal > 0) :
									?>
										<button data-id="<?php echo $m['idbank']; ?>" class="btn btn-xs btn-info btnUji" data-toggle="modal" data-target="#myUjian">
											<i class="far fa-check-square"></i>&nbsp;Ujikan
										</button>
									<?php else : ?>
										<button disabled="true" class="btn btn-xs btn-info btnUji">
											<i class="far fa-check-square"></i>&nbsp;Ujikan
										</button>
									<?php endif ?>
									<button data-id="<?php echo $m['idbank']; ?>&jd=<?php echo $idjadwal; ?>" class="btn btn-xs btn-danger btnHapus">
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
		$("#myUjian").on('hidden.bs.modal', function() {
			window.location.reload();
		})
	})
	$(function() {
		$('#tbstatussoal').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": false,
			"responsive": true,
		});
	})

	$("#btnAktivasi").click(function() {
		let jmlsoal = $("#jmlsoal").val()
		let idb = $("#idsoal").val()
		let jdw = $("#jduji").val()
		let rmb = $("#rmbuji").val()
		let hsl = $("#hasil").val()
		let soal = $("#soal").val()
		if (jdw == '') {
			toastr.error("Pilih Jadwal Terlebih Dahulu!!", "Maaf!")
		} else if (rmb == '') {
			toastr.error("Pilih Rombel Dulu!!", "Maaf!")
		} else if (hsl == '') {
			toastr.error("Pilih Setting Hasil Dulu!!", "Maaf!")
		} else if (soal == '') {
			toastr.error("Isikan Jumlah Soal Dulu!!", "Maaf!")
		} else if (soal >= jmlsoal) {
			toastr.error("Tidak Boleh Lebih Dari " + (jmlsoal - 1) + "!!", "Maaf!")
		} else {
			let data = new FormData()
			data.append('idb', idb)
			data.append('jdw', jdw)
			data.append('rmb', rmb);
			data.append('hsl', hsl);
			data.append('soal', soal);
			data.append('aksi', 'aktif')
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
							toastr.success('Bank Soal Berhasil Diujikan!!', 'Terima Kasih', {
								timeOut: 3000,
								fadeOut: 3000,
								onHidden: function() {
									window.location.reload()
								}
							});
						});
					}
				}
			})
		}
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
		$.ajax({
			url: "banksoal_simpan.php",
			type: 'POST',
			data: "aksi=3&id=" + id,
			success: function(data) {
				toastr.success(data);
			}
		})
	})
</script>