<?php
function uploadfile($files)
{
    $namaFile = $files['name'];
    $tmpFile = $files['tmp_name'];
    $ekstensiValid = array('jpg', 'jpeg', 'png', 'gif', 'wav', 'mp3', 'avi', 'mp4');
    $getEkstensiFile = explode(".", $namaFile);
    $ekstensiFile = strtolower(end($getEkstensiFile));
    if (in_array($ekstensiFile, $ekstensiValid)) {
        if ($ekstensiFile == 'jpg' || $ekstensiFile == 'jpeg' || $ekstensiFile == 'png' || $ekstensiFile == 'gif') {
            $dir = "../pictures/";
        }
        if ($ekstensiFile == 'wav' || $ekstensiFile == 'mp3') {
            $dir = "../audios/";
        }
        if ($ekstensiFile == 'avi' || $ekstensiFile == 'mp4') {
            $dir = "../videos/";
        }
        $namaFileBaru = uniqid() . '.' . $ekstensiFile;
        move_uploaded_file($tmpFile, $dir . $namaFileBaru);
        return $namaFileBaru;
    } else {
        echo "<script>
				$(function() {
					toastr.error('File *." . $ekstensiFile . " Tidak Boleh Diupload!','Mohon Maaf',{
					timeOut:4000,
					fadeOut:3000
					});
				});
			</script>";
        return false;
    }
}
if (isset($_GET['ids'])) {
    $stm = viewdata('tbstimulus', array('idstimulus' => $_GET['ids']))[0];
    $idbank = $stm['idbank'];
    $idstimulus = $stm['idstimulus'];
} else {
    $idbank = $_GET['id'];
    $idstimulus = '';
}

if (isset($_POST['simpan'])) {
    if ($_FILES['fileupl']['error'] == 4) {
        $data = array(
            'idbank' => $idbank,
            'stimulus' => $conn->real_escape_string($_POST['stimulus'])
        );
    } else {
        $filenya = $_FILES['fileupl']['name'];
        $getEkstensiFile = explode(".", $filenya);
        $ekstensiFile = strtolower(end($getEkstensiFile));
        if ($ekstensiFile == 'jpg' || $ekstensiFile == 'jpeg' || $ekstensiFile == 'png' || $ekstensiFile == 'gif') {
            $data = array(
                'idbank' => $idbank,
                'stimulus' => $conn->real_escape_string($_POST['stimulus']),
                'gambar' => uploadfile($_FILES['fileupl'])
            );
        }
        if ($ekstensiFile == 'wav' || $ekstensiFile == 'mp3') {
            $data = array(
                'idbank' => $idbak,
                'stimulus' => $conn->real_escape_string($_POST['stimulus']),
                'audio' => uploadfile($_FILES['fileupl'])
            );
        }
        if ($ekstensiFile == 'avi' || $ekstensiFile == 'mp4') {
            $data = array(
                'idbank' => $idbank,
                'stimulus' => $conn->real_escape_string($_POST['stimulus']),
                'video' => uploadfile($_FILES['fileupl'])
            );
        }
    }

    $keystim = array(
        'idstimulus' => $idstimulus
    );
    if (cekdata('tbstimulus', $keystim) == 0) {
        if (adddata('tbstimulus', $data) > 0) {
            echo "<script>
				$(function() {
					toastr.success('Stimulus Soal Berhasil Disimpan!','Terima Kasih',{
					timeOut:4000,
					fadeOut:3000
					});
				});
			</script>";
        } else {
            echo "<script>
				$(function() {
					toastr.error('Stimulus Soal Gagal Disimpan!','Mohon Maaf',{
					timeOut:4000,
					fadeOut:3000
					});
				});
			</script>";
        }
    } else {
        if (editdata('tbstimulus', $data, '', $keystim) > 0) {
            echo "<script>
                $(function() {
                    toastr.success('Stimulus Soal Berhasil Diupdate!','Terima Kasih',{
                    timeOut:4000,
                    fadeOut:3000
                    });
                });
            </script>";
        } else {
            echo "<script>
                $(function() {
                    toastr.error('Stimulus Soal Gagal Diupdate!','Terima Kasih',{
                    timeOut:4000,
                    fadeOut:3000
                    });
                });
            </script>";
        }
    }
}

?>
<script type="text/javascript" src="../assets/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        plugins: ["formula advlist lists charmap anchor", "code fullscreen", "table contextmenu paste jbimages"],
        toolbar: "undo redo | bold italic underline subscript superscript | alignleft aligncenter alignright alignjustify | bullist numlist | jbimages table formula code",
        menubar: false,
        relative_urls: false,
        forced_root_block: "",
        force_br_newlines: false,
        force_p_newlines: true,
    });
</script>
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        let id = "<?php echo $idstimulus; ?>";
        $.ajax({
            url: 'isisoal_editstimulus.php',
            type: 'post',
            dataType: 'json',
            data: 'ids=' + id,
            success: function(rs) {
                $("#judul").html(rs.judul);
                $("#stimulus").html(rs.stimulus);
            }
        })

    })
</script>
<div class="alert alert-warning">
    <label>Petunjuk:</label>
    <ol>
        <li class="text-sm">Isikan petunjuk dan teks sebelum menambahkan butir soal.</li>
        <li class="text-sm">Pastikan File Pendukung yang diupload berekstensi *.jpg, *.jpeg, *.png, *.gif,*.wav ,*.mp3, *.avi, atau *.mp4.</li>
    </ol>
</div>
<div class="card card-danger card-outline">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="card-header">
            <h4 class="card-title col-sm-6" id="judul">Tambah Stimulus Soal</h4>
            <div class="card-tools col-sm-6">
                <button title="Upload File Pendukung" class="btn btn-secondary btn-sm col-sm-2 ml-1 mb-2 float-right" id="upload">
                    <i class="fas fa-upload"></i> Upload
                </button>
                <button title="Simpan Stimulus" type="submit" class="btn btn-success btn-sm col-sm-2 ml-1 mb-2 float-right" id="simpan" name="simpan">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="index.php?p=isisoal&id=<?php echo  $idbank; ?>" class="btn btn-default btn-sm ml-1 mb-2 float-right">
                    <i class="fas fa-arrow-circle-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group mb-2">
                            <label>Stimulus Soal</label>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control form-control-sm" name="stimulus" id="stimulus" style="font-size:14pt; width:100%; height:175px;padding:5px"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group mb-2 ml-3">
                            <label>File Pendukung Stimulus</label>
                        </div>
                        <div class="form-group mb-2 ml-3">
                            <img src="../assets/img/nofile.png" alt="" class="img img-fluid img-bordered-sm img-thumbnail" height="175px">
                        </div>
                        <div class="form-group mt-2 ml-3">
                            <input type="file" name="fileupl" id="fileupl">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>