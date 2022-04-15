<?php
require_once "../assets/library/PHPExcel.php";
require_once "../assets/library/excel_reader.php";

function GetMapel()
{
	return viewdata('tbmapel');
}
if (isset($_POST['imprkp'])) {
	if (empty($_FILES['tmpnilai']['tmp_name'])) {
		echo "<script>
            $(function() {
                toastr.error('File Template Pengaturan Sesi Tidak Boleh Kosong!','Mohon Maaf!',{
                    timeOut:1000,
                    fadeOut:1000
                });
            });
        </script>";
	} else {
		$data = new Spreadsheet_Excel_Reader($_FILES['tmpnilai']['tmp_name']);
		$baris = $data->rowcount($sheet_index = 0);
		$isidata = $baris - 6;
		$sukses = 0;
		$gagal = 0;
		$update = 0;
		$no = 0;
		for ($i = 6; $i <= $baris; $i++) {
			$no++;
			$xnmpeserta = $data->val($i, 2);
			$xpeserta = $data->val($i, 5);
			$xjumsoal = $data->val($i, 8);
			$xskor = $data->val($i, 9);

			$xmapel = $data->val($i, 10);
			if ($xjumsoal == '') {
				echo "<script>
							$(function() {
								toastr.error('Cek Kolom Skor Maksimum a.n " . $xpeserta . "','Mohon Maaf!',{
									timeOut:10000,
									fadeOut:10000
								});
							});
						</script>";
			} else if ($xskor == '') {
				echo "<script>
							$(function() {
								toastr.error('Cek Kolom Skor Perolehan a.n " . $xpeserta . "','Mohon Maaf!',{
									timeOut:10000,
									fadeOut:10000
								});
							});
						</script>";
			} else if ($xmapel == '') {
				echo "<script>
							$(function() {
								toastr.error('Cek Kolom Kode Mapel a.n " . $xpeserta . "','Mohon Maaf!',{
									timeOut:10000,
									fadeOut:10000
								});
							});
						</script>";
			} else {
				$xsalah = $xjumsoal - $xskor;
				$xnilai = $xskor * 100 / $xjumsoal;
				$sqlnilai = "SELECT n.nilai FROM tbnilai n INNER JOIN tbpeserta ps USING(idsiswa) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbujian u  ON (u.idujian=ps.idujian AND u.idujian=n.idujian) WHERE ps.nmpeserta='$xnmpeserta' AND n.idmapel='$xmapel' AND u.status='1'";
				$cekdata = cquery($sqlnilai);
				if ($cekdata > 0) {
					$sql = "UPDATE tbnilai n INNER JOIN tbpeserta ps USING(idsiswa) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbujian u ON (u.idujian=ps.idujian AND u.idujian=n.idujian) SET n.nilai='$xnilai', n.jmlbenar='$xjumsoal', n.benar='$xskor', n.salah='$xsalah' WHERE ps.nmpeserta='$xnmpeserta' AND n.idmapel='$xmapel' AND u.status='1'";
					if (equery($sql) > 0) {
						$update++;
					}
				} else {
					$uji = viewdata('tbujian', array('status' => '1'))[0];
					$idujian = $uji['idujian'];
					$sql = "INSERT INTO tbnilai(idsiswa, nilai, jmlsoal, benar, salah, idmapel, idujian) SELECT idsiswa, '$xnilai', '$xjumsoal', '$xskor', '$xsalah', '$xmapel', '$idujian' FROM tbpeserta WHERE nmpeserta='$xnmpeserta'";
					if (equery($sql) > 0) {
						$sukses++;
					} else {
						$gagal++;
					}
				}
			}
		}
		echo "<script>
                $(function() {
                    toastr.info('Ada " . $sukses . " Nilai berhasil ditambahkan, " . $update . " data diupdate, " . $gagal . " data gagal!','Terima Kasih',{
                    timeOut:2000,
                    fadeOut:2000
                });
            });
        </script>";
	}
}


