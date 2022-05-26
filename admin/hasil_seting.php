<?php
define("BASEPATH", dirname(__FILE__));
include "dbfunction.php";
if (isset($_GET['ts'])) :
    $qkls = tampilKelas();
?>
    <option value="">..Pilih..</option>
    <?php
    foreach ($qkls as $kls) :
    ?>
        <option value="<?php echo $kls['idkelas']; ?>"><?php echo $kls['nmkelas']; ?></option>
    <?php endforeach ?>
<?php endif ?>

<?php
if (isset($_GET['kl'])) :
    $sqrb = "SELECT r.* FROM tbrombel r INNER JOIN tbthpel t USING(idthpel) WHERE t.aktif='1' AND idkelas='$_GET[kl]'";
    $qrb = vquery($sqrb);
?>
    <option value="">..Pilih..</option>
    <?php
    foreach ($qrb as $rb) :
    ?>
        <option value="<?php echo $rb['idrombel']; ?>"><?php echo $rb['nmrombel']; ?></option>
    <?php endforeach ?>
<?php endif ?>