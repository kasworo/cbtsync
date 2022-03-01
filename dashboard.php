<div class="form-group row">
	<div class="col-sm-12">
		<div class="card card-outline card-success">
			<div class="card-header">
				<h4 class="card-title">Statistik Ujian</h4>
			</div>
			<div class="card-body">
				<div class="form-group row">
					<div class="col-sm-8 offset-sm-2">
						<p style="text-align:center">Status Peserta</p>
						<div class="chart">
							<canvas id="hslChart" style="width:100%"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include "../config/konfigurasi.php";
$skrg=date('Y-m-d');
$qst=$conn->query("SELECT COUNT(*) as kabeh, COUNT(CASE WHEN lp.status='0' THEN 1 END) as sedang, COUNT(CASE WHEN lp.status='1' THEN 1 END) as selesai FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbsesiujian su USING(idsiswa) INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbtoken tk USING(idjadwal, idsesi) INNER JOIN tbsetingujian st USING(idjadwal,idrombel) LEFT JOIN tblogpeserta lp USING(idsiswa, idjadwal) WHERE tk.status='1' AND jd.tglujian='$skrg'GROUP BY tk.idjadwal, tk.idsesi");
$st=$qst->fetch_array();
$belum=$st['kabeh']-$st['sedang']-$st['selesai'];
$sedang=$st['sedang'];
$selesai=$st['selesai'];
?>
<script>
	$(function () {
		var donutChartCanvas = $('#hslChart').get(0).getContext('2d')
		var donutData        = {
		labels: [
			'Sudah Selesai', 
			'Sedang Mengerjakan',
			'Belum Login', 
		],
		datasets: [
			{
			data: [<?php echo $selesai.','.$sedang.','.$belum;?>],
			backgroundColor : ['rgba(200,10,10,0.9)', 'rgba(0,250,0,0.9)', 'rgba(100,100,100,0.9)'],
			}
		]
		}
		var donutOptions     = {
			maintainAspectRatio : false,
			responsive : true,
		}
		var donutChart = new Chart(donutChartCanvas, {
			type: 'doughnut',
			data: donutData,
			options: donutOptions      
		})
	})
</script>

<div class="form-group row">
	<div class="col-sm-12">
		<div class="card card-outline card-success">
			<div class="card-header">
				<h4 class="card-title">Informasi Status Ujian</h4>
			</div>
			<div class="card-body">
			<?php
				$saiki=date('Y-m-d');
				$jame=date('H:i:s');
				$qjd=$conn->query("SELECT jd.tglujian, nmtes FROM tbjadwal jd INNER JOIN tbujian u USING(idujian) INNER JOIN tbtes ts USING(idtes) INNER JOIN tbsetingujian su USING(idjadwal) WHERE u.idthpel='$idthpel' AND u.status='1' GROUP BY jd.tglujian ORDER BY tglujian DESC");
				$cekjd=$qjd->num_rows;
				if($cekjd==0):		
			?>
				<div class="form-group row">
					<span class="alert alert-danger col-sm-12">
						<marquee><strong>Belum Ada Ujian Aktif!!!</strong></marquee>
					</span>
				</div>
			<?php else:?>
				<div class="form-group row">
					<div class="col-sm-12">
						<div class="timeline">
							<?php while($jd=$qjd->fetch_array()):	?>
							<div class="time-label">
								<span class="bg-red"><?php echo indonesian_date($jd['tglujian']);?></span>
							</div>
							<div>
								<i class="far fa-calendar bg-blue"></i>
								<?php
									$qsu=$conn->query("SELECT jd.idjadwal, jd.kdjadwal, jd.nmjadwal, jd.matauji, tk.status, s.idsesi, s.nmsesi, s.mulai, s.selesai FROM tbjadwal jd INNER JOIN tbujian u USING(idujian) INNER JOIN tbtoken tk USING(idjadwal) INNER JOIN tbsesi s USING(idsesi) WHERE u.idthpel='$idthpel' AND jd.tglujian='$jd[tglujian]' ORDER BY tk.idtoken DESC");
									while($su=$qsu->fetch_array()):
								?>
								<div class="timeline-item mb-3 mt-2">
								<?php
											if($jd['tglujian'] == $saiki && $su['mulai']>$jame && $su['selesai']>=$jame){
												$judul='Segera';
												$pesan='Segera Dimulai';
												$badge="badge-warning";
												$himbauan='Silahkan klik tombol <b>Token</b> untuk merilis token ujian dan klik tombol <b>Detail</b> untuk melihat daftar peserta ujian aktif pada saat ini.';
											}
											elseif($jd['tglujian'] == $saiki && $su['mulai']<=$jame && $su['selesai']>$jame){
												$judul='Sedang Berlangsung';
												$pesan='Saat ini Sedang berlangsung';
												$badge="badge-success";
												$himbauan='Silahkan klik tombol <b>Token</b> untuk merilis token ujian dan klik tombol <b>Detail</b> untuk melihat daftar peserta ujian aktif pada saat ini.';
											}
											else{
												$judul='Sudah Selesai';
												$pesan='Telah selesai diselenggarakan';
												$badge="badge-danger";
												$himbauan='Silahkan klik tombol <b>Daftar Hadir</b> untuk mencetak daftar hadir peserta ujian, dan tombol <b>Berita Acara</b> untuk mencetak berita acara pelaksanaan ujian.';
											}
										?>
									<h3 class="timeline-header">
										Info Tes <span class="badge <?php echo $badge;?>"><?php echo $judul;?></span>
									</h3>
									<div class="timeline-body">
										<div class="col-sm-10 offset-sm-1">
											<p>
												<?php echo $pesan.' '.$jd['nmtes'].' '.$su['matauji'];?> untuk <?php echo strtolower($su['nmsesi']);?>, dimulai <?php echo date('H:i', strtotime($su['mulai']));?> sampai <?php echo date('H:i',strtotime($su['selesai']));?> WIB.
											</p>
											<p>
												<?php echo $himbauan;?>
											</p>
										</div>
									</div>
									<div class="timeline-footer">
										<?php if($su['status']=='1'): ?>
											<a href="index.php?p=token" class="btn btn-secondary btn-xs">
												<i class="fas fa-qrcode"></i>&nbsp;Token
											</a>
											<a href="index.php?p=statuspeserta" class="btn btn-primary btn-xs">
												<i class="fas fa-edit"></i>&nbsp;Detail
											</a>
										<?php else: ?>
											<a href="print_absen.php?j=<?php echo $su['idjadwal'];?>&s=<?php echo $su['idsesi'];?>"  target="_blank" class="btn btn-success btn-xs">
												<i class="fas fa-print"></i>&nbsp;Daftar Hadir
											</a>
											<a href="print_berita.php?j=<?php echo $su['idjadwal'];?>&s=<?php echo $su['idsesi'];?>"  target="_blank" class="btn btn-info btn-xs">
												<i class="fas fa-print"></i>&nbsp;Berita Acara
											</a>
										<?php endif?>
									</div>
								</div>
								<?php endwhile?>
							</div>
						<?php endwhile ?>
						<div>
							<i class="fas fa-clock bg-gray"></i>
						</div>
						</div>
					</div>
				</div>
			<?php endif?>
			</div>
		</div>
	</div>
</div> 