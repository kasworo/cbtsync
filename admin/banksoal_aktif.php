<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
$saiki = date('Y-m-d');
$sql = "SELECT COUNT(*) as jml FROM tbsoal so INNER JOIN tbstimulus st USING(idstimulus) WHERE st.idbank='$_POST[id]'";
$so = vquery($sql)[0];
if ($so['jml'] > 0) {
    $maks = $so['jml'];
    $psn = "Diizinkan Hanya " . $maks . " Dari " . $maks;
} else {
    $psn = "Jumlah Soal";
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#jduji").change(function() {
            $("#rmbuji").prop("disabled", false);
            $("#soal").prop("disabled", false);
            $("#mode").prop("disabled", false);
            $("#vhasil").prop("disabled", false);
            $("#opsi").prop("disabled", false);
        })
    })
</script>
<div class="form-group row mb-2">
    <input type="hidden" class="form-control form-sm" id="idsoal" value="<?php echo $_POST['id']; ?>">
    <label class="col-sm-4 offset-sm-1">Pilih Jadwal</label>
    <select class="form-control form-control-sm col-sm-6" id="jduji" name="jduji">
        <option>..Pilih..</option>
        <?php
        $squji = "SELECT j.* FROM tbjadwal j INNER JOIN tbujian u USING(idujian) WHERE u.status='1' AND j.tglujian='$saiki'";
        $quji = vquery($squji);
        foreach ($quji as $u) :
        ?>
            <option value="<?php echo $u['idjadwal']; ?>">
                <?php echo indonesian_date($u['tglujian']) . ' - ' . $u['kdjadwal']; ?>
            </option>
        <?php endforeach ?>
    </select>
</div>
<div class="form-group row mb-2">
    <label class="col-sm-4 offset-sm-1">Untuk Rombel</label>

    <select class="form-control form-control-sm col-sm-6" id="rmbuji" name="rmbuji" disabled="true">
        <option value="">..Pilih..</option>
        <?php
        $qrmb = "SELECT rm.* FROM tbrombel rm INNER JOIN tbbanksoal bs USING(idkelas) INNER JOIN tbthpel tp USING(idthpel) WHERE idbank='$_POST[id]' AND tp.aktif='1'";
        $rmb = vquery($qrmb);
        foreach ($rmb as $rm) :
        ?>
            <option value="<?php echo $rm['idrombel']; ?>"><?php echo $rm['nmrombel']; ?></option>
        <?php endforeach ?>
    </select>
</div>
<div class="form-group row mb-2">
    <input type="hidden" value="<?php echo $maks; ?>" id="jmlsoal">
    <label class="col-sm-4 offset-sm-1">Hasil Tes</label>
    <select class="form-control form-control-sm col-sm-6" id="vhasil" name="vhasil" disabled="true">
        <option value="">..Pilih..</option>
        <option value="1">Ditampilkan</option>
        <option value="0">Tidak Ditampilan</option>
    </select>
</div>
<div class="form-group row mb-2">
    <label class="col-sm-4 offset-sm-1">Jumlah Soal</label>
    <input class="form-control form-control-sm col-sm-6" id="soal" name="soal" placeholder="<?php echo $psn; ?>" disabled="true">
</div>