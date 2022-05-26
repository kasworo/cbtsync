<?php
require_once "../assets/library/PHPExcel.php";
require_once "../assets/library/excel_reader.php";
function GetTglUjian()
{
    $sqlsesi = "SELECT jd.tglujian FROM tbjadwal jd LEFT JOIN tbujian u USING(idujian) WHERE u.status='1' GROUP BY jd.tglujian";
    return cquery($sqlsesi);
}
if (isset($_POST['import'])) {
    //include "dbfunction.php";
    if (empty($_FILES['tmpsesi']['tmp_name'])) {
        echo "<script>
            $(function() {
                toastr.error('File Template Pengaturan Sesi Tidak Boleh Kosong!','Mohon Maaf!',{
                    timeOut:1000,
                    fadeOut:1000
                });
            });
        </script>";
    } else {
        $data = new Spreadsheet_Excel_Reader($_FILES['tmpsesi']['tmp_name']);
        $baris = $data->rowcount($sheet_index = 0);
        $isidata = $baris - 6;
        $sukses = 0;
        $gagal = 0;
        $update = 0;
        $no = 0;
        $dtgl = GetTglUjian();
        for ($i = 6; $i <= $baris; $i++) {
            $no++;
            $xnmpeserta = $data->val($i, 2);
            $xpeserta = $data->val($i, 5);
            $qsiswa = "SELECT idsiswa FROM tbpeserta WHERE nmpeserta='$xnmpeserta'";
            $sw = vquery($qsiswa)[0];
            $idsiswa = $sw['idsiswa'];
            for ($j = 1; $j <= $dtgl; $j++) {
                $tgluji = $data->val(4, $j + 7);
                $sqljd = "SELECT idjadwal FROM tbjadwal WHERE tglujian='$tgluji'";
                $qjd = vquery($sqljd);
                foreach ($qjd as $jd) {
                    $xjadwal = $jd['idjadwal'];
                    $xsesi = $data->val($i, $j + 7);
                    if ($xsesi == '') {
                        echo "<script>
							$(function() {
								toastr.error('Cek Kolom Sesi a.n " . $xpeserta . "','Mohon Maaf!',{
									timeOut:10000,
									fadeOut:10000
								});
							});
						</script>";
                    } else {
                        $sqlsesi = "SELECT*FROM tbsesiujian su WHERE su.idsiswa='$idsiswa' AND su.idjadwal='$xjadwal'";
                        $cekdata = cquery($sqlsesi);
                        if ($cekdata > 0) {
                            $sql = "UPDATE tbsesiujian su INNER JOIN tbpeserta ps USING(idsiswa) SET su.idsiswa=ps.idsiswa, idsesi='$xsesi' WHERE su.idsiswa='$idsiswa' AND su.idjadwal='$xjadwal'";
                            if (equery($sql) > 0) {
                                $update++;
                            }
                        } else {
                            $sql = "INSERT INTO tbsesiujian (idsiswa, idjadwal, idsesi) VALUES('$idsiswa','$xjadwal', '$xsesi')";
                            if (equery($sql) > 0) {
                                $sukses;
                            } else {
                                $gagal++;
                            }
                        }
                    }
                    // 	$pesan = 'Cek Kolom Kode Sesi a.n ' . $xpeserta . '!';
                    // 	$jns = 'error';
                    // 	$gagal++;
                    // } else {
                    // 	$sql = "SELECT*FROM tbsesiujian su INNER JOIN tbpeserta ps USING(idsiswa) WHERE ps.nmpeserta='$xnmpeserta' AND su.idjadwal='$xjadwal'";
                    // 	$qpd = $conn->query($sql);
                    // 	$ceksiswa = $qpd->num_rows;
                    // 	if ($ceksiswa > 0) {
                    // 		$query = $conn->query);
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
            }
        }
        echo "<script>
                $(function() {
                    toastr.info('Ada " . $sukses . " data berhasil ditambahkan ke rombel, " . $update . " data diupdate, " . $gagal . " data gagal!','Terima Kasih',{
                    timeOut:2000,
                    fadeOut:2000
                });
            });
        </script>";
    }
}

if (isset($_POST['setsesi'])) {
    $sukses = 0;
    $gagal = 0;
    $update = 0;
    $batal = 0;
    $tgl = $_POST['tgluji'];
    $sesi = $_POST['sesi'];
    $hitung = count($sesi);
    $ruang = array('idruang' => $_POST['idruang']);
    if (editdata('tbpeserta', $ruang, '', array('idsiswa' => $_POST['idsiswa'])) > 0) {
        for ($i = 0; $i < $hitung; $i++) {
            $qjd = viewdata('tbjadwal', array('tglujian' => $tgl[$i]));
            foreach ($qjd as $jd) {
                $data = array(
                    'idsiswa' => $_POST['idsiswa'],
                    'idsesi' => $sesi[$i],
                    'idjadwal' => $jd['idjadwal']
                );
                if (adddata('tbsesiujian', $data) > 0) {
                    $sukses++;
                } else {
                    $gagal++;
                }
            }
        }
    } else {
        for ($i = 0; $i < $hitung; $i++) {
            $qjd = viewdata('tbjadwal', array('tglujian' => $tgl[$i]));
            foreach ($qjd as $jd) {
                $key = array(
                    'idsiswa' => $_POST['idsiswa'],
                    'idjadwal' => $jd['idjadwal']
                );
                if (cekdata('tbsesiujian', $key) > 0) {
                    $data = array(
                        'idsesi' => $sesi[$i]
                    );
                    if (editdata('tbsesiujian', $data, '', $key) > 0) {
                        $update++;
                    } else {
                        $batal++;
                    }
                } else {
                    $data = array(
                        'idsiswa' => $_POST['idsiswa'],
                        'idsesi' => $sesi[$i],
                        'idjadwal' => $jd['idjadwal']
                    );
                    if (adddata('tbsesiujian', $data) > 0) {
                        $sukses++;
                    } else {
                        $gagal++;
                    }
                }
            }
        }
    }
}
?>
<div class="modal fade" id="myImportPeserta" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Pengaturan Sesi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="row">
                            <label for="tmpsiswa">Pilih File Template</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="tmpsesi" name="tmpsesi">
                                <label class="custom-file-label" for="tmpsesi">Pilih file</label>
                            </div>
                            <p style="color:red;margin-top:10px"><em>Hanya mendukung file *.xls (Microsoft Excel 97-2003)</em></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a href="peserta_template.php" class="btn btn-success btn-sm" target="_blank">
                        <i class="fas fa-download"></i> Download
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm" name="import">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                        <i class="fas fa-power-off"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="mySetPeserta" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="JudulSet">Tambah Pengaturan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="IdSetting"></div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-primary btn-sm" id="btnSetting" name="setsesi">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                        <i class="fas fa-power-off"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php if ($level == '1') : ?>
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <h4 class="card-title">Data Peserta Tes</h4>
            <div class="card-tools">
                <button class="btn btn-info btn-sm" id="btnNomor">
                    <i class="fas fa-plus-circle"></i>&nbsp;Tambah
                </button>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myImportPeserta">
                    <i class="fas fa-cloud-upload-alt"></i>&nbsp;Import
                </button>
                <button id="btnHapus" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash-alt"></i>&nbsp;Hapus
                </button>
                <a href="print_kartu.php" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="fas fa-print"></i>&nbsp;Kartu Peserta
                </a>
                <a href="print_nomeja.php" target="_blank" class="btn btn-default btn-sm">
                    <i class="fas fa-print"></i>&nbsp;Nomor Meja
                </a>
                <a href="print_daftar.php" target="_blank" class="btn btn-default btn-sm">
                    <i class="fas fa-print"></i>&nbsp;Daftar Peserta
                </a>
            </div>
        </div>
        <div class="card-body">
            <div id="pesan"></div>
            <div class="table-responsive">
                <table id="tb_peserta" class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th style="text-align: center;width:2.5%">No.</th>
                            <th style="text-align: center;width:12.5%">No. Peserta</th>
                            <th style="text-align: center;">Nama Peserta</th>
                            <th style="text-align: center;width:5%">Kelas</th>
                            <th style="text-align: center;width:5%">Password</th>
                            <th style="text-align: center;width:7.5%">Ruang</th>
                            <th style="text-align: center;width:15%">Sesi Ujian</th>
                            <th style="text-align: center;width:17.5%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($level == '1') {
                            $sqlp = "SELECT ps.idsiswa, ps.nmsiswa, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian, r1.nmruang, ps.idruang, ps.idujian, ps.aktif FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbujian u USING(idujian) LEFT JOIN tbruang r1 USING(idruang) WHERE u.status='1' AND ps.deleted='0' GROUP BY ps.idsiswa  ORDER BY ps.nmpeserta";
                        } else {
                            $sqlp = "SELECT ps.idsiswa, ps.nmsiswa, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian, r1.nmruang, ps.idruang, ps.idujian, ps.aktif FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbpengampu a USING(idrombel) INNER JOIN tbgtk g ON (a.idgtk=g.idgtk OR r.idgtk=g.idgtk)INNER JOIN tbuser us USING(username) INNER JOIN tbujian u USING(idujian) LEFT JOIN tbruang r1 USING(idruang) WHERE us.username='$_COOKIE[id]' AND u.status='1' AND ps.deleted='0' GROUP BY ps.idsiswa  ORDER BY ps.nmpeserta";
                        }
                        // var_dump($sqlp);
                        // die;
                        $qs = vquery($sqlp);
                        $no = 0;
                        foreach ($qs as $s) :
                            $no++;
                            if ($s['aktif'] == '1') {
                                $badg = 'badge badge-success';
                            } else {
                                $badg = 'badge badge-danger';
                            }
                            $sqls = "SELECT GROUP_CONCAT(idsesi SEPARATOR ', ') as sesi FROM tbsesiujian su INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbujian u USING(idujian) WHERE idsiswa='$s[idsiswa]' AND u.status='1'";
                            $ds = vquery($sqls)[0];
                        ?>
                            <tr>
                                <td style="text-align:center"><?php echo $no . '.'; ?></td>
                                <td style="text-align:center">
                                    <?php echo $s['nmpeserta']; ?>
                                </td>
                                <td>
                                    <?php echo ucwords(strtolower($s['nmsiswa'])); ?>
                                    <span class="<?php echo $badg; ?> float-right"><?php echo $s['aktif']; ?></span>
                                </td>
                                <td style="text-align:center">
                                    <?php echo $s['nmrombel']; ?>
                                </td>
                                <td style="text-align:center">
                                    <?php echo $s['passwd']; ?>
                                </td>
                                <td style="text-align:center">
                                    <?php echo $s['nmruang']; ?>
                                </td>
                                <td style="text-align:center">
                                    <?php echo $ds['sesi'] ?>
                                </td>
                                <td style="text-align:center">
                                    <button class="btn btn-success btn-xs btnSet" data-target="#mySetPeserta" data-toggle="modal" data-id="<?php echo $s['idsiswa']; ?>">
                                        <i class="far fa-edit"></i> Setting
                                    </button>
                                    <button class="btn btn-danger btn-xs btnHps" data-id="<?php echo $s['idsiswa']; ?>">
                                        <i class="far fa-trash-alt"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            $('#tb_peserta').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
        })
        $(".btnSet").click(function() {
            let id = $(this).data('id');
            $.ajax({
                url: 'peserta_edit.php',
                type: 'post',
                data: 'id=' + id,
                success: function(data) {
                    $("#IdSetting").html(data);
                }
            })
        })

        $(".btnHps").click(function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Anda Yakin?',
                text: "Menghapus Pengaturan Sesi",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    let data = new FormData();
                    data.append('id', id);
                    data.append('aksi', 'delsesi');
                    $.ajax({
                        type: "POST",
                        url: "peserta_simpan.php",
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 8000,
                        success: function(respons) {
                            if (respons == 1) {
                                $(function() {
                                    toastr.success('Pengaturan Sesi Berhasil Dihapus!!', 'Terima Kasih', {
                                        timeOut: 3000,
                                        fadeOut: 3000,
                                        onHidden: function() {
                                            this.location.reload();
                                        }
                                    });
                                });
                            }

                            if (respons == 0) {
                                $(function() {
                                    toastr.error('Pengaturan Sesi Gagal Dihapus!!', 'Mohon Maaf', {
                                        timeOut: 3000,
                                        fadeOut: 3000,
                                        onHidden: function() {
                                            this.location.reload();
                                        }
                                    });
                                });
                            }
                        }
                    });
                }
            })
        })
        $("#btnNomor").click(function() {
            let data = new FormData();
            data.append('aksi', 'isi');
            $.ajax({
                url: "peserta_simpan.php",
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 3000,
                success: function(data) {
                    toastr.info(data, 'Terima Kasih', {
                        timeOut: 3000,
                        fadeOut: 3000,
                        onHidden: function() {
                            window.location.reload();
                        }
                    });
                }
            })
        })

        $("#btnHapus").click(function() {
            Swal.fire({
                title: 'Anda Yakin?',
                text: "Semua Peserta Tes Akan Dihapus",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "peserta_simpan.php",
                        data: "aksi=hapus",
                        success: function(respons) {
                            toastr.success();
                        }
                    })
                }
            })
        })
    </script>
