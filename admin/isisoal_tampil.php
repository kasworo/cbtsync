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
        $xkodesoal = $data->val(3, 2);
        $qps = "SELECT idbank FROM tbbanksoal WHERE nmbank='$xkodesoal'";
        $ps = vquery($qps)[0];
        $kdbank = $ps['idbank'];
        $arr_stim = getstimulus(2);

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
                if ($xjnssoal == '1' || $xjnssosal == '2') {
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
            let ib = "<?php echo $kdbank; ?>";
            alert("<?php echo $isidata; ?>")
            window.location.href = "index.php?p=isisoal&id=" + ib;
        </script>
<?php
    }
}
$qwk = "SELECT hal FROM tbbanksoal WHERE idbank='$_GET[id]'";
if (cquery($qwk) > 0) {
    $wk = vquery($qwk)[0];
    $hal = $wk['hal'];
    if ($hal <= 1) {
        echo "<script>
			$(document).ready(function() {
				tampilsoal(1)
			})
			</script>";
    } else {
        echo "<script>
			$(document).ready(function() {
				tampilsoal(" . $hal . ")
			})
			</script>";
    }
} else {
    echo "<script>
    $(document).ready(function() {
        tampilsoal(1)
    })
    </script>";
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
<div class="modal fade" id="myViewSoal" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Daftar Soal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="fetched-data"></div>
            </div>
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
            <button data-target="#myViewSoal" data-id="<?php echo $_GET['id']; ?>" data-toggle="modal" class="btn btn-warning btn-sm" id="navSoal">
                <i class="fas fa-list-alt"></i>&nbsp;Daftar Soal
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="container" id="soal"></div>
    </div>
</div>
<script type="text/javascript">
    function tampilsoal(h) {
        let data = new FormData()
        data.append('h', h)
        data.append('id', <?php echo $_GET['id']; ?>)
        data.append('aksi', 'load')
        $.ajax({
            url: "isisoal_load.php",
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 8000,
            success: function(resp) {
                $("#soal").html(resp)
                $("#nomor").html('Soal Nomor ' + h)
            }
        })
    }
    $("#navSoal").click(function() {
        let ib = $(this).data('id');
        $.ajax({
            url: 'isisoal_navigasi.php',
            type: 'post',
            data: 'ib=' + ib,
            success: function(data) {
                $(".fetched-data").html(data)
            }
        })
    })
</script>