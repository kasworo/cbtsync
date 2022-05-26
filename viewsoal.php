<?php
include "dbfunction.php";
if (empty($_POST['h'])) {
    $urut = 1;
} else {
    $urut = $_POST['h'];
}
$dlog = array('hal' => $urut);
$join = array('tbsetingujian' => 'idjadwal');
$where = array('idsiswa' => $_COOKIE['pst'], 'idset' => $_POST['set']);
editdata('tblogpeserta', $dlog, $join, $where);

$sqcek = "SELECT idjwb FROM tbjawaban WHERE idsiswa='$_COOKIE[pst]' AND idset='$_POST[set]'";
$rowCount = cquery($sqcek);
$lowerLimit = $urut - 1;

$sql = "SELECT*FROM tbjawaban jw INNER JOIN tbsoal so USING(idbutir) INNER JOIN tbstimulus USING(idstimulus)  WHERE jw.idsiswa='$_COOKIE[pst]' AND jw.idset='$_POST[set]' LIMIT 1 OFFSET $lowerLimit";
$so = vquery($sql)[0];
?>
<style type="text/css">
    .arab {
        src: url('assets/webfonts/lpmq.ttf');
        text-align: right;
        line-height: 45px;
        font-family: "LPMQ Isep Misbah";
        font-size: 18pt;        
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
        background-image: url(./assets/img/cek.png);
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
        background-image: url(./assets/img/ceklis.png);
    }

    input[type="checkbox"]:hover {
        filter: brightness(98%);
    }

    input[type="checkbox"]:disabled {
        background: #e6e6e6;
        opacity: 0.6;
        pointer-events: none;
    }

    .zoom {
        display: inline-block;
        position: relative;
    }

    .zoom:after {
        content: '';
        display: block;
        width: 33px;
        height: 33px;
        position: absolute;
        top: 0;
        right: 0;
        background: url(icon.png);
    }

    .zoom img {
        display: block;
    }
</style>
<script src="assets/js/jquery.zoom.js"></script>
<script>
    $(document).ready(function() {
        $('#zoom').zoom()
        // $('#zoom').zoom({
        //     on: 'toggle'
        // })
    })
