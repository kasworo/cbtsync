<div class="form-group row">
	<div class="col-sm-12">
		<div class="card card-outline card-success">
			<div class="card-header">
				<h4 class="card-title">Informasi Status Ujian</h4>
			</div>
			<div class="card-body">
				<div class="form-group row">
					<div class="col-sm-6">
						<p style="text-align:center">Data Peserta</p>
						<div class="chart">
							<canvas id="pstChart" style="width:100%"></canvas>
						</div>
					</div>
					<div class="col-sm-6">
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
$qkls = "SELECT nmkelas FROM tbkelas INNER JOIN tbskul USING(idjenjang)";
$kls = vquery($qkls);
$kelas = [];
foreach ($kls as $kl) {
	$kelas[] = $kl['nmkelas'];
}
$kelase = json_encode($kelas);

$qcowok = "SELECT COUNT(*) as cowok FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbkelas k USING(idkelas) INNER JOIN tbthpel tp USING(idthpel) WHERE tp.aktif='1' AND ps.gender='L' GROUP BY rb.idkelas";
$laki = vquery($qcowok);
$co = [];
foreach ($laki as $lk) {
	$co[] = $lk['cowok'];
}
$jmlco = json_encode($co);

$qcewek = "SELECT COUNT(*) as cewek FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbkelas k USING(idkelas) INNER JOIN tbthpel tp USING(idthpel) WHERE tp.aktif='1' AND ps.gender='P' GROUP BY rb.idkelas";
$prm = vquery($qcewek);
$ce = [];
foreach ($prm as $pr) {
	$ce[] = $pr['cewek'];
}
$jmlce = json_encode($ce);
$skrg = date('Y-m-d');


$qpsesi = "SELECT COUNT(ps.idsiswa) as kabeh FROM tbpeserta ps INNER JOIN tbujian u USING(idujian) WHERE u.status='1'";
$ses = vquery($qpsesi)[0];
$kabeh = $ses['kabeh'];

$qpst = "SELECT SUM(CASE WHEN lp.status = '0' THEN 1 ELSE 0 END) as sedang, SUM(CASE WHEN lp.status = '1' THEN 1 ELSE 0 END) as rampung, COUNT(*) as semua FROM tblogpeserta lp INNER JOIN tbtoken tk USING(idjadwal) INNER JOIN tbjadwal jd USING(idjadwal) WHERE jd.tglujian='$skrg' AND tk.status='1'";
if (cquery($qpst) > 0) {
	$ps = vquery($qpst)[0];
	$sedang = $ps['sedang'];
	$rampung = $ps['rampung'];
	$semua = $ps['semua'];
} else {
	$sedang = 0;
	$rampung = 0;
}
$stat = array($kabeh - $semua, $sedang, $rampung);
$statistik = json_encode($stat);
?>
<script type="text/javascript">
	$(function() {
		let barChartCanvas = $('#pstChart').get(0).getContext('2d')
		let barData = {
			labels: <?php echo $kelase; ?>,
			datasets: [{
					label: 'Laki-laki',
					backgroundColor: 'rgba(60,141,188,0.9)',
					borderColor: 'rgba(60,141,188,0.8)',
					pointRadius: true,
					pointColor: 'rgba(210, 21,0, 1)',
					pointStrokeColor: 'rgba(60,141,188,1)',
					pointHighlightFill: '#fff',
					pointHighlightStroke: 'rgba(60,141,188,1)',
					data: <?php echo $jmlco; ?>
				},
				{
					label: 'Perempuan',
					backgroundColor: 'rgba(210, 21,0, 1)',
					borderColor: 'rgba(210, 21,0, 1)',
					pointRadius: true,
					pointColor: 'rgba(0,0,255, 1)',
					pointStrokeColor: '#c1c7d1',
					pointHighlightFill: '#fff',
					pointHighlightStroke: 'rgba(220,220,220,1)',
					data: <?php echo $jmlce; ?>
				},
			]
		}
		let barOptions = {
			maintainAspectRatio: true,
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						suggestedMin: 10,
						suggestedMax: 80
					}
				}]
			}

		}
		let barChart = new Chart(barChartCanvas, {
			type: 'bar',
			data: barData,
			options: barOptions
		})

		let donutChartCanvas = $('#hslChart').get(0).getContext('2d')
		let donutData = {
			labels: [
				'Belum Login',
				'Sedang Mengerjakan',
				'Selesai',
			],
			datasets: [{
				data: <?php echo $statistik; ?>,
				backgroundColor: ['rgba(100,100,100,0.9)', 'rgba(132,180,100,0.9)', 'rgba(200,10,10,0.9)'],
			}]
		}
		let donutOptions = {
			maintainAspectRatio: false,
			responsive: true,
		}
		let donutChart = new Chart(donutChartCanvas, {
			type: 'doughnut',
			data: donutData,
			options: donutOptions
		})
	});
</script>