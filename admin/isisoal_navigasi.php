<?php
include "dbfunction.php";
?>
<style>
    .kotak {
        position: relative;
        border-radius: 7.5px;
        top: 0px;
        padding-top: 2.5px;
        box-shadow: 5px 5px #888888;
        border: 2.5px solid #888888;
        color: #888888;
        margin-left: 25px;
        width: 40px;
        height: 40px;
        text-align: center;
        font-size: 15pt;
        font-weight: bold;
        z-index: 1;
    }

    .isi {
        position: relative;
        left: 12.5px;
        top: -47.5px;
        border-radius: 14px;
        border: 2.5px solid #888888;
        background-color: #888888;
        color: #FFFF;
        font-size: 12pt;
        font-weight: bold;
        width: 28px;
        height: 28px;
        text-align: center;
        z-index: 2;
    }
</style>
<div class="col-sm-12  d-flex align-items-stretch justify-content-start">
    <div class="container">
        <div class="row ml-auto">
            <?php
            $qsoal = "SELECT so.idbutir, so.jnssoal FROM tbstimulus st LEFT JOIN tbsoal so USING(idstimulus) INNER JOIN tbbanksoal bs USING(idbank)  WHERE bs.idbank='$_POST[ib]'";
            $qjwb = vquery($qsoal);
            $i = 0;
            foreach ($qjwb as $ids) :
                $i++;
                if ($ids['jnssoal'] == '1') {
                    $opsine = viewdata('tbopsi', array('idbutir' => $ids['idbutir']));
                    foreach ($opsine as $id => $op) {
                        if ($id == 0) {
                            if ($op['benar'] == '1') {
                                $hrf = 'A';
                            }
                        } else if ($id == 1) {
                            if ($op['benar'] == '1') {
                                $hrf = 'B';
                            }
                        } else if ($id == 2) {
                            if ($op['benar'] == '1') {
                                $hrf = 'C';
                            }
                        } else if ($id == 3) {
                            if ($op['benar'] == '1') {
                                $hrf = 'D';
                            }
                        } else {
                            if ($op['benar'] == '1') {
                                $hrf = 'E';
                            }
                        }
                    }
                    $val = $hrf;
                    $hint = $hrf;
                }

                if ($ids['jnssoal'] == '2') {
                    $huruf = [];
                    $opsine = viewdata('tbopsi', array('idbutir' => $ids['idbutir']));
                    foreach ($opsine as $id => $op) {
                        if ($id == 0) {
                            if ($op['benar'] == '1') {
                                $hrf = 'A';
                            } else {
                                $hrf = '';
                            }
                        } else if ($id == 1) {
                            if ($op['benar'] == '1') {
                                $hrf = 'B';
                            } else {
                                $hrf = '';
                            }
                        } else if ($id == 2) {
                            if ($op['benar'] == '1') {
                                $hrf = 'C';
                            } else {
                                $hrf = '';
                            }
                        } else if ($id == 3) {
                            if ($op['benar'] == '1') {
                                $hrf = 'D';
                            } else {
                                $hrf = '';
                            }
                        } else {
                            if ($op['benar'] == '1') {
                                $hrf = 'E';
                            } else {
                                $hrf = '';
                            }
                        }
                        if (!empty($hrf)) {
                            $huruf[] = $hrf;
                        }
                    }
                    $val = '<img src="../assets/img/cek.png" style="width:80%;padding-left:3px;padding-bottom:4px;margin:auto">';
                    $hint = implode(", ", $huruf);
                }
            ?>
                <a href="#" data-id="<?php echo $i; ?>" class="tombol" data-dismiss="modal">
                    <div class="kotak" title="<?php echo 'Nomor ' . $i . ' : ' . $hint; ?>"><?php echo $val; ?></div>
                    <div class="isi"><?php echo $i; ?></div>
                </a>
            <?php endforeach ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(".tombol").click(function() {
        let urut = $(this).data('id')
        tampilsoal(urut)
    })