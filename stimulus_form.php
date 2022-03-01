<?php
    if(isset($_POST['simpan'])){
        $data=array(
            'idbank'=>$_GET['id'],
            'stimulus'=>$_POST['stimulus']
        );
        $key = array_keys($data);
        $val = array_values($data);
        $tbl="tbstimulus";
        $sql = "INSERT INTO $tbl (".implode(', ', $key). ") VALUES ('". implode("', '", $val)."')";
        mysqli_query($conn,$sql);
        $result=mysqli_affected_rows($conn);
        if($result===1){
            echo "OK";
        }
    }
?>
<script type="text/javascript" src="../assets/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector: "textarea",
		plugins: ["formula advlist lists charmap anchor", "code fullscreen", "table contextmenu paste jbimages"],
		toolbar: "undo redo | bold italic underline subscript superscript | alignleft aligncenter alignright alignjustify | bullist numlist | jbimages table formula code",
		menubar:false,	relative_urls: false, forced_root_block : "", force_br_newlines : true,	force_p_newlines : false,
	});
</script>
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<div class="col-sm-12">
    <form action="" method="post">
        <div class="card card-danger card-outline">
            <div class="card-header">
                <h3 class="card-title col-sm-6"><strong>Tambah Stmulus</strong></h3>
                <div class="card-tools col-sm-6">
                    <button title="Upload File Pendukung" class="btn btn-secondary btn-sm col-sm-2 ml-1 mb-2 float-right" id="upload">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                    <button title="Simpan Stimulus" type="submit" class="btn btn-success btn-sm col-sm-2 ml-1 mb-2 float-right" id="simpan" name="simpan">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="index.php?p=isisoal&id=<?php echo $idbank;?>" class="btn btn-default btn-sm ml-1 mb-2 float-right">
                        <i class="fas fa-arrow-circle-left"></i> Kembali
                    </a>				
                </div>
            </div>
            <div class="card-body">
                <div class="col-sm-12">
                    <div class="form-group row mb-2">
                        <div class="col-sm-12">
                            <label>Stimulus Soal</label>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <div class="col-sm-12">
                            <textarea class="form-control form-control-sm" name="stimulus" id="stimulus" style="font-size:14pt; width:100%; height:200px;padding:5px"><?php echo $butirsoal;?></textarea> 
                        </div>
                    </div>
                </div>		
            </div>
        </div>
    </form>
</div>