</script>
<div class="card-body">
    <div class="container">
        <div class="col-sm-12" id="lembaransoal">
            <div class="form-group" id="lembaran">
                <?php
                $idbank = $so['idbank'];
                $stimulus = $so['stimulus'];
                $stimulus = str_replace('<p>', '<p class="m-0 p-0" style="text-align:justify;text-indent:30px">', $stimulus);
                //$stimulus = str_replace("<img src=", "<img id='zoom' src=", $stimulus);
                $idsoal = $so['idbutir'];
                $huruf = $so['huruf'];
                $jnssoal = $so['jnssoal'];
                $mode = $so['modeopsi'];
                $str = str_replace("/cbt/pictures/", "pictures/", $so['butirsoal']);
                $getopsi = explode(",", $so['viewopsi']);
                $getopsialt = explode(",", $so['viewopsialt']);
                $getbenar = explode(",", $so['jwbbenar']);
                $getsalah = explode(",", $so['jwbsalah']);
                ?>
                <div class="form-group mb-3">
                    <?php echo $stimulus;
                    if (!empty($so['gambar'])) :
                    ?>
                        <br />
                        <img class="img img-fluid" src="pictures/<?php echo $so['gambar']; ?>">
                    <?php endif ?>
                </div>
                <!-- Soal Pilihan Ganda Biasa-->
                <?php if ($jnssoal == '1') : ?>
                    <div class="form-group mb-2">
                        <?php
                        $butir = str_replace("<img src=", "<img class='img img-fluid mx-auto d-block' id='imageGallery' src=", $str);
                        echo $butir;
                        ?>
                    </div>
                    <div class="form-group" style="padding-left:5px">
                    <?php if($so['headeropsi']==NULL):?>
                        <table cellpadding="5px auto" cellspacing="2px">
                            <?php foreach ($getopsi as $id => $idopsi) : ?>
                                <tr valign="top">
                                    <td valign="top">
                                        <?php
                                        if ($id == 0) {
                                            $val = 'A';
                                        } else if ($id == 1) {
                                            $val = 'B';
                                        } else if ($id == 2) {
                                            $val = 'C';
                                        } else if ($id == 3) {
                                            $val = 'D';
                                        } else {
                                            $val = 'E';
                                        }
                                        ?>
                                        <div class="cc-selector">
                                            <input id="<?php echo $val; ?>" class="opsi" type="radio" name="opsijwb" value="<?php echo $idopsi; ?>" <?php echo ($idopsi == $so['jwbbenar']) ? 'checked' : ''; ?>>
                                            <label class="drinkcard-cc <?php echo $val; ?>" for="<?php echo $val; ?>"></label>
                                        </div>
                                    </td>
                                    <td valign="top">
                                        <?php
                                        $qopsi = "SELECT opsi FROM tbopsi WHERE idopsi='$idopsi'";
                                        $op = vquery($qopsi)[0];
                                        $ops = str_replace("/cbt/pictures/", "pictures/", $op['opsi']);
                                        $opsi = str_replace("<img src=", "<img class='img img-fluid img-responsive' id='imageGallery' src=", $ops);
                                        echo $opsi;
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </table>
                        <?php else :
                            $hdr = explode(",", $so['headeropsi']);
                        ?>
                           <table class="table table-bordered table-sm table-condensed">
                                        <thead>
                                            <th width="7.5%" style="text-align:center">Pilihan</th>
                                            <th style="text-align:center"><?php echo $hdr[0]; ?></th>
                                            <th style="text-align:center"><?php echo $hdr[1]; ?></th>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($getopsi as $id => $idopsi) : ?>
                                <tr>
                                    <td valign="top" style="text-align:center;">
                                        <?php
                                        if ($id == 0) {
                                            $val = 'A';
                                        } else if ($id == 1) {
                                            $val = 'B';
                                        } else if ($id == 2) {
                                            $val = 'C';
                                        } else if ($id == 3) {
                                            $val = 'D';
                                        } else {
                                            $val = 'E';
                                        }
                                        ?>
                                        <div class="cc-selector">
                                            <input id="<?php echo $val; ?>" class="opsi" type="radio" name="opsijwb" value="<?php echo $idopsi; ?>" <?php echo ($idopsi == $so['jwbbenar']) ? 'checked' : ''; ?>>
                                            <label class="drinkcard-cc <?php echo $val; ?>" for="<?php echo $val; ?>"></label>
                                        </div>
                                    </td>
                                    <td valign="top">
                                        <?php
                                        $qopsi = "SELECT opsi,opsialt FROM tbopsi WHERE idopsi='$idopsi'";
                                        $op = vquery($qopsi)[0];
                                        $ops = str_replace("/cbt/pictures/", "pictures/", $op['opsi']);
                                        $opsi = str_replace("<img src=", "<img class='img img-fluid img-responsive' id='imageGallery' src=", $ops);
                                        echo $opsi;

                                        $opsa = str_replace("/cbt/pictures/", "pictures/", $op['opsialt']);
                                        $opsialt = str_replace("<img src=", "<img class='img img-fluid img-responsive' id='imageGallery' src=", $opsa);
                                    ?>
                                    <td valign="top">
                                        <?php echo $opsialt;?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                        <?php endif?>
                        </div>
                <?php endif ?>
                

                <!-- Soal Pilihan Ganda Kompleks-->
                <?php if ($jnssoal == '2') : ?>
                    <div class="form-group mb-2">
                        <?php
                        $butir = str_replace("<img src=", "<img class='img img-fluid mx-auto d-block' id='imageGallery' src=", $str);
                        echo $butir;
                        ?>
                    </div>
                    <div class="form-group" style="padding-left:5px">
                        <table cellpadding="5px auto" cellspacing="2px">
                            <?php foreach ($getopsi as $id => $idopsi) : ?>
                                <tr>
                                    <td valign="top">
                                        <input id="jawab" class="opsi2" type="checkbox" name="opsijwb[]" value="<?php echo $idopsi; ?>" <?php echo in_array($idopsi, $getbenar) ? 'checked' : ''; ?>>
                                    </td>
                                    <td valign="top">
                                        <?php
                                        $qopsi = "SELECT opsi FROM tbopsi WHERE idopsi='$idopsi'";
                                        $op = vquery($qopsi)[0];
                                        $ops = str_replace("/cbt/pictures/", "pictures/", $op['opsi']);
                                        $opsi = str_replace("<img src=", "<img class='img img-fluid img-responsive' src=", $ops);
                                        echo $opsi;
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </table>
                    </div>
                <?php endif ?>

                <!-- Soal Pilihan Benar Atau Salah-->
                <?php if ($jnssoal == '3') : ?>
                    <div class="form-group mb-2">
                        <?php
                        $butir = str_replace("<img src=", "<img class='img img-fluid mx-auto d-block' id='imageGallery' src=", $str);
                        echo $butir;
                        ?>
                    </div>
                    <div class="form-group" style="padding-left:5px">
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
                                background-image: url(./assets/img/cek.png);
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
                                background-image: url(./assets/img/ceklis.png);
                            }

                            input[type="radio"]:hover {
                                filter: brightness(88%);
                            }

                            input[type="radio"]:disabled {
                                background: #e6e6e6;
                                opacity: 0.6;
                                pointer-events: none;
                            }
                        </style>
                        <div class="table-responsive-sm">
                            <table class="table table-condensed table-striped table-sm table-bordered" width="100%">
                                <!-- <thead>
                                    <th style="text-align:center;">Pernyataan</th>
                                    <th style="text-align:center;width:12.5%">Benar</th>
                                    <th style="text-align:center;width:12.5%">Salah</th>
                                </thead> -->                                
                                <?php 
                                $hdr=explode(",",$so['headeropsi']);
                                $kol=count($hdr);
                                ?>
                                <thead>
                                <?php for ($i=0;$i<$kol;$i++):
                                    if($i==$kol-1 || $i==$kol-2){
                                        $styl='text-align:center;width:12.5%';
                                    }
                                    else {
                                        $styl='text-align:center;';
                                    }
                                ?>
                                    <th style="<?php echo $styl;?>"><?php echo $hdr[$i];?></th>
                                <?php endfor ?>
                                </thead>
                                <tbody>
                                <?php foreach ($getopsi as $id => $idopsi) : ?>
                                    <tr>
                                        <td valign="top">
                                            <?php
                                            $qopsi = "SELECT opsi,opsialt FROM tbopsi WHERE idopsi='$idopsi'";
                                            $op = vquery($qopsi)[0];
                                            $opsi = $op['opsi'];
                                            $opsialt=$op['opsialt'];
                                            echo $opsi;
                                            ?>
                                        </td>
                                        <td style="text-align:center">
                                            <input id="BtnBenar<?php echo $idopsi; ?>" type="radio" name="opsijwb<?php echo $idopsi; ?>" value="1" <?php echo (in_array($idopsi, $getbenar)) ? "checked" : ""; ?>>
                                        </td>
                                        <td style="text-align:center">
                                            <input id="BtnSalah<?php echo $idopsi; ?>" type="radio" name="opsijwb<?php echo $idopsi; ?>" value="0" <?php echo (in_array($idopsi, $getsalah)) ? "checked" : ""; ?>>
                                        </td>
                                    </tr>
                                    <script type="text/javascript">
                                        $("#BtnBenar<?php echo $idopsi; ?>").click(function() {
                                            let data = new FormData()
                                            data.append('soal', "<?php echo $idsoal; ?>")
                                            data.append('opsi', "<?php echo $idopsi; ?>")
                                            data.append('jwb', $(this).val())
                                            data.append('aksi', 'simpan')
                                            $.ajax({
                                                type: "POST",
                                                url: "simpan.php",
                                                data: data,
                                                processData: false,
                                                contentType: false,
                                                cache: false,
                                                timeout: 8000,
                                                success: function(respons) {}
                                            })
                                        })
                                        $("#BtnSalah<?php echo $idopsi; ?>").click(function() {
                                            let data = new FormData()
                                            data.append('soal', "<?php echo $idsoal; ?>")
                                            data.append('opsi', "<?php echo $idopsi; ?>")
                                            data.append('jwb', $(this).val())
                                            data.append('aksi', 'simpan')
                                            $.ajax({
                                                type: "POST",
                                                url: "simpan.php",
                                                data: data,
                                                processData: false,
                                                contentType: false,
                                                cache: false,
                                                timeout: 8000,
                                                success: function(respons) {}
                                            })
                                        })
                                    </script>
                                <?php endforeach ?>
                                    </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif ?>

                <!-- Soal Pilihan Menjodohkan-->
                <?php if ($jnssoal == '4') : ?>
                    <div class="form-group mb-2">
                        <strong>
                            <em>
                                <?php
                                $butir = str_replace("<img src=", "<img class='img img-fluid mx-auto d-block' id='imageGallery' src=", $str);
                                echo $butir;
                                ?>
                            </em>
                        </strong>
                    </div>
                    <hr />
                    <div class="form-group row mb-2">
                        <div class="col-sm-4">
                            <div class="container">
                                <?php  $hdr=explode(",",$so['headeropsi']);?>
                                <div class="table-responsive mt-2">
                                    <table class="table table-bordered table-striped table-condensed table-sm" width="100%">
                                        <tr>
                                            <td colspan="2" style="text-align: center;border-right:none;font-weight:bold"><?php echo $hdr[0];?></td>
                                        </tr>
                                        <?php
                                        $hrfso = [];
                                        foreach ($getopsi as $id => $idopsi) :
                                            switch ($id) {
                                                case 0:
                                                    $awal = 'a';
                                                    break;
                                                case 1:
                                                    $awal = 'b';
                                                    break;
                                                case 2:
                                                    $awal = 'c';
                                                    break;
                                                case 3:
                                                    $awal = 'd';
                                                    break;
                                                case 4:
                                                    $awal = 'e';
                                                    break;
                                                case 5:
                                                    $awal = 'f';
                                                    break;
                                                case 6:
                                                    $awal = 'g';
                                                    break;
                                                case 7:
                                                    $awal = 'h';
                                                    break;
                                                case 8:
                                                    $awal = 'i';
                                                    break;
                                                case 9:
                                                    $awal = 'j';
                                                    break;
                                            }
                                            //$hrfso[] = "'" . $awal . "'";
                                            $hrfso[] = $awal;

                                            $qopsi = "SELECT opsi FROM tbopsi WHERE idopsi='$idopsi'";
                                            $op = vquery($qopsi)[0];
                                            $soale = $op['opsi'];
                                        ?>
                                            <tr>
                                                <td width="12.5%" style="text-align: center;border-right:none;"><?php echo $awal . '.'; ?></td>
                                                <td><?php echo $soale; ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="container">
                                <div class="table-responsive mt-2">
                                    <table class="table table-bordered table-striped table-condensed table-sm" width="100%">
                                        <tr>
                                            <td colspan="2" style="text-align: center;border-right:none;font-weight:bold"><?php echo $hdr[1];?></td>
                                        </tr>
                                        <?php
                                        $hrfopsi = [];
                                        foreach ($getopsialt as $ida => $idopsialt) :
                                            switch ($ida) {
                                                case 0:
                                                    $hrfe = 'A';
                                                    break;
                                                case 1:
                                                    $hrfe = 'B';
                                                    break;
                                                case 2:
                                                    $hrfe = 'C';
                                                    break;
                                                case 3:
                                                    $hrfe = 'D';
                                                    break;
                                                case 4:
                                                    $hrfe = 'E';
                                                    break;
                                                case 5:
                                                    $hrfe = 'F';
                                                    break;
                                            }
                                            $hrfopsi[] = $hrfe;
                                            // var_dump($hrfopsi);
                                            $qopal = "SELECT idopsi, opsialt FROM tbopsi WHERE idopsi='$idopsialt'";
                                            $opa = vquery($qopal)[0];
                                            $opals = $opa['idopsi'];
                                            $opsa = str_replace("/cbt/pictures/", "pictures/", $opa['opsialt']);
                                            $opsa = str_replace("<img src=", "<img class='img img-fluid img-responsive' id='imageGallery' src=", $opsa);

                                        ?>
                                            <tr>
                                                <td width="12.5%" style="text-align: center;border-right:none"><?php echo $hrfe . '.'; ?></td>
                                                <td><?php echo $opsa; ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php
                        // Untuk LineKonektor
                        $hurufso = json_encode($hrfso);
                        $hurufop = json_encode($hrfopsi);
                        //var_dump($hurufso);
                        $linenya = [];
                        $qjwb = "SELECT dari, huruf FROM tbmatching WHERE idbutir='$idsoal' AND idsiswa='$_COOKIE[pst]'";
                        $jwb = vquery($qjwb);
                        foreach ($jwb as $jw) {
                            $linenya[] = array(
                                'from' => $jw['dari'],
                                'to' => $jw['huruf']
                            );
                        }
                        $isilinknya = json_encode($linenya);
                        ?>

                        <div class="col-sm-4">
                            <div class="container">
                                <link rel="stylesheet" href="./fieldsLinker.css">
                                <script src="fieldsLinker.js"></script>
                                <div class="form-group row ml-4" id="original" class="text-center" style="display:block;"></div>
                                <hr>
                                <div class="form-group row ml-4">
                                    <button class="btn btn-sm btn-primary" id="simpanhsl">
                                        <i class="fas fa-save"></i>&nbsp;Simpan
                                    </button>
                                    <button class="btn btn-sm btn-danger ml-3" id="hapushsl">
                                        <i class="fas fa-trash-alt"></i>&nbsp;Hapus
                                    </button>
                                </div>
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        let fieldLinks;
                                        let inputOri;
                                        inputOri = {
                                            "localization": {},
                                            "options": {
                                                "associationMode": "oneToOne", // oneToOne,manyToMany
                                                "lineStyle": "square-ends",
                                                // "buttonErase": "<i class='fas fa-trash-alt'></i> Hapus",
                                                "displayMode": "original",
                                                "whiteSpace": "normal", //normal,nowrap,pre,pre-wrap,pre-line,break-spaces default => nowrap
                                                "mobileClickIt": true
                                            },
                                            "Lists": [{
                                                    //"name": "Columns in files",
                                                    "list": <?php echo $hurufso; ?>
                                                },
                                                {
                                                    "name": "",
                                                    "list": <?php echo $hurufop; ?>
                                                }
                                            ],
                                            //"existingLinks": [{ "from": "lastName", "to": "last_name" }, { "from": "firstName", "to": "first_name" }, { "from": "role", "to": "jobTitle" }]
                                            "existingLinks": <?php echo $isilinknya; ?>
                                        };


                                        $("#simpanhsl").on("click", function() {
                                            let results = fieldLinks.fieldsLinker("getLinks");
                                            let soal = "<?php echo $idsoal; ?>";
                                            datane = JSON.stringify(results["links"])
                                            $.ajax({
                                                type: "POST",
                                                url: "simpan.php",
                                                dataType: "json",
                                                data: {
                                                    'aksi': 'simpan',
                                                    'soal': soal,
                                                    'jawaban': datane
                                                }
                                            });
                                        });
                                        fieldLinks = $("#original").fieldsLinker("init", inputOri);

                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                <?php endif ?>

                <!-- Soal Pilihan Isian Singkat -->
                <?php if ($jnssoal == '5') : ?>
                    <div class="form-group mb-2">
                        <?php
                        $butir = str_replace("&hellip;.", "....", $str);
                        $butir = str_replace("&hellip;", "....", $butir);
                        $butir = str_replace("....", "<input type='text' class='jwbessai col-sm-3 col-md-2 col-lg-2 col-xs-4' style='width:60%;margin-left:10px;border:2.5px solid red'", $butir);
                        echo $butir;
                        ?>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $(".jwbessai").val("<?php echo $so['jwbbenar']; ?>")
                            //$(".jwbessai").focus();
                        })
                    </script>
                <?php endif ?>
            </div>
        </div>
    </div>
    <br />
    <div clas="form-group row mt-10">
        <?php
        $qhc = "SELECT g.nama, bs.tglbuat FROM tbbanksoal bs INNER JOIN tbgtk g USING(idgtk) WHERE bs.idbank='$idbank'";
        $g = vquery($qhc)[0];
        $thn = substr($g['tglbuat'], 0, 4);
        ?>
        <span style="font-size:10pt"><strong><?php echo $g['nama']; ?></strong> &copy; <?php echo $thn; ?></span>
    </div>
