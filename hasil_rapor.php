<div class="col-sm-12">
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Rekap Rapor</h4>
			<div class="card-tools">
				<a href="print_rapor.php" target="_blank" class="btn btn-default btn-sm">
					<i class="fas fa-print"></i>&nbsp;Cetak
				</a>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tb_peserta" class="table table-bordered table-striped table-sm">
					<thead>
					<tr>
						<th style="text-align: center;width:2.5%">No.</th>
						<th style="text-align: center;width:12.5%">No. Peserta</th>
						<th style="text-align: center;">Nama Peserta</th>
						<th style="text-align: center;width:12.5%">Kelas</th>
						<th style="text-align:center;width:10%">Jumlah</th>
						<th style="text-align:center;width:10%">Rata-rata</th>
						<th style="text-align:center;width:15%">Aksi</th>
					</tr>
					</thead>
					<tbody>
					<?php
						if($level=='1'){
							$qs=$conn->query("SELECT s.idsiswa, s.nmsiswa, s.nmpeserta, COUNT(n.nilai)as cacah, SUM(ROUND(n.nilai,2)) as jml, AVG(ROUND(n.nilai,2)) as rata, rb.nmrombel, u.idthpel FROM tbnilai n INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbpeserta s USING(idsiswa) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbujian u ON s.idujian=u.idujian AND s.idujian=n.idujian INNER JOIN tbthpel t ON t.idthpel=rb.idthpel AND t.idthpel=u.idthpel WHERE t.aktif='1' AND u.status='1' GROUP BY n.idsiswa, n.idujian ORDER BY jml DESC");
						}
						else {
							$qs=$conn->query("SELECT s.idsiswa, s.nmsiswa, s.nmpeserta, SUM(n.nilai) as jml, AVG(n.nilai) as rata, rb.nmrombel, u.idthpel FROM tbnilai n INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbpeserta s USING(idsiswa) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbujian u ON s.idujian=u.idujian AND s.idujian=n.idujian INNER JOIN tbthpel t ON t.idthpel=rb.idthpel AND t.idthpel=u.idthpel WHERE t.aktif='1' AND u.status='1' AND rb.username='$_COOKIE[id]' AND u.status='1' GROUP BY n.idsiswa, n.idujian ORDER BY jml DESC");
						}
						$no=0;
						while($s=$qs->fetch_array())
						{
						$no++;
					?>
					<tr>
						<td style="text-align:center">
						<?php echo $no.'.';?>
						</td>
						<td style="text-align:center" title="Password:<?php echo $s['passwd'];?>">
						<?php echo $s['nmpeserta'];?>
						</td>
						<td>
						<?php echo ucwords(strtolower($s['nmsiswa']));?>
						</td>
						<td style="text-align:center">
							<?php echo $s['nmrombel'];?>
						</td>
						<td style="text-align:center;">
							<?php echo number_format($s['jml'],1,',','.');?>
						</td>
						<td style="text-align:center;">
							<?php echo number_format($s['rata'],2,',','.');?>
						</td>
						<td style="text-align:center">
							<a href="print_rapor.php?id=<?php echo $s['nmpeserta'];?>" target="_blank" class="btn btn-secondary btn-xs">
								<i class="fas fa-print"></i> Cetak
							</a>
						</td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		$('#tb_peserta').DataTable({
			"paging": true,
			"lengthChange": true,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true,
		});
	})
</script>