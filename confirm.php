<?php
if (isset($_POST['konfirmasi'])) {
    $qjd = "SELECT t.idsesi,t.idjadwal, j.lambat, j.durasi FROM tbtoken t INNER JOIN tbjadwal j USING(idjadwal) WHERE t.token='$_POST[tkn]'";
    $cek = cquery($qjd);
    if ($cek > 0) {
        $jd = vquery($qjd)[0];
        setcookie('jdw', $jd['idjadwal']);
        header("Location: index.php?p=mulai");
    }
}
?>
<script type="text/javascript">
    $(document).ready(function(e) {
        let pst = "<?php echo $_COOKIE['pst']; ?>";
        $.ajax({
            url: 'confirm_isi.php',
            type: 'post',
            dataType: 'json',
            data: 'pst=' + pst,
            success: function(rsp) {
                $("#namapst").html(rsp.nama)
                $("#nomorpst").html(rsp.nopes)
                $("#noinduk").html(rsp.noinduk)
                $("#kelahiran").html(rsp.kelahiran)
                $("#agama").html(rsp.agama)
                $("#gender").html(rsp.gender)
                $("#tglujian").html(rsp.tgluji)
                $("#matauji").html(rsp.matauji)
                $("#durasi").html(rsp.durasi)
                $("#petunjuktoken").html(rsp.petunjuk)
                $("#KodeToken").addClass(rsp.tipe)
            }
        })
    })
</script>
<div class="col-sm-8 offset-sm-2">
    <div class=" card card-primary card-outline">
        <form id="frmToken" action="" method="post">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="assets/img/avatar.gif" alt="User profile picture">
                </div>
                <h4 class="profile-username text-center" id="namapst"></h4>
                <p class="text-muted text-center" id="nomorpst"></p>
                <hr />
                <div class="col-sm-10 offset-sm-1">
                    <div class="form-group row ml-auto">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <span><b>Nomor Induk</b></span>
                                <p id="noinduk"></p>
                            </div>
                            <div class="form-group">
                                <span><b>Tempat dan Tanggal Lahir</b></span>
                                <p id="kelahiran"></p>
                            </div>
                            <div class="form-group">
                                <span><b>Agama</b></span>
                                <p id="agama"></p>
                            </div>
                            <div class="form-group">
                                <span><b>Jenis Kelamin</b></span>
                                <p id="gender"></p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <span><b>Tanggal Ujian</b></span>
                                <p id="tglujian"></p>
                            </div>
                            <div class="form-group">
                                <span><b>Mata Ujian</b></span>
                                <p id="matauji"></p>
                            </div>
                            <div class="form-group">
                                <span><b>Alokasi Waktu</b></span>
                                <p id="durasi"></p>
                            </div>
                            <div class="form-group" id="token">
                                <span id="petunjuktoken"><b>Masukkan Token Dari Pengawas</b></span>
                                <input id="KodeToken" name="tkn" class="form-control col-10">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-md col-md-2 mb-2 ml-2" name="konfirmasi">
                        <i class="fas fa-sign-in-alt"></i>&nbsp;Submit
                    </button>
                    <button class="btn btn-primary btn-md col-md-2 mb-2 ml-2" id="btnRefresh">
                        <i class="fas fa-sync-alt"></i>&nbsp;Refresh
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#btnRefresh").click(function() {
            window.location.reload();
        })

        $("#frmToken").validate({
            rules: {
                tkn: {
                    required: true
                },
            },
            messages: {
                tkn: {
                    required: "Token Wajib Diisi!",
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        })
    })
</script>