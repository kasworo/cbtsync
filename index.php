<?php
session_start();
include "dbfunction.php";
if (empty($_SESSION['login'])) {
	header("Location: login.php");
}
if (empty($_COOKIE['pst'])) {
	header("Location: login.php");
	exit;
}
date_default_timezone_set('Asia/Jakarta');
$sql = "SELECT*FROM tbskul";
$s = vquery($sql)[0];
$nmskul = $s['nmskul'];
$logo = 'images/' . $s['logoskul'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Aplikasi CBT</title>
	<link href='assets/img/tutwuri.png' rel='icon' type='image/png' />
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="assets/css/all.min.css">
	<!-- Theme style -->

	<link rel="stylesheet" href="assets/css/adminlte.min.css">
	<link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	<link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<script type="text/javascript" src="assets/js/jquery-2.0.3.js"></script>
	<script type="text/javascript">
		function disableBackButton() {
			window.history.forward();
		}
		setTimeout("disableBackButton()", 0);
	</script>
</head>

<body class="hold-transition layout-top-nav layout-navbar-fixed layout-footer-fixed">
	<div class="wrapper">
		<nav class="main-header navbar navbar-expand-md navbar-light navbar-dark">
			<div class="navbar-brand">
				<img src="assets/img/logo.png" class="brand-image elevation-3">
				<span class="brand-text">Aplikasi NewCBT</span>
			</div>
			<?php if (isset($_GET['p']) && $_GET['p'] == 'ujian') : ?>
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<i class="fas fa-clock-alt"></i>
						<span id="u_timer" class="timer" style="color:white;font-weight:bold;font-size:14pt"></span>
					</li>
				</ul>
			<?php else : ?>
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link" href="logout.php" role="button">
							<i class="fas fa-power-off"></i> Logout
						</a>
					</li>
				</ul>
			<?php endif ?>
		</nav>

		<div class="content-wrapper" style="background:url(assets/img/boxed-bg.png);height: auto;">
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-8">
							<h4 class="m-0 text-dark"></h4>
						</div>
						<div class="col-sm-4">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"></li>
							</ol>
						</div>
					</div>
				</div>
			</div>
			<div class="content">
				<div class="container-fluid">
					<div class="form-group row">
						<?php
						if (isset($_GET['p'])) {
							switch ($_GET['p']) {
								case 'conf': {
										include "confirm.php";
										break;
									}
								case 'mulai': {
										include "mulai.php";
										break;
									}
								case 'ujian': {
										include "ujian.php";
										break;
									}
								case 'end': {
										include "akhir.php";
										break;
									}
								case 'selesai': {
										include "selesai.php";
										break;
									}
								case 'gethasil': {
										include "lihathasil.php";
										break;
									}
								case 'default': {
										include "confirm.php";
										break;
									}
							}
						} else {
							include "confirm.php";
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<footer class="main-footer text-sm">
			<div class="float-right d-none d-sm-inline">
				Kasworo Wardani &copy; 2022
			</div>
			<strong><?php echo $nmskul; ?>
		</footer>
	</div>
	<script src="assets/js/jquery.js"></script>
	<script src="assets/plugins/jquery/jquery.min.js"></script>
	<script src="assets/js/jquery.countdownTimer.js"></script>
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/adminlte.min.js"></script>
	<script src="assets/plugins/toastr/toastr.min.js"></script>
	<script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>
	<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
	<script src="assets/plugins/jquery-validation/jquery.validate.min.js"></script>
	<script src="assets/plugins/jquery-validation/additional-methods.min.js"></script>
	<script src="assets/plugins/mousetrap/mousetrap.min.js"></script>
	<script src="assets/js/jquery.jfontsize-1.0.js"></script>
</body>

</html>