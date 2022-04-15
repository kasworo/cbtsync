<div class="col-sm-12">
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Data Peserta Tes</h4>
			<div class="card-tools">
				<a href="index.php?p=hasiltes" class="btn btn-primary btn-sm">
					<i class="fas fa-arrow-left"></i>&nbsp;Kembali
				</a>
				<a href="hasil_analisis.php?id=<?php echo $_GET['id'];?>" target="_blank" class="btn btn-success btn-sm">
					<i class="far fa-file-excel"></i>&nbsp;Analisis
				</a>
				<?php if(isset($_GET['r'])):?>
				<a href="print_hasil.php?id=<?php echo $_GET['id'].'&r='.$_GET['r'];?>" target="_blank" class="btn btn-default btn-sm">
					<i class="fas fa-file-pdf"></i>&nbsp;Cetak
				</a>
				<?php else:?>
				<a href="print_hasil.php?id=<?php echo $_GET['id'];?>" target="_blank" class="btn btn-default btn-sm">
					<i class="fas fa-file-pdf"></i>&nbsp;Cetak
				</a>
				<?php endif ?>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tbpeserta" class="table table-bordered table-striped table-sm">
					<thead>
					<tr>
						<th style="text-align: center;width:2.5%">No.</th>
						<th style="text-align: center;width:12.5%">No. Peserta</th>
						<th style="text-align: center;">Nama Peserta</th>
						<th style="text-align: center;width:12.5%">Kelas</th>
						<th style="text-align: center;width:12.5%">Skor</th>
						<th style="text-align:center;width:12.5%">Detail</th>
					</tr>
					</thead>
					<tbody>
					<?php
						if(isset($_GET['r'])){
							$sql="SELECT ps.idsiswa, ps.nmsiswa, ps.nmpeserta,rb.idrombel, rb.nmrombel, bs.idbank, SUM(jw.skor) as benar FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbthpel t USING(idthpel) INNER JOIN tbjawaban jw USING(idsiswa) INNER JOIN tbsoal so USING(idbutir) INNER JOIN tbbanksoal bs USING(idbank) WHERE bs.idbank='$_GET[id]' AND rb.idrombel='$_GET[r]' AND t.aktif='1' GROUP BY jw.idsiswa, bs.idbank";
							
						}
						else{
							$sql="SELECT ps.idsiswa, ps.nmsiswa, ps.nmpeserta,rb.idrombel, rb.nmrombel, bs.idbank, SUM(jw.skor) as benar FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbthpel t USING(idthpel) INNER JOIN tbjawaban jw USING(idsiswa) INNER JOIN tbsoal so USING(idbutir) INNER JOIN tbbanksoal bs USING(idbank) WHERE bs.idbank='$_GET[id]' AND t.aktif='1' GROUP BY jw.idsiswa, bs.idbank";
						}
						$no=0;
						$qs=$conn->query($sql);
						while($s=$qs->fetch_array())
						{
                        	$no++;                        
					?>
					<tr>
						<td style="text-align:center">
						<?php echo $no.'.';?>
						</td>
						<td style="text-align:center">
						<?php echo $s['nmpeserta'];?>
						</td>
						<td>
						<?php echo ucwords(strtolower($s['nmsiswa']));?>
						</td>
                        <td style="text-align:center">
						    <?php echo $s['nmrombel'];?>
						</td>
                        <td style="text-align:center">
                            <?php echo number_format($s['benar'],2,',','.');?>
						</td>
                        <td style="text-align:center">
						    <a href="index.php?p=jawabantes&id=<?php echo $s['idbank'];?>&pst=<?php echo $s['idsiswa'];?>" class="btn btn-xs btn-info col-8 <?php echo $bdg;?>">
								<i class="fas fa-eye"></i>&nbsp;Detail
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
	$(function () {
		$('#tbpeserta').DataTable({
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