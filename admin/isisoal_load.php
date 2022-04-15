<?php
include "dbfunction.php";
function getisiopsi($idbtr)
{
    $rows = viewdata('tbopsi', array('idbutir' => $idbtr));
    $butir = [];
    foreach ($rows as $row) {
        $butir[] = array(
            'idopsi' => $row['idopsi'],
            'opsi' => $row['opsi'],
            'benar' => $row['benar']
        );
    }
    return $butir;
}

if (isset($_POST['aksi']) && $_POST['aksi'] == 'load') :
    if (empty($_POST['h'])) {
        $urut = 1;
    } else {
        $urut = $_POST['h'];
    }

    $dlog = array('hal' => $urut);
    $where = array('idbank' => $_POST['id']);
    editdata('tbbanksoal', $dlog, '', $where);
    $sqlcek = "SELECT st.*, so.idbutir, so.butirsoal, so.modeopsi, so.jnssoal FROM tbstimulus st LEFT JOIN tbsoal so USING(idstimulus) INNER JOIN tbbanksoal bs USING(idbank)  WHERE bs.idbank='$_POST[id]'";
    $rowCount = cquery($sqlcek);
    $lowerLimit = $urut - 1;

    $sql = "SELECT st.*, so.idbutir, so.butirsoal, so.modeopsi, so.jnssoal, so.headeropsi FROM tbstimulus st LEFT JOIN tbsoal so USING(idstimulus) INNER JOIN tbbanksoal bs  USING(idbank) WHERE bs.idbank='$_POST[id]' LIMIT 1 OFFSET $lowerLimit";
    if (cquery($sql) > 0) :
        $so = vquery($sql)[0];
