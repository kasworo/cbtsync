<?php
$ds = viewdata('tbsoal', array('idbutir' => $_GET['idb']))[0];
$idbutir = $_GET['idb'];
$nomor = $ds['nomersoal'];
$idstm = $ds['idstimulus'];
$jnssoal = $ds['jnssoal'];
$modeopsi = $ds['modeopsi'];
$tksoal = $ds['tksukar'];
$skormaks = $ds['skormaks'];
$butirsoal = $ds['butirsoal'];

$bnk = viewdata('tbstimulus', array('idstimulus' => $idstm))[0];
$idbank = $bnk['idbank'];
?>
<script type="text/javascript" src="../assets/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        plugins: ["formula advlist lists charmap anchor", "code fullscreen", "table contextmenu paste jbimages"],
        toolbar: "undo redo | bold italic underline subscript superscript | alignleft aligncenter alignright alignjustify | bullist numlist | jbimages table formula code",
        menubar: false,
        relative_urls: false,
        forced_root_block: "",
        force_br_newlines: true,
        force_p_newlines: false,
    });
</script>
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#savesoal").click(function() {
            let js = $("#kategori").val();
            let mo = $("#modeopsi").val();
            let tk = $("#kesulitan").val();
            let sk = $("#skormaks").val();
            let bt = tinymce.get("tanyasoal").getContent();
            if (js == '') {
                toastr.error('Pilih Jenis Soal!!', 'Mohon Maaf!');
            } else if (mo == '') {
                toastr.error('Pilih Mode Opsi!!', 'Mohon Maaf!');
            } else if (sk == '') {
                toastr.error('Pilih Tingkat Kesukaran!!', 'Mohon Maaf!');
            } else if (sk == '') {
                toastr.error('Skor Maksimum Tidak Boleh Kosong!!', 'Mohon Maaf!');
            } else {
                let data = new FormData();
                data.append('ids', "<?php echo $idstm; ?>");
                data.append('js', js);
                data.append('mo', mo);
                data.append('tk', tk);
                data.append('sk', sk);
                data.append('bt', bt);
                data.append('nm', "<?php echo $nomor; ?>");
                data.append('aksi', 'simpan');
                $.ajax({
                    url: "isisoal_simpan.php",
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 8000,
                    success: function(respons) {
                        if (respons == 1) {
                            $(function() {
                                toastr.success('Butir Soal Berhasil Ditambah!!', 'Terima Kasih', {
                                    timeOut: 3000,
                                    fadeOut: 3000,
                                    onHidden: function() {
                                        let ids = "<?php echo $idstm; ?>";
                                        let nm = "<?php echo $nomor; ?>";
                                        $.ajax({
                                            url: 'isisoal_editbutir.php',
                                            type: 'post',
                                            dataType: 'json',
                                            data: 'ids=' + ids + '&nm=' + nm,
                                            success: function(e) {
                                                window.location.href = "index.php?p=tambahsoal&idb=" + e.idbutir
                                            }
                                        })
                                    }
                                });
                            });
                        }
                        if (respons == 2) {
                            $(function() {
                                toastr.info('Butir Soal Berhasil Diupdate!!', 'Informasi', {
                                    timeOut: 3000,
                                    fadeOut: 3000,
                                    onHidden: function() {
                                        window.location.reload();
                                    }
                                });
                            });
                        }
                        if (respons == 0) {
                            toastr.error("Tambah Atau Update Butir Soal Gagal!", "Mohon Maaf");
                        }
                    }
                })
            }
        })
    })
</script>
<div class="callout callout-danger">
    <label>Petunjuk:</label>
    <ol>
        <li class="text-sm">Lengkapi pilihan jenis soal hingga skor maksimum sebelum butir soal diketikkan.</li>
        <li class="text-sm">Isikan opsi jawaban, tersedia maksimum 6 opsi jawaban.</li>
        <li class="text-sm">File Pendukung pada opsi jawaban harus berekstensi *.jpg, *.jpeg, *.png, atau *.gif.</li>
    </ol>
