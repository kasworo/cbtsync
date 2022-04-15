<?php
	if(!isset($_COOKIE['c_user'])){header("Location: login.php");}

	if(!empty($_GET['d']) && $_GET['d']=='1'){include "isisoal_upload.php";}
?>
<!--<link rel="stylesheet" href="../ujian.css">-->
<style type="text/css">
	input[type="radio"] {
		 left:5px;top:2px;position:relative; margin-right:15px;padding-right: 15px;cursor: pointer;-webkit-appearance: none;-moz-appearance: none; appearance: none;outline: 0;height: 20px;width: 20px;background-image:url(../assets/img/cek.png);
	}
	input[type="radio"]:checked {
		left:5px;top:2px;position:relative; margin-right:15px;padding-right: 15px;cursor: pointer;-webkit-appearance: none;-moz-appearance: none; appearance: none;outline: 0;height: 20px;width: 20px;background-image:url(../assets/img/ceklis.png);
	}

 	input[type="checkbox"] {
		 left:5px;top:2px;position:relative; margin-right:15px;padding-right: 15px;cursor: pointer;-webkit-appearance: none;-moz-appearance: none; appearance: none;outline: 0;height: 20px;width: 20px;background-image:url(../assets/img/cek.png);
	}
	input[type="checkbox"]:checked {
		left:5px;top:2px;position:relative; margin-right:15px;padding-right: 15px;cursor: pointer;-webkit-appearance: none;-moz-appearance: none; appearance: none;outline: 0;height: 20px;width: 20px;background-image:url(../assets/img/ceklis.png);
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
<div class="col-sm-12">
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h3 class="card-title">Hasil Jawaban</h3>
            <div class="card-tools">
                <a href="index.php?p=detailtes&id=<?php echo $_GET['id'];?>" class="btn btn-primary btn-sm">
					<i class="fas fa-arrow-left"></i>&nbsp;Kembali
				</a>
            </div>
		</div>
		<div class="card-body">
			<div class="col-sm-12">
			<?php
                $qsoal=$conn->query("SELECT jwb.*, so.jnssoal, so.butirsoal FROM tbjawaban jwb INNER JOIN tbsoal so USING(idbutir) WHERE so.idbank='$_GET[id]' AND jwb.idsiswa='$_GET[pst]' ORDER BY jwb.urut");
				$no=0;
				while($s=$qsoal->fetch_array()):
					$no++;
                    $butir=str_replace('<img ','<img class="img img-fluid img-responsive"',$s['butirsoal']);
                    $idbutir=$s['idbutir'];
                    $jnssoal=$s['jnssoal'];
                    $skor=$s['skor'];
                    $getopsi=explode(",",$s['viewopsi']);
                    $getopsialt=explode(",",$s['viewopsialt']);
                    $getbenar=explode(",",$s['jwbbenar']);
                    $getsalah=explode(",",$s['jwbsalah']);
                ?>
                <div class="form-group row mb-2">
					<table width="100%" cellpadding="5px auto" cellspacing="2px">
						<tr>
							<td width="2.5%" valign="top"><?php echo $no.".";?></td>
							<td valign="top" colspan="2" padding="5px" width="80%">
							    <?php echo $butir;?>
							</td>
						</tr>
					</table>
				</div>
                <div class="form-group row mb-2">
                <?php if($jnssoal=='1'):?>
                    <table width="100%" cellpadding="5px auto" cellspacing="2px">
                        <?php foreach ($getopsi as $id=>$idopsi):?>
                        <tr valign="top">
                            <td width="2.5%">&nbsp;</td>
                            <td valign="top" width="2.5%">
                                <?php 
                                    if($id==0){$val='A';}
                                    else if($id==1){$val='B';}
                                    else if($id==2){$val='C';}
                                    else if($id==3){$val='D';}
                                    else {$val='E';}
                                    if($idopsi==$s['jwbbenar']){$hrf='checked';} else {$hrf='';}
                                ?>
                                <input id="btnOpsi<?php echo $no.$i;?>" type="radio" name="opsi<?php echo $no;?>" value="<?php echo $idopsi;?>" <?php echo $hrf;?>>
							</td>
                            <td valign="top" width="75%">
                                <?php 
                                    $qopsi=$conn->query("SELECT opsi, benar FROM tbopsi WHERE idopsi='$idopsi'");
                                    $op=$qopsi->fetch_array();
                                    if($op['benar']=='1'){$badge='&nbsp;<span class="badge badge-success">Kunci</span>';}else{$badge='';}
                                    $ops = str_replace("/cbt/pictures/","pictures/",$op['opsi']);
                                    $opsi = str_replace("<img src=","<img class='img img-fluid img-responsive' src=",$ops);
                                    echo $opsi.$badge;
                                ?>
                            </td>
                        </tr>
                        <?php endforeach?>
                        <tr>
                            <td width="2.5%">&nbsp;</td>
                            <td colspan="2">Skor Perolehan: <strong style="color:red"><?php echo number_format($skor,2,',','.');?></strong></td>
                        </tr>
                    </table>
		    <?php elseif($jnssoal=='2'): ?>
                <table cellpadding="5px auto" cellspacing="2px" width="100%">
                    <?php foreach ($getopsi as $id=>$idopsi):?>
                    <tr>
                        <td width="2.5%">&nbsp;</td>
                        <td valign="top" width="2.5%">
                            <?php
                                if (in_array($idopsi,$getbenar)){
                                    $hrf='checked';
                                }
                                else {
                                    $hrf='';
                                }
                            ?>
                            <input id="jawab" class="opsi2" type="checkbox" name="opsijwb[]" value="<?php echo $idopsi;?>" <?php echo $hrf;?>>
                        </td>
                        <td valign="top" width="75%">
                            <?php 
                                $qopsi=$conn->query("SELECT opsi, benar FROM tbopsi WHERE idopsi='$idopsi'");
                                $op=$qopsi->fetch_array();
                                if($op['benar']=='1'){$badge='&nbsp;<span class="badge badge-success">Kunci</span>';}else{$badge='';}
                                $ops = str_replace("/cbt/pictures/","pictures/",$op['opsi']);
                                $opsi = str_replace("<img src=","<img class='img img-fluid img-responsive' src=",$ops);
                                echo $opsi.$badge;
                            ?>
                        </td>
                    </tr>
                    <?php endforeach?>
                    <tr>
                            <td width="2.5%">&nbsp;</td>
                            <td colspan="2">Skor Perolehan: <strong style="color:red"><?php echo number_format($skor,2,',','.');?></strong></td>
                        </tr>	
                </table>
                <?php elseif($jnssoal=='3'):?>
                    <table cellpadding="5px auto" cellspacing="2px" width="100%">
						<tr>
							<td valign="top" width="2.5%">&nbsp;</td>						
							<td valign="top" style="text-align:center;padding:5px;border:solid 1px;" width="75%">
								<strong>Pernyataan</strong>
							</td>
							<td style="text-align:center;padding:5px;border:solid 1px;"><strong>Benar</strong></td>
							<td style="text-align:center;padding:5px;border:solid 1px;"><strong>Salah</strong></td>
						</tr>
                    <?php foreach ($getopsi as $id=>$idopsi):?>
                    <tr>
                        <td width="2.5%">&nbsp;</td>
                        <td valign="top" style="padding:5px;border:solid 1px;">
                        <?php 
                            $qopsi=$conn->query("SELECT opsi, benar FROM tbopsi WHERE idopsi='$idopsi'");
                            $op=$qopsi->fetch_array();
                            if($op['benar']=='1'){$badge='&nbsp;<span class="badge badge-success">Benar</span>';}else{$badge='&nbsp;<span class="badge badge-danger">Salah</span>';}
                            $opsi = $op['opsi'];
                            echo $opsi.' '.$badge;
                        ?>
                        </td>
                        <td valign="top" style="text-align:center;padding:5px;border:solid 1px;">
                            <?php
                                // if (in_array($idopsi,$getbenar)){
                                //     $hrf1='checked';
                                // }
                                // else {
                                //     $hrf='';
                                // }
                                $qjwb=$conn->query("SELECT jawaban FROM tbmatching WHERE idopsi='$idopsi' AND idsiswa='$_GET[pst]'");
                                $mt=$qjwb->fetch_array();
                                if($mt['jawaban']=='1') {
                                    $hrf='checked';
                                    $hrf0='';
                                } else if($mt['jawaban']=='0') {
                                    $hrf='';
                                    $hrf0='checked';
                                }
                                else{
                                    $hrf='';
                                    $hrf0='';
                                }
                            ?>
                            <input id="BtnBenar<?php echo $idopsi;?>" class="opsibenar" type="radio" name="opsijwb<?php echo $idopsi;?>" value="<?php echo $idopsi;?>" <?php echo $hrf;?>>
                        </td>
                        <td valign="top" style="text-align:center;padding:5px;border:solid 1px;">
                            <!-- <?php
                                if (in_array($idopsi,$getsalah)){
                                    $hrf='checked';
                                }
                                else {
                                    $hrf='';
                                }
                            ?> -->
                            <input id="BtnSalah<?php echo $idopsi;?>" class="opsisalah" type="radio" name="opsijwb<?php echo $idopsi;?>" value="<?php echo $idopsi;?>" <?php echo $hrf0;?>>
                        </td>
                    </tr>
                    <?php endforeach ?>	
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="3">Skor Perolehan: <strong style="color:red"><?php echo number_format($skor,2,',','.');?></strong></td>              
                    </tr>
                </table>
                <?php elseif($jnssoal=='4'):?>            
			    <table cellpadding="5px auto" cellspacing="2px" width="100%">
					<?php foreach ($getopsi as $id=>$idopsi):?>
                        <tr>
                            <td width="2.5%">&nbsp;</td>
                            <td valign="top" colspan="2">
                                <?php 
                                    $qopsi=$conn->query("SELECT opsi FROM tbopsi WHERE idopsi='$idopsi'");
                                    $op=$qopsi->fetch_array();
                                    $opsi = $op['opsi'];
                                    echo $opsi;
                                ?>
                            </td>
                        </tr>
					    <?php
							foreach ($getopsialt as $ida=>$idopsialt):
								if($ida==0){$vala='A';}
								else if($ida==1){$vala='B';}
								else if($ida==2){$vala='C';}
								else if($ida==3){$vala='D';}
								else if($ida==4){$vala='E';}
								else if($ida==5){$vala='F';}
								else if($ida==6){$vala='G';}
								else if($ida==7){$vala='H';}
								else if($ida==8){$vala='I';}
								else {$vala='J';}								
								if (in_array($idopsialt, $getbenar) && $idopsialt==$idopsi){
									$hrf='checked';
								}
								else {
									$hrf='';
								}
                        ?>
                        <tr>
                            <td width="2.5%">&nbsp;</td>
                            <td valign="top" width="2.5%;">
                                <div class="cc-selector mt-1">
                                    <input id="OpsiM<?php echo $idopsi.$ida;?>" class="opsim" type="radio" name="opsijwb<?php echo $idopsi;?>" value="<?php echo $idopsialt;?>" <?php echo $hrf;?>>
                                    <label class="drinkcard-cc <?php echo $vala;?>" 
                                    for="OpsiM<?php echo $idopsi.$ida;?>"></label>
                                </div>
                            </td>
                            <td width="72.5%">
                            <?php
								$qopal=$conn->query("SELECT idopsi, opsialt FROM tbopsi WHERE idopsi='$idopsialt'");
								$opa=$qopal->fetch_array();
                                if($opa['idopsi']==$idopsi){$badge='&nbsp;<span class="badge badge-success">Benar</span>';}else{$badge='&nbsp;<span class="badge badge-danger">Salah</span>';}
								$opsa = str_replace("/cbt/pictures/","pictures/",$opa['opsialt']);
								$opsa = str_replace("<img src=","<img class='img img-fluid img-responsive' src=",$opsa);
								echo $opsa.' '.$badge;
							?>
                            </td>
						</tr>				
						<?php endforeach?>
					<?php endforeach?>
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="3">Skor Perolehan: <strong style="color:red"><?php echo number_format($skor,2,',','.');?></strong></td>              
                        </tr>
					</table>
                <?php else:?>
                    <table cellpadding="5px auto" cellspacing="2px" width="50%">
                        <tr>
                            <td width="2.5%">&nbsp;</td>
                            <td valign="top" colspan="3">
                                <strong>Jawaban</strong>
                            </td>
                        </tr>
                        <tr>
                            <td width="2.5%">&nbsp;</td>
                            <td width="2.5%">
                                <input type="radio"></td>
                                <td valign="top" width="25%">
                                <?php 
                                   echo $s['jwbbenar'];
                                ?>
                            </td>
                            <td valign="top" width="12.5%">
                                <?php 
                                    $qopsi=$conn->query("SELECT opsi FROM tbopsi WHERE idbutir='$idbutir' AND benar='1'");
                                    $op=$qopsi->fetch_array();
                                    $opsi = $op['opsi'];
                                    $badge='&nbsp;<span class="badge badge-success">Kunci</span>';
                                    echo $opsi.$badge;
                                ?>
                            </td>
                        </tr>
                        <tr>
                        <td>&nbsp;</td>
                        <td colspan="3">Skor Perolehan: <strong style="color:red"><?php echo number_format($skor,2,',','.');?></strong></td>              
                    </tr>
                </table>
			    <?php endif?>
                </div>
                <?php endwhile?>
            </div>
        </div>
    </div>
</div>
				