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
					pointColor: '#3b8bba',
					pointStrokeColor: 'rgba(60,141,188,1)',
					pointHighlightFill: '#fff',
					pointHighlightStroke: 'rgba(60,141,188,1)',
					data: <?php echo $jmlco; ?>
				},
				{
					label: 'Perempuan',
					backgroundColor: 'rgba(210, 214, 222, 1)',
					borderColor: 'rgba(210, 214, 222, 1)',
					pointRadius: true,
					pointColor: 'rgba(210, 214, 222, 1)',
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
				'Sudah Selesai',
				'Sedang Mengerjakan',
				'Belum Login',
			],
			datasets: [{
				data: ['10', '20', '23'],
				backgroundColor: ['rgba(200,10,10,0.9)', 'rgba(0,250,0,0.9)', 'rgba(100,100,0,0.9)'],
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