</div>
<div class="card card-danger card-outline" id="butirsoal">
    <div class="card-header">
        <h3 class="card-title" id="judul">Update Butir Soal</h3>
        <div class="card-tools">
            <a href="index.php?p=isisoal&id=<?php echo $idbank; ?>" class="btn btn-default btn-sm ml-1 mb-2">
                <i class="fas fa-arrow-circle-left"></i> Kembali
            </a>
            <button class="btn btn-success btn-sm ml-1 mb-2" id="savesoal" name="savesoal">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group row mb-2">
            <div class="col-sm-6">
                <div class="form-group row mb-2">
                    <div class="col-sm-4">
                        <label>Jenis Soal</label>
                    </div>
                    <div class="col-sm-6">
                        <select id="kategori" name="kategori" class="form-control form-control-sm">
                            <option value="">..Pilih..</option>
                            <option value="1" <?php echo ($jnssoal == '1') ? 'selected' : ''; ?>>Pilihan Ganda Biasa</option>
                            <option value="2" <?php echo ($jnssoal == '2') ? 'selected' : ''; ?>>Pilihan Ganda Kompleks</option>
                            <option value="3" <?php echo ($jnssoal == '3') ? 'selected' : ''; ?>>Benar / Salah</option>
                            <option value="4" <?php echo ($jnssoal == '4') ? 'selected' : ''; ?>>Menjodohkan</option>
                            <option value="5" <?php echo ($jnssoal == '5') ? 'selected' : ''; ?>>Isian Singkat</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <div class="col-sm-4">
                        <label>Mode Opsi</label>
                    </div>
                    <div class="col-sm-6">
                        <select id="modeopsi" name="modeopsi" class="form-control form-control-sm">
                            <option value="">..Pilih..</option>
                            <option value="1" <?php echo ($modeopsi == '1') ? 'selected' : ''; ?>>Tanpa Kolom</option>
                            <option value="2" <?php echo ($modeopsi == '2') ? 'selected' : ''; ?>>Tabel 2 Kolom</option>
                            <option value="3" <?php echo ($modeopsi == '3') ? 'selected' : ''; ?>>Tabel 3 Kolom</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group row mb-2">
                    <div class="col-sm-4">
                        <label>Tingkat Kesulitan</label>
                    </div>
                    <div class="col-sm-6">
                        <select id="kesulitan" name="kesulitan" class="form-control form-control-sm">
                            <option value="">..Pilih..</option>
                            <option value="1" <?php echo ($tksoal == '1') ? 'selected' : ''; ?>>Mudah</option>
                            <option value="2" <?php echo ($tksoal == '2') ? 'selected' : ''; ?>>Sedang</option>
                            <option value="3" <?php echo ($tksoal == '3') ? 'selected' : ''; ?>>Sulit</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <div class="col-sm-4">
                        <label>Skor Maksimum</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control form-control-sm" id="skormaks" name="skormaks" value="<?php echo $skormaks; ?>">
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div class="form-group row mb-2">
            <div class="col-sm-12">
                <label>Butir Soal</label>
            </div>
        </div>
        <div class="form-group row mb-2">
            <div class="col-sm-12">
                <textarea class="form-control form-control-sm" name="tanyasoal" id="tanyasoal" style="font-size:14pt; width:100%; height:100px;padding:5px"><?php echo $butirsoal; ?></textarea>
            </div>
        </div>
    </div>
</div>
<?php if (isset($_GET['idb'])) :
    /* Pilihan Ganda Biasa Dan Kompleks */
    if ($jnssoal == '1' || $jnssoal == '2') :
