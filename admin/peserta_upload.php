<?php
define("BASEPATH", dirname(__FILE__));
require_once "../assets/library/PHPExcel.php";
require_once "../assets/library/excel_reader.php";
include "dbfunction.php";
function GetTglUjian()
{
	$sqlsesi = "SELECT jd.tglujian FROM tbjadwal jd LEFT JOIN tbujian u USING(idujian) WHERE u.status='1' GROUP BY jd.tglujian";
	return cquery($sqlsesi);
}

if (empty($_FILES['tmpsesi']['tmp_name'])) :
?>
	<script type="text/javascript">
		$(function() {
			const Toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 2000
			});
			toastr.error("File Kosong Bro");
		});
	</script>
	<?php else :
	$data = new Spreadsheet_Excel_Reader($_FILES['tmpsesi']['tmp_name']);
	$baris = $data->rowcount($sheet_index = 0);
	$isidata = $baris - 6;
	$sukses = 0;
	$gagal = 0;
	$update = 0;
	$no = 0;
	$dtgl = GetTglUjian();
	for ($i = 6; $i <= $baris; $i++) :
		$no++;
		$xnmpeserta = $data->val($i, 2);
		$xpeserta = $data->val($i, 5);
		for ($j = 1; $j <= $dtgl; $j++) {
			$tgluji = $data->val(4, $j + 7);
			$sqljd = "SELECT idjadwal FROM tbjadwal WHERE tglujian='$tgluji'";
			$qjd = vquery($sqljd);
			foreach ($qjd as $jd) {
				$xjadwal = $jd['idjadwal'];
				$xsesi = $data->val($i, $j + 7);
				// var_dump($xsesi);
				// if ($xsesi == '') {
				// 	$pesan = 'Cek Kolom Kode Sesi a.n ' . $xpeserta . '!';
				// 	$jns = 'error';
				// 	$gagal++;
				// } else {
				// 	$sql = "SELECT*FROM tbsesiujian su INNER JOIN tbpeserta ps USING(idsiswa) WHERE ps.nmpeserta='$xnmpeserta' AND su.idjadwal='$xjadwal'";
				// 	$qpd = $conn->query($sql);
				// 	$ceksiswa = $qpd->num_rows;
				// 	if ($ceksiswa > 0) {
				// 		$query = $conn->query("UPDATE tbsesiujian su INNER JOIN tbpeserta ps USING(idsiswa) SET su.idsiswa=ps.idsiswa, idsesi='$xsesi' WHERE ps.nmpeserta='$xnmpeserta' AND su.idjadwal='$xjadwal'");
				// 		$pesan = 'Update Data Sukses!';
				// 		$jns = 'success';
				// 		$update++;
				// 	} else {
				// 		$query = $conn->query("INSERT INTO tbsesiujian (idsiswa, idjadwal,idsesi)
				// 		SELECT idsiswa, '$xjadwal', '$xsesi' FROM tbpeserta WHERE nmpeserta='$xnmpeserta'");
				// 		$pesan = 'Simpan Data Sukses!';
				// 		$jns = 'success';
				// 		$sukses++;
				// 	}
				// }
			}
			die;
		}
	?>
		<script type="text/javascript">
			$(function() {
				const Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000
				});
				toastr.<?php echo $jns; ?>("<?php echo $pesan; ?>");
				return false();
			})
		</script>
	<?php endfor ?>
	<?php flush(); ?>
	<script type="text/javascript">
		$(function() {
			const Toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 2000
			});
			toastr.info("Ada <?php echo $gagal; ?> gagal ditambahkan, <?php echo $update; ?> data berhasil diupdate dan <?php echo $sukses; ?> sukses ditambahkan");
			return false();
		})
	</script>
<?php endif ?>