</div>
<div class="card-footer">
    <div class="form-group row mb-2">
        <?php
        $prev = $urut - 1;
        $next = $urut + 1;
        ?>
        <div class="col-sm-3 mb-2">
            <?php if ($prev == 0) : ?>
                <button disabled class="btn btn-sm btn-default btn-block col-sm-8"><i class="fas fa-arrow-circle-left"></i>&nbsp;<strong>Sebelumnya</strong></button>
            <?php else : ?>
                <button data-id="<?php echo $prev; ?>" class="btn btn-sm btn-secondary btn-block col-sm-8 btnPrev"> <i class="fas fa-arrow-circle-left"></i>&nbsp;<strong>Sebelumnya</strong></button>
            <?php endif ?>
        </div>
        <div class="col-sm-3 mb-2">
            <!-- <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-warning btn-sm btn-block btnRagu">
                    <input type="checkbox" autocomplete="off"> Ragu-ragu
                </label>
            </div> -->
        </div>
        <div class="col-sm-3">
            <?php if ($next <= $rowCount) : ?>
                <button data-id="<?php echo $next; ?>" class="btn btn-sm btn-primary btn-block col-sm-8 btnNext"><strong>Berikutnya</strong>&nbsp;<i class="fas fa-arrow-circle-right"></i></button>
            <?php else : ?>
                <button data-toggle="modal" data-target="#mySelesai" class="btn btn-sm btn-danger btn-block col-sm-8">
                    <strong>Selesai</strong>&nbsp;<i class="fas fa-sign-out-alt"></i>
                </button>
            <?php endif ?>
        </div>
    </div>
