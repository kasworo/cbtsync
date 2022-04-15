<div class="col-sm-12">
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Laporan Hasil Ujian</h4>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tb_banksoal" class="table table-bordered table-striped table-sm">
					<thead>
						<tr>
							<th style="text-align: center;width:2.5%">No.</th>
							<th style="text-align: center;width:10.5%">Kelas</th>
							<th style="text-align: center;width:35%">Mata Pelajaran</th>
							<th style="text-align: center">Guru Bidang Studi</th>
							<th style="text-align: center;width:20%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($level == '1') {
							$sqkls = "SELECT pg.idrombel, rb.nmrombel, mp.nmmapel, g.nama, mp.idmapel FROM tbpengampu pg INNER JOIN tbgtk g USING(idgtk) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbrombel rb USING(idrombel)INNER JOIN tbthpel tp USING(idthpel) WHERE tp.aktif='1' ORDER BY pg.idrombel, pg.idmapel";
						}
						if ($level == '2') {
							$sqkls = "SELECT pg.idrombel, rb.nmrombel, mp.nmmapel, g.nama, mp.idmapel FROM tbpengampu pg INNER JOIN tbgtk g USING(idgtk) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbthpel tp USING(idthpel) INNER JOIN tbuser us USING(username) WHERE username='$_COOKIE[id]' AND tp.aktif='1' ORDER BY pg.idrombel, pg.idmapel";
						}
						$no = 0;
						$qk = vquery($sqkls);
						foreach ($qk as $m) :
							$no++;
						?>
							<tr>
								<td style="text-align:center">
									<?php echo $no . '.'; ?>
								</td>
								<td style="text-align:center">
									<?php echo $m['nmrombel']; ?>
								</td>
								<td>
									<?php echo $m['nmmapel']; ?>
								</td>
								<td>
									<?php echo $m['nama']; ?>
								</td>
								<td style="text-align: center">
									<form action="print_hasil.php" target="_blank" method="POST">
										<input type="hidden" id="idmapel" name="map" value="<?php echo $m['idmapel']; ?>">
										<input type="hidden" id="idrombel" name="rmb" value="<?php echo $m['idrombel']; ?>">
										<button type="submit" class="btn btn-xs btn-default col-xs-6">
											<i class="fas fa-print"></i>&nbsp;Cetak
										</button>
									</form>
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

	$("#btnRefresh").click(function() {
		window.location.reload();
	})
</script>