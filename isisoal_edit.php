<?php
	if(!isset($_COOKIE['c_user'])){header("Location: login.php");}
		$qbank=$conn->query("SELECT so.*, bs.nmbank, bs.idkelas FROM tbsoal so INNER JOIN tbbanksoal bs ON bs.idbank=so.idbank WHERE so.idbank='$_REQUEST[id]' AND so.nomersoal='$_REQUEST[nm]'");
		$b=$qbank->fetch_array();
		$idbank=$b['idbank'];
		$nmbank=$b['nmbank'];
		$kelas=$b['idkelas'];
		$idsoal=$b['idbutir'];
		$maks=$b['nomersoal'];
		$butirsoal=$b['butirsoal'];
		$jnssoal=$b['jnssoal'];
		$tksoal=$b['tksukar'];
		$modeopsi=$b['modeopsi'];
		$skormaks=$b['skormaks'];
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

<div class="modal fade" id="myAddOpsi" aria-modal="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Opsi Jawaban</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <?php if($modeopsi=='1' || $jnssoal=='4'):
					if($jnssoal=='4'){$judul1='Soal Menjodohkan';$judul2='Opsi Jawaban';}
					else {$judul1='Opsi Jawaban 1';$judul2='Opsi Jawaban 2';}
				?>
                    <div class="col-sm-12">
                        <div class="form-group row mb-2">
                            <label class="col-sm-6">
                                Jawaban Benar
                            </label>
                            <select class="form-control form-control-sm col-sm-6" id="kunci" name="kunci">
                                <option value="">..Pilih..</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                        <hr />
                        <label><?php echo $judul1;?></label>
                        <div class="form-group row mb-2">
                            <textarea class="form-control form-control-sm opsi" name="opsijwb" id="opsijwb"
                                style="font-size:14pt;padding:5px"></textarea>
                        </div>
                        <label><?php echo $judul2;?></label>
                        <div class="form-group row mb-2">
                            <textarea class="form-control form-control-sm opsi" name="opsijwbalt" id="opsijwbalt"
                                style="font-size:14pt; padding:5px"></textarea>
                        </div>
                    </div>

                    <?php else:?>
                    <div class="col-sm-12">
                        <div class="form-group row mb-2">
                            <label class="col-sm-6">
                                Jawaban Benar
                            </label>
                            <select class="form-control form-control-sm col-sm-3" id="kunci" name="kunci">
                                <option value="">..Pilih..</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                        <div class="form-group row mb-2">
                            <textarea class="form-control form-control-sm opsi" name="opsijwb" id="opsijwb"></textarea>
                        </div>
                    </div>
                    <?php endif?>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary btn-sm col-4" id="saveopsi">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-danger btn-sm col-4" data-dismiss="modal">
                    <i class="fas fa-power-off"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="card card-danger card-outline">
        <div class="card-header">
            <h3 class="card-title col-sm-6"><strong>Edit Butir Soal <?php echo $nmbank;?> (Nomor
                    <?php echo $maks;?>)</strong></h3>
            <div class="card-tools col-sm-6">
                <button title="Tambah Opsi Jawaban" class="btn btn-info btn-sm col-2 ml-1 mb-2 float-right" id="addopsi"
                    data-toggle="modal" data-target="#myAddOpsi">
                    <i class="fas fa-plus-circle"></i> Opsi
                </button>
                <button title="Update Butir Soal" type="submit"
                    class="btn btn-success btn-sm col-2 ml-1 mb-2 float-right" id="savesoal">
                    <i class="fas fa-save"></i> Update
                </button>
                <button title="Upload File Pendukung" type="submit"
                    class="btn btn-secondary btn-sm col-2 ml-1 mb-2 float-right" id="upload">
                    <i class="fas fa-upload"></i> Upload
                </button>
                <a href="index.php?p=isisoal&id=<?php echo $idbank;?>"
                    class="btn btn-default btn-sm ml-1 mb-2 float-right">
                    <i class="fas fa-arrow-circle-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="col-sm-12">
                <div class="form-group row mb-2">
                    <div class="col-sm-6">
                        <div class="form-group row mb-2">
                            <div class="col-sm-4">
                                <label>Jenis Soal</label>
                            </div>
                            <div class="col-sm-6">
                                <?php
									switch ($jnssoal){
										case '1':{$j1='selected';$j2='';$j3='';$j4='';$j5='';break;}
										case '2':{$j1='';$j2='selected';$j3='';$j4='';$j5='';break;}
										case '3':{$j1='';$j2='';$j3='selected';$j4='';$j5='';break;}
										case '4':{$j1='';$j2='';$j3='';$j4='selected';$j5='';break;}
										case '5':{$j1='';$j2='';$j3='';$j4='';$j5='selected';break;}
									}
								?>
                                <select id="kategori" name="kategori" class="form-control form-control-sm">
                                    <option value="">..Pilih..</option>
                                    <option value="1" <?php echo $j1;?> title="Pilihan Ganda 1 Jawaban Benar">Pilihan
                                        Ganda Biasa</option>
                                    <option value="2" <?php echo $j2;?> title="Pilihan Ganda > 1 Jawaban Benar">Pilih
                                        Ganda Kompleks</option>
                                    <option value="3" <?php echo $j3;?> title="Benar / Salah">Benar / Salah</option>
                                    <option value="4" <?php echo $j4;?> title="Menjodohkan">Menjodohkan</option>
                                    <option value="5" <?php echo $j5;?> title="Isian Singkat">Isian Singkat</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <div class="col-sm-4">
                                <label>Mode Opsi</label>
                            </div>
                            <div class="col-sm-6">
                                <?php 
									if($modeopsi=='0')
									{$mops0='selected';$mops1='';}
									else {$mops0='';$mops1='selected';}
								?>
                                <select id="modeopsi" name="modeopsi" class="form-control form-control-sm">
                                    <option value="">..Pilih..</option>
                                    <option value="0" <?php echo $mops0;?>>Biasa</option>
                                    <option value="1" <?php echo $mops1;?>>Tabel (2 Kolom)</option>
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
                                <?php
								switch ($tksoal){
									case '1':{$t1='selected';$t2='';$t3='';break;}
									case '2':{$t1='';$t2='selected';$t3='';break;}
									case '3':{$t1='';$t2='';$t3='selected';break;}
										}
								?>
                                <select id="kesulitan" name="kesulitan" class="form-control form-control-sm">
                                    <option value="">..Pilih..</option>
                                    <option value="1" <?php echo $t1;?>>Mudah</option>
                                    <option value="2" <?php echo $t2;?>>Sedang</option>
                                    <option value="3" <?php echo $t3;?>>Sulit</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <div class="col-sm-4">
                                <label>Skor Maksimum</label>
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control form-control-sm" id="skormaks" name="skormaks"
                                    disabled="true" value="<?php echo $skormaks;?>">
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
                        <textarea class="form-control form-control-sm" name="tanyasoal" id="tanyasoal"
                            style="font-size:14pt; width:100%; height:200px;padding:5px"><?php echo $butirsoal;?></textarea>
                    </div>
                </div>
                <hr />
                <?php if($modeopsi=='1'):?>
                <div class="form-group row mb-4">
                    <div class="col-sm-5">
                        Isikan Judul Kolom Tabel Jawaban
                    </div>
                </div>
                <?php
						$qhd=$conn->query("SELECT*FROM tbheaderopsi WHERE idbutir='$idsoal'");
						$h=$qhd->fetch_array();
						$hd1=$h['header1'];
						$hd2=$h['header2'];
					?>
                <div class="form-group row mb-3">
                    <div class="col-sm-5">
                        <input class="form-control form-control-sm mb-2" value="<?php echo $hd1;?>" id="header1">
                    </div>
                    <div class="col-sm-5">
                        <input class="form-control form-control-sm mb2" value="<?php echo $hd2;?>" id="header2">
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-secondary btn-sm btn-block col-sm-12" id="savehd">
                            <i class="fas fa-save"></i>&nbsp;Simpan
                        </button>
                    </div>
                </div>
                <script type="text/javascript">
                $("#savehd").click(function() {
                    var soal = "<?php echo $idsoal;?>";
                    var hd1 = $("#header1").val();
                    var hd2 = $("#header2").val();
                    $.ajax({
                        url: "isisoal_simpan.php",
                        type: 'POST',
                        data: "aksi=7&id=" + soal + "&hd1=" + hd1 + "&hd2=" + hd2,
                        success: function(data) {
                            toastr.success(data);
                        }
                    })
                })
                </script>
                <hr />
                <?php endif?>
                <div id="jawabopsi">
                    <?php
					$qopsi=$conn->query("SELECT*FROM tbopsi WHERE idbutir='$idsoal'");
					while($ops=$qopsi->fetch_array()):
					$i++;
					switch ($jnssoal):
						case '1':
						{
							$pesan='Pilih Ya jika opsi adalah Jawaban Benar';
							break; 
						}
						case '2':
						{
							$pesan='Pilih Ya jika opsi adalah Jawaban Benar';
							break; 
						}
						case '3':
						{
							$pesan='Pilih Ya jika opsi adalah Jawaban Benar';
							break; 
						}
						case '4':
						{
							$pesan='Pilih Ya jika opsi adalah Jawaban Benar';
							break; 
						}
						case '5':
						{
							$pesan='Isikan Skor Maksimum Untuk Opsi ini';
							break; 
						}
					endswitch;
					?>
                    <div class="form-group row mb-2">
                        <div class="col-sm-6">
                            <strong><?php echo $pesan;?></strong>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row mb-2">
                                <?php 
								if($ops['benar']=='1'){$cek='selected';$cek0='';}
								else{$cek='';$cek0='selected';}
							?>
                                <select class="form-control form-control-sm col-3 ml-2 mb-2" id="kunci<?php echo $i;?>"
                                    name="kunci">
                                    <option value="1" <?php echo $cek;?>>Ya</option>
                                    <option value="0" <?php echo $cek0;?>>Tidak</option>
                                </select>
                                <button class="btn btn-secondary btn-sm col-3 ml-2 mb-2" id="uplopsi<?php echo $i;?>">
                                    <i class="fas fa-image"></i>&nbsp;Upload
                                </button>
                                <button class="btn btn-success btn-sm col-2 ml-2 mb-2 mb-2"
                                    id="saveopsi<?php echo $i;?>">
                                    <i class="fas fa-save"></i>&nbsp;Update
                                </button>
                                <button data-id="<?php echo $ops['idopsi'];?>"
                                    class="btn btn-danger btn-sm col-2 ml-2 mb-2 mb-2 btnHpsOpsi">
                                    <i class="fas fa-trash-alt"></i>&nbsp;Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php if($modeopsi=='1' || $jnssoal=='4'):?>
                    <div class="form-group row mb-2">
                        <div class="col-sm-6">
                            <textarea class="form-control form-control-sm opsi" name="opsijwb"
                                id="opsijwb<?php echo $i;?>"
                                style="font-size:14pt; width:100%; height:150px;padding:5px"><?php echo $ops['opsi'];?></textarea>
                        </div>
                        <div class="col-sm-6">
                            <textarea class="form-control form-control-sm opsialt" name="opsijwbalt"
                                id="opsijwbalt<?php echo $i;?>"
                                style="font-size:14pt; width:100%; height:150px;padding:5px"><?php echo $ops['opsialt'];?></textarea>
                        </div>
                    </div>
                    <script type="text/javascript">
                    $("#saveopsi<?php echo $i;?>").click(function() {
                        var soal = "<?php echo $idsoal;?>";
                        var id = "<?php echo $ops['idopsi'];?>";
                        var skor = $("#kunci<?php echo $i?>").val();
                        var ops = tinymce.get("opsijwb<?php echo $i;?>").getContent();
                        var opsalt = tinymce.get("opsijwbalt<?php echo $i;?>").getContent();
                        $.ajax({
                            url: "isisoal_simpan.php",
                            type: 'POST',
                            data: "aksi=2&id=" + id + "&soal=" + soal + "&ops=" + encodeURIComponent(
                                ops) + "&ops2=" + encodeURIComponent(opsalt) + "&skr=" + skor,
                            success: function(data) {
                                toastr.success(data);
                            }
                        })
                    })
                    </script>
                    <hr />
                    <?php else: ?>
                    <div class="form-group row mb-2">
                        <div class="col-sm-12">
                            <textarea class="form-control form-control-sm opsi" name="opsijwb"
                                id="opsijwb<?php echo $i;?>"
                                style="font-size:14pt; width:100%; height:150px;padding:5px"
                                disabled="true"><?php echo $ops['opsi'];?></textarea>
                        </div>
                    </div>
                    <script type="text/javascript">
                    $("#saveopsi<?php echo $i;?>").click(function() {
                        var soal = "<?php echo $idsoal;?>";
                        var id = "<?php echo $ops['idopsi'];?>";
                        var skor = $("#kunci<?php echo $i?>").val();
                        var ops = tinymce.get("opsijwb<?php echo $i;?>").getContent();
                        $.ajax({
                            url: "isisoal_simpan.php",
                            type: 'POST',
                            data: "aksi=2&id=" + id + "&soal=" + soal + "&ops=" + encodeURIComponent(
                                ops) + "&skr=" + skor,
                            success: function(data) {
                                toastr.success(data);
                            }
                        })
                    })
                    </script>
                    <?php endif?>
                    <hr />
                    <?php endwhile ?>
                </div>
            </div>
        </div>
    </div>
    <?php if($modeopsi=='1' || $jnssoal=='4'):?>
    <script type="text/javascript">
    $("#saveopsi").click(function() {
        var soal = "<?php echo $idsoal;?>";
        var skor = $("#kunci").val();
        var ops = tinymce.get("opsijwb").getContent();
        var opsalt = tinymce.get("opsijwbalt").getContent();
        $.ajax({
            url: "isisoal_simpan.php",
            type: 'POST',
            data: "aksi=2&soal=" + soal + "&ops=" + encodeURIComponent(ops) + "&ops2=" +
                encodeURIComponent(opsalt) + "&skr=" + skor,
            success: function(data) {
                toastr.success(data);
            }
        })
    })
    </script>
    <?php else: ?>
    <script type="text/javascript">
    $("#saveopsi").click(function() {
        var soal = "<?php echo $idsoal;?>";
        var skor = $("#kunci").val();
        var ops = tinymce.get("opsijwb").getContent();
        if (skor == '' || skor == null) {
            toastr.error('Wajib Diisi!');
        } else {
            $.ajax({
                url: "isisoal_simpan.php",
                type: 'POST',
                data: "aksi=2&soal=" + soal + "&ops=" + encodeURIComponent(ops) + "&skr=" + skor,
                success: function(data) {
                    toastr.success(data);
                }
            })
        }
    })
    </script>
    <?php endif ?>
    <script type="text/javascript">
    $(document).ready(function() {
        $("#myAddOpsi").on('hidden.bs.modal', function() {
            window.location.reload();
        })
    })

    $(".btnHpsOpsi").click(function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Anda Yakin?',
            text: "Menghapus Opsi Dengan ID " + id,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "isisoal_simpan.php",
                    type: 'POST',
                    data: "aksi=5&id=" + id,
                    success: function(data) {
                        toastr.success(data);
                    }
                })
            }
        })
    })
    $("#savesoal").click(function() {
        var ib = "<?php echo $idbank;?>";
        var id = "<?php echo $idsoal;?>";
        var nm = "<?php echo $maks;?>";
        var js = $("#kategori").val();

        var mo = $("#modeopsi").val();
        var tk = $("#kesulitan").val();
        var sk = $("#skormaks").val();
        var bt = tinymce.get("tanyasoal").getContent();
        $.ajax({
            url: "isisoal_simpan.php",
            type: 'POST',
            data: "aksi=1&ib=" + ib + "&id=" + id + "&js=" + js + "&mo=" + mo + "&tk=" + tk + "&sk=" +
                sk + "&nm=" + nm + "&bt=" + encodeURIComponent(bt),
            success: function(data) {
                toastr.success(data);
            }
        })
    })
    </script>