</div>

<div class="modal fade" id="mySelesai" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title center" style="color:blue;">Konfirmasi Tes</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-sm-10 offset-sm-1">
                    <h5>Anda Yakin?</h5>
                    <p>
                        Setelah Klik Tombol Selesai, Maka Tes Ini Akan Segera Berakhir dan Anda Tidak Dapat Mengerjakan
                        Paket Soal Ini Kembali.
                    </p>
                    <p>
                        Klik Setuju kemudian klik tombol selesai untuk mengakhiri tes.</p>
                    <div class="col-sm-12">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="chkselesai">
                            <label class="form-check-label" for="chkselesai"><b>Setuju</b></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button class="btn btn-danger" id="btnSelesai" disabled>
                    <i class="fas fa-sign-out-alt"></i><span id="rampung">&nbsp;Selesai</span>
                </button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    Mousetrap.bind('enter', function() {
        let urut = "<?php echo $next; ?>"
        let kabeh = "<?php echo $rowCount; ?>"
        if (urut <= kabeh) {
            tampilsoal(urut)
        } else {
            tampilsoal(kabeh)
        }
    });

    Mousetrap.bind('right', function() {
        let urut = "<?php echo $next; ?>"
        let kabeh = "<?php echo $rowCount; ?>"
        if (urut <= kabeh) {
            tampilsoal(urut)
        } else {
            tampilsoal(kabeh)
        }
    });

    Mousetrap.bind('left', function() {
        let urut = "<?php echo $prev; ?>"
        if (urut >= 1) {
            tampilsoal(urut)
        } else {
            tampilsoal(1)
        }
    });

    $(".jwbessai").mouseleave(function() {
        let data = new FormData();
        data.append('soal', "<?php echo $idsoal; ?>")
        data.append('opsi', $(this).val())
        data.append('aksi', 'simpan')
        $.ajax({
            type: "POST",
            url: "simpan.php",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 8000,
            success: function(respons) {}
        })
    });

    $(".opsi2").click(function() {
        let opsi = [];
        $(".opsi2").each(function() {
            if ($(this).is(":checked")) {
                opsi.push($(this).val())
            }
        })
        opsi = opsi.toString();
        if (opsi.length > 0) {
            let data = new FormData()
            data.append('soal', "<?php echo $idsoal; ?>")
            data.append('opsi', opsi)
            data.append('aksi', 'simpan')
            $.ajax({
                type: "POST",
                url: "simpan.php",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 8000,
                success: function() {}
            })
        }
    })

    $(".opsi").click(function() {
        let data = new FormData();
        data.append('soal', "<?php echo $idsoal; ?>")
        data.append('opsi', $(this).val())
        data.append('aksi', 'simpan')
        $.ajax({
            type: "POST",
            url: "simpan.php",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 8000,
            success: function(respons) {}
        })
    })

    $("#btnSelesai").click(function() {
        let data = new FormData();
        data.append('idset', "<?php echo $_POST['set']; ?>")
        $.ajax({
            type: "POST",
            url: "gethasil.php",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 8000,
            success: function(respons) {
                if (respons == 1) {
                    $(function() {
                        toastr.success('Nilai Tes Berhasil Disimpan!!', 'Terima Kasih', {
                            timeOut: 3000,
                            fadeOut: 3000,
                            onHidden: function() {
                                window.location.href = "index.php?p=end"
                            }
                        });
                    });
                }
                if (respons == 2) {
                    $(function() {
                        toastr.success('Nilai Tes Berhasil Diupdate!!', 'Informasi', {
                            timeOut: 3000,
                            fadeOut: 3000,
                            onHidden: function() {
                                window.location.href = "index.php?p=end"
                            }
                        });
                    });
                }
                if (respons == 0) {
                    $(function() {
                        toastr.error('Gagal Ditambahkan atau Diupdate!!', 'Mohon Maaf', {
                            timeOut: 3000,
                            fadeOut: 3000,
                            onHidden: function() {
                                window.location.reload();
                            }
                        });
                    });
                }
            }
        })
    })

    $("#chkselesai").click(function() {
        if ($(this).is(':checked')) {
            $('#btnSelesai').prop("disabled", false)
        } else {
            $('#btnSelesai').prop("disabled", true)
        }
    })

    $(".btnPrev").click(function() {
        let urut = $(this).data('id')
        tampilsoal(urut)
    })

    $(".btnNext").click(function() {
        let urut = $(this).data('id')
        tampilsoal(urut)
    })

    $(".butirsoal").jfontsize({
        btnMinusClasseId: '#jfontsize-m2',
        btnDefaultClasseId: '#jfontsize-d2',
        btnPlusClasseId: '#jfontsize-p2',
        btnMinusMaxHits: 2,
        btnPlusMaxHits: 4,
        sizeChange: 5
    });
</script>