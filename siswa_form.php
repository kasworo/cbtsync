<?php
include "../config/konfigurasi.php";
include "siswa_getid.php";
if($_REQUEST['m']=='1'){
	$nama='';
	$nis='';
	$nisn='';
	$tmpl='';
	$tgll='';
	$agma='';
	$gend='';
	$almt='';
	$desa='';
	$foto='';
	$tmbl='Simpan';	
	$psn='Isilah data peserta didik dengan lengkap dan benar, kemudian klik tombol';		
}
else if ($_REQUEST['m']=='2'){
	$id=base64_decode($_REQUEST['id']);
	$qpg=$conn->query("SELECT*FROM tbpeserta WHERE idsiswa='$id'");
	$pd=$qpg->fetch_array();
	$kode=$pd['idsiswa'];
	$nama=$pd['nmsiswa'];
	$nis=$pd['nis'];
	$nisn=$pd['nisn'];
	$tmpl=$pd['tmplahir'];
	$tgll=$pd['tgllahir'];
	$agma=$pd['idagama'];
	$gend=$pd['gender'];;
	$almt=$pd['alamat'];
	$foto=$pd['fotosiswa'];
	$tmbl='Update';
	$psn='Silahkan cek kembali data siswa, lengkapi dan betulkan kemudian klik tombol';
}