$qts = "SELECT u.idujian, u.nmujian, ts.nmtes FROM tbujian u INNER JOIN tbtes ts USING(idtes) INNER JOIN tbthpel t USING(idthpel) WHERE t.aktif='1' AND u.status='1'";
$ts = vquery($qts)[0];
$iduji = $ts['idujian'];
?>
<div class="modal fade" id="myImportRekap" aria-modal="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="" method="POST" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title">Import Rekap Nilai</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-sm-12">
						<div class="form-group row mb-2">
							<label for="tmpnilai">Pilih File Template</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="tmpnilai" name="tmpnilai">
								<label class="custom-file-label" for="tmpnilai">Pilih file</label>
							</div>
							<p style="color:red;margin-top:10px"><em>Hanya mendukung file *.xls (Microsoft Excel 97-2003)</em></p>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<a href="rekap_template.php" class="btn btn-success btn-sm " target="_blank">
						<i class="fas fa-download"></i> Download
					</a>
					<button type="submit" class="btn btn-primary btn-sm" name="imprkp">
						<i class="fas fa-upload"></i> Upload
					</button>
					<button type="button" class="btn btn-danger btn-sm " data-dismiss="modal">
						<i class="fas fa-power-off"></i> Tutup
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="card card-secondary card-outline">
	<div class="card-header">
		<h4 class="card-title">Rekap Hasil Ujian</h4>
		<div class="card-tools">
			<?php if ($level == '1') : ?>
				<button class="btn btn-success btn-sm" id="btnUpdate">
					<i class="fas fa-sync-alt"></i>&nbsp;Update
				</button>
				<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myImportRekap">
					<i class="fas fa-cloud-upload-alt"></i>&nbsp;Import
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
						$i = 0;
						$qmp = GetMapel();
						foreach ($qmp as $mp) :
							$i++;
						?>
							<th style="text-align:center;width:5%">
								<?php echo $mp['akmapel']; ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php
					if ($level == '1') {
						$qrekap = "SELECT s.idsiswa, s.nmsiswa, s.nmpeserta, s.passwd, r.nmrombel, u.nmujian FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t USING(idthpel) INNER JOIN tbujian u USING(idujian) WHERE u.status='1' AND t.aktif='1' AND s.nmpeserta<>'' GROUP BY s.idsiswa ORDER BY s.nmpeserta ASC";
					} else {
						$qrekap = "SELECT s.idsiswa, s.nmsiswa, s.nmpeserta, s.passwd, r.nmrombel, u.nmujian FROM tbpeserta s INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbgtk g USING(idgtk) INNER JOIN tbthpel t USING(idthpel) INNER JOIN tbujian u USING(idujian) INNER JOIN tbuser us USING(username) WHERE u.status='1' AND t.aktif='1' AND s.nmpeserta<>'' AND us.username='$_COOKIE[id]' GROUP BY s.idsiswa ORDER BY s.idsiswa ASC";
					}
					$no = 0;
					$qs = vquery($qrekap);
					foreach ($qs as $s) :
						$no++;
					?>
						<tr>
							<td style="text-align:center">
								<?php echo $no . '.'; ?>
							</td>
							<td style="text-align:center">
								<?php echo $s['nmpeserta']; ?>
							</td>
							<td>
								<?php echo ucwords(strtolower($s['nmsiswa'])); ?>
							</td>
							<?php
							$qmp = GetMapel();
							$i = 0;
							foreach ($qmp as $mp) :
								$i++;
							?>
								<td style="text-align:center;width:3.5%">
									<?php
									$qnilai = "SELECT nilai FROM tbnilai n INNER JOIN tbujian u USING(idujian) INNER JOIN tbmapel mp USING(idmapel) WHERE n.idsiswa='$s[idsiswa]' AND n.idmapel='$mp[idmapel]' AND u.status='1'";
									if (cquery($qnilai) > 0) {
										$nil = vquery($qnilai)[0];
										echo number_format($nil['nilai'], 2, ',', '.');
									} else {
										echo '';
									}
									?>
								</td>
							<?php endforeach ?>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
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
	$("#btnUpdate").click(function() {
		var id = "<?php echo $iduji; ?>";
		$.ajax({
			url: "hasil_update.php",
			type: "POST",
			data: "u=" + id,
			success: function(data) {
				toastr.success(data);
			}
		})
	})
</script>