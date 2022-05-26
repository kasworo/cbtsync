<?php
if (isset($_POST['mulai'])) {
	$ipaddr = $_SERVER['REMOTE_ADDR'];
	$saiki = date('Y-m-d H:i:s');
	$jamx = date('H:i:s');
	$qjd = "SELECT t.idsesi,t.idjadwal, j.lambat, j.durasi, TIME_TO_SEC(timediff('$jamx', j.mulai)) as selisih FROM tbtoken t INNER JOIN tbjadwal j USING(idjadwal) WHERE t.status='1'";
	$jd = vquery($qjd)[0];
	$idjadwal = $jd['idjadwal'];
	$durasi = $jd['durasi'];
	$sesi = $jd['idsesi'];
	$selisih = $jd['selisih'];
	$ceksisa = $durasi * 60 - $selisih;
	if ($ceksisa < 0) {
		echo '<script type="text/javascript">
		$(document).ready(function() {
			Swal.fire({
				title: "Mohon Maaf",
				text: "Waktu Login Anda Sudah Habis",
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
		})
	</script>';
	} else {
		$qceklog = "SELECT*FROM tblogpeserta lp WHERE idjadwal='$idjadwal' AND idsiswa='$_COOKIE[pst]' AND lp.status='0'";
		$ceklog = cquery($qceklog);

		if ($ceklog == 0) {

			if ($jd['lambat'] == '0') {
				$sisa = $durasi * 60;
				$status = '0';
			} else {
				if ($selisih >= 1800) {
					$sisa = $durasi * 60 - $selisih;
					$status = '0';
				} else {
					$sisa = $durasi * 60;
					$status = '0';
				}
			}
			$datane = array(
				'idsiswa' => $_COOKIE['pst'],
				'idjadwal' => $idjadwal,
				'logmulai' => $saiki,
				'sisawaktu' => $sisa,
				'status' => $status,
				'ip' => $ipaddr,
				'hal'=>'1'
			);
			if (adddata('tblogpeserta', $datane) > 0) {
				header("Location: index.php?p=ujian");
			}
		} else {
			$key = array(
				'idsiswa' => $_COOKIE['pst'],
				'idjadwal' => $idjadwal,
				'status' => '0'
			);
			$datane = array(
				'logakhir' => $saiki,
				'sisawaktu' => $ceksisa,
				'ip' => $ipaddr
			);
			if (editdata('tblogpeserta', $datane, '', $key) > 0) {
				header("Location: index.php?p=ujian");
			}
		}
	}
}
?>
<div class="col-sm-8 offset-sm-2">
	<form id="frmMulai" action="" method="POST">
		<div class="card card-danger card-outline">
			<div class="card-header">
				<h4 class="card-titles text-center">Petunjuk Umum</h4>
			</div>
			<div class="card-body">
				<div class="form-group col-sm-10 col-sm-offset-1">
					<p align="justify">
						<strong>
							Pastikan Anda sudah Logout dengan benar pada Ujian Sebelumnya, atau ujian saat ini akan langsung selesai.
						</strong>
					</p>
					<p align="justify">
						Berdo'alah sesuai dengan agama dan kepercayaan masing-masing sebelum mengerjakan soal.
					</p>
					<p align="justify">
						Kerjakan soal dari yang paling mudah terlebih dahulu, dengan teliti dan sebenar-benarnya.
					</p>
					<p align="justify">
						Dilarang keras membawa, menyimpan, dan membuka catatan (contekan) dalam bentuk apapun.
					</p>
					<p align="justify">
						Penskoran pada tiap butir soal yang berlaku adalah sistem skoring pada aplikasi sesuai dengan bentuk atau tipe soalnya.
					</p>
				</div>
				<hr />
				<div class="form-group col-sm-10 col-sm-offset-1">
					<p style="color:blue;"><strong>Klik setuju kemudian klik tombol Mulai untuk memulai ujian.</strong></p>
					<div class="col-sm-12">
						<div class="form-check">
							<input type="checkbox" class="form-check-input" id="chkmulai" name="cek">
							<label class="form-check-label" for="chkmulai"><b>Setuju</b></label>
						</div>
					</div>
				</div>
			</div>
			<script type="text/javascript">
				$("#chkmulai").click(function() {
					if ($(this).is(":checked")) {
						$("#btnMulai").attr("disabled", false)
					} else {
						$("#btnMulai").attr("disabled", true)
					}
				})
			</script>
			<div class="card-footer">
				<div class="text-center">
					<button class="btn btn-danger btn-md col-md-2 mb-2" name="mulai" id="btnMulai" disabled="true">
						<i class="far fa-check-square"></i>&nbsp;Mulai
					</button>
				</div>
			</div>
		</div>
	</form>
</div>