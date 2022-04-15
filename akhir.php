<?php
$saiki = date('Y-m-d H:i:s');
$sql = "SELECT ps.nmpeserta, ps.nmsiswa, su.hasil, su.idjadwal, su.idrombel FROM tbsetingujian su INNER JOIN tbjadwal jd USING(idjadwal) INNER JOIN tblogpeserta lp USING(idjadwal) INNER JOIN tbpeserta ps USING(idsiswa) WHERE lp.idsiswa='$_COOKIE[pst]' AND lp.logakhir<='$saiki' ORDER BY lp.idjadwal DESC LIMIT 1";
$jd = vquery($sql)[0];
$idrombel = $jd['idrombel'];
$nmsiswa = $jd['nmsiswa'];
$nmpeserta = $jd['nmpeserta'];
$idjadwal = $jd['idjadwal'];
if ($jd['hasil'] == '0') :
?>
    <div class="col-sm-8 offset-sm-2">
        <div class="card card-primary card-outline">
            <div class="card-header text-center">
                <h3 class="card-title">Konfirmasi Selesai Tes</h3>
            </div>
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="assets/img/avatar.gif" alt="User profile picture">
                </div>
                <h4 class="profile-username text-center"><?php echo $nmsiswa; ?></h4>
                <p class="text-muted text-center"><?php echo $nmpeserta; ?></p>
                <p class="text-muted text-center">
                    Terima Kasih semoga anda mendapatkan hasil yang memuaskan.
                </p>
                <p class="text-muted text-center">
                    Silahkan klik Keluar dan Simpan agar nilai anda tersimpan di database.
                </p>
            </div>
            <div class="card-footer text-center">
                <a href="logout.php" class="btn btn-md btn-danger">
                    <i class="fas fa-power-off"></i>&nbsp;Selesai
                </a>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="col-sm-6 offset-sm-3">
        <div class="card card-primary card-outline">
            <div class="card-header text-center">
                <h3 class="card-title">Konfirmasi Selesai Tes</h3>
            </div>
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="assets/img/avatar.gif" alt="User profile picture">
                </div>
                <h4 class="profile-username text-center"><?php echo $nmsiswa; ?></h4>
                <p class="text-muted text-center"><?php echo $nmpeserta; ?></p>
                <div class="col-sm-8 offset-sm-2">
                    <?php
                    $qnil = "SELECT n.*, mp.idmapel, mp.nmmapel FROM tbnilai n INNER JOIN tbujian u USING(idujian) INNER JOIN tbjadwal jd USING(idujian) INNER JOIN tbsetingujian su USING(idjadwal) INNER JOIN tbrombelsiswa rs USING(idrombel,idsiswa) INNER JOIN tbbanksoal bs USING(idbank) INNER JOIN tbmapel mp ON (bs.idmapel=mp.idmapel AND n.idmapel=mp.idmapel AND n.idmapel=bs.idmapel) WHERE su.idjadwal='$idjadwal' AND rs.idsiswa='$_COOKIE[pst]'";
                    if (cquery($qnil) > 0) {
                        $n = vquery($qnil)[0];
                        $idmapel = $n['idmapel'];
                        $nmmapel = $n['nmmapel'];
                        $jmlsoal = number_format($n['jmlsoal'], 2, ',', '.');
                        $skor = number_format($n['benar'], 2, ',', '.');
                        $nilai = number_format($n['nilai'], 2, ',', '.');

                        $sqlkkm = "SELECT n.kkm, n.idmapel FROM tbkkm n INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbkelas k USING(idkelas) INNER JOIN tbrombel rb USING(idkelas) INNER JOIN tbthpel tp ON n.idthpel=tp.idthpel AND rb.idthpel=tp.idthpel AND n.idthpel=rb.idthpel WHERE rb.idrombel='$idrombel' AND n.idmapel='$idmapel'";
                        $k = vquery($sqlkkm)[0];
                        $kkm = $k['kkm'];
                        if ($nilai > $kkm) {
                            $pesan = "Terlampaui";
                            $badge = "badge badge-info";
                        } else if ($nilai == $kkm) {
                            $pesan = "Tercapai";
                            $badge = "badge badge-success";
                        } else {
                            $pesan = "Belum Tercapai, Belajar Lebih Giat!";
                            $badge = "badge badge-danger";
                        }
                    } else {
                        $nmmapel = '-';
                        $jmlsoal = '-';
                        $skor = '-';
                        $nilai = '-';
                        $pesan = "Semoga Tuntas";
                        $badge = "badge badge-warning";
                    }
                    ?>
                    <div class="form-group">
                        <span><b>Mata Pelajaran</b></span>
                        <p> <?php echo $nmmapel; ?></p>
                    </div>
                    <div class="form-group">
                        <span><b>Skor Maksimum (Jumlah Soal)</b></span>
                        <p><?php echo $jmlsoal; ?></p>
                    </div>
                    <div class="form-group">
                        <span><b>Jumlah Skor Perolehan</b></span>
                        <p><?php echo $skor; ?></p>
                    </div>
                    <div class="form-group">
                        <span><b>Nilai Akhir</b></span>
                        <p><?php echo $nilai; ?></p>
                    </div>
                    <div class="form-group">
                        <span><b>Ketercapaian</b></span>
                        <p>
                            <span class="<?php echo $badge; ?>">
                                <?php echo $pesan; ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="logout.php" class="btn btn-sm btn-danger">
                    <i class="fas fa-power-off"></i>&nbsp;Selesai
                </a>
            </div>
        </div>
    </div>
<?php endif ?>