<?php
    $qhd=$conn->query("SELECT*FROM tbheaderopsi WHERE idbutir='$s[idbutir]'");
	$cekheader=$qhd->num_rows;
	if($cekheader==0):
?>
<div class="form-group row mb-2">
    <?php if($s['jnssoal']=='1'):?>
    <table width="85%">
        <?php 
        $qops=$conn->query("SELECT*FROM tbopsi WHERE idbutir='$s[idbutir]'");
        $i=0; 
        while($op=$qops->fetch_array()):
            $i++;
            if($op['benar']=='1'){$hsl='checked';} else {$hsl='';}
            $opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);
    ?>
        <tr padding-top="10px">
            <td valign="top" width="2.5%">&nbsp;</td>
            <td valign="top" width="2.5%" style="text-align:center">
                <input id="btnOpsi<?php echo $no.$i;?>" type="radio" name="opsi<?php echo $no;?>"
                    value="<?php echo $op['idopsi'];?>" <?php echo $hsl;?>>
                <script type="text/javascript">
                $("#btnOpsi<?php echo $no.$i;?>").click(function() {
                    var ib = "<?php echo $s['idbutir'];?>";
                    var id = $(this).val();
                    $.ajax({
                        url: "isisoal_simpan.php",
                        type: 'POST',
                        data: "aksi=6&id=" + id + "&ib=" + ib,
                        success: function(data) {
                            toastr.success(data);
                        }
                    })
                })
                </script>
            </td>
            <td valign="top">
                <?php echo $opsi;?>
            </td>
        </tr>
        <?php endwhile?>
    </table>
    <?php endif ?>
</div>
<?php else : ?>
<div class="form-group row mb-2">
</div>
<?php endif ?>
<?php
                        $qops=$conn->query("SELECT*FROM tbopsi WHERE idbutir='$s[idbutir]'");
						$i=0;
						if($s['jnssoal']=='1'):
							while($op=$qops->fetch_array()):
								$i++;
								if($op['benar']=='1'){$hsl='checked';} else {$hsl='';}
								$opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);
						?>

<?php elseif($s['jnssoal']=='2'):
						while($op=$qops->fetch_array()):
						$i++;
						if($op['benar']=='1'){$hsl='checked';} else {$hsl='';}
						$opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);
					?>
<table width="85%">
    <tr padding-top="10px">
        <td valign="top" width="2.5%">&nbsp;</td>
        <td valign="top" width="3.5%" style="text-align:center">
            <input type="checkbox" id="btnCeklis<?php echo $no.$i;?>" <?php echo $hsl;?>>
            <script type="text/javascript">
            $("#btnCeklis<?php echo $no.$i;?>").click(function() {
                var ib = "<?php echo $s['idbutir'];?>";
                var id = "<?php echo $op['idopsi'];?>";
                if ($(this).is(":checked")) {
                    nil = 1;
                } else {
                    nil = 0;
                }
                $.ajax({
                    url: "isisoal_simpan.php",
                    type: 'POST',
                    data: "aksi=6&id=" + id + "&ib=" + ib + "&nil=" + nil,
                    success: function(data) {
                        toastr.success(data);
                    }
                })


            })
            </script>
        </td>
        <td valign="top">
            <?php echo $opsi;?>
        </td>
    </tr>
    <?php endwhile?>
</table>
<?php elseif($s['jnssoal']=='3'):?>
<table width="85%">
    <tr>
        <td valign="top" width="2.5%">&nbsp;</td>
        <td valign="top" style="text-align:center;padding:5px;border:solid 1px;" width="75%">
            <strong>Pernyataan</strong>
        </td>
        <td style="text-align:center;padding:5px;border:solid 1px;"><strong>Benar</strong></td>
        <td style="text-align:center;padding:5px;border:solid 1px;"><strong>Salah</strong></td>
    </tr>
    <?php 
							while($op=$qops->fetch_array()):
							$i++;							
							$opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);							
						?>
    <tr padding-top="10px">
        <td valign="top" width="2.5%">&nbsp;</td>
        <td valign="top" style="padding:5px;border:solid 1px;">
            <?php echo $opsi;?>
        </td>
        <td valign="top" style="text-align:center;padding:5px;border:solid 1px;">
            <input type="radio" id="btnBenar<?php echo $no.$i;?>" name="opsi<?php echo $no.$i;?>" value="1"
                <?php echo $op['benar']==1 ? 'checked' :'';?>>
            <script type="text/javascript">
            $("#btnBenar<?php echo $no.$i;?>").click(function() {
                var ib = "<?php echo $s['idbutir'];?>";
                var id = "<?php echo $op['idopsi'];?>";
                var nil = $(this).val();
                $.ajax({
                    url: "isisoal_simpan.php",
                    type: 'POST',
                    data: "aksi=6&id=" + id + "&ib=" + ib + "&nil=" + nil,
                    success: function(data) {
                        toastr.success(data);
                    }
                })
            })
            </script>
        </td>
        <td valign="top" style="text-align:center;padding:5px;border:solid 1px;">
            <input type="radio" id="btnSalah<?php echo $no.$i;?>" name="opsi<?php echo $no.$i;?>" value="0"
                <?php $op['benar']==0 ? 'checked' :'';?>>
            <script type="text/javascript">
            $("#btnSalah<?php echo $no.$i;?>").click(function() {
                var ib = "<?php echo $s['idbutir'];?>";
                var id = "<?php echo $op['idopsi'];?>";
                var nil = $(this).val();
                $.ajax({
                    url: "isisoal_simpan.php",
                    type: 'POST',
                    data: "aksi=6&id=" + id + "&ib=" + ib + "&nil=" + nil,
                    success: function(data) {
                        toastr.success(data);
                    }
                })
            })
            </script>
        </td>
    </tr>
    <?php endwhile?>
