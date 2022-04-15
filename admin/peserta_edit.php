<?php
include "dbfunction.php";
$pst = viewdata('tbpeserta', array('idsiswa' => $_POST['id']))[0];
$sqlsesi = "SELECT idsesi, jd.tglujian FROM tbjadwal jd LEFT JOIN tbsesiujian su USING(idjadwal) LEFT JOIN tbujian u USING(idujian) WHERE u.status='1' AND su.idsiswa='$pst[idsiswa]' GROUP BY jd.tglujian";
$no = 0;
$ceksesi = cquery($sqlsesi);
if ($ceksesi > 0) {
    $isisesi = [];
    $dsesi = vquery($sqlsesi);
    foreach ($dsesi as $ses) {
        $isisesi[] = array(
            'idsesi' => $ses['idsesi'],
            'tglujian' => $ses['tglujian']
        );
    }
    $data = array(
        'idsiswa' => $pst['idsiswa'],
        'nmsiswa' => $pst['nmsiswa'],
        'ruang' => $pst['idruang'],
        'sesi' => $isisesi
    );
} else {
    $sqljd = "SELECT jd.tglujian FROM tbjadwal jd INNER JOIN tbujian u USING(idujian) WHERE u.status='1' GROUP BY jd.tglujian";
    $qjd = vquery($sqljd);
    $isisesi = [];
    foreach ($qjd as $jd) {
        $isisesi[] = array(
            'idsesi' => '',
            'tglujian' => $jd['tglujian']
        );
    }
    $data = array(
        'idsiswa' => $pst['idsiswa'],
        'nmsiswa' => $pst['nmsiswa'],
        'ruang' => $pst['idruang'],
        'sesi' => $isisesi
    );
}

?>
<div class="form-group row mb-2">
    <div class="col-sm-4 offset-1">
        <label>Nama Peserta</label>
    </div>
    <div class="col-sm-6">
        <input type="hidden" class="form-control form-control-sm" id="idsiswa" name="idsiswa" value="<?php echo $data['idsiswa']; ?>">
        <input class="form-control form-control-sm" id="nmsiswa" name="nmsiswa" value="<?php echo ucwords(strtolower($data['nmsiswa'])); ?>" disabled>
    </div>
</div>
<div class="form-group row mb-2">
    <div class="col-sm-4 offset-1">
        <label>Ruang Ujian</label>
    </div>
    <div class="col-sm-6">
        <select class="form-control form-control-sm" id="idruang" name="idruang">
            <option value="">..Pilih..</option>
            <?php
            $dr = viewdata('tbruang', array('status' => '1'));
            foreach ($dr as $r) :
            ?>
                <option value="<?php echo $r['idruang']; ?>" <?php echo ($r['idruang'] == $data['ruang']) ? "selected" : ""; ?>><?php echo $r['nmruang']; ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<?php
$hari = 0;
foreach ($data['sesi'] as $dat) :
    $hari++;
?>
    <div class="form-group row mb-2">
        <div class="col-sm-4 offset-1">
            <label>Sesi Hari Ke-<?php echo $hari; ?></label>
        </div>
        <div class="col-sm-6">
            <input type="hidden" id="tgluji" name="tgluji[]" value="<?php echo $dat['tglujian']; ?>">
            <select class="form-control form-control-sm" id="sesi" name="sesi[]">
                <option value="">..Pilih..</option>
                <option value="1" <?php echo ($dat['idsesi'] == 1) ? "selected" : ""; ?>>Sesi 1</option>
                <option value="2" <?php echo ($dat['idsesi'] == 2) ? "selected" : ""; ?>>Sesi 2</option>
                <option value="3" <?php echo ($dat['idsesi'] == 3) ? "selected" : ""; ?>>Sesi 3</option>
            </select>
        </div>
    </div>
<?php endforeach ?>