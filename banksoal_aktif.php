<?php
	define("BASEPATH", dirname(__FILE__));
	include "../config/konfigurasi.php";
	include "../config/fungsi_tgl.php";
	$saiki=date('Y-m-d');
	$qgetsoal=$conn->query("SELECT COUNT(*) as jml FROM tbsoal WHERE idbank='$_POST[id]'");
	$so=$qgetsoal->fetch_array();
	if($so['jml']>0){
	$psn="Maksimum Jumlah Soal ".$so['jml'];
	}
	else {
		$psn="Jumlah Soal";
	}
?>
<script type="text/javascript">
$("#jduji").change(function() {
    $("#rmbuji").prop("disabled", false);
    $("#soal").prop("disabled", false);
    $("#mode").prop("disabled", false);
    $("#opsi").prop("disabled", false);
})
</script>
<div class="form-group row mb-2">
    <label class="col-sm-4 offset-sm-1">Pilih Jadwal</label>
    <select class="form-control form-control-sm col-sm-6" id="jduji" name="jduji">
        <option>..Pilih..</option>
        <?php 
            $quji=$conn->query("SELECT j.* FROM tbjadwal j INNER JOIN tbujian u USING(idujian) WHERE u.status='1' AND j.tglujian='$saiki'");
			while($u=$quji->fetch_array()):
		?>
        <option value="<?php echo $u['idjadwal'];?>"><?php echo indonesian_date($u['tglujian']).' - '.$u['nmjadwal'];?>
        </option>
        <?php endwhile ?>
    </select>
</div>
<div class="form-group row mb-2">
    <input type="hidden" id="idsoal" value="<?php echo $_POST['id'];?>">
    <label class="col-sm-4 offset-sm-1">Untuk Rombel</label>
    <select class="form-control form-control-sm col-sm-6" id="rmbuji" name="rmbuji" disabled="true">
        <option>..Pilih..</option>
        <?php 
			$qrmb=$conn->query("SELECT rm.* FROM tbrombel rm INNER JOIN tbbanksoal bs USING(idkelas) INNER JOIN tbthpel tp USING(idthpel) WHERE idbank='$_POST[id]' AND tp.aktif='1'");
			while($rm=$qrmb->fetch_array()):
		?>
        <option value="<?php echo $rm['idrombel'];?>"><?php echo $rm['nmrombel'];?></option>
        <?php endwhile ?>
    </select>
</div>
<div class="form-group row mb-2">
    <label class="col-sm-4 offset-sm-1">Jumlah Soal</label>
    <input class="form-control form-control-sm col-sm-6" id="soal" name="soal" placeholder="<?php echo $psn;?>"
        disabled="true">
</div>
<div class="form-group row mb-2">
    <label class="col-sm-4 offset-sm-1">Random Soal</label>
    <select class="form-control form-control-sm col-sm-6" id="mode" name="mode" disabled="true">
        <option value="">..Pilih..</option>
        <option value="0">Tidak</option>
        <option value="1">Ya</option>
    </select>
</div>
<div class="form-group row mb-2">
    <label class="col-sm-4 offset-sm-1">Random Opsi</label>
    <select class="form-control form-control-sm col-sm-6" id="opsi" name="opsi" disabled="true">
        <option value="">..Pilih..</option>
        <option value="0">Tidak</option>
        <option value="1">Ya</option>
    </select>
</div>