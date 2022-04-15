<?php
require_once "../assets/library/PHPExcel.php";
require_once "../assets/library/excel_reader.php";
if ($level == '1') :
    if (isset($_POST['import'])) {
        if (empty($_FILES['tmprombel']['tmp_name'])) {
            echo "<script>
                        $(function() {
                            toastr.error('File Template Peserta Ujian Kosong!','Mohon Maaf!',{
                                timeOut:1000,
                                fadeOut:1000
                            });
                        });
                    </script>";
        } else {
            $data = new Spreadsheet_Excel_Reader($_FILES['tmprombel']['tmp_name']);
            $baris = $data->rowcount($sheet_index = 0);
            $isidata = $baris - 4;
            $sukses = 0;
            $gagal = 0;
            $update = 0;
            for ($i = 5; $i <= $baris; $i++) {
                $xnis = $data->val($i, 2);
                $xnisn = $data->val($i, 3);
                $xnama = $data->val($i, 4);
                $xrombel = $data->val($i, 5);
                $xidthpel = $data->val($i, 6);

                if ($xnis == '') {
                    echo "<script>
							$(function() {
								toastr.error('Cek Kolom NIS a.n " . $xnama . "','Mohon Maaf!',{
									timeOut:10000,
									fadeOut:10000
								});
							});
						</script>";
                } else if (strlen($xnisn) <> 10 || $xnisn == '') {
                    echo "<script>
							$(function() {
								toastr.error('Cek Kolom NISN a.n " . $xnama . "','Mohon Maaf!',{
									timeOut:10000,
									fadeOut:10000
								});
							});
						</script>";
                } else if ($xrombel == '') {
                    echo "<script>
                        $(function() {
                            toastr.error('Cek Kolom Rombel a.n " . $xnama . "','Mohon Maaf!',{
                                timeOut:10000,
                                fadeOut:10000
                            });
                        });
                    </script>";
                } else {
                    $idsiswa = getidsiswa($xnis, $xnisn);
                    $keyR = array(
                        'idrombel' => $xrombel,
                        'idthpel' => $xidthpel
                    );
                    $cekrmb = cekdata('tbrombel', $keyR);
                    if ($cekrmb == 0) {
                        echo "<script>
                            $(function() {
                                toastr.error('Rombel Belum Terdaftar!','Mohon Maaf!',{
                                    timeOut:10000,
                                    fadeOut:10000
                                });
                            });
                        </script>";
                        $gagal++;
                    } else {
                        $keys = array(
                            'nis' => $xnis,
                            'nisn' => $xnisn
                        );
                        $ceksiswa = cekdata('tbpeserta', $keys);
                        if ($ceksiswa == 0) {
                            echo "<script>
                                $(function() {
                                    toastr.error('Peserta Didik Belum Terdaftar!','Mohon Maaf!',{
                                        timeOut:10000,
                                        fadeOut:10000
                                    });
                                });
                            </script>";
                            $gagal++;
                        } else {
                            $qrs = "SELECT rs.* FROM tbrombelsiswa rs INNER JOIN tbpeserta s USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t USING(idthpel) WHERE s.nis='$xnis' AND s.nisn='$xnisn' AND t.aktif='1'";

                            $cekrs = cquery($qrs);
                            if ($cekrs == 0) {
                                $datane = array(
                                    'idsiswa' => $idsiswa,
                                    'idrombel' => $xrombel
                                );
                                if (adddata('tbrombelsiswa', $datane) > 0) {
                                    $sukses++;
                                } else {
                                    $gagal++;
                                }
                            } else {
                                $keyn = array(
                                    'idsiswa' => $idsiswa
                                );
                                $datane = array('idrombel' => $xrombel);
                                $join = array(
                                    'tbrombel' => 'idrombel',
                                    'tbthpel' => 'idthpel'
                                );

                                $field = array(
                                    'idthpel' => $xidthpel
                                );
                                if (editdata('tbrombelsiswa', $datane, $join, $field) > 0) {
                                    $update++;
                                }
                            }
                        }
                    }
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
?>
    <script type="text/javascript" src="js/salinrombel.js"></script>
    <div class="modal fade" id="myImportRombel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Rombel Peserta Didik</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-sm-12">
                            <div class="row">
                                <label for="tmprombel">Pilih File Template</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="tmprombel" name="tmprombel">
                                    <label class="custom-file-label" for="tmprombel">Pilih file</label>
                                </div>
                                <p style="color:red;margin-top:10px"><em>Hanya mendukung file *.xls (Microsoft Excel 97-2003)</em></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <a href="rombel_template.php" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-download"></i> Download</a>
                        <button type="submit" class="btn btn-primary btn-sm" name="import">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                        <a href="#" type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                            <i class="fas fa-power-off"></i> Tutup
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myAddRombel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judule">Atur Rombel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="form-group row mb-2">
                            <label class="col-sm-5">Nama Peserta Didik</label>
                            <div class="col-sm-6">
                                <input type="hidden" class="form-control form-control-sm" id="idsiswa" name="idsiswa">
                                <input type="text" readonly="true" class="form-control form-control-sm" id="nmsiswa" name="nmsiswa">
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-sm-5">Kelas</label>
                            <div class="col-sm-6">
                                <select class="form-control form-control-sm" id="kdkelas" name="kdkelas" onchange="pilrombel(this.value)">
                                    <option value="">..Pilih..</option>
                                    <?php
                                    $sqkls = "SELECT idkelas,nmkelas FROM tbkelas INNER JOIN tbskul USING (idjenjang)";
                                    $qkls = vquery($sqkls);
                                    foreach ($qkls as $kl) :
                                    ?>
                                        <option value="<?php echo $kl['idkelas']; ?>"><?php echo $kl['nmkelas']; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-sm-5">Rombongan Belajar</label>
                            <div class="col-sm-6">
                                <select class="form-control form-control-sm" id="kdrombel" name="kdrombel">
                                    <option value="">..Pilih..</option>
                                    <?php
                                    $qrmb = "SELECT*FROM tbrombel r INNER JOIN tbthpel t ON r.idthpel=t.idthpel WHERE t.aktif='1'";
                                    $rmb = vquery($qrmb);
                                    foreach ($rmb as $rm) :
                                    ?>
                                        <option value="<?php echo $rm['idrombel']; ?>"><?php echo $rm['nmrombel']; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-primary btn-sm col-4" id="btnSimpan">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-danger btn-sm col-4" data-dismiss="modal">
                        <i class="fas fa-power-off"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mySalinRombel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Salin Data Anggota Rombel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="form-group row mb-2">
                            <label class="col-sm-5">Kelas</label>
                            <div class="col-sm-6">
                                <select class="form-control form-control-sm" id="klsasal" name="klsasal" onchange="pilkelas(this.value)">
                                    <option value="">..Pilih..</option>
                                    <?php
                                    $qkls = $conn->query("SELECT idkelas,nmkelas FROM tbkelas INNER JOIN tbskul USING (idjenjang)");
                                    while ($kl = $qkls->fetch_array()) {
                                    ?>
                                        <option value="<?php echo $kl['idkelas']; ?>"><?php echo $kl['nmkelas']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-sm-5">Rombel Asal</label>
                            <div class="col-sm-6">
                                <select class="form-control form-control-sm" id="rombelasl" name="rombelasl" onchange="pilrombelasl(this.value)">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-sm-5">Rombel Tujuan</label>
                            <div class="col-sm-6">
                                <select class="form-control form-control-sm" id="rombelnew" name="rombelnew">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-primary btn-sm col-4" id="btnSalin">
                        <i class="far fa-copy"></i> Salin
                    </button>
                    <button type="button" class="btn btn-danger btn-sm col-4" data-dismiss="modal">
                        <i class="fas fa-power-off"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h4 class="card-title">Data Pembagian Rombel <?php echo $tapel; ?></h4>
                <div class="card-tools">
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myImportRombel">
                        <i class="fas fa-cloud-upload-alt"></i>&nbsp;Import
                    </button>
                    <!-- <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#mySalinRombel">
                        <i class="far fa-copy"></i>&nbsp;Salin
                    </button> -->
                    <button id="btnrefresh" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash-alt"></i>&nbsp;Refresh
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tb_rombel" class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th style="text-align: center;width:2.5%">No.</th>
                                <th style="text-align: center;">Nama Peserta Didik</th>
                                <th style="text-align: center;width:20%">Nomor Induk</th>
                                <th style="text-align: center;width:20%">Rombel</th>
                                <th style="text-align: center;width:20%">Set Rombel</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sqrs = "SELECT idsiswa, nmsiswa, nis, nisn, r.nmrombel FROM tbpeserta p LEFT JOIN tbrombelsiswa rs USING(idsiswa) LEFT JOIN tbrombel r  USING(idrombel) LEFT JOIN tbthpel t USING(idthpel) WHERE deleted='0'";
                            $no = 0;
                            $qs = vquery($sqrs);
                            foreach ($qs as $s) :
                                $no++;
                            ?>
                                <tr>
                                    <td style="text-align:center"><?php echo $no . '.'; ?></td>
                                    <td title="<?php echo $s['idsiswa']; ?>"><?php echo ucwords(strtolower($s['nmsiswa'])); ?>
                                    </td>
                                    <td style="text-align:center"><?php echo $s['nis'] . ' / ' . $s['nisn']; ?></td>
                                    <td style="text-align:center"><?php echo $s['nmrombel']; ?></td>
                                    <td style="text-align:center">
                                        <a href="#myAddRombel" data-toggle="modal" data-id="<?php echo $s['idsiswa']; ?>" class="btn btn-xs btn-warning btnUpdate col-6">
                                            <i class="fas fa-cogs"></i>&nbsp;Set Rombel
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="js/pilihrombel.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#myAddRombel").on('hidden.bs.modal', function() {
                window.location.reload();
            })

            $("#btnSimpan").click(function() {
                let idkelas = $("#kdkelas").val();
                let idrombel = $("#kdrombel").val();
                if (idkelas == '') {
                    toastr.error("Pilihan Kelas Tidak Boleh Kosong", "Maaf!");
                } else if (idrombel == '') {
                    toastr.error("Rombel Tidak Boleh Kosong", "Maaf!");
                } else {
                    data = new FormData();
                    data.append('ids', $("#idsiswa").val());
                    data.append('idr', idrombel);
                    data.append('aksi', 'simpan');
                    $.ajax({
                        url: "rombel_simpan.php",
                        type: 'POST',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 8000,
                        success: function(respons) {
                            if (respons == 1) {
                                $(function() {
                                    toastr.success('Simpan Anggota Rombel Berhasil!!', 'Terima Kasih', {
                                        timeOut: 3000,
                                        fadeOut: 3000,
                                        onHidden: function() {
                                            $("#myAddRombel").modal('hide');
                                        }
                                    });
                                });
                            }
                            if (respons == 2) {
                                $(function() {
                                    toastr.info('Update Anggota Rombel Berhasil!!', 'Informasi', {
                                        timeOut: 3000,
                                        fadeOut: 3000,
                                        onHidden: function() {
                                            $("#myAddRombel").modal('hide');
                                        }
                                    });
                                });
                            }
                            if (respons == 0) {
                                $(function() {
                                    toastr.error('Gagal Update atau Simpan Data!!', 'Mohon Maaf', {
                                        timeOut: 3000,
                                        fadeOut: 3000,
                                        onHidden: function() {
                                            $("#myAddRombel").modal('hide');
                                        }
                                    });
                                });
                            }
                        }
                    })
                }
            })
            $(".btnUpdate").click(function() {
                let id = $(this).data('id');
                $.ajax({
                    url: 'rombel_edit.php',
                    type: 'post',
                    dataType: 'json',
                    data: 'id=' + id,
                    success: function(data) {
                        $("#idsiswa").val(data.idsiswa);
                        $("#nmsiswa").val(data.nmsiswa);
                        $("#kdkelas").val(data.kelas);
                        $("#kdrombel").val(data.rombel);
                        $("#judule").html(data.judul);
                        $("#simpan").html(data.tmb);
                    }
                })
            })
            $("#btnSalin").click(function() {
                let rombelasl = $("#rombelasl").val();
                let rombelnew = $("#rombelnew").val();
                $.ajax({
                    url: "rombel_simpan.php",
                    type: 'POST',
                    data: "aksi=2&ra=" + rombelasl + "&rb=" + rombelnew,
                    success: function(data) {
                        toastr.success(data);
                    }
                })
            })
            $("#btnrefresh").click(function() {
                window.location.reload();
            })
        });
    </script>
<?php else : ?>
    <div class="col-sm-12">
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h4 class="card-title">Data Pembagian Rombel <?php echo $tapel; ?></h4>
            </div>
            <div class="card-body">
                <div class="row">
                </div>
                <br />
                <div class="table-responsive">
                    <table id="tb_rombel" class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th style="text-align: center;width:2.5%">No.</th>
                                <th style="text-align: center;">Nama Peserta Didik</th>
                                <th style="text-align: center;width:17.5%">Nomor Induk</th>
                                <th style="text-align: center;width:10%">Rombel</th>
                                <th style="text-align: center;width:35%">Mata Pelajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $qs = $conn->query("SELECT s.nmsiswa, s.nis, s.nisn, nmmapel, nmrombel FROM tbsiswa s LEFT JOIN tbrombelsiswa rs USING(idsiswa) LEFT JOIN tbrombel r USING(idrombel) LEFT JOIN tbpengampu p USING(idrombel) INNER JOIN tbmapel m USING(idmapel) WHERE p.username='$_COOKIE[c_user]'");
                            $no = 0;
                            while ($s = $qs->fetch_array()) {
                                $no++;
                            ?>
                                <tr>
                                    <td style="text-align:center"><?php echo $no . '.'; ?></td>
                                    <td title="<?php echo $s['idsiswa']; ?>"><?php echo ucwords(strtolower($s['nmsiswa'])); ?>
                                    </td>
                                    <td style="text-align:center"><?php echo $s['nis'] . ' / ' . $s['nisn']; ?></td>
                                    <td style="text-align:center"><?php echo $s['nmrombel']; ?></td>
                                    <td><?php echo $s['nmmapel']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>
<script type="text/javascript">
    $(function() {
        $('#tb_rombel').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
        });
    })
</script>