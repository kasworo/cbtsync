<?php
	if(!isset($_COOKIE['id'])){header("Location: login.php");}
	if($level=='1'):
	if(!empty($_REQUEST['d']) && $_REQUEST['d']=='1'){include"rombel_upload.php";}
?>
<script type="text/javascript" src="js/salinrombel.js"></script>

<div class="modal fade" id="myImportRombel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="?p=datarombel&d=1" method="POST" enctype="multipart/form-data">
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
                            <p style="color:red;margin-top:10px"><em>Hanya mendukung file *.xls (Microsoft Excel
                                    97-2003)</em></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a href="rombel_template.php" class="btn btn-success btn-sm" target="_blank"><i
                            class="fas fa-download"></i> Download</a>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-upload"></i> Upload</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fas fa-power-off"></i> Tutup</button>
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
                            <input type="text" readonly="true" class="form-control form-control-sm" id="nmsiswa"
                                name="nmsiswa">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-5">Kelas</label>
                        <div class="col-sm-6">
                            <select class="form-control form-control-sm" id="kdkelas" name="kdkelas"
                                onchange="pilrombel(this.value)">
                                <option value="">..Pilih..</option>
                                <?php
										$qkls=$conn->query("SELECT idkelas,nmkelas FROM tbkelas INNER JOIN tbskul USING (idjenjang)");
										while($kl=$qkls->fetch_array()):
									?>
                                <option value="<?php echo $kl['idkelas'];?>"><?php echo $kl['nmkelas'];?></option>
                                <?php endwhile?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-5">Rombongan Belajar</label>
                        <div class="col-sm-6">
                            <select class="form-control form-control-sm" id="kdrombel" name="kdrombel">
                                <?php
										$qrmb=$conn->query("SELECT*FROM tbrombel r INNER JOIN tbthpel t ON r.idthpel=t.idthpel WHERE t.aktif='1'");
										while($rm=$qrmb->fetch_array()):
									?>
                                <option value="<?php echo $rm['idrombel'];?>"><?php echo $rm['nmrombel'];?></option>
                                <?php endwhile?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary btn-sm col-4" id="simpan">
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
                            <select class="form-control form-control-sm" id="klsasal" name="klsasal"
                                onchange="pilkelas(this.value)">
                                <option value="">..Pilih..</option>
                                <?php
									$qkls=$conn->query("SELECT idkelas,nmkelas FROM tbkelas INNER JOIN tbskul USING (idjenjang)");
									while($kl=$qkls->fetch_array()){
								?>
                                <option value="<?php echo $kl['idkelas'];?>"><?php echo $kl['nmkelas'];?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-5">Rombel Asal</label>
                        <div class="col-sm-6">
                            <select class="form-control form-control-sm" id="rombelasl" name="rombelasl"
                                onchange="pilrombelasl(this.value)">
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
            <h4 class="card-title">Data Pembagian Rombel <?php echo $tapel;?></h4>
            <div class="card-tools">
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myImportRombel">
                    <i class="fas fa-cloud-upload-alt"></i>&nbsp;Import
                </button>
                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#mySalinRombel">
                    <i class="far fa-copy"></i>&nbsp;Salin
                </button>
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
							$qs=$conn->query("SELECT idsiswa,nmsiswa, nis, nisn FROM tbpeserta WHERE deleted='0'");
							$no=0;
							while($s=$qs->fetch_array())
							{
								$no++;
								$qrs=$conn->query("SELECT rs.*, r.nmrombel FROM tbrombelsiswa rs INNER JOIN tbrombel r USING(idrombel) INNER JOIN tbthpel t USING(idthpel) WHERE r.idthpel='$idthpel' AND rs.idsiswa='$s[idsiswa]'");
								$rs=$qrs->fetch_array();
								$nmrombel=$rs['nmrombel'];
					?>
                        <tr>
                            <td style="text-align:center"><?php echo $no.'.';?></td>
                            <td title="<?php echo $s['idsiswa'];?>"><?php echo ucwords(strtolower($s['nmsiswa']));?>
                            </td>
                            <td style="text-align:center"><?php echo $s['nis'].' / '.$s['nisn'];?></td>
                            <td style="text-align:center"><?php echo $nmrombel;?></td>
                            <td style="text-align:center">
                                <a href="#myAddRombel" data-toggle="modal" data-id="<?php echo $s['idsiswa'];?>"
                                    class="btn btn-xs btn-warning btnUpdate col-6">
                                    <i class="fas fa-cogs"></i>&nbsp;Set Rombel
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
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
})
</script>
<script type="text/javascript">
$("#simpan").click(function() {
    var idsiswa = $("#idsiswa").val();
    var idrombel = $("#kdrombel").val();
    $.ajax({
        url: "rombel_simpan.php",
        type: 'POST',
        data: "aksi=1&id=" + idsiswa + "&rm=" + idrombel,
        success: function(data) {
            toastr.success(data);
        }
    })
})
$(".btnUpdate").click(function() {
    var id = $(this).data('id');
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
    var rombelasl = $("#rombelasl").val();
    var rombelnew = $("#rombelnew").val();
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
</script>
<?php else: ?>
<div class="col-sm-12">
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <h4 class="card-title">Data Pembagian Rombel <?php echo $tapel;?></h4>
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
							$qs=$conn->query("SELECT s.nmsiswa, s.nis, s.nisn, nmmapel, nmrombel FROM tbsiswa s LEFT JOIN tbrombelsiswa rs USING(idsiswa) LEFT JOIN tbrombel r USING(idrombel) LEFT JOIN tbpengampu p USING(idrombel) INNER JOIN tbmapel m USING(idmapel) WHERE p.username='$_COOKIE[c_user]'");
							$no=0;
							while($s=$qs->fetch_array())
							{
								$no++;
						?>
                        <tr>
                            <td style="text-align:center"><?php echo $no.'.';?></td>
                            <td title="<?php echo $s['idsiswa'];?>"><?php echo ucwords(strtolower($s['nmsiswa']));?>
                            </td>
                            <td style="text-align:center"><?php echo $s['nis'].' / '.$s['nisn'];?></td>
                            <td style="text-align:center"><?php echo $s['nmrombel'];?></td>
                            <td><?php echo $s['nmmapel'];?></td>

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