<?php
	$saiki=date('Y-m-d');
	$qjd=$conn->query("SELECT jd.idjadwal, tk.idsesi FROM tbjadwal jd INNER JOIN tbtoken tk USING(idjadwal) WHERE tglujian='$saiki' AND tk.status='1'");
	$jd=$qjd->fetch_array();
	$jadwal=$jd['idjadwal'];
	$idsesi=$jd['idsesi'];
?>
<div class="col-sm-12">
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
			    <?php if($level=='1'):?>
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
		        if($level=='1'){
					$sql="SELECT ps.idsiswa,ps.nmsiswa, ps.nmpeserta, ps.passwd, ru.nmruang, lp.status, st.idset, lp.sisawaktu FROM tbpeserta ps INNER JOIN tbruang ru USING(idruang) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbtoken tk USING(idjadwal, idsesi) INNER JOIN tbsetingujian st USING(idjadwal,idrombel) INNER JOIN tblogpeserta lp USING(idsiswa, idjadwal) WHERE jd.tglujian='$saiki' AND tk.status='1' GROUP BY ps.idsiswa,st.idbank, su.idsesi ORDER BY ps.nmpeserta";
					
				}
				else {
					$sql="SELECT ps.idsiswa,ps.nmsiswa, ps.nmpeserta, ps.passwd, ru.nmruang, lp.status, st.idset, lp.sisawaktu FROM tbpeserta ps INNER JOIN tbruang ru USING(idruang) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbtoken tk USING(idsesi, idjadwal) INNER JOIN tbsetingujian st USING(idjadwal,idrombel) LEFT JOIN tblogpeserta lp USING(idsiswa, idjadwal) INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbpengampu a USING(idrombel,idmapel) WHERE tk.status='1' AND rb.username='$useraktif' OR a.username='$useraktif' AND jd.tglujian='$saiki' GROUP BY ps.idsiswa,st.idbank ORDER BY ps.nmpeserta";
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
						$no=0;
						$qs=$conn->query($sql);
						while($s=$qs->fetch_array()):
						$no++;
                        if($s['status']=='0'){
                            $status="Sedang Mengerjakan";
                            $bdg="color:green";
                        }
                        else if($s['status']=='1'){
                            $status="Tes Selesai";
                            $bdg="color:red";
                        }
                        else{
                            $status="Belum Login";
                            $bdg="color:black";                            
                        }
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
                        <td>
                            <?php
								$qskor=$conn->query("SELECT COUNT(*) as kabeh, COUNT(CASE WHEN jwbbenar IS NOT NULL THEN 1 END) as terisi FROM tbjawaban WHERE idsiswa='$s[idsiswa]' AND idset='$s[idset]' GROUP BY idsiswa");
								$skr=$qskor->fetch_array();
								$terisi=$skr['terisi'];
								$kabeh=$skr['kabeh'];
								if($terisi>0){
									echo $terisi.' dari '.$kabeh. ' Soal';
								}
								else
								{
									echo 'Belum Mengerjakan';
								}
                            ?>
						</td>
						<td style="text-align:center">
						    <?php echo $s['sisawaktu'];?>
						</td>
                        <td>
						    <span style="<?php echo $bdg;?>"><?php echo $status;?></span>
						</td>
						<td style="text-align:center;">
							<?php if($s['status']=='1'): ?>
						    <button class="btn btn-xs btn-primary col-10 btnReset" data-id="<?php echo $s['idsiswa'];?>">
								<i class="fas fa-sync-alt"></i>&nbsp;Reset
							</button>
							<?php else: ?>
							<button class="btn btn-xs btn-danger col-10 btnLogout" data-id="<?php echo $s['idsiswa'];?>">
								<i class="fas fa-power-off"></i>&nbsp;Logout
							</button>
							<?php endif?>
						</td>
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
	$(".btnReset").click(function(){
		var id=$(this).data('id');
		var jadwal="<?php echo $jadwal;?>";
		$.ajax({
        url:'status_update.php',
			type:'post',
			data:'aksi=1&id='+id+'&jd='+jadwal,
			success:function(data)
			{
				toastr.success(data);
			}
		})
	})
	$("#btnReset").click(function(){
		var jadwal="<?php echo $jadwal;?>";
		$.ajax({
        url:'status_update.php',
			type:'post',
			data:'aksi=1&jd='+jadwal,
			success:function(data)
			{
				toastr.success(data);
			}
		})
	})
	$(".btnLogout").click(function(){
		var id=$(this).data('id');
		var jadwal="<?php echo $jadwal;?>";
		$.ajax({
        url:'status_update.php',
			type:'post',
			data:'aksi=2&id='+id+'&jd='+jadwal,
			success:function(data)
			{
				toastr.success(data);
			}
		})
	})

	$("#btnLogout").click(function(){
		var jadwal="<?php echo $jadwal;?>";
		$.ajax({
        url:'status_update.php',
			type:'post',
			data:'aksi=2&jd='+jadwal,
			success:function(data)
			{
				toastr.success(data);
			}
		})
	})
</script>