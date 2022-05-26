<?php
session_start();
include "dbfunction.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (empty($_COOKIE['id'])) {
    header("Location: logout.php");
    exit;
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
        $fotouser = '../assets/img/avatar.gif';
    } else {
        if (file_exists('foto/' . $foto)) {
            $fotouser = 'foto/' . $foto;
        } else {
            $fotouser = '../assets/img/avatar.gif';
        }
    }

    $tahun = array(
        'aktif' => '1'
    );
    $tp = viewdata("tbthpel", $tahun)[0];
    $idthpel = $tp['idthpel'];
    $tapel = $tp['desthpel'];


    $sk = viewdata('tbskul')[0];
    $idskul = $sk['idskul'];
    $nmskul = $sk['nmskul'];
    $jenjang = $sk['idjenjang'];
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Aplikasi NewCBT</title>
        <link href='../assets/img/tutwuri.png' rel='icon' type='image/png' />
        <link rel="stylesheet" href="../assets/css/all.min.css">
        <link rel="stylesheet" href="../assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
        <link rel="stylesheet" href="../assets/css/adminlte.min.css">
        <link rel="stylesheet" href="../assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
        <link rel="stylesheet" href="../assets/plugins/toastr/toastr.min.css">
        <link rel="stylesheet" href="../assets/plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="../assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="../assets/css/jquery.datetimepicker.css">
        <link rel="stylesheet" type="text/css" href="../assets/css/dropzone.css" />
        <script type="text/javascript" src="../assets/js/dropzone.js"></script>
        <script type="text/javascript" src="../assets/js/jquery-1.4.js"></script>
        <script type="text/javascript" src="../assets/js/ajaxupload.3.5.js"></script>

    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

        <div class="modal fade" id="myLaporTes" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="judule">Laporan Hasil Tes</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <script type="text/javascript" src="js/laporan.js"></script>
                    <div class="modal-body">
                        <div class="form-group row mb-2 mt-2">
                            <input type="hidden" class="form-control form-control-sm col-sm-5" id="idlapor" name="idlapor">

                            <label class="col-sm-5 offset-sm-1">Ujian (Tes)</label>
                            <select class="form-control form-control-sm col-sm-5" name="hsl_tes" id="hsl_tes" onchange="getKelas(this.value)">
                                <?php
                                $sqtes = "SELECT u.idujian, u.nmujian, ts.nmtes FROM tbujian u INNER JOIN tbtes ts USING(idtes) INNER JOIN tbthpel t USING(idthpel) WHERE t.aktif='1'";
                                $qtes = vquery($sqtes);
                                ?>
                                <option value="">..Pilih..</option>
                                <?php
                                foreach ($qtes as $ts) : ?>
                                    <option value="<?php echo $ts['idujian']; ?>"><?php echo $ts['nmujian'] . ' - ' . $ts['nmtes']; ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group row mb-2 mt-2">
                            <label class="col-sm-5 offset-sm-1">Kelas</label>
                            <select class="form-control form-control-sm col-sm-5" name="hsl_kls" id="hsl_kls" onchange="getRombel(this.value)"></select>
                        </div>
                        <div class=" form-group row mb-2 mt-2">
                            <label class="col-sm-5 offset-sm-1">Rombel</label>
                            <select class="form-control form-control-sm col-sm-5" name="hsl_rmb" id="hsl_rmb"></select>
                        </div>
                    </div>
                    <div class=" modal-footer justify-content-between">
                        <button class="btn btn-primary btn-sm col-4" id="btnLapor" name="lapor_hsl">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <a href="#" class="btn btn-danger btn-sm col-4" data-dismiss="modal">
                            <i class="fas fa-power-off"></i> Tutup
                        </a>
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
                <?php echo $navigasi; ?>
            </nav>
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <a href="index.php?p=dashboard" class="brand-link" title="Aplikasi CBT">
                    <img src="../assets/img/logo.png" width="100" class="brand-image elevation-3" style="opacity: 1.0">
                    <span class="brand-text font-weight-light">Aplikasi NewCBT</span>
                </a>
                <div class="sidebar">
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="<?php echo $fotouser; ?>" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block"><?php echo $nmuser; ?></a>
                        </div>
                    </div>
                    <?php include "sidemenu.php"; ?>
                </div>
            </aside>
            <div class="content-wrapper" style="background:url(../assets/img/boxed-bg.png)">
                <?php
                if (isset($_GET['p'])) {
                    switch ($_GET['p']) {
                        case 'dashboard': {
                                $head = 'Dashboard';
                                $menu = '';
                                break;
                            }
                        case 'datasekolah': {
                                $head = 'Data Master';
                                $menu = 'Identitas Satuan Pendidikan';
                                break;
                            }
                        case 'datauser': {
                                $head = 'Data Master';
                                $menu = 'Guru Bidang Studi';
                                break;
                            }
                        case 'adduser': {
                                $head = 'Guru Bidang Studi';
                                $menu = 'Tambah / Edit Data Pengguna';
                                break;
                            }
                        case 'datakur': {
                                $head = 'Data Master';
                                $menu = 'Kurikulum';
                                break;
                            }
                        case 'datamapel': {
                                $head = 'Data Master';
                                $menu = 'Mata Pelajaran';
                                break;
                            }
                        case 'datasiswa': {
                                $head = 'Data Master';
                                $menu = 'Biodata Peserta Didik';
                                break;
                            }
                        case 'addsiswa': {
                                $head = 'Biodata Peserta Didik';
                                $menu = 'Tambah / Edit Biodata Peserta Didik';
                                break;
                            }
                        case 'datakelas': {
                                $head = 'Manajemen KBM';
                                $menu = 'Data Rombongan Belajar';
                                break;
                            }
                        case 'datarombel': {
                                $head = 'Manajemen KBM';
                                $menu = 'Anggota Rombongan Belajar';
                                break;
                            }
                        case 'datakkm': {
                                $head = 'Manajemen KBM';
                                $menu = 'Pengaturan KKM';
                                break;
                            }
                        case 'dataampu': {
                                $head = 'Manajemen KBM';
                                $menu = 'Data Guru Pengampu';
                                break;
                            }
                        case 'datates': {
                                $head = 'Manajemen Ujian';
                                $menu = 'Jenis Tes';
                                break;
                            }
                        case 'sesi': {
                                $head = 'Manajemen Ujian';
                                $menu = 'Sesi Ujian';
                                break;
                            }
                        case 'ruang': {
                                $head = 'Manajemen Ujian';
                                $menu = 'Ruang Ujian';
                                break;
                            }
                        case 'jadwal': {
                                $head = 'Manajemen Ujian';
                                $menu = 'Jadwal Ujian';
                                break;
                            }
                        case 'datapeserta': {
                                $head = 'Manajemen Ujian';
                                $menu = 'Peserta Ujian';
                                break;
                            }
                        case 'banksoal': {
                                $head = 'Manajemen Ujian';
                                $menu = 'Bank Soal';
                                break;
                            }
                        case 'isisoal': {
                                $head = 'Manajemen Ujian';
                                $menu = 'Bank Soal';
                                break;
                            }
                        case 'editsoal': {
                                $head = 'Manajemen Ujian';
                                $menu = 'Bank Soal';
                                break;
                            }
                        case 'statussoal': {
                                $head = 'Status Ujian';
                                $menu = 'Ujikan Bank Soal';
                                break;
                            }
                        case 'token': {
                                $head = 'Status Ujian';
                                $menu = 'Rilis Token';
                                break;
                            }
                        case 'statuspeserta': {
                                $head = 'Status Ujian';
                                $menu = 'Status Peserta';
                                break;
                            }
                        case 'hasiltes': {
                                $head = 'Laporan';
                                $menu = 'Hasil Tes';
                                break;
                            }
                        case 'ledger': {
                                $head = 'Laporan';
                                $menu = 'Rekap Nilai';
                                break;
                            }
                        case 'rapor': {
                                $head = 'Laporan';
                                $menu = 'Rapor Murni';
                                break;
                            }
                        default: {
                                $head = 'Dashboard';
                                $menu = 'Dashboard';
                                break;
                            }
                    }
                } else {
                    $head = 'Dashboard';
                    $menu = 'Dashboard';
                }
                ?>
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark" id="hdTitle"><?php echo $head; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item" id="hdMenu"><?php echo $menu; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <section class="content">
                    <div class="container-fluid">
                        <div class="form-group" id="konten">
                            <?php
                            if (isset($_GET['p'])) {
                                switch ($_GET['p']) {
                                    case 'dashboard': {
                                            include "dashboard.php";
                                            break;
                                        }
                                    case 'datasekolah': {
                                            include "sekolah_tampil.php";
                                            break;
                                        }
                                    case 'datagtk': {
                                            include "gtk_tampil.php";
                                            break;
                                        }
                                    case 'addgtk': {
                                            include "gtk_form.php";
                                            break;
                                        }
                                    case 'datakur': {
                                            include "kurikulum_tampil.php";
                                            break;
                                        }
                                    case 'datates': {
                                            include "tes_tampil.php";
                                            break;
                                        }
                                    case 'datamapel': {
                                            include "mapel_tampil.php";
                                            break;
                                        }
                                    case 'datasiswa': {
                                            include "siswa_tampil.php";
                                            break;
                                        }
                                    case 'addsiswa': {
                                            include "siswa_form.php";
                                            break;
                                        }
                                    case 'datakelas': {
                                            include "kelas_tampil.php";
                                            break;
                                        }
                                    case 'dataampu': {
                                            include "pengampu_tampil.php";
                                            break;
                                        }
                                    case 'datarombel': {
                                            include "rombel_tampil.php";
                                            break;
                                        }
                                    case 'datakkm': {
                                            include "kkm_tampil.php";
                                            break;
                                        }
                                    case 'dataujian': {
                                            include "ujian_tampil.php";
                                            break;
                                        }
                                    case 'sesi': {
                                            include "sesi_tampil.php";
                                            break;
                                        }
                                    case 'ruang': {
                                            include "ruang_tampil.php";
                                            break;
                                        }
                                    case 'jadwal': {
                                            include "jadwal_tampil.php";
                                            break;
                                        }
                                    case 'panitia': {
                                            include "panitia_tampil.php";
                                            break;
                                        }
                                    case 'banksoal': {
                                            include "banksoal_tampil.php";
                                            break;
                                        }
                                    case 'isisoal': {
                                            include "isisoal_tampil.php";
                                            break;
                                        }
                                    case 'addstimulus': {
                                            include "isisoal_addstimulus.php";
                                            break;
                                        }
                                    case 'tambahsoal': {
                                            include "isisoal_addbutir.php";
                                            break;
                                        }
                                    case 'editsoal': {
                                            include "isisoal_editbutir.php";
                                            break;
                                        }
                                    case 'editsoal': {
                                            include "isisoal_edit.php";
                                            break;
                                        }
                                    case 'datapeserta': {
                                            include "peserta_tampil.php";
                                            break;
                                        }
                                    case 'statuspeserta': {
                                            include "status_peserta.php";
                                            break;
                                        }
                                    case 'statussoal': {
                                            include "status_soal.php";
                                            break;
                                        }
                                    case 'token': {
                                            include "token_tampil.php";
                                            break;
                                        }
                                    case 'hasiltes': {
                                            include "hasil_tes.php";
                                            break;
                                        }
                                    case 'detailtes': {
                                            include "hasil_detail.php";
                                            break;
                                        }
                                    case 'jawabantes': {
                                            include "hasil_jawab.php";
                                            break;
                                        }
                                    case 'ledger': {
                                            include "hasil_rekap.php";
                                            break;
                                        }
                                    case 'hadir': {
                                            include "hasil_hadir.php";
                                            break;
                                        }
                                    case 'rapor': {
                                            include "hasil_rapor.php";
                                            break;
                                        }
                                    case 'backup': {
                                            include "backup.php";
                                            break;
                                        }
                                    default: {
                                            include "dashboard.php";
                                            break;
                                        }
                                }
                            } else {
                                include "dashboard.php";
                            }
                            ?>
                        </div>
                    </div>
                </section>
            </div>
            <footer class="main-footer text-sm">
                <strong>Copyright &copy;</strong> Kasworo Wardani, Template By <a href="http://adminlte.io">AdminLTE.io</a>.
                All rights reserved.
                <div class="float-right d-none d-sm-inline-block">
                    <b>CBTSync Versi</b> 1.0.0
                </div>
            </footer>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-center",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "500",
                    "hideDuration": "3000",
                    "timeOut": "3000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
                bsCustomFileInput.init();
            });
            $("#MasterSkul").click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "sekolah_tampil.php",
                    type: 'POST',
                    data: "html",
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 8000,
                    success: function(respons) {
                        $("#hdTitle").html('Master')
                        $("#hdMenu").html('Data Sekolah12')
                        $("#konten").html(respons)
                    }
                })
            })
            $(".btnReport").click(function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                if (id == '1') {
                    $("#judule").html("Laporan Hasil Ujian");
                }
                if (id == '2') {
                    $("#judule").html("Rekapitulasi Hasil Ujian");
                }
                if (id == '3') {
                    $("#judule").html("Rapor Peserta Didik");
                }
                $("#idlapor").val(id);
            })
            $("#btnLapor").click(function(e) {
                e.preventDefault()
                let idl = $("#idlapor").val()
                if (idl == 1) {
                    myurl = "hasil_tes.php"
                }
                if (idl == 2) {
                    myurl = "hasil_rekap.php"
                }
                if (idl == 3) {
                    myurl = "hasil_rapor.php"
                }
                let uji = $("#hsl_tes").val()
                let kls = $("#hls_kls").val()
                let rmb = $("#hsl_rmb").val()
                if (uji == '') {
                    toastr.error("Pilih Jenis Tes Dulu", "Maaf!");
                } else if (kls == '') {
                    toastr.error("Pilih Kelas Dulu", "Maaf!");
                } else if (rmb == '') {
                    toastr.error("Pilih Rombel Dulu", "Maaf!");
                } else {
                    let data = new FormData()
                    data.append('uji', uji);
                    // data.append('kls', kls);
                    data.append('rmb', rmb);
                    $.ajax({
                        url: myurl,
                        type: 'POST',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 8000,
                        success: function(respons) {
                            $("#myLaporTes").modal('hide')
                            $("#konten").html(respons)
                        }
                    })
                }

                //window.location.href = myurl;

            })
        </script>
        <script src="../assets/js/jquery.js"></script>
        <script type="text/javascript" src="../assets/plugins/jquery/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/adminlte.min.js"></script>
        <script src="../assets/js/dreamimage.js"></script>
        <script src="../assets/plugins/toastr/toastr.min.js"></script>
        <script src="../assets/plugins/sweetalert2/sweetalert2.min.js"></script>
        <script src="../assets/plugins/select2/js/select2.full.min.js"></script>
        <script src="../assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <script src="../assets/js/jquery.datetimepicker.full.js"></script>
        <script src="../assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
        <script src="../assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="../assets/plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="../assets/plugins/chart.js/chart.js"></script>

    </body>

    </html>
<?php } ?>