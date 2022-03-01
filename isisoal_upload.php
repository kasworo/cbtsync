<?php
	require_once "../assets/library/PHPExcel.php";
	require_once "../assets/library/excel_reader.php";
	include "../config/konfigurasi.php";
	if(empty($_FILES['tmpisisoal']['tmp_name'])){ 
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
})
</script>
<?php } else {
		$data = new Spreadsheet_Excel_Reader($_FILES['tmpisisoal']['tmp_name']);	
		$baris = $data->rowcount($sheet_index=0);
		$tgl=date('Y-m-d');
		$isidata=ceil(($baris-10)/6);
		$xkodesoal=$data->val(4,1);
		$qps=$conn->query("SELECT idbank FROM tbbanksoal WHERE nmbank='$xkodesoal'");
		$ps=$qps->fetch_array();
		$kdbank=$ps['idbank'];
		for($i=1;$i<=$isidata;$i++){
			$xbutirsoal=addslashes($data->val(6*($i-1)+11,3));
			$jnssoal=$data->val(6*($i-1)+11,4);
			if($jnssoal==''){$xjnssoal='1';} else {$xjnssoal=$jnssoal;}
			$qsoal=$conn->query("INSERT INTO tbsoal (idbank, jnssoal, nomersoal, tksukar,butirsoal, skormaks) VALUES('$kdbank','$xjnssoal','$i','1','$xbutirsoal','1')");
			$qcs=$conn->query("SELECT MAX(idbutir) as idsoal FROM tbsoal WHERE idbank='$kdbank'");
			$cs=$qcs->fetch_array();
			$idsoal=$cs['idsoal'];
			for($j=1;$j<=5;$j++){
				$xopsi=addslashes($data->val(6*($i-1)+$j+11,3));
				$xbenar=strval($data->val(6*($i-1)+$j+11,4));
				if($xjnssoal=='1' || $xjnssoal=='2'){
					if($xbenar=='1'){$skor=1;} else {$skor=0;}
				}
				else{
					$skor=1;
				}
				if($xopsi!==''){
					$qops=$conn->query("INSERT INTO tbopsi (idbutir, opsi, benar, skor) VALUES ('$idsoal','$xopsi','$xbenar','$skor')");
				}
			}
			
		}
?>
<script type="text/javascript">
var ib = "<?php echo $kdbank;?>";
alert("<?php echo $isidata;?>")
window.location.href = "index.php?p=isisoal&id=" + ib;
</script>
<?php	
	} 
?>