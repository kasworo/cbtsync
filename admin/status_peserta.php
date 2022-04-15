<?php
$saiki = date('Y-m-d');
$sqjd = "SELECT jd.idjadwal, tk.idsesi FROM tbjadwal jd INNER JOIN tbtoken tk USING(idjadwal) WHERE jd.tglujian='$saiki' AND tk.status='1'";
if (cquery($sqjd) > 0) :
	$jd = vquery($sqjd)[0];
	$idjadwal = $jd['idjadwal'];
	$idsesi = $jd['idsesi'];
?>
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Data Peserta Tes</h4>
			<div class="card-tools">
				<button class="btn btn-success btn-sm" id="btnReset">
					<i class="fas fa-sync-alt"></i>&nbsp;Reset
				</button>
				<button class="btn btn-sm btn-danger" id="btnLogout">
					<i class="fas fa-power-off"></i>&nbsp;Logout
				</button>
				<?php if ($level == '1') : ?>
					<a href="print_absen.php" target="_blank" class="btn btn-secondary btn-sm">
						<i class="fas fa-print"></i>&nbsp;Daftar Hadir
					</a>
					<a href="print_berita.php" target="_blank" class="btn btn-default btn-sm">
						<i class="fas fa-print"></i>&nbsp;Berita Acara
					</a>
				<?php endif ?>
			</div>
		</div>
		<div class="card-body">
			<?php
			if ($level == '1') {
				$sql = "SELECT ps.idsiswa,ps.nmsiswa, ps.nmpeserta, ps.passwd, ru.nmruang, lp.status, st.idset, lp.sisawaktu FROM tbpeserta ps INNER JOIN tbruang ru USING(idruang) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbtoken tk USING(idjadwal, idsesi) INNER JOIN tbsetingujian st USING(idjadwal,idrombel) LEFT JOIN tblogpeserta lp USING(idsiswa, idjadwal) WHERE jd.tglujian='$saiki' AND tk.status='1' GROUP BY ps.idsiswa,st.idbank, su.idsesi ORDER BY ps.nmpeserta";
			}
			if ($level == '2') {
				$sql = "SELECT ps.idsiswa,ps.nmsiswa, ps.nmpeserta, ps.passwd, ru.nmruang, lp.status, st.idset, lp.sisawaktu FROM tbpeserta ps INNER JOIN tbruang ru USING(idruang) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbtoken tk USING(idsesi, idjadwal) INNER JOIN tbsetingujian st USING(idjadwal,idrombel) LEFT JOIN tblogpeserta lp USING(idsiswa, idjadwal) INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbpengampu a USING(idrombel,idmapel) INNER JOIN tbgtk g ON (a.idgtk=g.idgtk OR rb.idgtk=g.idgtk)INNER JOIN tbuser us USING(username) WHERE tk.status='1' AND us.username='$_COOKIE[id]' AND jd.tglujian='$saiki' GROUP BY ps.idsiswa,st.idbank ORDER BY ps.nmpeserta";
			}
			?>
			<div class="table-responsive">
				<table id="tbstatus" class="table table-bordered table-striped table-sm">
					<thead>
						<tr>
							<th style="text-align: center;width:2.5%">No.</th>
							<th style="text-align: center;width:12.5%">No. Peserta</th>
							<th style="text-align: center;">Nama Peserta</th>
							<th style="text-align: center;width:17.5%">Progress</th>
							<th style="text-align: center;width:10%">Sisa Waktu</th>
							<th style="text-align:center;width:17.5%">Status</th>
							<th style="text-align:center;width:10%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 0;
						$qs = vquery($sql);
						foreach ($qs as $s) :
							$no++;
							if ($s['status'] == '0') {
								$status = "Sedang Mengerjakan";
								$bdg = "color:green";
							} else if ($s['status'] == '1') {
								$status = "Tes Selesai";
								$bdg = "color:red";
							} else {
								$status = "Belum Login";
								$bdg = "color:black";
							}
						?>
							<tr>
								<td style="text-align:center">
									<?php echo $no . '.'; ?>
								</td>
								<td style="text-align:center" title="Password:<?php echo $s['passwd']; ?>">
									<?php echo $s['nmpeserta']; ?>
								</td>
								<td>
									<?php echo ucwords(strtolower($s['nmsiswa'])); ?>
								</td>
								<td>
									<?php
									$qskor = $conn->query("SELECT COUNT(*) as kabeh, COUNT(CASE WHEN jwbbenar IS NOT NULL THEN 1 END) as terisi FROM tbjawaban WHERE idsiswa='$s[idsiswa]' AND idset='$s[idset]' GROUP BY idsiswa");
									$skr = $qskor->fetch_array();
									$terisi = $skr['terisi'];
									$kabeh = $skr['kabeh'];
									if ($terisi > 0) {
										echo $terisi . ' dari ' . $kabeh . ' Soal';
									} else {
										echo 'Belum Mengerjakan';
									}
									?>
								</td>
								<td style="text-align:center">
									<?php echo $s['sisawaktu']; ?>
								</td>
								<td>
									<span style="<?php echo $bdg; ?>"><?php echo $status; ?></span>
								</td>
								<td style="text-align:center;">
									<?php if ($s['status'] == '1') : ?>
										<button class="btn btn-xs btn-primary col-10 btnReset" data-id="<?php echo $s['idsiswa']; ?>">
											<i class="fas fa-sync-alt"></i>&nbsp;Reset
										</button>
									<?php else : ?>
										<button class="btn btn-xs btn-danger col-10 btnLogout" data-id="<?php echo $s['idsiswa']; ?>">
											<i class="fas fa-power-off"></i>&nbsp;Logout
										</button>
									<?php endif ?>
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
			$("#tbstatus").DataTable({
				"select": true,
				"paging": true,
				"lengthChange": true,
				"searching": true,
				"ordering": false,
				"info": true,
				"autoWidth": false,
				"responsive": true,
			});
		})
		$(document).ready(function() {
			$(".btnReset").click(function() {
				let data = new FormData()
				data.append('id', $(this).data('id'))
				data.append('jd', <?php echo $idjadwal; ?>)
				data.append('aksi', 'reset')
				$.ajax({
					type: "POST",
					url: "status_update.php",
					data: data,
					processData: false,
					contentType: false,
					cache: false,
					timeout: 8000,
					success: function(respons) {
						if (respons == 1) {
							$(function() {
								toastr.info('Reset Login Peserta Berhasil!!!!', 'Informasi', {
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
			$("#btnReset").click(function() {
				let data = new FormData()
				data.append('jd', <?php echo $idjadwal; ?>)
				data.append('aksi', 'reset')
				$.ajax({
					type: "POST",
					url: "status_update.php",
					data: data,
					processData: false,
					contentType: false,
					cache: false,
					timeout: 8000,
					success: function(respons) {
						if (respons == 1) {
							$(function() {
								toastr.info('Reset Login Semua Peserta Berhasil!!', 'Informasi', {
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

			$(".btnLogout").click(function() {
				let data = new FormData()
				data.append('id', $(this).data('id'))
				data.append('jd', <?php echo $idjadwal; ?>)
				data.append('aksi', 'logout')
				$.ajax({
					type: "POST",
					url: "status_update.php",
					data: data,
					processData: false,
					contentType: false,
					cache: false,
					timeout: 8000,
					success: function(respons) {
						if (respons == 1) {
							$(function() {
								toastr.info('Peserta Ujian Berhasil Dilogout!!', 'Informasi', {
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

			$("#btnLogout").click(function() {
				let data = new FormData()
				data.append('jd', <?php echo $idjadwal; ?>)
				data.append('aksi', 'logout')
				$.ajax({
					type: "POST",
					url: "status_update.php",
					data: data,
					processData: false,
					contentType: false,
					cache: false,
					timeout: 8000,
					success: function(respons) {
						if (respons == 1) {
							$(function() {
								toastr.info('Semua Peserta Ujian Berhasil Dilogout!!', 'Informasi', {
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
		})
	</script>
<?php else : ?>
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Data Peserta Tes</h4>
		</div>
		<div class="card-body">

		</div>
	</div>
<?php endif ?>