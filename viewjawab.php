<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
if (isset($_COOKIE['pst'])) :
	$sqlset = "SELECT idset FROM tbsetingujian su INNER JOIN tbrombelsiswa USING(idrombel) WHERE idsiswa='$_COOKIE[pst]' AND su.idjadwal='$_COOKIE[jdw]'";
	$ds = vquery($sqlset)[0];
	$idset = $ds['idset'];
?>
	<style>
		.kotak {
			position: relative;
			border-radius: 7.5px;
			top: 0px;
			padding-top: 2.5px;
			box-shadow: 5px 5px #888888;
			border: 2.5px solid #888888;
			color: #888888;
			margin-left: 25px;
			width: 40px;
			height: 40px;
			text-align: center;
			font-size: 15pt;
			font-weight: bold;
			z-index: 1;
		}

		.isi {
			position: relative;
			left: 12.5px;
			top: -47.5px;
			border-radius: 14px;
			border: 2.5px solid #888888;
			background-color: #888888;
			color: #FFFF;
			font-size: 12pt;
			font-weight: bold;
			width: 28px;
			height: 28px;
			text-align: center;
			z-index: 2;
		}
	</style>
	<div class="col-sm-12  d-flex align-items-stretch justify-content-start">
		<div class="container">
			<div class="row ml-auto">
				<?php
				$sqjwb = "SELECT jwb.*, so.jnssoal FROM tbjawaban jwb INNER JOIN tbsoal so USING(idbutir) WHERE jwb.idset='$idset' AND jwb.idsiswa='$_COOKIE[pst]'";
				$qjwb = vquery($sqjwb);
				$i = 0;
				foreach ($qjwb as $jw) :
					$i++;
					if ($jw['jnssoal'] == '1') :
				?>
						<a href="#" data-id="<?php echo $i; ?>" class="tombol" data-dismiss="modal">
							<div class="kotak"><?php echo $jw['huruf']; ?></div>
							<div class="isi"><?php echo $i; ?></div>
						</a>
						<?php else :
						if (isset($jw['jwbbenar'])) :
						?>
							<a href="#" data-id="<?php echo $i; ?>" class="tombol" data-dismiss="modal">
								<div class="kotak"><img src="assets/img/cek.png" style="width:92%;padding-left:3px;padding-bottom:3px;margin:auto"></div>
								<div class="isi"><?php echo $i; ?></div>
							</a>
						<?php else : ?>
							<a href="#" data-id="<?php echo $i; ?>" class="tombol" data-dismiss="modal">
								<div class="kotak"></div>
								<div class="isi"><?php echo $i; ?></div>
							</a>
						<?php endif ?>
					<?php endif ?>
				<?php endforeach ?>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(".tombol").click(function() {
			let urut = $(this).data('id')
			tampilsoal(urut)
		})
	</script>
<?php endif ?>