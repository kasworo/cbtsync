<?php
	if(!isset($_COOKIE['c_user'])){header("Location: login.php");}
	if(!empty($_GET['d']) && $_GET['d']=='1'){include "isisoal_upload.php";}
?>
<style type="text/css">
input[type="radio"] {
    left: 5px;
    top: 2px;
    position: relative;
    margin-right: 15px;
    padding-right: 15px;
    cursor: pointer;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    outline: 0;
    height: 20px;
    width: 20px;
    background-image: url(../assets/img/cek.png);
}

input[type="radio"]:checked {
    left: 5px;
    top: 2px;
    position: relative;
    margin-right: 15px;
    padding-right: 15px;
    cursor: pointer;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    outline: 0;
    height: 20px;
    width: 20px;
    background-image: url(../assets/img/ceklis.png);
}

input[type="checkbox"] {
    left: 5px;
    top: 2px;
    position: relative;
    margin-right: 15px;
    padding-right: 15px;
    cursor: pointer;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    outline: 0;
    height: 20px;
    width: 20px;
    background-image: url(../assets/img/cek.png);
}

input[type="checkbox"]:checked {
    left: 5px;
    top: 2px;
    position: relative;
    margin-right: 15px;
    padding-right: 15px;
    cursor: pointer;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    outline: 0;
    height: 20px;
    width: 20px;
    background-image: url(../assets/img/ceklis.png);
}
</style>
<div class="modal fade" id="myUploadSoal" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?p=isisoal&d=1" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Import Template Soal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="form-group row mb-2">
                            <label for="tmpisisoal">Pilih File Template</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="tmpisisoal" name="tmpisisoal">
                                <label class="custom-file-label" for="tmpisisoal">Pilih file</label>
                            </div>
                            <p style="color:red;margin-top:10px"><em>Hanya mendukung file *.xls (Microsoft Excel
                                    97-2003)</em></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a href="isisoal_template.php?id=<?php echo $_GET['id'];?>" class="btn btn-success btn-sm "
                        target="_blank"><i class="fas fa-download"></i> Download</a>
                    <button type="submit" class="btn btn-primary btn-sm "><i class="fas fa-upload"></i> Upload</button>
                    <button type="button" class="btn btn-danger btn-sm " data-dismiss="modal"><i
                            class="fas fa-power-off"></i> Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <h3 class="card-title">Isi Soal</h3>
            <div class="card-tools">
                <a href="index.php?p=banksoal" class="btn btn-default btn-sm">
                    <i class="fas fa-arrow-circle-left"></i>&nbsp;Kembali
                </a>
                <a href="index.php?p=tambahsoal&id=<?php echo $_GET['id'];?>" class="btn btn-success btn-sm">
                    <i class="fas fa-plus-circle"></i>&nbsp;Tambah
                </a>
                <button data-target="#myUploadSoal" data-id="<?php echo $_GET['id'];?>" data-toggle="modal"
                    class="btn btn-secondary btn-sm">
                    <i class="fas fa-cloud-upload-alt"></i>&nbsp;Import
                </button>
                <button id="hapusall" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash-alt"></i>&nbsp;Hapus
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="col-sm-12">
                <?php
                    $qsoal=$conn->query("SELECT*FROM tbsoal so WHERE idbank='$_GET[id]'");
                    $no=0;
                    while($s=$qsoal->fetch_array()):
                    $no++;
                    $butir=str_replace('<img ','<img class="img img-fluid"',$s['butirsoal']);
                ?>
                <div class="form-group row mb-2">
                    <table width="100%" cellspacing="2px">
                        <tr>
                            <td width="2.5%" valign="top"><?php echo $no.".";?></td>
                            <td valign="top" width="77.5%">
                                <?php echo $butir;?>
                            </td>
                            <td valign="top">
                                <a href="index.php?p=editsoal&id=<?php echo $s['idbank'].'&nm='.$s['nomersoal'];?>"
                                    class="d-md-block d-sm-none btn btn-xs btn-primary col-1">
                                    <i class="fas fa-edit"></i></a>
                                <button data-id="<?php echo $s['idbutir'];?>"
                                    class="d-sm-none d-sm-block btn btn-xs btn-danger col-1 btnHapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php
                    $qhd=$conn->query("SELECT*FROM tbheaderopsi WHERE idbutir='$s[idbutir]'");
                    $cekheader=$qhd->num_rows;
                    if($cekheader==0):
                ?>
                <div class="form-group row mb-2">
                    <?php if($s['jnssoal']=='1'):?>
                    <table width="85%">
                        <?php 
                            $qops=$conn->query("SELECT*FROM tbopsi WHERE idbutir='$s[idbutir]'");
                            $i=0; 
                            while($op=$qops->fetch_array()):
                                $i++;
                                if($op['benar']=='1'){$hsl='checked';} else {$hsl='';}
                                $opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);
                        ?>
                        <tr padding-top="10px">
                            <td valign="top" width="2.5%">&nbsp;</td>
                            <td valign="top" width="2.5%" style="text-align:center">
                                <input id="btnOpsi<?php echo $no.$i;?>" type="radio" name="opsi<?php echo $no;?>"
                                    value="<?php echo $op['idopsi'];?>" <?php echo $hsl;?>>
                                <script type="text/javascript">
                                $("#btnOpsi<?php echo $no.$i;?>").click(function() {
                                    var ib = "<?php echo $s['idbutir'];?>";
                                    var id = $(this).val();
                                    $.ajax({
                                        url: "isisoal_simpan.php",
                                        type: 'POST',
                                        data: "aksi=6&id=" + id + "&ib=" + ib,
                                        success: function(data) {
                                            toastr.success(data);
                                        }
                                    })
                                })
                                </script>
                            </td>
                            <td valign="top">
                                <?php echo $opsi;?>
                            </td>
                        </tr>
                        <?php endwhile?>
                    </table>
                    <?php elseif($s['jnssoal']=='2'):?>
                    <table width="85%">
                        <?php 
                            $qops=$conn->query("SELECT*FROM tbopsi WHERE idbutir='$s[idbutir]'");
                            $i=0; 
                            while($op=$qops->fetch_array()):
                                $i++;
                                if($op['benar']=='1'){$hsl='checked';} else {$hsl='';}
                                $opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);
                        ?>
                        <tr padding-top="10px">
                            <td valign="top" width="2.5%">&nbsp;</td>
                            <td valign="top" width="3.5%" style="text-align:center">
                                <input type="checkbox" id="btnCeklis<?php echo $no.$i;?>" <?php echo $hsl;?>>
                                <script type="text/javascript">
                                $("#btnCeklis<?php echo $no.$i;?>").click(function() {
                                    var ib = "<?php echo $s['idbutir'];?>";
                                    var id = "<?php echo $op['idopsi'];?>";
                                    if ($(this).is(":checked")) {
                                        nil = 1;
                                    } else {
                                        nil = 0;
                                    }
                                    $.ajax({
                                        url: "isisoal_simpan.php",
                                        type: 'POST',
                                        data: "aksi=6&id=" + id + "&ib=" + ib + "&nil=" + nil,
                                        success: function(data) {
                                            toastr.success(data);
                                        }
                                    })


                                })
                                </script>
                            </td>
                            <td valign="top">
                                <?php echo $opsi;?>
                            </td>
                        </tr>
                        <?php endwhile?>
                    </table>
                    <?php elseif($s['jnssoal']=='3'):?>
                    <table width="85%">
                        <tr>
                            <td valign="top" width="2.5%">&nbsp;</td>
                            <td valign="top" style="text-align:center;padding:5px;border:solid 1px;" width="75%">
                                <strong>Pernyataan</strong>
                            </td>
                            <td style="text-align:center;padding:5px;border:solid 1px;"><strong>Benar</strong></td>
                            <td style="text-align:center;padding:5px;border:solid 1px;"><strong>Salah</strong></td>
                        </tr>
                        <?php 
                            $qops=$conn->query("SELECT*FROM tbopsi WHERE idbutir='$s[idbutir]'");
                            $i=0; 
                            while($op=$qops->fetch_array()):
                                $i++;
                                if($op['benar']=='1'){$hsl='checked';} else {$hsl='';}
                                $opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);
                        ?>
                        <tr padding-top="10px">
                            <td valign="top" width="2.5%">&nbsp;</td>
                            <td valign="top" style="padding:5px;border:solid 1px;">
                                <?php echo $opsi;?>
                            </td>
                            <td valign="top" style="text-align:center;padding:5px;border:solid 1px;">
                                <input type="radio" id="btnBenar<?php echo $no.$i;?>" name="opsi<?php echo $no.$i;?>"
                                    value="1" <?php echo $op['benar']==1 ? 'checked' :'';?>>
                                <script type="text/javascript">
                                $("#btnBenar<?php echo $no.$i;?>").click(function() {
                                    var ib = "<?php echo $s['idbutir'];?>";
                                    var id = "<?php echo $op['idopsi'];?>";
                                    var nil = $(this).val();
                                    $.ajax({
                                        url: "isisoal_simpan.php",
                                        type: 'POST',
                                        data: "aksi=6&id=" + id + "&ib=" + ib + "&nil=" + nil,
                                        success: function(data) {
                                            toastr.success(data);
                                        }
                                    })
                                })
                                </script>
                            </td>
                            <td valign="top" style="text-align:center;padding:5px;border:solid 1px;">
                                <input type="radio" id="btnSalah<?php echo $no.$i;?>" name="opsi<?php echo $no.$i;?>"
                                    value="0" <?php $op['benar']==0 ? 'checked' :'';?>>
                                <script type="text/javascript">
                                $("#btnSalah<?php echo $no.$i;?>").click(function() {
                                    var ib = "<?php echo $s['idbutir'];?>";
                                    var id = "<?php echo $op['idopsi'];?>";
                                    var nil = $(this).val();
                                    $.ajax({
                                        url: "isisoal_simpan.php",
                                        type: 'POST',
                                        data: "aksi=6&id=" + id + "&ib=" + ib + "&nil=" + nil,
                                        success: function(data) {
                                            toastr.success(data);
                                        }
                                    })
                                })
                                </script>
                            </td>
                        </tr>
                        <?php endwhile?>
                    </table>
                    <?php elseif($s['jnssoal']=='4'): ?>
                    <table width="85%">
                        <tr>
                            <td valign="top" width="2.5%">&nbsp;</td>
                            <td width="75%" style="text-align:center;padding:5px;border:solid 1px;"><strong>Butir
                                    Soal</strong></td>
                            <td style="text-align:center;padding:5px;border:solid 1px;"><strong>Alternatif
                                    Jawaban</strong></td>
                        </tr>
                        <?php 
							while($op=$qops->fetch_array()):
							$i++;
							if($op['benar']=='1'){$hsl='checked';} else {$hsl='';}
							$opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);
							$opsialt=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsialt']);
						?>
                        <tr padding-top="10px">
                            <td valign="top" width="2.5%">&nbsp;</td>
                            <td valign="top" style="padding:5px;border:solid 1px;">
                                <?php echo $opsi;?>
                            </td>
                            <td valign="top" style="padding:5px;border:solid 1px;">
                                <?php echo $opsialt;?>
                            </td>
                        </tr>
                        <?php endwhile?>
                    </table>
                    <?php else: ?>
                    <table width="75%">
                        <tr>
                            <td valign="top" width="2.5%">&nbsp;</td>
                            <td valign="top"><strong>Kunci Jawaban</strong></td>
                        </tr>
                        <?php
							while($op=$qops->fetch_array()):
							$i++;
							if($op['benar']=='1'){$hsl='checked';} else {$hsl='';}
							$opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);
						?>

                        <tr padding-top="10px">
                            <td valign="top" width="2.5%">&nbsp;</td>
                            <td valign="top">
                                <?php echo $opsi;?>
                            </td>
                        </tr>
                        <?php endwhile?>
                    </table>
                    <?php endif ?>
                </div>
                <?php else : ?>
                <div class="form-group row mb-2">
                </div>
                <?php endif ?>
                <?php endwhile ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(".btnEdit").click(function() {
    var id = $(this).data('id');
    $.ajax({
        url: "isisoal_edit.php",
        type: 'POST',
        dataType: 'json',
        data: "aksi=3&id=" + id,
        success: function() {
            window.location.href = "index.php?p=editsoal";
        }
    })
})
$(".btnHapus").click(function() {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Anda Yakin?',
        text: "Menghapus Soal Ini",
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
                data: "aksi=3&id=" + id,
                success: function() {
                    window.location.href = "index.php?p=isisoal&id=" + ib;
                }
            })
        }
    })
})

$("#hapusall").click(function() {
    var ib = "<?php echo $_GET['id'];?>";
    Swal.fire({
        title: 'Anda Yakin?',
        text: "Menghapus Seluruh Soal",
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
                data: "aksi=4&ib=" + ib,
                success: function() {
                    window.location.href = "index.php?p=isisoal&id=" + ib;
                }
            })
        }
    })
})
</script>
</script>