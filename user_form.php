<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	include "user_getid.php";
	if($_REQUEST['m']=='1'){
		$kode=getuserid();
		$nama='';
		$nip='';
		$tmpl='';
		$tgll='';
		$agma='';
		$gend='';
		$almt='';
		$foto='';
		$tombol='Simpan';
		$pesan='Silahkan isikan data pengguna (guru bidang studi) dengan lengkap dan benar, kemudian klik tombol <strong>Simpan</strong>';	  
	}
	else if ($_REQUEST['m']=='2'){
		$id=base64_decode($_REQUEST['id']);
		$qpg=$conn->query("SELECT*FROM tbuser WHERE username='$id'");
		$pg=$qpg->fetch_array();
		$kode=$pg['username'];
		$nama=$pg['nama'];
		$nip=$pg['nip'];
		$tmpl=$pg['tmplahir'];
		$tgll=$pg['tgllahir'];
		$agma=$pg['agama'];
		$gend=$pg['gender'];
		$almt=$pg['alamat'];
		$foto=$pg['foto'];
		$tombol='Update';
		$pesan='Silahkan cek kembali data pengguna (guru bidang studi), lengkapi dan betulkan jika masih terdapat data yang masih kurang atau salah, kemudian klik tombol <strong>Update</strong>';
	}
	else if ($_REQUEST['m']=='3'){
		$qpg=$conn->query("SELECT*FROM tbuser WHERE username='$_COOKIE[c_user]'");
		$pg=$qpg->fetch_array();
		$kode=$pg['username'];
		$nama=$pg['nama'];
		$nip=$pg['nip'];
		$tmpl=$pg['tmplahir'];
		$tgll=$pg['tgllahir'];
		$agma=$pg['agama'];
		$gend=$pg['gender'];
		$almt=$pg['alamat'];
		$foto=$pg['foto'];
		$tombol='Update';
		$pesan='Silahkan cek kembali data anda, lengkapi dan betulkan jika masih terdapat data yang masih kurang atau salah, kemudian klik tombol <strong>Update</strong>';
	}

	if($foto=='' || $foto==null){
		$fotouser='../assets/img/avatar.gif';
	}
	else{
		if(file_exists('foto/'.$foto)){
			$fotouser='foto/'.$foto;
		}
		else{
			$fotouser='../assets/img/avatar.gif';
		}
	}
?>
<div class="col-sm-12">
	<div class="alert alert-warning">
		<p><strong>Petunjuk</strong><br/><?php echo $pesan;?></p>
	</div>
	<div class="card card-primary card-outline">
		<div class="card-header">
			<h5 class="card-title m-0">Data Pengguna</h5>
			<div class="card-tools">
				<button class="btn btn-primary btn-sm" id="simpan">
					<i class="fas fa-fw fa-save"></i>
					<span>&nbsp;<?php echo $tombol;?></span>
				</button>
				<?php if($_COOKIE['c_login']=='1' && $_REQUEST['m']=='2'):?>
				<a href="index.php?p=datauser" class="btn btn-sm btn-danger">
					<i class="fas fa-fw fa-power-off"></i>
					<span>&nbsp;Tutup</span>
				</a>
				<?php else: ?>
				<a href="index.php?p=dashboard" class="btn btn-sm btn-danger">
					<i class="fas fa-fw fa-power-off"></i>
					<span>&nbsp;Tutup</span>
				</a>
			<?php endif ?>
			</div>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-sm-3">
					<div id="fotouser" style="text-align:center;margin-top:10px">
						<img class="img img-responsive img-circle" src="<?php echo $fotouser;?>" width="75%"/>
						<span id="fotouser_status"></span>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">Nama Lengkap</label>
						<div class="col-sm-6">
							<input class="form-control form-control-sm" name="nmuser" id="nmuser" value="<?php echo $nama;?>">
						</div>
					</div>				
					<div class="row" style="padding-bottom:5px">
						<label class="col-sm-5">NIP</label>
						<div class="col-sm-6">
							<input class="form-control form-control-sm" name="nip" id="nip" value="<?php echo $nip;?>"> 
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
	$(function(){
		var btnUpload=$('#fotouser');
		var status=$('#fotouser_status');
		new AjaxUpload(btnUpload, {
			action	: 'user_foto.php?id=<?php echo $kode;?>',
			name	: 'filefoto',
			onSubmit: function(file, ext){
			if (! (ext && /^(jpg)$/.test(ext)))
			{
				toastr.error('Hanya Mendukung File *.jpg atau *.JPG Saja Bro!!');
				return false;
			}
				status.text('Upload Sedang Berlangsung...');
			},
			onComplete: function(file, response)
			{
				status.text('');
				if(response==="success")
				{
					$('#fotouser').html('<img src="foto/'+file+'" height="180px" alt="" />').addClass('success');
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
				position: 'top-end',
				showConfirmButton: false,
				timer: 3000
			});
			$("#simpan").click(function(){
				var username="<?php echo $kode;?>";
				var nmuser = $("#nmuser").val();
				var nip = $("#nip").val();
				var tmplahir = $("#tmplahir").val();
				var tgllahir = $("#tgllahir").val();
				var agama = $("#agama").val();
				var gender = $("#gender").val();
				var almt = $("#almt").val();
				if(nmuser=="" || nmuser==null){
					toastr.error('Nama Pengguna Wajib Diisi!');
					$("#nmuser").focus();
				}
				else if(nip=="" || nip==null){
					toastr.error('Nomor Induk Siswa Wajib Diisi!');
					$("#nip").focus();
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
						url:"user_simpan.php",
						data: "aksi=simpan&id="+username+"&nama="+nmuser+"&nip="+nip+"&tmplahir="+tmplahir+"&tgllahir="+tgllahir + "&gender="+gender+"&agama="+agama+"&almt="+almt,
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