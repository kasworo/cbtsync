<script type="text/javascript" src="js/pilihbank.js"></script>
<div class="modal fade" id="myAddBank" aria-modal="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Bank Soal</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-sm-12">
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Penilaian</label>
						<select class="form-control form-control-sm col-sm-6" id="idjtes" name="idjtes">
							<option value="">..Pilih..</option>
							<?php
							$quji=$conn->query("SELECT u.idujian, u.nmujian, ts.nmtes FROM tbujian u INNER JOIN tbtes ts ON ts.idtes=u.idtes INNER JOIN tbthpel t ON u.idthpel=t.idthpel WHERE t.aktif='1' AND status='1'");
							while($ts=$quji->fetch_array()):
							?>
							<option value="<?php echo $ts['idujian'];?>"><?php echo $ts['nmujian'].' - '.$ts['nmtes'];?></option>
							<?php endwhile?>
						</select>
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Kelas</label>
						<select class="form-control form-control-sm col-sm-6" id="idkelas" name="idkelas" onchange="pilkelas(this.value)">
							<option value="">..Pilih..</option>
							<?php
							$qkls=$conn->query("SELECT idkelas,nmkelas FROM tbkelas INNER JOIN tbskul USING (idjenjang)");
							while($kl=$qkls->fetch_array()):
							?>
							<option value="<?php echo $kl['idkelas'];?>"><?php echo $kl['nmkelas'];?></option>
							<?php endwhile?>
						</select>
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Mata Pelajaran</label>
						<select class="form-control form-control-sm col-sm-6" id="idmapel" name="idmapel" onchange="pilmapel(this.value)">
							<option value="">..Pilih..</option>
							<?php
							$qmp=$conn->query("SELECT idmapel, nmmapel FROM tbmapel");
							while($mp=$qmp->fetch_array()):
							?>
							<option value="<?php echo $mp['idmapel'];?>"><?php echo $mp['nmmapel'];?></option>
							<?php endwhile?>
						</select>
					</div>
					<div class="form-group row mb-2">
						<label class="col-sm-4 offset-sm-1">Guru Bidang Studi</label>
						<select class="form-control form-control-sm col-sm-6" id="idguru" name="idguru">
							<option value="">..Pilih..</option>
							<?php
								$qus=$conn->query("SELECT username,nama FROM tbuser u  WHERE level='2'");
								while($us=$qus->fetch_array()):
							?>
							<option value="<?php echo $us['username'];?>"><?php echo $us['nama'];?></option>
							<?php endwhile?>
						</select>
					</div>
					<div class="form-group row mb-2">			
						<label class="col-sm-4 offset-sm-1">Paket Soal</label>
						<input class="form-control form-control-sm col-sm-6" id="idbank" name="idbank" readonly>
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-primary btn-sm col-4" id="simpan">
					<i class="fas fa-save"></i> Simpan
				</button>
				<button type="button" class="btn btn-danger btn-sm col-4" data-dismiss="modal">
					<i class="fas fa-power-off"></i> Tutup
				</button>
			</div>
		</div>
	</div>
</div>

