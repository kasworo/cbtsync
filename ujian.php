<?php
if (!empty($_COOKIE['pst']) && !empty($_COOKIE['uji'])) :
	$sqlset = "SELECT su.idset, su.idjadwal FROM tbsetingujian su INNER JOIN tbrombelsiswa USING(idrombel) WHERE idsiswa='$_COOKIE[pst]'";;
	$ds = vquery($sqlset)[0];
	$idset = $ds['idset'];
	$idjadwal = $ds['idjadwal'];
	$qwk = "SELECT lp.sisawaktu, hal FROM tblogpeserta lp INNER JOIN tbjadwal jd USING(idjadwal) WHERE lp.idjadwal='$idjadwal' AND idsiswa='$_COOKIE[pst]'";
	$wk = vquery($qwk)[0];
	$hal = $wk['hal'];
	if ($hal <= 1) {
		$page = 1;
	} else {
		$page = $hal;
	}
	$durasi = $wk['sisawaktu'];
	if ($durasi > 0) {
		$jam = floor($durasi / 3600);
		$menit = floor(($durasi - ($jam * 3600)) / 60);
		$detik = $durasi - ($jam * 3600 + $menit * 60);
	}
	echo "<script>
			$(document).ready(function(){
				tampilsoal(" . $page . ")
			})
		</script>";

?>
	<link rel="stylesheet" href="ujian.css">
	<div class="modal fade" id="mySoal" aria-modal="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Daftar Soal</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="fetched-data"></div>
					<br />
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="card card-danger card-outline">
			<div class="card-header">
				<h3 class="card-title" id="nomor" style="font-size:14pt;font-weight:bold;">
				</h3>
				<div class="card-tools" id="hurufsoal">
					<a id="jfontsize-m2" class="btn btn-sm btn-default" href="#" title="Perkecil Font">
						<i class="fas fa-minus-circle fa-fw"></i>
					</a>
					<a id="jfontsize-d2" class="btn btn-sm btn-default" href="#" title="Reset Ukuran Font">
						<i class="fas fa-sync fa-fw"></i>
					</a>
					<a id="jfontsize-p2" class="btn btn-sm btn-default" href="#" title="Perbesar Font">
						<i class="fas fa-plus-circle fa-fw"></i>
					</a>
					<button data-id="" data-toggle="modal" data-target="#mySoal" class="btn btn-sm btn-success ViewJawab">
						<i class="fa fa-list fa-fw"></i><span class="d-xs-none d-none">&nbsp;Daftar Soal</span>
					</button>
				</div>
			</div>
			<div id="soal"></div>
		</div>
	</div>
	<script type="text/javascript">
		function timeisUp() {
			Swal.fire({
				title: "Mohon Maaf",
				text: "Waktu Ujian Anda Sudah Habis",
				icon: "error",
				showCancelButton: false,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "OK",
			}).then((result) => {
				if (result.value) {
					window.location.href = "index.php?p=end";
				}
			})
		}

		function simpanWaktu() {
			let jdw = "<?php echo $idjadwal; ?>";
			$.ajax({
				url: "simpanwaktu.php",
				type: "POST",
				data: "jdw=" + jdw,
				cache: false,
				success: function() {

				}
			})
		}
		$(function() {
			$('#u_timer').countdowntimer({
				hours: <?php echo $jam; ?>,
				minutes: <?php echo $menit; ?>,
				seconds: <?php echo $detik; ?>,
				size: "lg",
				timeUp: timeisUp
			});
			simpanWaktu();
		});

		function tampilsoal(h) {
			let data = new FormData()
			data.append('h', h)
			data.append('set', "<?php echo $idset; ?>")
			$.ajax({
				url: "viewsoal.php",
				type: 'POST',
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 8000,
				success: function(resp) {
					$("#soal").html(resp)
					$("#nomor").html('Soal Nomor ' + h)
				}
			})

		}
		$(".ViewJawab").click(function() {
			let id = "<?php echo $_COOKIE['pst']; ?>"
			let ib = $(this).data('id');
			$.ajax({
				url: 'viewjawab.php',
				type: 'post',
				data: 'id=' + id + '&ib=' + ib,
				success: function(data) {
					$(".fetched-data").html(data)
				}
			})
		})
	</script>
<?php else : ?>
	<script type="text/javascript">
		$(document).ready(function() {
			Swal.fire({
				title: "Mohon Maaf",
				text: "Kamu Tidak Diizinkan Ujian Lagi",
				icon: 'error',
				showCancelButton: false,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'OK',
			}).then((result) => {
				if (result.value) {
					window.location.href = "index.php?p=conf";
				}
			})
		})
	</script>
<?php endif ?>