<?php
$sqcek = "SELECT idjwb FROM tbjawaban jwb INNER JOIN tbsoal so USING(idbutir) INNER JOIN tbstimulus st USING(idstimulus) WHERE idsiswa='$_POST[idsw]' AND idset='$_POST[idset]'";
$rowCount = cquery($sqcek);
if ($rowCount > 0) {
    echo "<script>
    $(document).ready(function() {
        tampilsoal(1)
    })
    </script>";
}

$qsu = "SELECT bs.idujian, su.idrombel, bs.idmapel FROM tbsetingujian su INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) WHERE su.idset='$_POST[idset]'";
$du = vquery($qsu)[0];
$iduji = $du['idujian'];
$idmapel = $du['idmapel'];
$idrombel = $du['idrombel'];

$sqcek = "SELECT jw.idsiswa, ps.nmsiswa, ps.nis, ps.nisn, idset, COUNT(idset) as semua, SUM(skor) as benar, bs.nmbank, mp.nmmapel FROM tbjawaban jw INNER JOIN tbsoal so USING(idbutir) INNER JOIN tbstimulus st USING(idstimulus) INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbpeserta ps USING(idsiswa) WHERE jw.idsiswa='$_POST[idsw]' AND jw.idset='$_POST[idset]'";
$sta = vquery($sqcek)[0];
//var_dump($sta);


?>

<div class="modal fade" id="myViewHasil" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ringkasan Jawaban</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="fetched-data"></div>
            </div>
        </div>
    </div>
</div>
<div class="card card-secondary card-outline">
    <div class="card-header">
        <h3 class="card-title">Detail Hasil Tes</h3>
        <div class="card-tools justify-content-between">
            <form action="index.php?p=detailtes" method="POST">
                <input type="hidden" id="iduji" name="iduji" value="<?php echo $iduji; ?>">
                <input type="hidden" id="idmapel" name="idmap" value="<?php echo $idmapel; ?>">
                <input type="hidden" id="idrombel" name="idrmb" value="<?php echo $idrombel; ?>">
                <button type="submit" class="btn btn-sm btn-secondary" id="btnKembali">
                    <i class="fas fa-arrow-circle-left"></i>&nbsp;Kembali
                </button>
                <a href="#" data-toggle="modal" data-target="#myViewHasil" class="btn btn-sm btn-warning ViewHasil">
                    <i class="fa fa-eye fa-fw"></i>&nbsp;Detail
                </a>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><strong>Peserta Ujian</strong></h3>
                </div>
                <div class=" card-body justify-content-between">
                    <div class="table-responsive p-0 mt-3">
                        <table class="table table-condensed table-sm">
                            <tr>
                                <td width="25%"><strong>Nama Peserta Ujian</strong></td>
                                <td width="2.5%">:</td>
                                <td><?php echo $sta['nmsiswa']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nomor Induk</strong></td>
                                <td>:</td>
                                <td><?php echo $sta['nis'] . ' / ' . $sta['nisn']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Mata Pelajaran</strong></td>
                                <td>:</td>
                                <td><?php echo $sta['nmmapel']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Bank Soal</strong></td>
                                <td>:</td>
                                <td><?php echo $sta['nmbank']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Skor Perolehan</strong></td>
                                <td>:</td>
                                <td><?php echo number_format($sta['benar'], 2, ',', '.') . ' dari ' . $sta['semua']; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" id="hasil"></div>

    </div>
</div>
<script type="text/javascript">
    function tampilsoal(h) {
        let data = new FormData()
        data.append('h', h)
        data.append('idsw', <?php echo $_POST['idsw']; ?>)
        data.append('idset', <?php echo $_POST['idset']; ?>)
        data.append('aksi', 'load')
        $.ajax({
            url: "hasil_load.php",
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 8000,
            success: function(resp) {
                $("#hasil").html(resp)
                $("#nomor").html('Soal Nomor ' + h)
            }
        })
    }
</script>