<?php endif ?>
<?php if ($level == '2') : ?>
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <h4 class="card-title">Data Peserta Tes</h4>
            <div class="card-tools">
                <a href="print_kartu.php" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="fas fa-print"></i>&nbsp;Cetak Kartu
                </a>
            </div>
        </div>
        <div class="card-body">
            <div id="pesan"></div>
            <div class="table-responsive">
                <table id="tb_peserta" class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th style="text-align: center;width:2.5%">No.</th>
                            <th style="text-align: center;width:12.5%">No. Peserta</th>
                            <th style="text-align: center;">Nama Peserta</th>
                            <th style="text-align: center;width:5%">Kelas</th>
                            <th style="text-align: center;width:5%">Password</th>
                            <th style="text-align: center;width:7.5%">Ruang</th>
                            <th style="text-align: center;width:15%">Sesi Ujian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sqlp = "SELECT ps.idsiswa, ps.nmsiswa, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian, r1.nmruang, ps.idruang, ps.idujian, ps.aktif FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbpengampu a USING(idrombel) INNER JOIN tbgtk g ON (a.idgtk=g.idgtk OR r.idgtk=g.idgtk)INNER JOIN tbuser us USING(username) INNER JOIN tbujian u USING(idujian) LEFT JOIN tbruang r1 USING(idruang) WHERE us.username='$_COOKIE[id]' AND u.status='1' AND ps.deleted='0' GROUP BY ps.idsiswa  ORDER BY ps.nmpeserta";
                        $qs = vquery($sqlp);
                        $no = 0;
                        foreach ($qs as $s) :
                            $no++;
                            if ($s['aktif'] == '1') {
                                $badg = 'badge badge-success';
                            } else {
                                $badg = 'badge badge-danger';
                            }
                            $sqls = "SELECT GROUP_CONCAT(idsesi SEPARATOR ', ') as sesi FROM tbsesiujian su INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tbujian u USING(idujian) WHERE idsiswa='$s[idsiswa]' AND u.status='1'";
                            $ds = vquery($sqls)[0];
                        ?>
                            <tr>
                                <td style="text-align:center"><?php echo $no . '.'; ?></td>
                                <td style="text-align:center">
                                    <?php echo $s['nmpeserta']; ?>
                                </td>
                                <td>
                                    <?php echo ucwords(strtolower($s['nmsiswa'])); ?>
                                    <span class="<?php echo $badg; ?> float-right"><?php echo $s['aktif']; ?></span>
                                </td>
                                <td style="text-align:center">
                                    <?php echo $s['nmrombel']; ?>
                                </td>
                                <td style="text-align:center">
                                    <?php echo $s['passwd']; ?>
                                </td>
                                <td style="text-align:center">
                                    <?php echo $s['nmruang']; ?>
                                </td>
                                <td style="text-align:center">
                                    <?php echo $ds['sesi'] ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            $('#tb_peserta').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
        })
    </script>
<?php endif ?>