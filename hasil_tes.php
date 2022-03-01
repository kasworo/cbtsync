<div class="col-sm-12">
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Laporan Hasil Ujian</h4>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tb_banksoal" class="table table-bordered table-striped table-sm">
				<?php if($level=='1'): ?>
					<thead>
						<tr>
							<th style="text-align: center;width:2.5%">No.</th>
							<th style="text-align: center;width:10.5%">Kelas</th>
							<th style="text-align: center;width:35%">Mata Pelajaran</th>
							<th style="text-align: center">Pembuat Soal </th>
							<th style="text-align: center;width:20%">Aksi</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$qk=$conn->query("SELECT bs.*,k.nmkelas, mp.nmmapel, us.nama FROM tbbanksoal bs INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbkelas k USING(idkelas) INNER JOIN tbsetingujian su USING(idbank) INNER JOIN tbujian uj USING(idujian) INNER JOIN tbuser us USING(username) WHERE uj.status='1' GROUP BY su.idbank ORDER BY bs.idmapel, bs.idkelas");
						$no=0;
						while($m=$qk->fetch_array()):
						$no++;
					?>
						<tr>
							<td style="text-align:center">
								<?php echo $no.'.';?>
							</td>
							<td style="text-align:center">
								<?php echo $m['nmkelas'];?>
							</td>
							<td>
								<?php echo $m['nmmapel'];?>
							</td>
							<td>
								<?php echo $m['nama'];?>
							</td>
							<td style="text-align: center">
								<a href="index.php?p=detailtes&id=<?php echo $m['idbank'];?>" class="btn btn-xs btn-success btnDetail col-xs-6">
									<i class="fas fa-eye"></i>&nbsp;Detail
								</a>
								<a href="print_hasil.php?id=<?php echo $m['idbank'];?>" target="_blank" class="btn btn-xs btn-default col-xs-6">
									<i class="fas fa-print"></i>&nbsp;Cetak
								</a>
							</td>
						</tr>
						<?php endwhile ?>
					</tbody>
				<?php else: ?>
					<thead>
						<tr>
							<th style="text-align: center;width:2.5%">No.</th>
							<th style="text-align: center;width:10.5%">Kelas</th>
							<th style="text-align: center;width:35%">Mata Pelajaran</th>
							<th style="text-align: center">Kode Bank Soal</th>
							<th style="text-align: center;width:20%">Aksi</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$sql="SELECT p.idrombel, bs.idbank, rb.nmrombel, bs.nmbank, mp.nmmapel FROM tbsetingujian su INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) INNER JOIn tbkelas k USING(idkelas) INNER JOIN tbrombel rb USING(idkelas,idrombel) INNER JOIN tbujian uj USING(idujian) INNER JOIN tbtes ts USING(idtes) INNER JOIN tbpengampu p USING(idmapel, idrombel) INNER JOIN tbuser us ON us.username=p.username WHERE p.username='$useraktif' AND uj.status='1' GROUP BY p.idrombel, p.idmapel ORDER BY bs.idmapel, p.idrombel ";
						$no=0;
						$qk=$conn->query($sql);
						while($m=$qk->fetch_array()):
							$no++;
					?>
					<tr>
						<td style="text-align:center">
							<?php echo $no.'.';?>
						</td>
						<td style="text-align:center">
							<?php echo $m['nmrombel'];?>
						</td>
						<td>
							<?php echo $m['nmmapel'];?>
						</td>
						<td>
							<?php echo $m['nmbank'];?>
						</td>
						<td style="text-align: center">
							<a href="index.php?p=detailtes&id=<?php echo $m['idbank'].'&r='.$m['idrombel'];?>" class="btn btn-xs btn-success btnDetail col-xs-6">
								<i class="fas fa-eye"></i>&nbsp;Detail
							</a>
							<a href="print_hasil.php?id=<?php echo $m['idbank'].'&r='.$m['idrombel'];?>" target="_blank" class="btn btn-xs btn-default col-xs-6">
								<i class="fas fa-print"></i>&nbsp;Cetak
							</a>
						</td>
					</tr>
					<?php endwhile ?>
				</tbody>
				<?php endif ?>
				
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
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
	
	$("#btnRefresh").click(function(){
		window.location.reload();
	})
</script>