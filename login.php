<?php
session_start();
include "dbfunction.php";
$error = false;
if (isset($_COOKIE['pst']) && isset($_COOKIE['uji'])) {
    $pwd = $_COOKIE['key'];
    $keys = array(
        'idsiswa' => $_COOKIE['pst']
    );
    $data = viewdata('tbpeserta', $keys)[0];
    if ($pwd === hash('sha256', $data['passwd'])) {
        $_SESSION['login'] = true;
    }
}

if (isset($_SESSION['login'])) {
    header("Location: index.php?p=conf");
    exit;
}

if (isset($_POST['login'])) {
    $user = $conn->real_escape_string($_POST['user']);
    $pass = $_POST['pass'];
    $keys = array(
        'nmpeserta' => $user
    );
    $cekuser = cekdata('tbpeserta', $keys);
    if ($cekuser === 1) {
        $data = viewdata('tbpeserta', $keys)[0];
        if ($data['passwd'] == $pass &&  $data['aktif'] === '1') {
            $_SESSION['login'] = true;
            setcookie('pst', $data['idsiswa']); //, time() + 3600);
            if (isset($_POST['ingat'])) {
                setcookie('key', hash('sha256', $data['passwd'])); //, time() + 3600);
            }
            header("Location:index.php?p=conf");
            exit;
        }
    } else {
        $sqlu = "SELECT us.username, ps.idsiswa, us.passwd, us.aktif FROM tbuser us INNER JOIN tbpeserta ps ON ps.nisn=us.username WHERE us.username='$user'";
        $cekdata = cquery($sqlu);
        if ($cekdata > 0) {
            $data = vquery($sqlu)[0];
            if ($data['passwd'] == $pass &&  $data['aktif'] === '1') {
                $_SESSION['login'] = true;
                setcookie('pst', $data['idsiswa']); //, time() + 3600);
                if (isset($_POST['ingat'])) {
                    setcookie('key', hash('sha256', $data['passwd'])); //, time() + 3600);
                }
                header("Location:index.php?p=conf");
                exit;
            }
        }
    }
    $error = true;
}
$qujian = "SELECT nmtes, desthpel FROM tbujian u INNER JOIN tbtes USING(idtes) INNER JOIN tbthpel USING(idthpel) WHERE u.status='1'";
if (cquery($qujian) > 0) {
    $du = vquery($qujian)[0];
    $periode = $du['desthpel'];
    $nmujian = $du['nmtes'];
} else {
    $periode = '';
    $nmujian = '';
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Aplikasi NewCBT</title>
    <link href='assets/img/tutwuri.png' rel='icon' type='image/png' />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="assets/css/adminlte.min.css">
</head>

<body class="hold-transition login-page" style="background:url(assets/img/boxed-bg.png)">
    <div class="login-box">
        <div class="login-logo">
            <span style="color:#8c0a0a;font-size:18pt;font-weight:bold">Selamat Datang Peserta
                <br />
                <p style="font-size:14pt;font-weight:bold"><?php echo $nmujian . '<br/>' . $periode; ?> </p>
            </span>
        </div>
        <form action="" method="post" id="FrmLogin">
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Silahkan Login Menggunakan <br />Username dan Password<br />Yang Sudah
                        Diberikan</p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="user" name="user" placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <style>
                        #viewpass:hover {
                            cursor: pointer;
                        }
                    </style>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="pass" name="pass" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-eye" title="Tampilkan Password" id="viewpass"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group mb-3">
                        <div class="col-sm-8">
                            <div class="icheck-primary mb-3">
                                <input type="checkbox" id="ingat" name="ingat">
                                <label for="ingat">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-primary btn-block mb-3" id="login" name="login">
                                <i class="fas fa-sign-in-alt"></i>&nbsp;Login
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/plugins/toastr/toastr.min.js"></script>
    <script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="assets/js/adminlte.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#viewpass").click(function() {
                var x = $("#pass").attr('type');
                if (x === 'password') {
                    $(this).removeClass("fas fa-eye");
                    $(this).addClass("fas fa-eye-slash");
                    $(this).attr("title", "Sembunyikan Password");
                    $("#pass").attr('type', 'text');
                } else {
                    $(this).removeClass("fas fa-eye-slash");
                    $(this).attr("title", "Tampilkan Password");
                    $(this).addClass("fas fa-eye");
                    $("#pass").attr('type', 'password');
                }
            })
        })
    </script>
    <?php
    if ($error) {
        echo "<script>
				$(function() {
					toastr.error('Cek Username dan Password Anda!','Mohon Maaf',{
						timeOut:1000,
						fadeOut:1000,
						onHidden:function(){
							$('#user').focus();
						}
					});
				});
			</script>";
    }
    ?>
</body>

</html>