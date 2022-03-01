<?php
	$qsoal = $conn->query("SELECT MAX(nomersoal) as maksi, nmbank FROM tbsoal so LEFT JOIN tbbanksoal bs ON bs.idbank=so.idbank WHERE so.idbank = '$_REQUEST[id]'");
	$sm = $qsoal->fetch_array();
	$maks = $sm['maksi']+1;
	$idbank=$_REQUEST['id'];
	$nmbank=$sm['nmbank'];
	$idsoal='';
	$butirsoal='';
	$jnssoal='';
	$tksoal='';
?>
<script type="text/javascript" src="../assets/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector: "textarea",
		plugins: ["formula advlist lists charmap anchor", "code fullscreen",  "table contextmenu paste jbimages"],
		toolbar: "undo redo | bold italic underline subscript superscript | alignleft aligncenter alignright alignjustify | bullist numlist | jbimages table formula code",menubar:false,	relative_urls: false, forced_root_block : "", force_br_newlines : true,	force_p_newlines : false,
	});
</script>
<div class="col-sm-12">
	<div class="card card-danger card-outline">
		<div class="card-header">
			<h3 class="card-title"><strong>Input Butir Soal <?php echo $nmbank;?>(Nomor <?php echo $maks;?>)</strong></h3>
			<div class="card-tools">
				<?php 
					$qcek=$conn->query("SELECT*FROM tbsoal WHERE idbank='$idbank'");
					$cek=$qcek->num_rows;
					if($cek==0){
				?>
				<a href="index.php?p=banksoal" class="btn btn-default btn-sm">
					<i class='fas fa-arrow-circle-left'></i> Kembali
				</a>
				<?php } else { ?>
				<a href="index.php?p=isisoal&id=<?php echo $idbank;?>" class="btn btn-default btn-sm">
					<i class='fas fa-arrow-circle-left'></i> Kembali
				</a>
				<?php } ?>
				<button type="submit" class="btn btn-success btn-sm" id="savesoal">
					<i class="fas fa-save"></i> Simpan
				</button>
			</div>
		</div>
		<div class="card-body">
			<div class="col-sm-12">
				<div class="form-group row mb-2">
					<div class="col-sm-6">
						<div class="form-group row mb-2">
							<div class="col-sm-4">
								<label>Jenis Soal</label>
							</div>
							<div class="col-sm-6">
								<select id="kategori" name="kategori" class="form-control form-control-sm">
									<option value="" >..Pilih..</option>
									<option value="1" title="Pilihan Ganda 1 Jawaban Benar">Pilihan Ganda Biasa</option>
									<option value="2" title="Pilihan Ganda > 1 Jawaban Benar">Pilih Ganda Kompleks</option>
									<option value="3" title="Benar / Salah">Benar / Salah</option>
									<option value="4" title="Menjodohkan">Menjodohkan</option>
									<option value="5" title="Isian Singkat">Isian Singkat</option>
								</select>
							</div>
						</div>
						<div class="form-group row mb-2">
							<div class="col-sm-4">
								<label>Mode Opsi</label>
							</div>
							<div class="col-sm-6">
								<select id="modeopsi" name="modeopsi" class="form-control form-control-sm">
									<option value="" >..Pilih..</option>
									<option value="0">Biasa</option>
									<option value="1">Tabel (2 Kolom)</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group row mb-2">
							<div class="col-sm-4">
								<label>Tingkat Kesulitan</label>
							</div>
							<div class="col-sm-6">
								<select id="kesulitan" name="kesulitan" class="form-control form-control-sm">
									<option value="">..Pilih..</option>
									<option value="1">Mudah</option>
									<option value="2">Sedang</option>
									<option value="3">Sulit</option>
								</select>
							</div>
						</div>
						<div class="form-group row mb-2">
							<div class="col-sm-4">
								<label>Skor Maksimum</label>
							</div>
							<div class="col-sm-6">
								<input class="form-control form-control-sm" id="skormaks" name="skormaks">
							</div>
						</div>
					</div>					
				</div>				
				<hr/>
				<div class="form-group row mb-2">
					<div class="col-sm-12">
						<label>Butir Soal</label>
					</div>
				</div>
				<div class="form-group row mb-2">
					<div class="col-sm-12">
						<textarea class="form-control form-control-sm" name="tanyasoal" id="tanyasoal" style="font-size:14pt; width:100%; height:250px;padding:5px"><?php echo $butirsoal;?></textarea> 
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#kesulitan").change(function(){
			var js=$("#kategori").val();
			var tk=$(this).val();
			if(js!=='5'){
				$("#skormaks").attr('disabled','true');
				$("#skormaks").val(1);
			}
			else
			{
				$("#skormaks").val();
			}

		})
				
		$("#savesoal").click(function(){
			var ib="<?php echo $idbank;?>";
			var id="<?php echo $idsoal;?>";
			var nm="<?php echo $maks;?>";
			var js=$("#kategori").val();
			var mo=$("#modeopsi").val();
			var tk=$("#kesulitan").val();
			var sk=$("#skormaks").val();
			var bt=tinymce.get("tanyasoal").getContent();
			$.ajax({
				url:"isisoal_simpan.php",
				type:'POST',
				data:"aksi=1&ib="+ib+"&id="+id+"&js="+js+"&mo="+mo+"&tk="+tk+"&sk="+sk+"&nm="+nm+"&bt="+encodeURIComponent(bt),
				success:function(data){
					window.location.href="index.php?p=editsoal&id="+ib+"&nm="+nm;
				}
			})
		})
	})
</script>
