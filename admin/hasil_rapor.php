<?php
include "dbfunction.php";
$idujian = $_POST['uji'];
if (isset($_POST['setrapor'])) {
	$keyset = array('idujian' => $idujian);
	$data = array(
		'tmpterbit' => $_POST['tmpterbit'],
		'tglterbit' => $_POST['tglterbit']
	);
	if (cekdata('tbsetrapor', $keyset) > 0) {
		$row = editdata('tbsetrapor', $data, '', $keyset);
	} else {
		$row = adddata('tbsetrapor', $data);
	}
}

$user = array(
	'username' => $_COOKIE['id']
);

$u = viewdata('tbuser', $user)[0];
$level = $u['level'];
?>
<div class="modal fade" id="mySetRapor" aria-modal="true">
	<div class="modal-dialog">
		<form action="" method="post">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="mdSetRaporJdl">Tambah Setting Cetak Rapor</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-sm-12">
						<div class="form-group row mb-2">
							<label class="col-sm-5">Dikeluarkan Di</label>
							<div class="col-sm-6">
								<input type="hidden" class="form-control form-control-sm" name="idset" id="idset">
								<input class="form-control form-control-sm" name="tmpterbit" id="tmpterbit">
							</div>
						</div>
						<div class="form-group row mb-2">
							<label class="col-sm-5">Tanggal Dikeluarkan</label>
							<div class="col-sm-6">
								<input class="form-control form-control-sm" name="tglterbit" id="tglterbit">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="submit" class="btn btn-primary btn-md col-4" id="btnSimpan" name="setrapor">
						<i class="far fa-save"></i> Simpan
					</button>
					<a href="#" class="btn btn-danger btn-md col-4" data-dismiss="modal">
						<i class="fas fa-power-off"></i> Tutup
					</a>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="card card-secondary card-outline">
	<div class="card-header">
		<h4 class="card-title">Rekap Rapor</h4>
		<div class="card-tools">
			<form action="print_rapor.php" method="POST" target="_blank">
				<a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#mySetRapor" data-id="<?php echo $idujian; ?>" id="btnSeting">
					<i class="fa fa-cog"></i>&nbsp;Setting
				</a>
				<input type="hidden" name="uji" value="<?php echo $idujian; ?>">
				<input type="hidden" name="rmb" value="<?php echo $_POST['rmb']; ?>">
				<button type="submit" class="btn btn-default btn-sm">
					<i class="fas fa-print"></i>&nbsp;Cetak
				</button>
			</form>
		</div>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table id="tb_peserta" class="table table-bordered table-striped table-sm">
				<thead>
					<tr>
						<th style="text-align: center;width:2.5%">No.</th>
						<th style="text-align: center;width:17.5%">No. Induk</th>
						<th style="text-align: center;">Nama Peserta</th>
						<th style="text-align: center;width:12.5%">Kelas</th>
						<th style="text-align:center;width:10%">Jumlah</th>
						<th style="text-align:center;width:10%">Rata-rata</th>
						<th style="text-align:center;width:15%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ($level == '1') {
						$sqlr = "SELECT s.idsiswa, s.nmsiswa, s.nis, s.nisn, COUNT(n.nilai)as cacah, SUM(ROUND(n.nilai,2)) as jml, AVG(ROUND(n.nilai,2)) as rata, rb.nmrombel, u.idthpel FROM tbnilai n INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbpeserta s USING(idsiswa) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbujian u ON u.idujian=n.idujian INNER JOIN tbthpel t ON t.idthpel=rb.idthpel AND t.idthpel=u.idthpel WHERE t.aktif='1' AND u.idujian='$_POST[uji]' AND rb.idrombel='$_POST[rmb]' GROUP BY n.idsiswa, n.idujian ORDER BY rata DESC";
					}
					if ($level == '2') {
						$sqlr = "SELECT s.idsiswa, s.nmsiswa, s.nis, s.nisn, COUNT(n.nilai)as cacah, SUM(ROUND(n.nilai,2)) as jml, AVG(ROUND(n.nilai,2)) as rata, rb.nmrombel, u.idthpel FROM tbnilai n INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbpeserta s USING(idsiswa) INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel rb USING(idrombel) INNER JOIN tbgtk g USING(idgtk) INNER JOIN tbujian u ON s.idujian=u.idujian AND s.idujian=n.idujian INNER JOIN tbthpel t ON t.idthpel=rb.idthpel AND t.idthpel=u.idthpel INNER JOIN tbuser us USING(username) WHERE us.username='$_COOKIE[id]' AND t.aktif='1' AND u.status='1' GROUP BY n.idsiswa, n.idujian ORDER BY rata DESC";
					}
					// var_dump($sqlr);
					// die;
					$no = 0;
					$qs = vquery($sqlr);
					foreach ($qs as $s) :
						$no++;
					?>
						<tr>
							<td style="text-align:center">
								<?php echo $no . '.'; ?>
							</td>
							<td style="text-align:center">
								<?php echo $s['nis'] . ' / ' . $s['nisn']; ?>
							</td>
							<td>
								<?php echo ucwords(strtolower($s['nmsiswa'])); ?>
							</td>
							<td style="text-align:center">
								<?php echo $s['nmrombel']; ?>
							</td>
							<td style="text-align:center;">
								<?php echo number_format($s['jml'], 1, ',', '.'); ?>
							</td>
							<td style="text-align:center;">
								<?php echo number_format($s['rata'], 2, ',', '.'); ?>
							</td>
							<td style="text-align:center">
								<form action="print_rapor.php" method="post" target="_blank">
									<input type="hidden" name="id" value="<?php echo $s['idsiswa']; ?>">
									<input type="hidden" name="rmb" value="<?php echo $_POST['rmb']; ?>">
									<input type="hidden" name="uji" value="<?php echo $idujian; ?>">
									<button type="submit" class="btn btn-secondary btn-xs">
										<i class="fas fa-print"></i> Cetak
									</button>
								</form>
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
		$("#tb_peserta").DataTable({
			"paging": true,
			"lengthChange": true,
			"searching": true,
			"ordering": false,
			"info": true,
			"autoWidth": false,
			"responsive": true
		})
	});
	$(document).ready(function() {
		$('#tglterbit').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		})
		$("#btnSeting").click(function() {
			let data = new FormData()
			data.append('id', $(this).data('id'))
			$.ajax({
				url: 'hasil_raporset.php',
				type: 'post',
				dataType: 'json',
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 8000,
				success: function(rsp) {
					$("#mdSetRaporJdl").html(rsp.judul)
					$("#btnSimpan").html(rsp.tombol)
					$("#idset").val(rsp.idsetrapor)
					$("#tmpterbit").val(rsp.tmpterbit)
					$("#tglterbit").val(rsp.tglterbit)

				}
			})
		})
	})
</script>