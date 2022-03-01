<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	$qskul=$conn->query("SELECT idskul FROM tbskul");
	$sk=$qskul->fetch_array();
	$idskul=$sk['idskul'];
	
	if (isset($_POST['simpan'])) {
		$sql=$conn->query("SELECT*FROM tbuser WHERE username='$_POST[user]' AND level='1'");
		$cek=$sql->num_rows;
		if($cek>0){
			header("Location:login.php");
		}		
		else{
			if($_POST['paswd']!== $_POST['paswd2']){
				echo "<script>
                    $(function() {
                        toastr.error('Password Konfirmasi Tidak Sesuai!','Mohon Maaf',{
                            timeOut:1000,
                            fadeOut:1000,
                            onHidden:function(){
                                $('#paswd').focus();
                            }
                        });
                    });
                </script>";
				exit;
			}
			$paswd=password_hash($_POST['paswd'], PASSWORD_DEFAULT);
			$conn->query("INSERT INTO tbuser (idskul, nama, tmplahir, tgllahir, username, passwd,level, aktif) VALUES ('$idskul','$_POST[nama]', '$_POST[tmp]', '$_POST[tgl]', '$_POST[user]', '$paswd','1','1')");
			$data=$conn->affected_rows;
			if($data==1){
				echo "<script>
                    $(function() {
                        toastr.success('Tambah Akun Admin Berhasil!','Mohon Maaf',{
                            timeOut:1000,
                            fadeOut:1000,
                            onHidden:function(){
                                location.href='login.php';
                            }
                        });
                    });
                </script>";
			}
			else {
				echo "<script>
                    $(function() {
                        toastr.warning('Tambah Akun Admin Gagal!','Mohon Maaf');
                    });
                </script>";
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Aplikasi CBT</title>
	<link href='../assets/img/tutwuri.png' rel='icon' type='image/png'/>
	<link rel="stylesheet" href="../assets/css/all.min.css">
	<link rel="stylesheet" href="../assets/css/adminlte.min.css">
	<link rel="stylesheet" href="../assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	<link rel="stylesheet" href="../assets/plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="../assets/css/jquery.datetimepicker.css">	
	<link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
</head>
<body class="hold-transition register-page" style="background:url(../assets/img/boxed-bg.png);no-repeat">
	<div class="register-box">
		<div class="register-logo">
			<span><b>Selamat Datang</b></span>
		</div>
		<div class="card">
			<div class="card-body register-card-body">
				<p class="login-box-msg">Silahkan Isikan Data Administrator</p>
				<form action="" method="post" role="form" id="FrmRegistrasi">
					<div class="form-group mb-3">
						<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Lengkap">
					</div>
					<div class="form-group mb-3">
						<input type="text" class="form-control" name="tmp" id="tmp" placeholder="Tempat Lahir">
					</div>
					<div class="form-group mb-3">
						<input type="text" class="form-control" name="tgl" id="tgl" placeholder="Tanggal Lahir">
					</div>
					<hr/>
					<div class="form-group mb-3">
						<div class="input-group">
							<input type="text" class="form-control" name="user" id="user" placeholder="Username">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-user"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group mb-3">
						<div class="input-group">
							<input type="password" class="form-control" name="paswd" id="paswd" placeholder="Password">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-key"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group mb-5">
						<div class="input-group">
							<input type="password" class="form-control" name="paswd2" id="paswd2" placeholder="Konfirmasi Password">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-key"></span>
								</div>
							</div>
						</div>
					</div>					
					<div class="row">
						<div class="col-sm-8">
							<button type="submit" name="simpan" class="btn btn-primary btn-block col-sm-8">
								<i class="fas fa-save"></i>&nbsp;Simpan
							</button>
						</div>
					</div>
				</form>			
			</div>
		</div>
	</div>
	<script type="text/javascript" src="../assets/js/jquery-1.4.js"></script>
	<script src="../assets/plugins/jquery/jquery.min.js"></script>
	<script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="../assets/plugins/jquery-validation/jquery.validate.min.js"></script>
	<script src="../assets/plugins/jquery-validation/additional-methods.min.js"></script>
	<script src="../assets/js/adminlte.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function () {
		// $.validator.setDefaults({
		// 	submitHandler: function () {
		// 	alert( "Form successful submitted!" );
		// 	}
		// });
		$('#FrmRegistrasi').validate({
			rules: {
				nama: {
					required: true
				},
				tmp: {
					required: true
				},
				tgl: {
					required: true
				},
				user: {
					required: true
				},
				paswd: {
					required: true
				},
			},
			messages: {
				nama: {
					required: "Nama Admin Wajib Diisi!",
				},
				tmp: {
					required: "Tempat Lahir Admin Wajib Diisi!"
				},
				tgl: {
					required: "Tanggal Lahir Admin Wajib Diisi!"
				},
				user: {
					required: "Username Wajib Diisi!"
				},
				paswd: {
					required: "Password Wajib Diisi!"
				},
			},
			errorElement: 'span',
			errorPlacement: function (error, element) {
				error.addClass('invalid-feedback');
				element.closest('.form-group').append(error);
			},
			highlight: function (element, errorClass, validClass) {
				$(element).addClass('is-invalid');
			},
			unhighlight: function (element, errorClass, validClass) {
				$(element).removeClass('is-invalid');
			}
		});
	});
	</script>	
</body>
</html>