if($foto=='' || $foto==null){
	$fotosiswa='../assets/img/avatar.gif';
}
else{
	if(file_exists('../foto/'.$foto)){
		$fotosiswa='../foto/'.$foto;
	}
	else{
		$fotosiswa='../assets/img/avatar.gif';
	}
}
?>
<div class="col-sm-12">
	<div class="alert alert-warning">
		<p><strong>Petunjuk</strong><br/><?php echo $psn;?>&nbsp;<strong><?php echo $tmbl;?></strong></p>
	</div>
	<div class="card card-primary card-outline">
		<div class="card-header">
			<h5 class="card-title m-0">Data Peserta Didik</h5>
			<div class="card-tools">
				<button class="btn btn-primary btn-sm" id="simpan">
					<i class="fas fa-fw fa-save"></i>
					<span>&nbsp;<?php echo $tmbl;?></span>
				</button>
				<a href="index.php?p=datasiswa" class="btn btn-sm btn-danger">
					<i class="fas fa-fw fa-power-off"></i>
					<span>&nbsp;Tutup</span>
				</a>
			</div>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-sm-3">
					<div id="fotosiswa" style="text-align:center;margin-top:10px">
						<img class="img img-fluid img-responsive img-rounded" src="<?php echo $fotosiswa;?>" width="75%">
						<span id="fotosiswa_status"></span>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">Nama Peserta Didik</label>
						<div class="col-sm-6">
							<input class="form-control form-control-sm" name="nmsiswa" id="nmsiswa" value="<?php echo $nama;?>">
						</div>
					</div>				
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">NIS</label>
						<div class="col-sm-6">
							<input class="form-control form-control-sm" name="nis" id="nis" value="<?php echo $nis;?>" onkeyup="validAngka(this)"> 
						</div>
					</div>
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">NISN</label>
						<div class="col-sm-6">
							<input class="form-control form-control-sm" name="nisn" id="nisn" value="<?php echo $nisn;?>" onkeyup="validAngka(this)"> 
						</div>
					</div>
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">Tempat Lahir</label>			
						<div class="col-sm-6">
							<input class="form-control form-control-sm" name="tmplahir" id="tmplahir" value="<?php echo $tmpl;?>">
						</div>
					</div>
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">Tanggal Lahir</label>
						<div class="col-sm-6">
							<input class="form-control form-control-sm" name="tgllahir" id="tgllahir" value="<?php echo $tgll;?>">
						</div>
					</div>				
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">Jenis Kelamin</label>
						<div class="col-sm-6">
							<select class="form-control form-control-sm" name="gender" id="gender">
								<?php
								if($gend=="L"){$jk0="";$jk1="selected";$jk2="";}
								else if($gend=="P"){$jk0="";$jk1="";$jk2="selected";}
								else {$jk0="selected";$jk1="";$jk2="";}
								?>
								<option value="" <?php echo $jk0;?>>..Pilih..</option>
								<option value="L" <?php echo $jk1;?>>Laki-laki</option>
								<option value="P" <?php echo $jk2;?>>Perempuan</option>
							</select>
						</div>
					</div>
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">Agama</label>
						<div class="col-sm-6">
							<select class="form-control form-control-sm" name="agama" id="agama">
								<?php
									switch ($agma) {
										case 'A':{
											$agm1="selected";$agm2="";$agm3="";
											$agm4="";$agm5="";$agm6="";break;
										}
										case 'B':{
											$agm2="selected";$agm1="";$agm3="";
											$agm4="";$agm5="";$agm6="";break;
										}
										case 'C':{
											$agm3="selected";$agm2="";$agm1="";
											$agm4="";$agm5="";$agm6="";break;
										}
										case 'D':{
											$agm4="selected";$agm2="";$agm3="";
											$agm1="";$agm5="";$agm6="";break;
										}
										case 'E':{
											$agm5="selected";$agm2="";$agm3="";
											$agm4="";$agm1="";$agm6="";break;
										}
										case 'F':{
											$agm5="selected";$agm2="";$agm3="";
											$agm4="";$agm5="";$agm1="";break;
										}
									   default:{
											$agm1="";$agm2="";$agm3="";
											$agm4="";$agm5="";$agm6="";break;
										}
										
									}
								?>
								<option value="">..Pilih..</option>
								<option <?php echo $agm1;?> value="A">Islam</option>
								<option <?php echo $agm2;?> value="B">Kristen</option>
								<option <?php echo $agm3;?> value="C">Katholik</option>
								<option <?php echo $agm4;?> value="D">Hindu</option>
								<option <?php echo $agm5;?> value="E">Buddha</option>
								<option <?php echo $agm6;?> value="F">Konghucu</option>
							</select>
						</div>						
					</div>
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">Alamat</label>
						<div class="col-sm-6">
							<textarea class="form-control form-control-sm" name="almt" id="almt"><?php echo $almt;?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function validAngka(a)
	{
		if(!/^[0-9.]+$/.test(a.value))
		{
			a.value = a.value.substring(0,a.value.length-1000);
		}
	}
	$(function(){
		var btnUpload=$('#fotosiswa');
		var status=$('#fotosiswa_status');
		new AjaxUpload(btnUpload, {
			action	: 'siswa_foto.php?id=<?php echo $kode;?>',
			name	: 'fotosiswa',
			onSubmit: function(file, ext){
			if (! (ext && /^(jpg)$/.test(ext)))
			{
				toastr.error('Hanya Mendukung File *.jpg, atau *.JPG Saja Bro!!');
				return false;
			}
				status.text('Upload Sedang Berlangsung...');
			},
			onComplete: function(file, response)
			{
				status.text('');
				if(response==="success")
				{
					$('#fotosiswa').html('<img src="foto/'+file+'" height="180px" alt="" />').addClass('success');
					window.location.reload();
				}
				else
				{
					toastr.error('Upload Gagal Bro!!');
				}
			}
		});
	});
	$(document).ready(function(){
		$('#tgllahir').datetimepicker({
			timepicker:false,
			format: 'Y-m-d'
		});
		$(function() {
			const Toast = Swal.mixin({
				toast: true,
				position: ' mid-end',
				showConfirmButton: false,
				timer: 3000
			});
			$("#simpan").click(function(){
				var idsiswa="<?php echo $kode;?>";
				var nmsiswa = $("#nmsiswa").val();
				var nis = $("#nis").val();
				var nisn = $("#nisn").val();
				var tmplahir = $("#tmplahir").val();
				var tgllahir = $("#tgllahir").val();
				var agama = $("#agama").val();
				var gender = $("#gender").val();
				var nmortu=$("#nmortu").val();
				var krjortu=$("#krjortu").val();
				var almt = $("#almt").val();
				var desa = $("#desa").val()
				var kec = $("#kec").val();
				var kab	= $("#kab").val();
				var prov = $("#prov").val();
				var kdpos = $("#kdpos").val();
				var nohp = $("#nohp").val();
				if(nmsiswa=="" || nmsiswa==null){
					toastr.error('Nama Peserta Didik Wajib Diisi!');
					$("#nmsiswa").focus();
				}
				else if(nis=="" || nis==null){
					toastr.error('Nomor Induk Siswa Wajib Diisi!');
					$("#nis").focus();
				}
				else if(tmplahir=="" || tmplahir==null){
					toastr.error('Tempat Lahir Wajib Diisi!');
					$("#tmplahir").focus();
				}
				else if(tgllahir=="" || tgllahir==null){
					toastr.error('Tanggal Lahir Wajib Diisi!');
					$("#tgllahir").focus();
				}
				else if(gender=="" || gender==null){
					toastr.error('Jenis Kelamin Wajib Diisi!');
					$("#gender").focus();
				}
				else if(agama=="" || agama==null){
					toastr.error('Agama Wajib Diisi!');
					$("#agama").focus();
				}
				else {
					$.ajax({
						type:"POST",
						url:"siswa_simpan.php",
						data: "aksi=simpan&id="+idsiswa+"&nama="+nmsiswa+"&nis="+nis+"&nisn="+nisn+"&tmplahir="+tmplahir+"&tgllahir="+tgllahir + "&gender="+gender+"&agama="+agama +"&nmortu="+nmortu+"&krjortu="+krjortu+"&almt="+almt+"&desa="+desa + "&kec="+kec +"&kab="+kab + "&prov=" + prov +"&kdpos="+kdpos + "&nohp="+nohp,
						success:function(data)
						{
							toastr.success(data);
						}	
					})
				}
			})
		})
	})
</script>