<?php
include "dbfunction.php";
$user = array(
	'username' => $_COOKIE['id']
);

$u = viewdata('tbuser', $user)[0];
$level = $u['level'];
?>
<div class="card card-secondary card-outline">
	<div class="card-header">
		<h4 class="card-title">Laporan Hasil Ujian</h4>
		<div class="card-tools">
			<a href="#myLaporTes" class="btn btn-sm btn-default btnReport" data-toggle="modal" data-id="2">
				<i class="fas fa-list-alt nav-icon"></i>&nbsp;Rekap Hasil Tes
			</a>
			<a href="#myLaporTes" class="btn btn-sm btn-default btnReport" data-toggle="modal" data-id="3">
				<i class="fas fa-list-alt nav-icon"></i>&nbsp;Rapor Murni
			</a>
			<a href="#myLaporTes" class="btn btn-sm btn-default btnReport" data-toggle="modal" data-id="1">
				<i class="fas fa-list-alt nav-icon"></i>&nbsp;Hasil Tes Lain
			</a>
		</div>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table id="tb_banksoal" class="table table-bordered table-striped table-sm">
				<thead>
					<tr>
						<th style="text-align: center;width:2.5%">No.</th>
						<th style="text-align: center;width:10.5%">Kelas</th>
						<th style="text-align: center;width:35%">Mata Pelajaran</th>
						<th style="text-align: center">Guru Bidang Studi</th>
						<th style="text-align: center;width:20%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ($level == '1') {
						$uji = viewdata('tbujian', array('idujian' => $_POST['uji']))[0];
						$iduji = $uji['idujian'];
						$sqkls = "SELECT pg.idrombel, rb.nmrombel, mp.nmmapel, g.nama, mp.idmapel FROM tbpengampu pg INNER JOIN tbgtk g USING(idgtk) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbrombel rb USING(idrombel)INNER JOIN tbthpel tp USING(idthpel) WHERE tp.aktif='1' AND pg.idrombel='$_POST[rmb]' ORDER BY pg.idrombel, pg.idmapel";
					}
					if ($level == '2') {
						$uji = viewdata('tbujian', array('status' => '1'))[0];
						$iduji = $uji['idujian'];
						$sqkls = "SELECT pg.idrombel, rb.nmrombel, mp.nmmapel, g.nama, mp.idmapel FROM tbpengampu pg INNER JOIN tbgtk g USING(idgtk) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbthpel tp USING(idthpel) INNER JOIN tbuser us USING(username) WHERE username='$_COOKIE[id]' AND tp.aktif='1' ORDER BY pg.idrombel, pg.idmapel";
					}
					$no = 0;
					$qk = vquery($sqkls);
					foreach ($qk as $m) :
						$no++;
					?>
						<tr>
							<td style="text-align:center">
								<?php echo $no . '.'; ?>
							</td>
							<td style="text-align:center">
								<?php echo $m['nmrombel']; ?>
							</td>
							<td>
								<?php echo $m['nmmapel']; ?>
							</td>
							<td>
								<?php echo $m['nama']; ?>
							</td>
							<td style="text-align: center">
								<div class="form-inline justify-content-center ml-auto">
									<form action="index.php?p=detailtes" method="POST">
										<input type="hidden" id="iduji" name="iduji" value="<?php echo $iduji; ?>">
										<input type="hidden" id="idmapel" name="idmap" value="<?php echo $m['idmapel']; ?>">
										<input type="hidden" id="idrombel" name="idrmb" value="<?php echo $m['idrombel']; ?>">
										<button type="submit" class="btn btn-xs btn-primary col-xs-6">
											<i class="fas fa-eye"></i>&nbsp;Lihat
										</button>
									</form>
									&nbsp;
									<form action="print_hasil.php" target="_blank" method="POST">
										<input type="hidden" id="iduji" name="uji" value="<?php echo $iduji; ?>">
										<input type="hidden" id="idmapel" name="map" value="<?php echo $m['idmapel']; ?>">
										<input type="hidden" id="idrombel" name="rmb" value="<?php echo $m['idrombel']; ?>">
										<button type="submit" class="btn btn-xs btn-default col-xs-6">
											<i class="fas fa-print"></i>&nbsp;Cetak
										</button>
									</form>
								</div>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
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
	$(".btnReport").click(function(e) {
		e.preventDefault();
		let id = $(this).data('id');
		if (id == '1') {
			$("#judule").html("Laporan Hasil Ujian");
		}
		if (id == '2') {
			$("#judule").html("Rekapitulasi Hasil Ujian");
		}
		if (id == '3') {
			$("#judule").html("Rapor Peserta Didik");
		}
		$("#idlapor").val(id);
	})

	$("#btnRefresh").click(function() {
		window.location.reload();
	})
</script>