?>

        <style type="text/css">
            .arab {
                font-family: "Amiri Quran";
                font-size: 18pt;
                src: url('../assets/webfonts/amiri.ttf');
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

        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title" id="nomor" style="font-weight:bold;font-family:Times;font-size:14pt"></h3>
                <div class="card-tools">
                    <a href="index.php?p=addstimulus&ids=<?php echo $so['idstimulus']; ?>" class="btn btn-tool btn-xs" style="font-family:sans-serif;font-size:10pt">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="index.php?p=tambahsoal&ids=<?php echo $so['idstimulus']; ?>" class="btn btn-tool btn-xs" style="font-family:sans-serif;font-size:10pt">
                        <i class="fas fa-plus-circle"></i> Soal
                    </a>
                    <button class="btn btn-tool btn-xs" style="font-family:sans-serif;font-size:10pt">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="col-sm-12" id="lembaransoal">
                    <div class="form-group" id="lembaran">
                        <?php
                        $idbank = $so['idbank'];
                        $stimulus = $so['stimulus'];
                        $stimulus = str_replace('<p>', '<p class="m-0 p-0" style="text-align:justify;text-indent:32px">', $stimulus);
                        $stimulus = str_replace("<img src=", "<img class='img img-fluid mx-auto d-block' src=", $stimulus);
                        $idsoal = $so['idbutir'];
                        $jnssoal = $so['jnssoal'];
                        $mode = $so['modeopsi'];
                        $str = str_replace("/cbt/pictures/", "pictures/", $so['butirsoal']);
                        $getopsi = getisiopsi($so['idbutir']);
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
                                <br />
                                <a href="index.php?p=editsoal&idb=<?php echo $so['idbutir']; ?>" style="font-family: sans-serif;font-size:10pt">Edit</a> | <a href="#" style="font-family: sans-serif;font-size:10pt">Hapus</a>
                            </div>
                            <div class="form-group" style="padding-left:5px">
                                <table cellpadding="5px auto" cellspacing="2px">
                                    <?php foreach ($getopsi as $id => $op) : ?>
                                        <tr valign="top">
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
                                            <td valign="top">
                                                <div class="cc-selector">
                                                    <input id="<?php echo $val; ?>" class="opsi" type="radio" name="opsijwb" data-id="<?php echo $so['idbutir']; ?>" value="<?php echo $op['idopsi']; ?>" <?php echo ($op['benar'] == '1') ? 'checked' : ''; ?>>
                                                    <label class="drinkcard-cc <?php echo $val; ?>" for="<?php echo $val; ?>"></label>
                                                </div>
                                            </td>
                                            <td valign="top" for="<?php echo $val; ?>">
                                                <?php
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

                        <!-- Soal Pilihan Ganda Kompleks-->
                        <?php if ($jnssoal == '2') : ?>
                            <div class="form-group mb-2">
                                <?php
                                $butir = str_replace("<img src=", "<img class='img img-fluid mx-auto d-block' src=", $str);
                                echo $butir;
                                ?>
                                <br />
                                <a href="index.php?p=editsoal&idb=<?php echo $so['idbutir']; ?>" style="font-family: sans-serif;font-size:10pt">Edit</a> | <a href="#" style="font-family: sans-serif;font-size:10pt">Hapus</a>
                            </div>
                            <div class="form-group" style="padding-left:5px">
                                <table cellpadding="5px auto" cellspacing="2px">
                                    <?php foreach ($getopsi as $id => $op) : ?>
                                        <tr>
                                            <td valign="top">
                                                <input id="jawab" data-id="<?php echo $so['idbutir']; ?>" class="opsi2" type="checkbox" name="opsijwb[]" value="<?php echo $op['idopsi']; ?>" <?php echo ($op['benar'] == '1') ? 'checked' : ''; ?>>
                                            </td>
                                            <td valign="top">
                                                <?php
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
                                $butir = str_replace("<img src=", "<img class='img img-fluid mx-auto d-block' src=", $str);
                                echo $butir;
                                ?>
                                <br />
                                <a href="index.php?p=editsoal&idb=<?php echo $so['idbutir']; ?>" style="font-family: sans-serif;font-size:10pt">Edit</a> | <a href="#" style="font-family: sans-serif;font-size:10pt">Hapus</a>
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
                                        <thead>
                                            <?php if (empty($so['headeropsi'])) : ?>
                                                <th style="text-align:center;">Pernyataan</th>
                                                <th style="text-align:center;width:12.5%">Benar</th>
                                                <th style="text-align:center;width:12.5%">Salah</th>
                                            <?php else :
                                                $hdr = explode(",", $so['headeropsi']);

                                            ?>
                                                <th style="text-align:center;"><?php echo $hdr[0]; ?></th>
                                                <th style="text-align:center;width:12.5%"><?php echo $hdr[1]; ?></th>
                                                <th style="text-align:center;width:12.5%"><?php echo $hdr[2]; ?></th>
                                            <?php endif ?>

                                        </thead>
                                        <?php foreach ($getopsi as $id => $op) : ?>
                                            <tr>
                                                <td valign="top">
                                                    <?php
                                                    $ops = str_replace("/cbt/pictures/", "pictures/", $op['opsi']);
                                                    $opsi = str_replace("<img src=", "<img class='img img-fluid img-responsive' src=", $ops);
                                                    echo $opsi;
                                                    ?>
                                                </td>
                                                <td style="text-align:center">
                                                    <input id="BtnBenar<?php echo $op['idopsi']; ?>" type="radio" name="opsijwb<?php echo $op['idopsi']; ?>" value="1" <?php echo ($op['benar'] == '1') ? "checked" : ""; ?>>
                                                </td>
                                                <td style="text-align:center">
                                                    <input id="BtnSalah<?php echo $op['idopsi']; ?>" type="radio" name="opsijwb<?php echo $op['idopsi']; ?>" value="0" <?php echo ($op['benar'] == '0') ? "checked" : ""; ?>>
                                                </td>
                                            </tr>
                                            <script type="text/javascript">
                                                $("#BtnBenar<?php echo $op['idopsi']; ?>").click(function() {
                                                    alert($(this).val());
                                                    let data = new FormData()
                                                    data.append('idsoal', "<?php echo $so['idbutir']; ?>")
                                                    data.append('idopsi', "<?php echo $op['idopsi']; ?>")
                                                    data.append('benar', $(this).val())
                                                    data.append('aksi', 'isikunci')
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "isisoal_simpan.php",
                                                        data: data,
                                                        processData: false,
                                                        contentType: false,
                                                        cache: false,
                                                        timeout: 8000,
                                                        success: function(respons) {}
                                                    })
                                                })
                                                $("#BtnSalah<?php echo $op['idopsi']; ?>").click(function() {
                                                    let data = new FormData()
                                                    data.append('idsoal', "<?php echo $so['idbutir']; ?>")
                                                    data.append('idopsi', "<?php echo $op['idopsi']; ?>")
                                                    data.append('benar', $(this).val())
                                                    data.append('aksi', 'isikunci')
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "isisoal_simpan.php",
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
                                        $butir = str_replace("<img src=", "<img class='img img-fluid mx-auto d-block' src=", $str);
                                        echo $butir;
                                        ?>
                                    </em>
                                </strong>
                                <br />
                                <a href="index.php?p=editsoal&idb=<?php echo $so['idbutir']; ?>" style="font-family: sans-serif;font-size:10pt">Edit</a> | <a href="#" style="font-family: sans-serif;font-size:10pt">Hapus</a>
                            </div>
                        <?php endif ?>

                        <!-- Soal Pilihan Isian Singkat -->
                        <?php if ($jnssoal == '5') : ?>
                            <div class="form-group mb-2">
                                <?php
                                $butir = str_replace("....", "<input type='text' class='jwbessai col-sm-3 col-md-2 col-lg-2 col-xs-4' style='width:60%;margin-left:10px;border:2.5px solid red' data-id='" . $so['idbutir'] . "'>", $str);
                                echo $butir;
                                foreach ($getopsi as $id => $op) {
                                    $opsine = $op['opsi'];
                                }
                                ?>
                                <br />
                                <a href="index.php?p=editsoal&idb=<?php echo $so['idbutir']; ?>" style="font-family: sans-serif;">Edit</a> | <a href="#" style="font-family: sans-serif;">Hapus</a>
                            </div>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $(".jwbessai").val("<?php echo $opsine; ?>")
                                    $(".jwbessai").focus();
                                })
                            </script>
                        <?php endif ?>
                    </div>
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
                            <button disabled class="btn btn-sm btn-default btn-block col-sm-8">
                                <i class="fas fa-arrow-circle-left"></i>&nbsp;Sebelumnya
                            </button>
                        <?php else : ?>
                            <button data-id="<?php echo $prev; ?>" class="btn btn-sm btn-secondary btn-block col-sm-8 btnPrev">
                                <i class="fas fa-arrow-circle-left"></i>&nbsp;Sebelumnya
                            </button>
                        <?php endif ?>
                    </div>

                    <div class="col-sm-3">
                        <?php if ($next <= $rowCount) : ?>
                            <button data-id="<?php echo $next; ?>" class="btn btn-sm btn-primary btn-block col-sm-8 btnNext">
                                Berikutnya&nbsp;<i class="fas fa-arrow-circle-right"></i>
                            </button>
                        <?php else : ?>
                            <button data-id="1" class="btn btn-sm btn-danger btn-block col-sm-8">
                                Selesai&nbsp;<i class="fas fa-sign-out-alt"></i>
                            </button>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>

        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title" id="nomor" style="font-weight:bold;font-family:Times;font-size:14pt"></h3>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">Silahkan tambah stimulus terlebih dahulu!</div>
            </div>
            <div class="card-footer">
                <div class="form-group row mb-2">
                    <?php
                    $prev = $urut - 1;
                    $next = $urut + 1;
                    ?>
                    <div class="col-sm-3 mb-2">
                        <?php if ($prev == 0) : ?>
                            <button disabled class="btn btn-sm btn-default btn-block col-sm-8">
                                <i class="fas fa-arrow-circle-left"></i>&nbsp;Sebelumnya
                            </button>
                        <?php else : ?>
                            <button data-id="<?php echo $prev; ?>" class="btn btn-sm btn-secondary btn-block col-sm-8 btnPrev">
                                <i class="fas fa-arrow-circle-left"></i>&nbsp;Sebelumnya
                            </button>
                        <?php endif ?>
                    </div>

                    <div class="col-sm-3">
                        <?php if ($next <= $rowCount) : ?>
                            <button data-id="<?php echo $next; ?>" class="btn btn-sm btn-primary btn-block col-sm-8 btnNext">
                                Berikutnya&nbsp;<i class="fas fa-arrow-circle-right"></i>
                            </button>
                        <?php else : ?>
                            <button data-id="1" class="btn btn-sm btn-danger btn-block col-sm-8">
                                Selesai&nbsp;<i class="fas fa-sign-out-alt"></i>
                            </button>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
    <script type="text/javascript">
        $("#imageZoom").click(function() {
            $(this).dreamimage();
        })
        $(document).ready(function() {

            $(".btnPrev").click(function() {
                let urut = $(this).data('id')
                tampilsoal(urut)
            })

            $(".btnNext").click(function() {
                let urut = $(this).data('id')
                tampilsoal(urut)
            })
        })

        $(".opsi").click(function() {
            let data = new FormData();
            data.append('idopsi', $(this).val())
            data.append('idsoal', $(this).data('id'))
            data.append('aksi', 'isikunci')
            $.ajax({
                type: "POST",
                url: "isisoal_simpan.php",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 8000,
                success: function(respons) {}
            })
        })

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
                data.append('idsoal', $(this).data('id'))
                data.append('idopsi', opsi)
                data.append('aksi', 'isikunci')
                $.ajax({
                    type: "POST",
                    url: "isisoal_simpan.php",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 8000,
                    success: function() {}
                })
            }
        })
        $(".jwbessai").mouseleave(function() {
            let data = new FormData();
            data.append('idsoal', $(this).data('id'))
            data.append('idopsi', $(this).val())
            data.append('aksi', 'isikunci')
            $.ajax({
                type: "POST",
                url: "isisoal_simpan.php",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 8000,
                success: function(respons) {}
            })
        });
    </script>
<?php endif ?>