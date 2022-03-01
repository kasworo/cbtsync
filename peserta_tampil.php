<?php
if (isset($_REQUEST['d']) && $_REQUEST['d']=='1'){include "peserta_upload.php";}
?>

<div class="modal fade" id="myImportPeserta" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="?p=datapeserta&d=1" method="POST" enctype="multipart/form-data">
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
                            <p style="color:red;margin-top:10px"><em>Hanya mendukung file *.xls (Microsoft Excel
                                    97-2003)</em></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a href="sesi_template.php" class="btn btn-success btn-sm" target="_blank"><i
                            class="fas fa-download"></i> Download</a>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-upload"></i> Upload</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fas fa-power-off"></i> Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <h4 class="card-title">Data Peserta Tes</h4>
            <div class="card-tools">
                <?php if($level=='1'):?>
                <button class="btn btn-info btn-sm" id="btnNomor">
                    <i class="fas fa-plus-circle"></i>&nbsp;Tambah
                </button>
                <?php endif?>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myImportPeserta">
                    <i class="fas fa-cloud-upload-alt"></i>&nbsp;Import
                </button>

                <?php if($level=='1'): ?>
                <button id="btnHapus" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash-alt"></i>&nbsp;Hapus
                </button>
                <?php endif ?>
                <a href="print_kartu.php" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="fas fa-print"></i>&nbsp;Kartu Peserta
                </a>
                <a href="print_nomeja.php" target="_blank" class="btn btn-default btn-sm">
                    <i class="fas fa-print"></i>&nbsp;Nomor Meja
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
                            <th style="text-align: center;width:12.5%">Ruang</th>
                            <?php
						$qjd=$conn->query("SELECT*FROM tbjadwal j INNER JOIN tbujian u USING(idujian) WHERE u.status='1'");
						$i=0;
						while($jd=$qjd->fetch_array()):
							$i++;
						?>
                            <th style="text-align:center;width:5%"><?php echo $i;?></th>
                            <?php endwhile ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
						if($level=='1'){
							$qs=$conn->query("SELECT ps.idsiswa, ps.nmsiswa, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian, r1.nmruang, ps.idruang, ps.idujian, ps.aktif FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbujian u USING(idujian) LEFT JOIN tbruang r1 USING(idruang) WHERE u.status='1' AND ps.deleted='0' GROUP BY ps.idsiswa  ORDER BY ps.nmpeserta");
						}
						else{
							$qs=$conn->query("SELECT ps.idsiswa, ps.nmsiswa, ps.nmpeserta, ps.passwd, r.nmrombel, u.nmujian,r1.nmruang, ps.idruang, ps.aktif FROM tbpeserta ps INNER JOIN tbrombelsiswa rs USING(idsiswa) INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbpengampu p USING(idrombel) INNER JOIN tbujian u USING(idujian) LEFT JOIN tbruang r1 USING(idruang) WHERE u.status='1' AND p.username='$useraktif' GROUP BY ps.idsiswa ORDER BY ps.nmpeserta");						
						}
						$no=0;
						while($s=$qs->fetch_array())
						{
						$no++;
						if($s['aktif']=='1'){$badg='badge badge-success';} else {$badg='badge badge-danger';}
					?>
                        <tr>
                            <td style="text-align:center"><?php echo $no.'.';?></td>
                            <td style="text-align:center" title="Password:<?php echo $s['passwd'];?>">
                                <?php echo $s['nmpeserta'];?>
                            </td>
                            <td>
                                <?php echo ucwords(strtolower($s['nmsiswa']));?>
                                <span class="<?php echo $badg;?> float-right"><?php echo $s['aktif'];?></span>
                            </td>
                            <td style="text-align:center">
                                <select class="form-control col-xs-2 setruang" data-id="<?php echo $s['nmpeserta'];?>"
                                    id="idruang" name="idruang">
                                    <option>..Pilih..</option>
                                    <?php
									$qru=$conn->query("SELECT idruang, nmruang FROM tbruang WHERE status='1'");
									while($ru=$qru->fetch_array()):
										if($s['idruang']==$ru['idruang']){$ruang='selected';}
										else {$ruang='';}
								?>
                                    <option value="<?php echo $ru['idruang'];?>" <?php echo $ruang;?>>
                                        <?php echo $ru['nmruang'];?></option>
                                    <?php endwhile ?>
                                </select>
                            </td>
                            <?php
						$qjd=$conn->query("SELECT j.* FROM tbjadwal j INNER JOIN tbujian u USING(idujian) WHERE u.status='1'");
						$i=0;
						while($jd=$qjd->fetch_array()):
							$i++;
							$ql=$conn->query("SELECT*FROM tbsesiujian WHERE idsiswa='$s[idsiswa]' AND idjadwal='$jd[idjadwal]'");
							$l=$ql->fetch_array();
						?>
                            <td style="text-align:center;">
                                <input class="form-control input-xs col-xs-1 setsesi"
                                    data-id="<?php echo $s['idsiswa'].'&jd='.$jd['idjadwal'];?>" id="idsesi"
                                    name="idsesi" value="<?php echo $l['idsesi'];?>" style="text-align:center;">
                            </td>
                            <?php endwhile ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/pilihpeserta.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#myGeneratePeserta").on('hidden.bs.modal', function() {
        window.location.reload();
    })
})
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

$(".setsesi").change(function() {
    var idp = $(this).data('id');
    var sesi = $(this).val();
    $.ajax({
        url: "peserta_simpan.php",
        type: 'POST',
        data: "aksi=4&idpes=" + idp + "&sesi=" + sesi,
        success: function(data) {
            toastr.success(data);
        }
    })
})
$(".setruang").change(function() {
    var idp = $(this).data('id');
    var idr = $(this).val();
    if (idp !== '') {
        $.ajax({
            url: 'peserta_simpan.php',
            type: 'POST',
            data: 'aksi=3&idpes=' + idp + '&ruang=' + idr,
            success: function(data) {
                toastr.success(data);
            }
        })
    }
})

$("#btnNomor").click(function() {
    $.ajax({
        url: "peserta_simpan.php",
        type: 'POST',
        data: "aksi=1",
        success: function(data) {
            toastr.info(data, 'Terima Kasih', {
                timeOut: 3000,
                fadeOut: 3000,
                onHidden: function() {

                }
            });
        }
    })
})

$("#btnSetSave").click(function() {
    var idp = $("#nmpeserta").val();
    var idr = $("#idruang").val();
    $.ajax({
        url: 'peserta_simpan.php',
        type: 'POST',
        data: 'aksi=3&idpes=' + idp + '&ruang=' + idr,
        success: function(data) {
            toastr.success(data);
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
                data: "aksi=2",
                success: function(data) {
                    toastr.success(data);
                }
            })
        }
    })
})
</script>