</table>
<?php elseif($s['jnssoal']=='4'): ?>
<table width="85%">
    <tr>
        <td valign="top" width="2.5%">&nbsp;</td>
        <td width="75%" style="text-align:center;padding:5px;border:solid 1px;"><strong>Butir
                Soal</strong></td>
        <td style="text-align:center;padding:5px;border:solid 1px;"><strong>Alternatif
                Jawaban</strong></td>
    </tr>
    <?php 
							while($op=$qops->fetch_array()):
							$i++;
							if($op['benar']=='1'){$hsl='checked';} else {$hsl='';}
							$opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);
							$opsialt=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsialt']);
						?>
    <tr padding-top="10px">
        <td valign="top" width="2.5%">&nbsp;</td>
        <td valign="top" style="padding:5px;border:solid 1px;">
            <?php echo $opsi;?>
        </td>
        <td valign="top" style="padding:5px;border:solid 1px;">
            <?php echo $opsialt;?>
        </td>
    </tr>
    <?php endwhile?>
</table>
<?php else: ?>
<table width="75%">
    <tr>
        <td valign="top" width="2.5%">&nbsp;</td>
        <td valign="top"><strong>Kunci Jawaban</strong></td>
    </tr>
    <?php
							while($op=$qops->fetch_array()):
							$i++;
							if($op['benar']=='1'){$hsl='checked';} else {$hsl='';}
							$opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);
						?>

    <tr padding-top="10px">
        <td valign="top" width="2.5%">&nbsp;</td>
        <td valign="top">
            <?php echo $opsi;?>
        </td>
    </tr>
    <?php endwhile?>
</table>
<?php endif?>
<?php else: 
					 	$qops=$conn->query("SELECT*FROM tbopsi WHERE idbutir='$s[idbutir]'");
						$i=0;
						if($s['jnssoal']=='1'):
					?>
<table width="75%">
    <tr>
        <td valign="top" width="2.5%">&nbsp;</td>
        <td valign="top" width="3.5%" style="text-align:center;padding:5px;border:solid 1px;"></td>
        <td width="25%" style="text-align:center;padding:5px;border:solid 1px;">
            <strong><?php echo $hd['header1'];?></strong>
        </td>
        <td width="25%" style="text-align:center;padding:5px;border:solid 1px;">
            <strong><?php echo $hd['header2'];?></strong>
        </td>
    </tr>
    <?php
							while($op=$qops->fetch_array()):
							$i++;
							if($op['benar']=='1'){$hsl='checked';} else {$hsl='';}
							$opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);
                    		$opsialt=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsialt']);
						?>
    <tr>
        <td valign="top" width="2.5%">&nbsp;</td>
        <td valign="top" width="3.5%" style="border:solid 1px; text-align:center;padding:5px">
            <input id="btnOpsi<?php echo $no.$i;?>" type="radio" name="opsi<?php echo $no;?>"
                value="<?php echo $op['idopsi'];?>" <?php $op['benar']==1?'checked':'';?>>
            <script type="text/javascript">
            $("#btnOpsi<?php echo $no.$i;?>").click(function() {
                var
                    ib = "<?php echo $s['idbutir'];?>";
                var id = $(this).val();
                $.ajax({
                    url: "isisoal_simpan.php",
                    type: 'POST',
                    data: "aksi=6&id=" + id + "&ib=" + ib,
                    success: function(data) {
                        window.location.reload();
                    }
                })
            })
            </script>
        </td>
        <td valign="top" style="border:solid 1px; padding:5px;">
            <?php echo $opsi;?>
        </td>
        <td valign="top" style="border:solid 1px; padding:5px;">
            <?php echo $opsialt;?>
        </td>
    </tr>
    <?php endwhile ?>
