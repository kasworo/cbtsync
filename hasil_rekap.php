<?php
	$qts=$conn->query("SELECT u.idujian, u.nmujian, ts.nmtes FROM tbujian u INNER JOIN tbtes ts ON ts.idtes=u.idtes INNER JOIN tbthpel t ON t.idthpel=u.idthpel WHERE t.aktif='1' AND status='1'");
	$ts=$qts->fetch_array();
	$iduji=$ts['idujian'];
?>
<div class="col-sm-12">
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Rekap Hasil Ujian</h4>
			<div class="card-tools">
				<?php if($level=='1'): ?>
				<button class="btn btn-success btn-sm" id="btnUpdate">
					<i class="fas fa-sync-alt"></i>&nbsp;Update
				</button>
				<?php endif ?>
				<a href="index.php?p=rapor" target="_blank" class="btn btn-info btn-sm">
					<i class="fas fa-list-alt"></i>&nbsp;Rapor
				</a>
				<a href="print_rekap.php" target="_blank" class="btn btn-default btn-sm">
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
						<?php
						$qmp=$conn->query("SELECT*FROM tbmapel");
						$i=0;
						while($mp=$qmp->fetch_array()):
							$i++;
						?>
						<th style="text-align:center;width:5%">
							<?php echo $mp['akmapel'];?></th>
						<?php endwhile ?>
					</tr>
					</thead>
					<tbody>
					<?php
						if($level=='1'){
							$qrekap="SELECT s.idsiswa, s.nmsiswa, s.nmpeserta, s.passwd, r.nmrombel, u.nmujian FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t USING(idthpel) INNER JOIN tbujian u USING(idujian) WHERE u.status='1' AND t.aktif='1' AND s.nmpeserta<>'' GROUP BY s.idsiswa ORDER BY s.nmpeserta ASC";
						}
						else {
							$qrekap="SELECT s.idsiswa, s.nmsiswa, s.nmpeserta, s.passwd, r.nmrombel, u.nmujian FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t USING(idthpel) INNER JOIN tbujian u USING(idujian) WHERE u.status='1' AND t.aktif='1' AND s.nmpeserta<>'' AND r.username='$useraktif' GROUP BY s.idsiswa ORDER BY s.nmpeserta ASC";
							
						}
						$no=0;
						$qs=$conn->query($qrekap);
						while($s=$qs->fetch_array()):
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
						<?php
						$qmp=$conn->query("SELECT*FROM tbmapel");
						$i=0;
						while($mp=$qmp->fetch_array()):
							$i++;
						?>
						<td style="text-align:center;width:3.5%">
							<?php
								$qnilai=$conn->query("SELECT nilai FROM tbnilai n INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbujian u ON u.idujian=n.idujian AND u.idujian=bs.idujian INNER JOIN tbmapel mp USING(idmapel) WHERE n.idsiswa='$s[idsiswa]' AND bs.idmapel='$mp[idmapel]' AND u.status='1'");
								$nil=$qnilai->fetch_array();
								if($nil['nilai']==0){
									echo '-';
								}
								else{
									echo number_format($nil['nilai'],2,',','.');
								}
							?>
						</td>
						<?php endwhile ?>
					</tr>
					<?php endwhile ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
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
	$("#btnUpdate").click(function(){
		var id="<?php echo $iduji;?>";	
		$.ajax({
			url:"hasil_update.php",
			type:"POST",
			data:"u="+id,
			success:function(data)
			{
				toastr.success(data);
			}
		})
	})
</script>