<div class="col-sm-12">
	<div class="card card-secondary card-outline">
		<div class="card-header">
			<h4 class="card-title">Data Bank Soal</h4>
			<div class="card-tools">
				<button class="btn btn-success btn-sm" id="btnTambah" data-toggle="modal" data-target="#myAddBank">
					<i class="fas fa-plus-circle"></i>&nbsp;Tambah
				</button>
				<button id="hapusall" class="btn btn-danger btn-sm">
					<i class="fas fa-trash-alt"></i>&nbsp;Hapus
				</button>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tb_banksoal" class="table table-bordered table-striped table-sm">
					<thead>
						<tr>
							<th style="text-align: center;width:2.5%">No.</th>
							<th style="text-align: center;width:12.5%">Paket Soal</th>
							<th style="text-align: center;width:40%">Mata Pelajaran</th>
							<th style="text-align: center;width:27.5%">Guru Bidang Studi</th>
							<th style="text-align: center;">Aksi</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($level=='1'){
							$qk=$conn->query("SELECT bs.*, mp.nmmapel, us.nama, uj.nmujian FROM tbbanksoal bs INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbujian uj USING(idujian) INNER JOIN tbtes ts USING(idtes) INNER JOIN tbuser us USING(username) INNER JOIN tbthpel t USING(idthpel) WHERE t.aktif='1' AND uj.status='1' AND bs.deleted='0' ORDER BY bs.idbank ASC");
						}
						else {
							$qk=$conn->query("SELECT bs.*, mp.nmmapel, us.nama, uj.nmujian FROM tbbanksoal bs INNER JOIN tbmapel mp USING(idmapel) INNER JOIN tbujian uj USING(idujian) INNER JOIN tbtes ts USING(idtes) INNER JOIN tbuser us USING(username) INNER JOIN tbthpel t USING(idthpel) WHERE t.aktif='1'AND bs.username='$useraktif' AND bs.deleted='0' ORDER BY bs.idbank ASC");
						}
						$no=0;
						while($m=$qk->fetch_array()):
						$no++;
						$qset=$conn->query("SELECT*FROM tbsetingujian WHERE idbank='$m[idbank]'");
						$cekset=$qset->num_rows;
						if($cekset>0){
							$status='sudah';
							$bdg='badge-danger';
						}
						else {
							$status='belum';
							$bdg='badge-success';
						}
					?>
						<tr>
							<td style="text-align:center"><?php echo $no.'.';?></td>
							<td>
								<?php echo $m['nmbank'];?>								
							</td>
							<td>
								<?php echo $m['nmmapel'];?>
								<span class="float-right badge <?php echo $bdg;?>"><?php echo $status;?></span>
							</td>
							<td><?php echo $m['nama'];?></td>
							<td style="text-align: center">
								<button data-id="<?php echo $m['idbank'];?>" class="btn btn-xs btn-secondary btnIsi">
									<i class="fas fa-edit"></i>&nbsp;Soal
								</button>
								<button data-id="<?php echo $m['idbank'];?>" class="btn btn-xs btn-danger btnHapus">
									<i class="fas fa-trash-alt"></i>&nbsp;Hapus
								</button>
							</td>
						</tr>
					<?php endwhile?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#myAddBank").on('hidden.bs.modal', function () {
			window.location.reload();
		})		
	})
	$(function () {
		$('#tb_banksoal').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": false,
			"responsive": true,
		});
	})
	$("#idguru").change(function(){
		var kls =$("#idkelas").val();
		var map = $("#idmapel").val();
		var idguru=$(this).val();
		if(idguru==''){
			toastr.error("Guru Bidang Studi Tidak Boleh Kosong","Maaf!");
		}
		else
		{
			$.ajax({
				url: "banksoal_getid.php",
				data: "kls="+kls+"&map="+map,
				cache: false,
				success: function(msg){
					$("#idbank").val(msg);
				}
			})
		}
	})
	
	$("#simpan").click(function(){
		var tes=$("#idjtes").val();
		var kls=$("#idkelas").val();
		var rmb=$("#idrombel").val();
		var map=$("#idmapel").val();
		var usr=$("#idguru").val();
		var bnk=$("#idbank").val();
			$.ajax({
			url:"banksoal_simpan.php",
			type:'POST',
			data:"aksi=1&tes="+tes+"&kls="+kls+"&rmb="+rmb+"&map="+map+"&usr="+usr+"&bnk="+bnk,
			success:function(data){
				toastr.success(data);
			}
		})
	})

	$("#btnAktivasi").click(function(){
		var idb=$("#idsoal").val();
		var rmb=$("#rmbuji").val();
		var jdw=$("#jduji").val();
		var soal=$("#soal").val();
		var mode=$("#mode").val();
		var opsi=$("#opsi").val();
		$.ajax({
		 	url:"banksoal_simpan.php",
		 	type:'POST',
		 	data:"aksi=2&id="+idb+"&rmb="+rmb+"&jdw="+jdw+"&soal="+soal+"&mode="+mode+"&opsi="+opsi,
			success:function(data){
				toastr.success(data);
			}
		})
	})
	$(".btnIsi").click(function(){
		var id=$(this).data('id');
		window.location.href='index.php?p=isisoal&id='+id;
	})

	$(".btnUji").click(function(){
		var id=$(this).data('id');
		$.ajax({
			url:'banksoal_aktif.php',
			type:'post',
			data:'id='+id,
			success:function(data)
			{
				$(".fetched-data").html(data)
			}
		})
	})

	$(".btnHapus").click(function(){
		var id=$(this).data('id');
		Swal.fire({
			title: 'Anda Yakin',
			text: "Menghapus Bank Soal ini?",
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Hapus',
			cancelButtonText:'Batal'
		}).then((result) => {
			if (result.value) {
			$.ajax({
				type:"POST",
				url:"banksoal_simpan.php",
				data: "aksi=4&id="+id,
				success: function(data){
					toastr.info(data,'Terimakasih',{
                        timeOut:1000,
                        fadeOut:1000,
                           onHidden:function(){
                        }
                    });
				}
			})		
			}
		})
	})
	$("#hapusall").click(function(){
		Swal.fire({
			title: 'Anda Yakin?',
			text: "Menghapus Bank Soal",
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Hapus',
			cancelButtonText:'Batal'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					type:"POST",
					url:"banksoal_simpan.php",
					data: "aksi=5&id="+iduji,
					success: function(data){			
						toastr.info(data,'Terimakasih',{
                        timeOut:1000,
                        fadeOut:1000,
                           onHidden:function(){
                        }
                    });
					}
				})
			}
		})
	})
	$("#btnRefresh").click(function(){
		window.location.reload();
	})
</script>