</table>
<?php elseif($s['jnssoal']=='2'): ?>
<table width="75%">
    <tr>
        <td valign="top" width="2.5%">&nbsp;</td>
        <td valign="top" width="3.5%" style="text-align:center;padding:5px"></td>
        <td width="25%" style="text-align:center;padding:5px"><?php echo $hd['header1'];?></td>
        <td width="25%" style="text-align:center;padding:5px"><?php echo $hd['header2'];?></td>
    </tr>
    <?php
					while($op=$qops->fetch_array()):
						$i++;
						if($op['benar']=='1'){$hsl='checked';} else {$hsl='';}
						$opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);
					?>
    <tr padding-top="10px">
        <td valign="top" width="2.5%">&nbsp;</td>
        <td valign="top" width="3.5%" style="text-align:center">
            <input type="checkbox" id="btnCeklis<?php echo $no.$i;?>" name="opsi<?php echo $no;?>[]"
                value="<?php echo $op['idopsi'];?>" <?php echo $hsl;?>>
            <script type="text/javascript">
            $("#btnCeklis<?php echo $no.$i;?>").click(function() {
                var ib = "<?php echo $s['idbutir'];?>";
                var id = [];
                $("#btnCeklis<?php echo $no.$i;?>").each(function() {
                    if ($(this).is(":checked")) {
                        id.push($(this).val());
                    }
                });
                id = id.toString();
                if (id.length > 0) {
                    $.ajax({
                        url: "isisoal_simpan.php",
                        type: 'POST',
                        data: "aksi=6&id=" + id + "&id=" + ib,
                        success: function(data) {}

                    })
                } else {
                    alert("Isi Dulu Pak Bro!");
                }
            })
            </script>
        </td>
        <td valign="top">
            <?php echo $opsi;?>
        </td>
        <td valign="top">
            <?php echo $opsialt;?>
        </td>
    </tr>
    <?php endwhile?>
</table>
<?php elseif($s['jnssoal']=='3'):?>
<table width="85%">
    <tr>
        <td valign="top" width="2.5%">&nbsp;</td>
        <td valign="top" style="text-align:center;padding:5px;border:solid 1px;" width="75%">
            <strong>Pernyataan</strong>
        </td>
        <td style="text-align:center;padding:5px;border:solid 1px;">
            <strong><?php echo $hd['header1'];?></strong>
        </td>
        <td style="text-align:center;padding:5px;border:solid 1px;">
            <strong><?php echo $hd['header2'];?></strong>
        </td>
    </tr>
    <?php 
							while($op=$qops->fetch_array()):
							$i++;
							
							
							if($op['benar']=='1'){
								$benar='checked';
								$salah='';
							} else {
								$benar='';
								$salah='checked';
							}
							$opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);							
						?>
    <tr padding-top="10px">
        <td valign="top" width="2.5%">&nbsp;</td>
        <td valign="top" style="padding:5px;border:solid 1px;">
            <?php echo $opsi.' '.$op['benar'];?>
        </td>
        <td valign="top" style="text-align:center;padding:5px;border:solid 1px;">
            <input type="radio" id="btnBenar<?php echo $no.$i;?>" name="opsi<?php echo $no.$i;?>" value="1"
                <?php echo $benar;?>>
            <script type="text/javascript">
            $("#btnBenar<?php echo $no.$i;?>").click(function() {
                var ib = "<?php echo $s['idbutir'];?>";
                var id = "<?php echo $op['idopsi'];?>";
                var nil = $(this).val();
                $.ajax({
                    url: "isisoal_simpan.php",
                    type: 'POST',
                    data: "aksi=6&id=" + id + "&ib=" + ib + "&nil=" + nil,
                    success: function(data) {
                        toastr.success(data);
                    }
                })
            })
            </script>
        </td>
        <td valign="top" style="text-align:center;padding:5px;border:solid 1px;">
            <input type="radio" id="btnSalah<?php echo $no.$i;?>" name="opsi<?php echo $no.$i;?>" value="0"
                <?php echo $salah;?>>
            <script type="text/javascript">
            $("#btnSalah<?php echo $no.$i;?>").click(function() {
                var ib = "<?php echo $s['idbutir'];?>";
                var id = "<?php echo $op['idopsi'];?>";
                var nil = $(this).val();
                $.ajax({
                    url: "isisoal_simpan.php",
                    type: 'POST',
                    data: "aksi=6&id=" + id + "&ib=" + ib + "&nil=" + nil,
                    success: function(data) {
                        toastr.success(data);
                    }
                })
            })
            </script>
        </td>
    </tr>
    <?php endwhile?>
</table>
<?php else: 
					while($op=$qops->fetch_array()):
						$i++;
						if($op['benar']=='1'){$hsl='checked';} else {$hsl='';}
						$opsi=str_replace('<img ','<img class="img img-fluid img-responsive"',$op['opsi']);
					?>
<table width="75%">
    <tr padding-top="10px">
        <td valign="top" width="2.5%">&nbsp;</td>
        <td valign="top">
            <?php echo $opsi;?>
        </td>
    </tr>
    <tr padding-top="10px">
        <td valign="top" width="2.5%">&nbsp;</td>
        <td valign="top">
            <?php echo $opsi;?>
        </td>
    </tr>
    <?php endwhile?>
</table>
<?php endif?>
<?php endif?>
</div>