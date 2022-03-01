<?php
	include "token_function.php";
	$skrg=date('Y-m-d');
	$jamujian=date('H:i:s');	
   // var_dump($skrg);die;	
	
	$sql = $conn->query("SELECT jd.* FROM tbjadwal jd INNER JOIN tbsetingujian su USING(idjadwal) INNER JOIN tbujian u USING(idujian) WHERE jd.tglujian='$skrg' AND u.status='1' GROUP BY jd.idjadwal");
	$cekuji=$sql->num_rows;	
	//var_dump($cekuji);die;
	$qsesi=$conn->query("SELECT*FROM tbsesi WHERE mulai<='$jamujian' AND selesai>='$jamujian'");
	$ceksesi=$qsesi->num_rows;
	//var_dump($jamujian);
	//var_dump($ceksesi);die;
	if($cekuji>0 && $ceksesi>0)
	{
		$qcek=$conn->query("SELECT TIME_TO_SEC(timediff('$jamujian',jamrilis)) AS waktu, token, idsesi, idjadwal, jamrilis FROM tbtoken WHERE status='1'");
		$ck=$qcek->fetch_array();
		$cekselisih=$ck['waktu'];
		$token=$ck['token'];
		$kdsesi=$ck['idsesi'];
		$kdjadwal=$ck['idjadwal'];
		$jamrilis=$ck['jamrilis'];
		
		if($cekselisih>=900){
			$token=gettoken(6);
			$qupd=$conn->query("UPDATE tbtoken SET token='$token', jamrilis='$jamujian' WHERE status='1'");
		}
		$s = $sql->fetch_array();
		$ttglujian=$s['tglujian'];
		$statustoken=$s['viewtoken'];		
		$statushasil=$s['hasil'];

		if($statustoken=='0'){
			$tampilkan='Token Tampil';
			$ikon=' fa-eye';
			$tomb='btn-default';
		}
		else {
			$tampilkan='Token Sembunyi';
			$ikon='fa-eye-slash';
			$tomb='btn-success';
		}

		if($statushasil=='0'){
			$vhasil='Hasil Tampil';
			$iknhsl='fas fa-check-square';
			$btnhsl='btn-default';
		}
		else {
			$clh='';
			$vhasil='Hasil Sembunyi';
			$iknhsl='far fa-check-square';
			$btnhsl='btn-danger';
		}
		$pesan='<font style="color:green;font-style: bold;font-size: 12pt">AKTIF</font>';			
		$dis='';
		$selisih=600-$s['selisih'];
		$st='color:green;font-style:bold;font-size:11pt';
		$token=$token.' (Update Terakhir '.$jamrilis.')';
?>	
<script type="text/javascript">
	function counter(time){
		var interval = setInterval(function(){			
		time = time - 1;	
		if(time == 0){
			clearInterval(interval);
			window.location.reload();
		}
		$("#waktu").text(time)
		}, 1000)
	}
	$(document).ready(function(){
		var wktu="<?php echo 900-$cekselisih;?>";
		if(wktu<=0){
			counter(1);
		}
		else{
			counter(wktu);
		}
	})
</script>	
<?php }
	else
	{
		
		$qupd=$conn->query("UPDATE tbtoken t INNER JOIN tbjadwal j USING(idjadwal) SET t.status='0' WHERE j.tglujian<>'$skrg'");
		$jamtoken='';
		$pesan='<font style="color:red;font-style: bold;font-size: 12pt">TIDAK ADA JADWAL</font>';
		$dis='disabled';
		$token='Tidak Ada Jadwal Ujian';
		$vhasil='Set Hasil';
		$iknhsl='fas fa-check-square';
		$btnhsl='btn-info';
		$tampilkan='Set Token';
		$ikon='fa-eye-slash';
		$tomb='btn-danger';
		$st='color:red;font-style:bold;font-size:11pt';
	}
?>

