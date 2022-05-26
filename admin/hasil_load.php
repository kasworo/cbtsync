<?php
include "dbfunction.php";

if ($_POST['aksi'] == 'load') :
    if (empty($_POST['h'])) {
        $urut = 1;
    } else {
        $urut = $_POST['h'];
    }
    $lowerLimit = $urut - 1;
    $sqcek = "SELECT idjwb FROM tbjawaban jwb INNER JOIN tbsoal so USING(idbutir) INNER JOIN tbstimulus st USING(idstimulus) WHERE idsiswa='$_POST[idsw]' AND idset='$_POST[idset]'";
    $rowCount = cquery($sqcek);

    $sql = "SELECT*FROM tbjawaban jw INNER JOIN tbsoal so USING(idbutir) INNER JOIN tbstimulus USING(idstimulus)  WHERE jw.idsiswa='$_POST[idsw]' AND jw.idset='$_POST[idset]' LIMIT 1 OFFSET $lowerLimit";
    $so = vquery($sql)[0];
?>
    <style type="text/css">
        .arab {
            src: url('../assets/webfonts/lpmq.ttf');
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
    <link rel="stylesheet" href="../ujian.css">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" id="nomor" style="font-weight:bold;color:red"></h3>
        </div>
        <div class=" card-body">
            <div class="container" id="lembaransoal">
                <div class="col-sm-12" id="lembaran">
                    <?php
                    $idbank = $so['idbank'];
                    $stimulus = $so['stimulus'];
                    $stimulus = str_replace("<img src=", "<img class='img img-fluid mx-auto d-block' id='imageGallery' src=", $stimulus);
                    $stimulus = str_replace('<p>', '<p class="m-0 p-0" style="text-align:justify;text-indent:30px">', $stimulus);

                    $idsoal = $so['idbutir'];
                    $huruf = $so['huruf'];
                    $jnssoal = $so['jnssoal'];
                    $str = str_replace("../../pictures/", "../pictures/", $so['butirsoal']);
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
                            <img class="img img-fluid" src="../pictures/<?php echo $so['gambar']; ?>">
                        <?php endif ?>
                    </div>
                    <!-- Soal Pilihan Ganda Biasa-->
                    <?php if ($jnssoal == '1') : ?>
                        <div class="form-group mb-2">
                            <?php
                            $butir = str_replace("<img src=", "<img class='img img-fluid mx-auto d-block' src=", $str);
                            echo $butir;
                            ?>
                        </div>
                        <div class="form-group" style="padding-left:5px">
                            <?php if ($so['headeropsi'] == NULL) : ?>
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
                                                $qopsi = "SELECT opsi, benar FROM tbopsi WHERE idopsi='$idopsi'";
                                                $op = vquery($qopsi)[0];
                                                $ops = str_replace("/cbt/pictures/", "pictures/", $op['opsi']);
                                                $opsi = str_replace("<img src=", "<img class='img img-fluid img-responsive' id='imageGallery' src=", $ops);
                                                echo $opsi;
                                                if ($op['benar'] == '1') {
                                                    echo "&nbsp;<i class='fa fa-check-circle' aria-hidden='true' style='color:green'></i>";
                                                } else {
                                                    echo "&nbsp;<i class='fa fa-times-circle' aria-hidden='true' style='color:red'></i>";
                                                }
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
                                                    $qopsi = "SELECT opsi, opsialt, benar FROM tbopsi WHERE idopsi='$idopsi'";
                                                    $op = vquery($qopsi)[0];
                                                    $ops = str_replace("/cbt/pictures/", "pictures/", $op['opsi']);
                                                    $opsi = str_replace("<img src=", "<img class='img img-fluid img-responsive' id='imageGallery' src=", $ops);
                                                    echo $opsi;

                                                    $opsa = str_replace("/cbt/pictures/", "pictures/", $op['opsialt']);
                                                    $opsialt = str_replace("<img src=", "<img class='img img-fluid img-responsive' id='imageGallery' src=", $opsa);
                                                    ?>
                                                <td valign="top">
                                                    <?php echo $opsialt;
                                                    if ($op['benar'] == '1') {
                                                        echo "&nbsp;<i class='fa fa-check-circle' aria-hidden='true' style='color:green'></i>";
                                                    } else {
                                                        echo "&nbsp;<i class='fa fa-times-circle' aria-hidden='true' style='color:red'></i>";
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            <?php endif ?>
                            <hr />
                            <div class="form-group mb-2 mt-2">
                                <label style="color:red;font-family:verdana;">Skor Perolehan: <?php echo number_format($so['skor'], 2, ',', '.'); ?></label>
                            </div>
                        </div>
                    <?php endif ?>

                    <!-- Soal Pilihan Ganda Kompleks-->
                    <?php if ($jnssoal == '2') : ?>
                        <div class=" form-group mb-2">
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
                                            $qopsi = "SELECT opsi, benar FROM tbopsi WHERE idopsi='$idopsi'";
                                            $op = vquery($qopsi)[0];
                                            $ops = str_replace("/cbt/pictures/", "pictures/", $op['opsi']);
                                            $opsi = str_replace("<img src=", "<img class='img img-fluid img-responsive' src=", $ops);
                                            echo $opsi;
                                            if ($op['benar'] == '1') {
                                                echo "&nbsp;<i class='fa fa-check-circle' aria-hidden='true' style='color:green'></i>";
                                            } else {
                                                echo "&nbsp;<i class='fa fa-times-circle' aria-hidden='true' style='color:red'></i>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </table>
                        </div>
                        <hr />
                        <div class="form-group mb-2 mt-2">
                            <label style="color:red;font-family:verdana;">Skor Perolehan: <?php echo number_format($so['skor'], 2, ',', '.'); ?></label>
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
                                    <?php
                                    $hdr = explode(",", $so['headeropsi']);
                                    $kol = count($hdr);
                                    ?>
                                    <thead>
                                        <?php for ($i = 0; $i < $kol; $i++) :
                                            if ($i == $kol - 1 || $i == $kol - 2) {
                                                $styl = 'text-align:center;width:12.5%';
                                            } else {
                                                $styl = 'text-align:center;';
                                            }
                                        ?>
                                            <th style="<?php echo $styl; ?>"><?php echo $hdr[$i]; ?></th>
                                        <?php endfor ?>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($kol > 3) :
                                            foreach ($getopsi as $id => $idopsi) : ?>
                                                <tr>
                                                    <td valign="top">
                                                        <?php
                                                        $qopsi = "SELECT opsi,opsialt, benar FROM tbopsi WHERE idopsi='$idopsi'";
                                                        $op = vquery($qopsi)[0];
                                                        $opsi = $op['opsi'];
                                                        $opsialt = $op['opsialt'];
                                                        echo $opsi;
                                                        ?>
                                                    </td>
                                                    <td valign="top">
                                                        <?php
                                                        echo $opsialt;
                                                        if ($op['benar'] == '1') {
                                                            echo "&nbsp;<i class='fa fa-check-circle' aria-hidden='true' style='color:green'></i>";
                                                        } else {
                                                            echo "&nbsp;<i class='fa fa-times-circle' aria-hidden='true' style='color:red'></i>";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="text-align:center">
                                                        <input id="BtnBenar<?php echo $idopsi; ?>" type="radio" name="opsijwb<?php echo $idopsi; ?>" value="1" <?php echo (in_array($idopsi, $getbenar)) ? "checked" : ""; ?>>
                                                    </td>
                                                    <td style="text-align:center">
                                                        <input id="BtnSalah<?php echo $idopsi; ?>" type="radio" name="opsijwb<?php echo $idopsi; ?>" value="0" <?php echo (in_array($idopsi, $getsalah)) ? "checked" : ""; ?>>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                            <?php else :
                                            foreach ($getopsi as $id => $idopsi) : ?>
                                                <tr>
                                                    <td valign="top">
                                                        <?php
                                                        $qopsi = "SELECT opsi,opsialt, benar FROM tbopsi WHERE idopsi='$idopsi'";
                                                        $op = vquery($qopsi)[0];
                                                        $opsi = $op['opsi'];
                                                        $opsialt = $op['opsialt'];
                                                        echo $opsi;
                                                        if ($op['benar'] == '1') {
                                                            echo "&nbsp;<i class='fa fa-check-circle' aria-hidden='true' style='color:green'></i>";
                                                        } else {
                                                            echo "&nbsp;<i class='fa fa-times-circle' aria-hidden='true' style='color:red'></i>";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="text-align:center">
                                                        <input id="BtnBenar<?php echo $idopsi; ?>" type="radio" name="opsijwb<?php echo $idopsi; ?>" value="1" <?php echo (in_array($idopsi, $getbenar)) ? "checked" : ""; ?>>
                                                    </td>
                                                    <td style="text-align:center">
                                                        <input id="BtnSalah<?php echo $idopsi; ?>" type="radio" name="opsijwb<?php echo $idopsi; ?>" value="0" <?php echo (in_array($idopsi, $getsalah)) ? "checked" : ""; ?>>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        <?php endif ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr />
                            <div class="form-group mb-2 mt-2">
                                <label style="color:red;font-family:verdana;">Skor Perolehan: <?php echo number_format($so['skor'], 2, ',', '.'); ?></label>
                            </div>
                        </div>
                    <?php endif ?>

                    <!-- Soal Pilihan Menjodohkan-->
                    <?php if ($jnssoal == '4') : ?>
                        <div class="form-group mb-2">
                            <?php
                            $butir = str_replace("<img src=", "<img class='img img-fluid mx-auto d-block' id='imageGallery' src=", $str);
                            echo $butir;
                            ?>
                        </div>
                        <hr />
                        <div class="form-group row mb-2">
                            <div class="col-sm-4">
                                <div class="container">
                                    <?php $hdr = explode(",", $so['headeropsi']); ?>
                                    <div class="table-responsive mt-2">
                                        <table class="table table-bordered table-striped table-condensed table-sm" width="100%">
                                            <tr>
                                                <td colspan="2" style="text-align: center;border-right:none;font-weight:bold"><?php echo $hdr[0]; ?></td>
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
                                                <td colspan="2" style="text-align: center;border-right:none;font-weight:bold"><?php echo $hdr[1]; ?></td>
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
                                                $opsa = str_replace("../pictures/", "pictures/", $opa['opsialt']);
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
                            $qjwb = "SELECT dari, huruf FROM tbmatching WHERE idbutir='$idsoal' AND idsiswa='$_POST[idsw]'";
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
                                    <link rel="stylesheet" href="../fieldsLinker.css">
                                    <script src="../fieldsLinker.js"></script>
                                    <div class="form-group row ml-4" id="original" class="text-center" style="display:block;"></div>
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
                                            fieldLinks = $("#original").fieldsLinker("init", inputOri);

                                        });
                                    </script>
                                </div>
                            </div>

                        </div>
                        <hr />
                        <div class="form-group mb-2 mt-2">
                            <label style="color:red;font-family:verdana;">Skor Perolehan: <?php echo number_format($so['skor'], 2, ',', '.'); ?></label>
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
                            })
                        </script>
                        <hr />
                        <div class="form-group mb-2 mt-2">
                            <label style="color:red;font-family:verdana;">Skor Perolehan: <?php echo number_format($so['skor'], 2, ',', '.'); ?></label>
                        </div>
                </div>
            <?php endif ?>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="form-group row mb-auto ml-auto">
            <?php
            $prev = $urut - 1;
            $next = $urut + 1;
            if ($prev >= 1) : ?>
                <div class="col-sm-3 mb-2">
                    <button data-id="<?php echo $prev; ?>" class="btn btn-sm btn-secondary btn-block col-sm-8 btnPrev">
                        <i class="fas fa-arrow-circle-left"></i>&nbsp;<strong>Sebelumnya</strong>
                    </button>
                </div>
            <?php endif ?>
            <div class="col-sm-3">
                <?php if ($next <= $rowCount) : ?>
                    <button data-id="<?php echo $next; ?>" class="btn btn-sm btn-primary btn-block col-sm-8 btnNext">
                        <strong>Berikutnya</strong>&nbsp;<i class="fas fa-arrow-circle-right"></i>
                    </button>
                <?php else : ?>
                    <button data-id="1" class="btn btn-sm btn-danger btn-block col-sm-8 btnNext">
                        <strong>Selesai</strong>&nbsp;<i class="fas fa-sign-out-alt"></i>
                    </button>
                <?php endif ?>
            </div>
        </div>
    </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".btnNext").click(function() {
                let urut = $(this).data('id')
                tampilsoal(urut)
            })
            $(".btnPrev").click(function() {
                let urut = $(this).data('id')
                tampilsoal(urut)
            })

        })
        $(".ViewHasil").click(function() {
            let idsw = "<?php echo $_POST['idsw']; ?>"
            let idset = "<?php echo $_POST['idset']; ?>";
            $.ajax({
                url: 'hasil_opsi.php',
                type: 'post',
                data: 'id=' + idsw + '&ids=' + idset,
                success: function(data) {
                    $(".fetched-data").html(data)
                }
            })
        })
    </script>
<?php endif ?>