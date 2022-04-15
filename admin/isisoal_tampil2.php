<?php
function getisiopsi($idbtr)
{
    return viewdata('tbopsi', array('idbutir' => $idbtr));
}

function getstimulus($idbs)
{
    $rows = viewdata('tbstimulus', array('idbank' => $idbs));
    $stim = [];
    foreach ($rows as $row) {
        $stim[] = $row['idstimulus'];
    }
    return $stim;
}
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

    input[type="checkbox"]:hover {
        filter: brightness(98%);
    }

    input[type="checkbox"]:disabled {
        background: #e6e6e6;
        opacity: 0.6;
        pointer-events: none;
    }
</style>
<?php if (isset($_POST['import'])) {
    require_once "assets/library/PHPExcel.php";
    require_once "assets/library/excel_reader.php";
    if (empty($_FILES['tmpisisoal']['tmp_name'])) {
?>
        <script type="text/javascript">
            $(function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
                toastr.error("File Kosong Bro");
            });
        </script>
    <?php } else {
        $data = new Spreadsheet_Excel_Reader($_FILES['tmpisisoal']['tmp_name']);
        $baris = $data->rowcount($sheet_index = 0);
        $tgl = date('Y-m-d');
        $isidata = ceil(($baris - 7) / 9);
        var_dump($isidata);
        $xkodesoal = $data->val(3, 2);
        $qps = "SELECT idbank FROM tbbanksoal WHERE nmbank='$xkodesoal'";
        $ps = vquery($qps)[0];
        $kdbank = $ps['idbank'];
        $arr_stim = getstimulus(2);
        var_dump($arr_stim);

        for ($i = 1; $i <= $isidata; $i++) {
            // Data Stimulus
            $xkdstim = $data->val(9 * ($i - 1) + 7, 2);
            $idstim = $arr_stim[$xkdstim - 1];
            $xstim = $data->val(9 * ($i - 1) + 8, 2);
            $cekstimulus = cekdata('tbstimulus', array('idstimulus' => $idstim));
            if ($cekstimulus == 0) {
                $stimulus = array(
                    'idbank' => $kdbank,
                    'stimulus' => $xstim
                );
                adddata('tbstimulus', $stimulus);
            } else {
                if (!empty($xstim)) {
                    $stimulus = array(
                        'stimulus' => $xstim
                    );
                    editdata('tbstimulus', $stimulus, '', array('idstimulus' => $idstim));
                }
            }
            // Butir Soal
            $xnomersoal = $data->val(9 * ($i - 1) + 9, 2);
            $xjnssoal = $data->val(9 * ($i - 1) + 10, 2);
            $xbutirsoal = $conn->real_escape_string($data->val(9 * ($i - 1) + 11, 2));

            // Opsi Jawaban
            $xopsijwb = explode("#", $data->val(9 * ($i - 1) + 12, 2));
            $xopsialt = explode("#", $data->val(9 * ($i - 1) + 13, 2));
            $qsoal = $conn->query("INSERT INTO tbsoal (idstimulus, jnssoal, nomersoal, tksukar, butirsoal, skormaks) VALUES('$kdbank','$xjnssoal','$i','1','$xbutirsoal','1')");
            //$qcs = $conn->query("SELECT MAX(idbutir) as idsoal FROM tbsoal WHERE idbank='$kdbank'");
            //$cs = $qcs->fetch_array();
            //$idsoal = $cs['idsoal'];

            for ($j = 1; $j <= 6; $j++) {
                $xopsi = addslashes($data->val(6 * ($i - 1) + $j + 11, 3));
                $xbenar = strval($data->val(6 * ($i - 1) + $j + 11, 4));
                if ($xjnssoal == '1' || $xjnssoal == '2') {
                    if ($xbenar == '1') {
                        $skor = 1;
                    } else {
                        $skor = 0;
                    }
                } else {
                    $skor = 1;
                }
                if ($xopsi !== '') {
                    $qops = $conn->query("INSERT INTO tbopsi (idbutir, opsi, benar, skor) VALUES ('$idsoal','$xopsi','$xbenar','$skor')");
                }
            }
        }
    ?>
        <script type="text/javascript">
            var ib = "<?php echo $kdbank; ?>";
            alert("<?php echo $isidata; ?>")
            window.location.href = "index.php?p=isisoal&id=" + ib;
        </script>
<?php
    }
}
?>
<div class="modal fade" id="myUploadSoal" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
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
                            <p style="color:red;margin-top:10px"><em>Hanya mendukung file *.xls (Microsoft Excel 97-2003)</em></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a href="isisoal_template.php?id=<?php echo $_GET['id']; ?>" class="btn btn-success btn-sm " target="_blank"><i class="fas fa-download"></i> Download</a>
                    <button type="submit" class="btn btn-primary btn-sm" name="import"><i class="fas fa-upload"></i> Upload</button>
                    <button type="button" class="btn btn-danger btn-sm " data-dismiss="modal"><i class="fas fa-power-off"></i> Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="card card-secondary card-outline">
    <div class="card-header">
        <h3 class="card-title">Detail Isi Bank Soal</h3>
        <div class="card-tools">
            <a href="index.php?p=banksoal" class="btn btn-default btn-sm">
                <i class="fas fa-arrow-circle-left"></i>&nbsp;Kembali
            </a>
            <a href="index.php?p=addstimulus&id=<?php echo $_GET['id']; ?>" class="btn btn-success btn-sm">
                <i class="fas fa-plus-circle"></i>&nbsp;Tambah
            </a>
            <button data-target="#myUploadSoal" data-id="<?php echo $_GET['id']; ?>" data-toggle="modal" class="btn btn-secondary btn-sm">
                <i class="fas fa-cloud-upload-alt"></i>&nbsp;Import
            </button>
            <button id="hapusall" class="btn btn-danger btn-sm">
                <i class="fas fa-trash-alt"></i>&nbsp;Hapus
            </button>
        </div>
    </div>
    <div class="card-body">
        <?php
        $stm = viewdata('tbstimulus', array('idbank' => $_GET['id']));
        foreach ($stm as $st) :
            $stimulus = $st['stimulus'];
            $stimulus = str_replace('<p>', '<p class="m-0 p-0" style="text-align:justify;text-indent:45px">', $stimulus);
        ?>
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Stimulus Soal <?php echo $st['idstimulus']; ?></h3>
                    <div class="card-tools">
                        <a href="index.php?p=addstimulus&ids=<?php echo $st['idstimulus']; ?>" class="btn btn-tool btn-xs">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="index.php?p=tambahsoal&ids=<?php echo $st['idstimulus']; ?>" class="btn btn-tool btn-xs">
                            <i class="fas fa-plus-circle"></i> Soal
                        </a>
                        <button class="btn btn-tool btn-xs">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row p-3">
                        <?php
                        echo $stimulus;
                        ?>
                    </div>
                    <?php
                    if (!empty($st['gambar'])) :
                    ?>
                        <div class="row">
                            <img class="img img-fluid" src="../pictures/<?php echo $st['gambar']; ?>">
                        </div>
                    <?php endif ?>

                    <div class=" row">

                        <?php
                        $so = viewdata('tbsoal', array('idstimulus' => $st['idstimulus']));
                        ?>
                        <table width="100%">
                            <?php foreach ($so as $s) : ?>
                                <tr>
                                    <td width="2.5%" style="vertical-align:top">
                                        <?php echo $s['nomersoal'] . '.'; ?>
                                    </td>
                                    <td colspan="4">
                                        <?php echo $s['butirsoal']; ?>
                                    </td>
                                    <td style="vertical-align: top;" width="15%">
                                        <a href="index.php?p=editsoal&idb=<?php echo $s['idbutir']; ?>" class="btn btn-tool btn-xs">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button class="btn btn-tool btn-xs">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                                <!-- Pilihan Ganda Biasa -->
                                <?php if ($s['jnssoal'] == '1') : ?>
                                    <?php
                                    $ops = getisiopsi($s['idbutir']);
                                    foreach ($ops as $op) :
                                    ?>
                                        <tr>
                                            <td width="2.5%">
                                            </td>
                                            <td width="3.5%" style="vertical-align:top">
                                                <input type="radio" id="btnCeklis" name="opsi<?php echo $op['idbutir']; ?>" <?php echo ($op['benar'] == 1) ? "checked" : ""; ?>>
                                            </td>
                                            <td colspan="4" style="text-align:left">
                                                <?php echo $op['opsi']; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                                <!-- Pilihan Ganda Kompleks -->
                                <?php if ($s['jnssoal'] == '2') : ?>
                                    <?php
                                    $ops = getisiopsi($s['idbutir']);

                                    foreach ($ops as $op) :
                                    ?>
                                        <tr>
                                            <td width="2.5%">
                                            </td>
                                            <td width="3.5%">
                                                <input type="checkbox" id="btnCeklis" name="opsi<?php echo $op['idbutir']; ?>" <?php echo ($op['benar'] == 1) ? "checked" : ""; ?>>
                                            </td>
                                            <td colspan="4">
                                                <?php echo $op['opsi']; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                                <!-- Benar Salah -->
                                <?php if ($s['jnssoal'] == '3') : ?>
                                    <tr>
                                        <td width="2.5%">
                                        </td>
                                        <td width="65%" style="text-align:center;border:solid 1px; font-weight:bold" colspan="2">Pernyataan</td>
                                        <td width="7.5%" style="text-align:center;border:solid 1px; font-weight:bold">Benar</td>
                                        <td width="7.5%" style="text-align:center;border:solid 1px; font-weight:bold">Salah</td>
                                    </tr>
                                    <?php
                                    $ops = getisiopsi($s['idbutir']);
                                    foreach ($ops as $op) :
                                    ?>
                                        <tr>
                                            <td width="2.5%">
                                            </td>
                                            <td style="border:solid 1px; font-weight:normal" colspan="2">
                                                <?php echo $op['opsi']; ?>
                                            </td>
                                            <td width="7.5%" style="text-align:center;border:solid 1px; font-weight:normal">
                                                <input type="checkbox" id="btnCeklis" name="opsi<?php echo $op['idbutir']; ?>" <?php echo ($op['benar'] == 1) ? "checked" : ""; ?>>
                                            </td>
                                            <td width="7.5%" style="text-align:center;border:solid 1px; font-weight:normal">
                                                <input type="checkbox" id="btnCeklis" name="opsi<?php echo $op['idbutir']; ?>" <?php echo ($op['benar'] == 0) ? "checked" : ""; ?>>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                                <?php
                                /* Soal Menjodohkan */
                                if ($s['jnssoal'] == '4') : ?>
                                    <tr>
                                        <td valign="top" width="2.5%">&nbsp;</td>
                                        <td width="40%" style="text-align:center;padding:5px;border:solid 1px;" colspan="2"><strong>Pernyataan</strong></td>
                                        <td style="text-align:center;padding:5px;border:solid 1px;"><strong>Jawaban</strong></td>
                                    </tr>
                                    <?php
                                    $ops = getisiopsi($s['idbutir']);
                                    foreach ($ops as $op) : ?>
                                        <tr>
                                            <td valign="top" width="2.5%">&nbsp;</td>
                                            <td width="30%" style="padding:5px;border:solid 1px;" colspan="2"><?php echo $op['opsi']; ?></td>
                                            <td style="padding:5px;border:solid 1px;"><?php echo $op['opsialt']; ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                            <?php endforeach ?>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>