<div class="col-sm-12">
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Rilis Token Ujian</h4>
		</div>
		<div class="card-body" style="min-height: 380px">
			<div class="col-sm-12">
				<div class="form-group row mb-3">
					<div class="callout callout-danger">
						<p><strong>Petunjuk:</strong></p>
						<p>
							Pastikan <strong><em>Status Tes</em></strong> sudah <strong style="color:red;">Aktif</strong>, pilih jadwal pada pilihan <strong><em>Daftar Tes</em></strong>.<br/>
							Jangan lupa pilih<strong><em> Kelompok Peserta</em></strong> dan pastikan token muncul di bagian <strong><em>Token Aktif</em></strong>, klik tombol <strong><em>Simpan</em></strong>.
							<br/>Token akan tergenerate secara otomatis dalam rentang waktu <strong> 15 (limabelas)</strong> menit, klik tombol <strong><em>Refresh</em></strong> jika token terbaru tidak muncul.
						</p>
					</div>
				</div>
				<div class="form-group row mb-3">
					<div class="col-sm-3 offset-sm-1">
						<label>Status Tes</label>
					</div>
					<div class="col-sm-4">
						<label>
							<?php echo $pesan;?>
						</label>
					</div>
				</div>
				<div class="form-group row mb-3">
					<div class="col-sm-3 offset-sm-1">
						<label>Daftar Tes</label>
					</div>
					<div class="col-sm-4">
						<select class="form-control form-control-sm" id="jadwal" <?php echo $dis;?>>
							<option value="">..Pilih..</option>
							<?php
								if($level=='1')
								{
									$sqljadwal = $conn->query("SELECT*FROM tbjadwal j INNER JOIN  tbujian u USING(idujian) WHERE u.status='1' AND j.tglujian='$skrg'");
								}
								else
								{
									$sqljadwal = $conn->query("SELECT*FROM tbjadwal j INNER JOIN  tbujian u USING(idujian) WHERE u.status='1' AND j.tglujian='$skrg'");
								}
								while($jd=$sqljadwal->fetch_array()):
									if($jd['idjadwal']==$kdjadwal){$stat="selected";} else {$stat="";}
							?>
							
							<option value="<?php echo $jd['idjadwal'];?>" <?php echo $stat;?>><?php echo indonesian_date($jd['tglujian']).' - '.$jd['nmjadwal'];?></option>
							<?php endwhile ?>
						</select>
					</div>
				</div>
				<div class="form-group row mb-3">
					<div class="col-sm-3 offset-sm-1">
						<label>Pilih Sesi</label>
					</div>
					<div class="col-sm-4">	
						<select class="form-control form-control-sm" id="sesi" <?php echo $dis;?>>
							<option value="">..Pilih ..</option>
							<?php
								$qses = $conn->query("SELECT idsesi, nmsesi FROM tbsesi WHERE selesai>='$jamujian' AND mulai<='$jamujian'");
								while($ses=$qses->fetch_array()):
									if($ses['idsesi']==$kdsesi){$ssta='selected';} else{$ssta='';}
							?>
							<option value="<?php echo $ses['idsesi'];?>" <?php echo $ssta;?>><?php echo $ses['nmsesi'];?></option>
							<?php endwhile ?>
						</select>
					</div>
				</div>
					<div class="form-group row mb-3">
					<div class="col-sm-3 offset-sm-1">
						<label>Token Aktif</label>
					</div>
					<div class="col-sm-4">
						<input type="text" class="form-control form-control-sm" style="<?php echo $st;?>" id="token" value="<?php echo $token;?>" disabled/>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button class="btn btn-primary btn-sm mb-2 ml-2" id="simpan" <?php echo $dis;?>>
				<i class="fas fa-fw fa-save"></i> Simpan
			</button>
			<button class="btn btn-secondary btn-sm mb-2 ml-2" id="refresh" <?php echo $dis;?>>
				<i class="fas fa-fw fa-sync-alt"></i> Refresh
			</button>
			<button class="btn <?php echo $btnhsl;?> btn-sm mb-2 ml-2" id="btnhasil" <?php echo $dis;?> title="Tampil / Sembunyikan Hasil">
				<i class="fa-fw <?php echo $iknhsl;?>"></i>&nbsp;<?php echo $vhasil;?>
			</button>
			<button class="btn <?php echo $tomb;?> btn-sm mb-2 ml-2" id="btntoken" <?php echo $dis;?> title="Tampil / Sembunyikan Token">
				<i class="fas fa-fw <?php echo $ikon;?>"></i>&nbsp;<?php echo $tampilkan;?>
			</button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#sesi").change(function(){
		var jdw=$("#jadwal").val();
		var sesi= $(this).val();
		if(jdw =='' || jdw==null){
			toastr.error("Pilih Jadwal Dulu!","Maaf");
		}
		else if(sesi =='' || sesi==null){
			toastr.error("Pilih Sesi Dulu!","Maaf");
		}
		else{
			$.ajax({
                url: "token_getisi.php",
                type:"POST",
				data: "jdw="+jdw+"&sesi="+sesi,
				cache: false,
				success: function(data){
					var json = data,
					obj = JSON.parse(json);
					$("#token").val(obj.pesan);
				}
			});
		}
	});
	$("#simpan").click(function(){
		var jdw=$("#jadwal").val();
		var sesi= $("#sesi").val();
		var vtoken=$("#token").val();
		var token=vtoken.substr(0,6);
		$.ajax({
			url:"token_simpan.php",
			type:"POST",
			data:"jdw="+jdw+"&sesi="+sesi+"&token="+token,
			cache:false,
			success:function(data)
			{
				toastr.info(data);
			}
		})
	})
	$("#refresh").click(function(){
		window.location.reload();
	})
	$("#btntoken").click(function(){
		var jdw=$("#jadwal").val();
		$.ajax({
			url:"jadwal_simpan.php",
			type:"POST",
			data:"aksi=2&jdw="+jdw,
			cache:false,
			success:function(data)
			{
				toastr.info(data);
			}
		})
	})
	$('#btnhasil').click(function(){
		var jdw=$("#jadwal").val();
		$.ajax({
			url:'jadwal_simpan.php',
			type:'post',
			data:'aksi=3&jdw='+jdw,
			success:function(data)
			{
				toastr.success(data);
			}
		})
	});	
</script>