<?php
$sqlu = "SELECT  ps.nmpeserta, ps.idsiswa, ps.nmsiswa, rb.nmrombel, bs.idbank, su.idset, COUNT(jwb.idset) as soal, ROUND(SUM(skor),2) as benar FROM tbjawaban jwb INNER JOIN tbpeserta ps USING(idsiswa) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbsetingujian su USING(idset) INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbujian u ON u.idujian=jd.idujian  INNER JOIN tbbanksoal bs USING(idbank) WHERE bs.idmapel='$_POST[idmap]' AND bs.idujian='$_POST[iduji]' AND rs.idrombel='$_POST[idrmb]' GROUP BY jwb.idset, jwb.idsiswa";
$qu = vquery($sqlu);
?>
<div class="col-sm-12">
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Ringkasan Hasil Tes</h4>
			<div class="card-tools">
				<button class="btn btn-sm btn-default col-xs-6" id="btnHasil">
					<i class="fas fa-arrow-circle-left"></i>&nbsp;Kembali
				</button>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tbhasiltes" class="table table-bordered table-striped table-sm">
					<thead>
						<tr>
							<th style="text-align: center;width:2.5%">No.</th>
							<th style="text-align: center;width:12.5%">No. Peserta</th>
							<th style="text-align: center;">Nama Peserta</th>
							<th style="text-align: center;width:12.5%">Kelas</th>
							<th style="text-align: center;width:12.5%">Hasil</th>
							<th style="text-align:center;width:12.5%">Detail</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($qu as $s) :
						?>
							<tr>
								<td style="text-align:center">
									<?php echo $no++ . '.'; ?>
								</td>
								<td style="text-align:center">
									<?php echo $s['nmpeserta']; ?>
								</td>
								<td>
									<?php echo ucwords(strtolower($s['nmsiswa'])); ?>
								</td>
								<td style="text-align:center">
									<?php echo $s['nmrombel']; ?>
								</td>
								<td style="text-align:center">
									<?php echo $s['benar'] . ' dari ' . $s['soal']; ?>
								</td>
								<td style="text-align:center">
									<form action="index.php?p=jawabantes" method="POST">
										<input type="hidden" id="idbank" name="idset" value="<?php echo $s['idset']; ?>">
										<input type="hidden" id="idsiswa" name="idsw" value="<?php echo $s['idsiswa']; ?>">
										<button type="submit" class="btn btn-xs btn-primary col-xs-6">
											<i class="fas fa-list"></i>&nbsp;Detail
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
	$("#btnHasil").click(function() {
		let data = new FormData()
		data.append('uji', "<?php echo $_POST['iduji']; ?>");
		// data.append('kls', kls);
		data.append('rmb', "<?php echo $_POST['idrmb']; ?>");
		$.ajax({
			url: "hasil_tes.php",
			type: 'POST',
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 8000,
			success: function(respons) {
				$("#konten").html(respons)
			}
		})
	})
	$(function() {
		$('#tbhasiltes').DataTable({
			"paging": true,
			"lengthChange": true,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
		})
	});
</script>