<?php
session_start();
if (!isset($_SESSION['login'])) {
	header("Location: login.php");
	exit;
}
include "dbfunction.php";
if (empty($_COOKIE['id'])) { {
		header("Location: logout.php");
		exit;
	}
} else {
	$user = array(
		'username' => $_COOKIE['id']
	);

	$u = viewdata('tbuser', $user)[0];
	$nmuser = $u['namatmp'];
	$level = $u['level'];
	$foto = '';
	if ($level == '1' || $level == '2') {
		$navigasi = '<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="logout.php" title="Keluar / Logout">
						<i class="fas fa-power-off"></i>
					</a>
				</li>
			</ul>';
	} else {
		$navigasi = '';
	}
	if ($foto == '' || $foto == null) {
		$fotouser = 'assets/img/avatar.gif';
	} else {
		if (file_exists('foto/' . $foto)) {
			$fotouser = 'foto/' . $foto;
		} else {
			$fotouser = 'assets/img/avatar.gif';
		}
	}
	$tahun = array(
		'aktif' => '1'
	);

	$tp = viewdata("tbthpel", $tahun)[0];
	$tapel = $tp['desthpel'];
?>	
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Aplikasi CBT</title>
	<link href='../assets/img/tutwuri.png' rel='icon' type='image/png'/>
	<link rel="stylesheet" href="../assets/css/all.min.css">
	<link rel="stylesheet" href="../assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<link rel="stylesheet" href="../assets/css/adminlte.min.css">
	<link rel="stylesheet" href="../assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	<link rel="stylesheet" href="../assets/plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/jquery.datetimepicker.css"> 
	<link rel="stylesheet" type="text/css" href="../assets/css/dropzone.css"/>
	<script type="text/javascript" src="../assets/js/dropzone.js"></script>
	<script type="text/javascript" src="../assets/js/jquery-1.4.js"></script>
	<script type="text/javascript" src="../assets/js/ajaxupload.3.5.js" ></script>
	<script type="text/javascript">
		$(document).ready(function () {
			toastr.options = {
				"closeButton": false,
				"positionClass": "toast-top-center",
				"preventDuplicates":true,
				"onclick": null,
				"showDuration": "300",
				"hideDuration": "1000",
				"timeOut": "5000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut"
			}
			bsCustomFileInput.init();
		});
	</script>
	<?php
		if(date('Y-m-d')>='2023-03-31'):
	?>
		<script type="text/javascript">
		$(document).ready(function () {
			Swal.fire({
			title: 'Perhatian',
			text: "Masa Penggunaan Aplikasi Anda Sudah Berakhir!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Hapus',
			cancelButtonText:'Batal'
		}).then((result) => {
			if (result.value) {
				window.location.reload();
			}
		})
			
		});
	</script>
	<?php endif?>
</head>
<body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
	<div class="modal fade" id="myPassword" aria-modal="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
						<h5 class="modal-title">Ganti Password</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="col-sm-12">
							<div class="form-group row mb-2">
								<label class="col-sm-5 offset-sm-1">Password Lama</label>
								<input type="password" class="form-control form-control-sm col-sm-5" id="passlama" name="passlama">
							</div>
							<div class="form-group row mb-2">
								<label class="col-sm-5 offset-sm-1">Password Baru</label>
								<input type="password" class="form-control form-control-sm col-sm-5" id="passbaru" name="passbaru">
							</div>
							<div class="form-group row mb-2">
								<label class="col-sm-5 offset-sm-1">Konfirmasi Password</label>
								<input type="password" class="form-control form-control-sm col-sm-5" id="passkonf" name="passkonf">
							</div>
						</div>
					</div>
					<div class="modal-footer justify-content-between">
						<button type="submit" class="btn btn-primary btn-sm col-4" id="gantipass">
							<i class="fas fa-save"></i> Update
						</button>
						<button type="button" class="btn btn-danger btn-sm col-4" data-dismiss="modal">
							<i class="fas fa-power-off"></i> Tutup
						</button>
					</div>
			</div>
		</div>
	</div>
<div class="wrapper">
	<nav class="main-header navbar navbar-expand navbar-dark navbar-light">
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" data-widget="pushmenu" href="#" role="button">
					<i class="fas fa-bars"></i>
				</a>
			</li>
		</ul>
		<div class="form-inline ml-2">
			<div class="input-group input-group-sm">
			<input class="form-control form-control-navbar" type="search" id="dicari" placeholder="Cari Peserta Didik..." aria-label="Search">
			<div class="input-group-append">
				<button class="btn btn-navbar" id="goleki">
					<i class="fas fa-search"></i>
				</button>
			</div>
			</div>
		</div>
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<span class="btn nav-link"	data-toggle="modal" data-target="#myPassword" title="Ganti Password">
					<i class="fas fa-key"></i>
				</span>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="index.php?p=adduser&m=3" title="Edit Data">
					<i class="fas fa-edit"></i>
				</a>
				</li>
			<li class="nav-item">
				<a class="nav-link" href="logout.php" title="Keluar / Logout">
					<i class="fas fa-power-off"></i>
				</a>
			</li>
		</ul>
	</nav>
	<aside class="main-sidebar sidebar-dark-primary elevation-4">
		<a href="index.php?p=dashboard" class="brand-link">
			<img src="../assets/img/logo.png" width="100" class="brand-image elevation-3"
				style="opacity: 0.9">
			<span class="brand-text font-weight-bold">NewCBT App</span>
		</a>
		<div class="sidebar">
			<div class="user-panel mt-3 pb-3 mb-3 d-flex">
				<div class="image">
					<img src="<?php echo $fotouser;?>" class="img-circle elevation-2" alt="User Image">
				</div>
				<div class="info">
					<a href="#" class="d-block"><?php echo $nmuser;?></a>
				</div>
			</div>
			<?php
				include "sidemenu.php";
			?>
		</div>
	</aside>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
				<?php
					switch ($_GET['p']){
						case 'dashboard' : {$head='Dashboard';$menu='';break;}
						case 'datasekolah' : {$head='Data Master';$menu='Identitas Satuan Pendidikan';break;}
						case 'datauser' : {$head='Data Master';$menu='Guru Bidang Studi';break;}
						case 'adduser' : {$head='Guru Bidang Studi';$menu='Tambah / Edit Data Pengguna';break;}
						case 'datakur' : {$head='Data Master';$menu='Kurikulum';break;}
						case 'datamapel' : {$head='Data Master';$menu='Mata Pelajaran';break;}
						case 'datasiswa' : {$head='Data Master';$menu='Biodata Peserta Didik';break;}
						case 'addsiswa' : {$head='Biodata Peserta Didik';$menu='Tambah / Edit Biodata Peserta Didik';break;}
						case 'datakelas' : {$head='Manajemen KBM';$menu='Data Rombongan Belajar';break;}
						case 'datarombel' : {$head='Manajemen KBM';$menu='Anggota Rombongan Belajar';break;}
						case 'datakkm' : {$head='Manajemen KBM';$menu='Pengaturan KKM';break;}
						case 'dataampu' : {$head='Manajemen KBM';$menu='Data Guru Pengampu';break;}
						case 'datates' : {$head='Manajemen Ujian';$menu='Jenis Tes';break;}
						case 'sesi' : {$head='Manajemen Ujian';$menu='Sesi Ujian';break;}
						case 'ruang' : {$head='Manajemen Ujian';$menu='Ruang Ujian';break;}
						case 'jadwal' : {$head='Manajemen Ujian';$menu='Jadwal Ujian';break;}
						case 'datapeserta' : {$head='Manajemen Ujian';$menu='Peserta Ujian';break;}
						case 'banksoal' : {$head='Manajemen Ujian';$menu='Bank Soal';break;}
						case 'isisoal' : {$head='Manajemen Ujian';$menu='Bank Soal';break;}
						case 'editsoal' : {$head='Manajemen Ujian';$menu='Bank Soal';break;}
						case 'statussoal' : {$head='Status Ujian';$menu='Ujikan Bank Soal';break;}
						case 'token' : {$head='Status Ujian';$menu='Rilis Token';break;}
						case 'statuspeserta' : {$head='Status Ujian';$menu='Status Peserta';break;}
						case 'hasiltes' : {$head='Laporan';$menu='Hasil Tes';break;}
						case 'ledger' : {$head='Laporan';$menu='Rekap Nilai';break;}
						case 'rapor' : {$head='Laporan';$menu='Rapor Murni';break;}
						default:{$head='Dashboard';$menu='Dashboard';}
					}
				?>
					<div class="col-sm-6">
						<h1 class="m-0 text-dark"><?php echo $head;?></h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $menu;?></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<section class="content">
			<div class="container-fluid">
				<div class="form-group">
				<?php
					switch ($_GET['p']){
						case 'dashboard' : {include "dashboard.php";break;}
						case 'datasekolah' : {include "sekolah_tampil.php";break;}
						case 'datauser' : {include "user_tampil.php";break;}
						case 'adduser': {include "user_form.php";break;}
						case 'datakur' : {include "kurikulum_tampil.php";break;}
						case 'datates' : {include "tes_tampil.php";break;}
						case 'datamapel' : {include "mapel_tampil.php";break;}
						case 'datasiswa' : {include "siswa_tampil.php";break;}
						case 'addsiswa' : {include "siswa_form.php";break;}
						case 'datakelas' : {include "kelas_tampil.php";break;}
						case 'dataampu' : {include "pengampu_tampil.php";break;}
						case 'datarombel' : {include "rombel_tampil.php";break;}
						case 'datakkm' : {include "kkm_tampil.php";break;}
						case 'dataujian' : {include "ujian_tampil.php";break;}
						case 'sesi' : {include "sesi_tampil.php";break;}
						case 'ruang' : {include "ruang_tampil.php";break;}
						case 'jadwal' : {include "jadwal_tampil.php";break;}
						case 'panitia' : {include "panitia_tampil.php";break;}
						case 'banksoal' : {include "banksoal_tampil.php";break;}
						case 'isisoal' : {include "isisoal_tampil.php";break;}
						case 'addstimulus' : {include "stimulus_form.php";break;}
						case 'tambahsoal' : {include "isisoal_add.php";break;}
						
						case 'editsoal' : {include "isisoal_edit.php";break;}
						case 'datapeserta' : {include "peserta_tampil.php";break;}
						case 'statuspeserta' : {include "status_peserta.php";break;}
						case 'statussoal' : {include "status_soal.php";break;}
						case 'token':{include "token_tampil.php";break;}
						case 'hasiltes':{include "hasil_tes.php";break;}
						case 'detailtes':{include "hasil_detail.php";break;}
						case 'jawabantes':{include "hasil_jawab.php";break;}
						case 'ledger':{include "hasil_rekap.php";break;}
						case 'hadir':{include "hasil_hadir.php";break;}
						case 'rapor':{include "hasil_rapor.php";break;}
						case 'backup':{include "backup.php";break;}
						default:{include "dashboard.php";break;}
					}
				?>
				</div>
			</div>
		</section>
	</div>
	<footer class="main-footer text-sm">
		<strong>Copyright &copy;</strong> Kasworo Wardani
		<div class="float-right d-none d-sm-inline-block">
			<b>New CBT Versi</b> 1.1.1
		</div>
	</footer>
</div>
<script type="text/javascript" src="../assets/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#gantipass").click(function(){
			var passlama=$("#passlama").val();
			var passbaru=$("#passbaru").val();
			var passkonf=$("#passkonf").val();
			var id="<?php echo $_COOKIE['c_user'];?>";
			if(passlama==''){
				toastr.error('Password Lama Tidak Boleh Kosong');
				$("#passbaru").focus();
			}
			else if(passkonf!==passbaru){
				toastr.error('Password Tidak Sama');
				$("#passbaru").focus();
			}
			$.ajax({
				type:"POST",
				url:"user_simpan.php",
				data: "aksi=pass&id="+id+"&passbaru="+passbaru,
				success:function(data){
					toastr.success(data)
				}	
			})	
		})		
	})
</script>
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery.countdownTimer.js"></script>
<script src="../assets/js/adminlte.min.js"></script>
<script src="../assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="../assets/plugins/toastr/toastr.min.js"></script>
<script src="../assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../assets/plugins/raphael/raphael.min.js"></script>
<script src="../assets/plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="../assets/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="../assets/js/jquery.datetimepicker.full.js"></script>
<script src="../assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="../assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../assets/plugins/chart.js/Chart.min.js"></script>
<script src="js/myindex.js"></script>
</body>
</html>