?>
        <div class="card card-danger card-outline" id="opsijawab">
            <div class="card-header">
                <h5 class="card-title">Tambahkan Opsi Jawaban</h5>
                <div class="card-tools">
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle"></i>&nbsp;Tambah
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php
                $qopsi = viewdata('tbopsi', array('idbutir' => $_GET['idb']));
                $i = 0;
                foreach ($qopsi as $ops) :
                    $i++;
                ?>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group mb-2">
                                <span>Opsi Jawaban <?php echo $i ?></span>
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <textarea class="form-control form-control-sm" name="opsijawaban" id="opsijawaban<?php echo $i; ?>" style="font-size:12pt;height:100px;padding:5px"><?php echo $ops['opsi'] ?></textarea>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group mb-2 ml-4">
                                <span>Jawaban Benar</span>
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <select class="form-control form-control-sm col-6" name="opsibenar" id="opsibenar<?php echo $i; ?>">
                                    <option value="">..Pilih..</option>
                                    <option value="1" <?php echo ($ops['benar'] == '1') ? "selected" : ""; ?>>Ya</option>
                                    <option value="0" <?php echo ($ops['benar'] == '0') ? "selected" : ""; ?>>Tidak</option>
                                </select>
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <span>File Pendukung</span>
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <img src="../assets/img/nofile.png" class="img img-rounded img-fluid img-bordered-sm" width="80px">
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <input type="file" name="opsifile" id="opsifile<?php echo $i; ?>">
                            </div>
                            <hr />
                            <div class="form-group mb-2 ml-4">
                                <button class="btn btn-info btn-xs" id="saveopsi<?php echo $i; ?>">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <button class="btn btn-danger btn-xs" id="delopsi<?php echo $i; ?>">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <script type="text/javascript">
                        $("#saveopsi<?php echo $i; ?>").click(function(e) {
                            e.preventDefault();
                            let benar = $("#opsibenar<?php echo $i; ?>").val();
                            if (benar == '') {
                                $(function() {
                                    toastr.danger('Pilihan Jawaban Benar Belum Diisi!!', 'Mohon Maaf', {
                                        timeOut: 3000,
                                        fadeOut: 3000,
                                        onHidden: function() {
                                            $("#opsibenar<?php echo $i; ?>").focus();
                                        }
                                    });
                                });

                            } else {
                                let data = new FormData();
                                data.append('idops', "<?php echo $ops['idopsi']; ?>");
                                data.append('idbutir', "<?php echo $idbutir; ?>");
                                data.append('benar', benar);
                                data.append('opsi', tinymce.get("opsijawaban<?php echo $i; ?>").getContent());
                                data.append('file', $("#opsifile<?php echo $i; ?>")[0].files[0]);
                                data.append('aksi', 'opsi');
                                $.ajax({
                                    url: "isisoal_simpan.php",
                                    type: 'POST',
                                    enctype: 'multipart/form-data',
                                    data: data,
                                    processData: false,
                                    contentType: false,
                                    cache: false,
                                    timeout: 8000,
                                    success: function(respons) {
                                        if (respons == '1') {
                                            $(function() {
                                                toastr.success('Opsi Jawaban Berhasil Disimpan!!', 'Terima Kasih', {
                                                    timeOut: 3000,
                                                    fadeOut: 3000,
                                                    onHidden: function() {
                                                        window.location.reload();
                                                    }
                                                })
                                            })
                                        }
                                        if (respons == '2') {
                                            $(function() {
                                                toastr.info('Opsi Jawaban Berhasil Diupdate!!', 'Informasi', {
                                                    timeOut: 3000,
                                                    fadeOut: 3000,
                                                    onHidden: function() {
                                                        window.location.reload();
                                                    }
                                                })
                                            })
                                        }
                                        if (respons == '0') {
                                            $(function() {
                                                toastr.error('Opsi Jawaban Gagal Disimpan Atau Diudpate!', 'Mohon Maaf', {
                                                    timeOut: 3000,
                                                    fadeOut: 3000,
                                                    onHidden: function() {
                                                        window.location.reload();
                                                    }
                                                })
                                            })
                                        }
                                    }
                                })
                            }
                        })
                        $("#delopsi<?php echo $i; ?>").click(function(e) {
                            e.preventDefault();
                            Swal.fire({
                                title: 'Peringatan!',
                                text: "Yakin Menghapus Opsi Jawaban Ini?",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Hapus',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.value) {
                                    let data = new FormData();
                                    data.append('idops', "<?php echo $ops['idopsi']; ?>");
                                    data.append('aksi', 'delopsi');
                                    $.ajax({
                                        url: "isisoal_simpan.php",
                                        type: 'POST',
                                        data: data,
                                        processData: false,
                                        contentType: false,
                                        cache: false,
                                        timeout: 8000,
                                        success: function(respons) {
                                            if (respons == '1') {
                                                $(function() {
                                                    toastr.success('Opsi Jawaban Berhasil Dihapus!!', 'Terima Kasih', {
                                                        timeOut: 3000,
                                                        fadeOut: 3000,
                                                        onHidden: function() {
                                                            window.location.reload();
                                                        }
                                                    })
                                                })
                                            }
                                            if (respons == '0') {
                                                $(function() {
                                                    toastr.error('Opsi Jawaban Gagal Dihapus!', 'Mohon Maaf', {
                                                        timeOut: 3000,
                                                        fadeOut: 3000,
                                                        onHidden: function() {
                                                            window.location.reload();
                                                        }
                                                    })
                                                })
                                            }
                                        }
                                    })
                                }
                            })
                        })
                    </script>
                <?php endforeach ?>
            </div>
        </div>
    <?php endif ?>

    <?php
    /* Benar Atau Salah */
    if ($jnssoal == '3') : ?>
        <div class="card card-danger card-outline" id="opsijawab">
            <div class="card-header">
                <h5 class="card-title">Tambahkan Opsi Jawaban</h5>
            </div>
            <div class="card-body">
                <?php
                $qopsi = viewdata('tbopsi', array('idbutir' => $_GET['idb']));
                $i = 0;
                foreach ($qopsi as $ops) :
                    $i++;
                ?>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group mb-2">
                                <span>Opsi Jawaban <?php echo $i ?></span>
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <textarea class="form-control form-control-sm" name="opsijawaban" id="opsijawaban<?php echo $i; ?>" style="font-size:12pt;height:100px;padding:5px"><?php echo $ops['opsi'] ?></textarea>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group mb-2 ml-4">
                                <span>Jawaban Benar</span>
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <select class="form-control form-control-sm col-6" name="opsibenar" id="opsibenar<?php echo $i; ?>">
                                    <option value="">..Pilih..</option>
                                    <option value="1" <?php echo ($ops['benar'] == '1') ? "selected" : ""; ?>>Ya</option>
                                    <option value="0" <?php echo ($ops['benar'] == '0') ? "selected" : ""; ?>>Tidak</option>
                                </select>
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <span>File Pendukung</span>
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <img src="../assets/img/nofile.png" class="img img-rounded img-fluid img-bordered-sm" width="80px">
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <input type="file" name="opsifile" id="opsifile<?php echo $i; ?>">
                            </div>
                            <hr />
                            <div class="form-group mb-2 ml-4">
                                <button class="btn btn-info btn-xs" id="saveopsi<?php echo $i; ?>">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <button class="btn btn-danger btn-xs" id="delopsi<?php echo $i; ?>">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <script type="text/javascript">
                        $("#saveopsi<?php echo $i; ?>").click(function(e) {
                            e.preventDefault();
                            let benar = $("#opsibenar<?php echo $i; ?>").val();
                            if (benar == '') {
                                $(function() {
                                    toastr.danger('Pilihan Jawaban Benar Belum Diisi!!', 'Mohon Maaf', {
                                        timeOut: 3000,
                                        fadeOut: 3000,
                                        onHidden: function() {
                                            $("#opsibenar<?php echo $i; ?>").focus();
                                        }
                                    });
                                });

                            } else {
                                let data = new FormData();
                                data.append('idops', "<?php echo $ops['idopsi']; ?>");
                                data.append('idbutir', "<?php echo $idbutir; ?>");
                                data.append('benar', benar);
                                data.append('opsi', tinymce.get("opsijawaban<?php echo $i; ?>").getContent());
                                data.append('file', $("#opsifile<?php echo $i; ?>")[0].files[0]);
                                data.append('aksi', 'opsi');
                                $.ajax({
                                    url: "isisoal_simpan.php",
                                    type: 'POST',
                                    enctype: 'multipart/form-data',
                                    data: data,
                                    processData: false,
                                    contentType: false,
                                    cache: false,
                                    timeout: 8000,
                                    success: function(respons) {
                                        if (respons == '1') {
                                            $(function() {
                                                toastr.success('Opsi Jawaban Berhasil Disimpan!!', 'Terima Kasih', {
                                                    timeOut: 3000,
                                                    fadeOut: 3000,
                                                    onHidden: function() {
                                                        window.location.reload();
                                                    }
                                                })
                                            })
                                        }
                                        if (respons == '2') {
                                            $(function() {
                                                toastr.info('Opsi Jawaban Berhasil Diupdate!!', 'Informasi', {
                                                    timeOut: 3000,
                                                    fadeOut: 3000,
                                                    onHidden: function() {
                                                        window.location.reload();
                                                    }
                                                })
                                            })
                                        }
                                        if (respons == '0') {
                                            $(function() {
                                                toastr.error('Opsi Jawaban Gagal Disimpan Atau Diudpate!', 'Mohon Maaf', {
                                                    timeOut: 3000,
                                                    fadeOut: 3000,
                                                    onHidden: function() {
                                                        window.location.reload();
                                                    }
                                                })
                                            })
                                        }
                                    }
                                })
                            }
                        })
                        $("#delopsi<?php echo $i; ?>").click(function(e) {
                            e.preventDefault();
                            Swal.fire({
                                title: 'Peringatan!',
                                text: "Yakin Menghapus Opsi Jawaban Ini?",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Hapus',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.value) {
                                    let data = new FormData();
                                    data.append('idops', "<?php echo $ops['idopsi']; ?>");
                                    data.append('aksi', 'delopsi');
                                    $.ajax({
                                        url: "isisoal_simpan.php",
                                        type: 'POST',
                                        data: data,
                                        processData: false,
                                        contentType: false,
                                        cache: false,
                                        timeout: 8000,
                                        success: function(respons) {
                                            if (respons == '1') {
                                                $(function() {
                                                    toastr.success('Opsi Jawaban Berhasil Dihapus!!', 'Terima Kasih', {
                                                        timeOut: 3000,
                                                        fadeOut: 3000,
                                                        onHidden: function() {
                                                            window.location.reload();
                                                        }
                                                    })
                                                })
                                            }
                                            if (respons == '0') {
                                                $(function() {
                                                    toastr.error('Opsi Jawaban Gagal Dihapus!', 'Mohon Maaf', {
                                                        timeOut: 3000,
                                                        fadeOut: 3000,
                                                        onHidden: function() {
                                                            window.location.reload();
                                                        }
                                                    })
                                                })
                                            }
                                        }
                                    })
                                }
                            })
                        })
                    </script>
                <?php endforeach ?>
            </div>
        </div>
    <?php endif ?>
    <?php /* Menjodohkan (Matching) */
    if ($jnssoal == '4') : ?>
        <div class="card card-danger card-outline" id="opsijawab">
            <div class="card-header">
                <h5 class="card-title">Tambahkan Opsi Jawaban</h5>
            </div>
            <div class="card-body">
                <?php
                $qopsi = viewdata('tbopsi', array('idbutir' => $_GET['idb']));
                $i = 0;
                foreach ($qopsi as $ops) :
                    $i++;
                ?>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group mb-2">
                                <span>Pernyataan <?php echo $i ?></span>
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <textarea class="form-control form-control-sm" name="opsijawaban" id="opsijawaban<?php echo $i; ?>" style="font-size:12pt;height:200px;padding:5px"><?php echo $ops['opsi'] ?></textarea>
                            </div>
                            <hr />
                            <div class="form-group mb-2">
                                <span>Jawaban<?php echo $i ?></span>
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <textarea class="form-control form-control-sm" name="opsialt" id="opsialt<?php echo $i; ?>" style="font-size:12pt;height:200px;padding:5px"><?php echo $ops['opsi'] ?></textarea>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group mb-2 ml-4">
                                <span>Jawaban Benar <?php echo ($ops['benar'] == '1') ? "selected" : ""; ?></span>
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <select class="form-control form-control-sm col-6" name="opsibenar" id="opsibenar<?php echo $i; ?>">
                                    <option value="">..Pilih..</option>
                                    <option value="1" <?php echo ($ops['benar'] == '1') ? "selected" : ""; ?>>Ya</option>
                                    <option value="0" <?php echo ($ops['benar'] == '0') ? "selected" : ""; ?>>Tidak</option>
                                </select>
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <span>File Pendukung</span>
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <img src="../assets/img/nofile.png" class="img img-rounded img-fluid img-bordered-sm" width="150px">
                            </div>
                            <div class="form-group mb-2 ml-4">
                                <input type="file" name="opsifile" id="opsifile<?php echo $i; ?>">
                            </div>
                            <hr />
                            <div class="form-group mb-2 ml-4">
                                <button class="btn btn-info btn-xs" id="saveopsi<?php echo $i; ?>">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <button class="btn btn-danger btn-xs" id="delopsi<?php echo $i; ?>">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <script type="text/javascript">
                        $("#saveopsi<?php echo $i; ?>").click(function(e) {
                            e.preventDefault();
                            let benar = $("#opsibenar<?php echo $i; ?>").val();
                            if (benar == '') {
                                $(function() {
                                    toastr.danger('Pilihan Jawaban Benar Belum Diisi!!', 'Mohon Maaf', {
                                        timeOut: 3000,
                                        fadeOut: 3000,
                                        onHidden: function() {
                                            $("#opsibenar<?php echo $i; ?>").focus();
                                        }
                                    });
                                });

                            } else {
                                let data = new FormData();
                                data.append('idops', "<?php echo $ops['idopsi']; ?>");
                                data.append('idbutir', "<?php echo $idbutir; ?>");
                                data.append('benar', benar);
                                data.append('opsi', tinymce.get("opsijawaban<?php echo $i; ?>").getContent());
                                data.append('opsia', tinymce.get("opsialt<?php echo $i; ?>").getContent());
                                data.append('file', $("#opsifile<?php echo $i; ?>")[0].files[0]);
                                data.append('aksi', 'opsi');
                                $.ajax({
                                    url: "isisoal_simpan.php",
                                    type: 'POST',
                                    enctype: 'multipart/form-data',
                                    data: data,
                                    processData: false,
                                    contentType: false,
                                    cache: false,
                                    timeout: 8000,
                                    success: function(respons) {
                                        if (respons == '1') {
                                            $(function() {
                                                toastr.success('Opsi Jawaban Berhasil Disimpan!!', 'Terima Kasih', {
                                                    timeOut: 3000,
                                                    fadeOut: 3000,
                                                    onHidden: function() {
                                                        window.location.reload();
                                                    }
                                                })
                                            })
                                        }
                                        if (respons == '2') {
                                            $(function() {
                                                toastr.info('Opsi Jawaban Berhasil Diupdate!!', 'Informasi', {
                                                    timeOut: 3000,
                                                    fadeOut: 3000,
                                                    onHidden: function() {
                                                        window.location.reload();
                                                    }
                                                })
                                            })
                                        }
                                        if (respons == '0') {
                                            $(function() {
                                                toastr.error('Opsi Jawaban Gagal Disimpan Atau Diudpate!', 'Mohon Maaf', {
                                                    timeOut: 3000,
                                                    fadeOut: 3000,
                                                    onHidden: function() {
                                                        window.location.reload();
                                                    }
                                                })
                                            })
                                        }
                                    }
                                })
                            }
                        })
                        $("#delopsi<?php echo $i; ?>").click(function(e) {
                            e.preventDefault();
                            Swal.fire({
                                title: 'Peringatan!',
                                text: "Yakin Menghapus Opsi Jawaban Ini?",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Hapus',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.value) {
                                    let data = new FormData();
                                    data.append('idops', "<?php echo $ops['idopsi']; ?>");
                                    data.append('aksi', 'delopsi');
                                    $.ajax({
                                        url: "isisoal_simpan.php",
                                        type: 'POST',
                                        data: data,
                                        processData: false,
                                        contentType: false,
                                        cache: false,
                                        timeout: 8000,
                                        success: function(respons) {
                                            if (respons == '1') {
                                                $(function() {
                                                    toastr.success('Opsi Jawaban Berhasil Dihapus!!', 'Terima Kasih', {
                                                        timeOut: 3000,
                                                        fadeOut: 3000,
                                                        onHidden: function() {
                                                            window.location.reload();
                                                        }
                                                    })
                                                })
                                            }
                                            if (respons == '0') {
                                                $(function() {
                                                    toastr.error('Opsi Jawaban Gagal Dihapus!', 'Mohon Maaf', {
                                                        timeOut: 3000,
                                                        fadeOut: 3000,
                                                        onHidden: function() {
                                                            window.location.reload();
                                                        }
                                                    })
                                                })
                                            }
                                        }
                                    })
                                }
                            })
                        })
                    </script>
                <?php endforeach ?>
            </div>
        </div>
    <?php endif ?>
<?php endif ?>