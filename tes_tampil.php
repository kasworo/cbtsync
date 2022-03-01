<div class="modal fade" id="myAddUjian" aria-modal="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
					<h5 class="modal-title">Aktivasi Tes</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-sm-12">            
            <div class="form-group row mb-2">
                <label class="col-sm-4 offset-sm-1">Nama Jenis Tes</label>
                <select type="text" readonly="true" class="form-control form-control-sm col-sm-6" id="gettes" name="gettes" disabled>
                  <option value="">..Pilih..</option>
                  <?php
                    $qtes=$conn->query("SELECT idtes, nmtes FROM tbtes");
                    while($ts=$qtes->fetch_array()):
                  ?>
                  <option value="<?php echo $ts['idtes'];?>"><?php echo $ts['nmtes'];?></option>
                  <?php endwhile ?>
                </select>
						</div>
            <div class="form-group row mb-2">            
              <label class="col-sm-4 offset-sm-1">Tahun Pelajaran</label>
              <select type="text" readonly="true" class="form-control form-control-sm col-sm-6" id="getth" name="getth" disabled>
                <option value="">..Pilih..</option>
                <?php
                  $qtp=$conn->query("SELECT idthpel, desthpel FROM tbthpel");
                  while($tp=$qtp->fetch_array()):
                ?>
                <option value="<?php echo $tp['idthpel'];?>"><?php echo $tp['desthpel'];?></option>
                <?php endwhile ?>
              </select>              
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-success btn-sm col-4" id="aktivasi">
            <i class="far fa-check-square"></i>&nbsp;Aktifkan
          </button>
					<button type="button" class="btn btn-danger btn-sm col-4" data-dismiss="modal">
            <i class="fas fa-power-off"></i> Tutup
          </button>
				</div>
		</div>
	</div>
</div>
<?php
  if($level=='1'):
?>
<div class="modal modal-md fade" id="myAddJenisTes" aria-modal="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
					<h5 class="modal-title">Tambah Data Jenis Tes</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-sm-12">            
            <div class="form-group row mb-2">            
							<label class="col-sm-4 offset-sm-1">Nama Jenis Tes</label>
              <input type="hidden" class="form-control form-control-sm col-sm-6" id="idtes" name="idtes">
							<input type="text" class="form-control form-control-sm col-sm-6" id="nmtes" name="nmtes">
						</div>
            <div class="form-group row mb-2">            
							<label class="col-sm-4 offset-sm-1">Kode</label>
							<input type="tes" class="form-control form-control-sm col-sm-6" id="aktes" name="aktes">
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
            <h4 class="card-title">Data Jenis Tes</h4>
            <div class="card-tools">
              <button class="btn btn-success btn-sm" id="btnTambah" data-toggle="modal" data-target="#myAddJenisTes">
                <i class="fas fa-plus-circle"></i>&nbsp;Tambah
              </button>
              <button class="btn btn-info btn-sm" id="btnRefresh">
                <i class="fas fa-sync-alt"></i>&nbsp;Refresh
              </button>
              <button id="hapusall" class="btn btn-danger btn-sm">
                <i class="fas fa-trash-alt"></i>&nbsp;Hapus
              </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
              <table id="tb_tes" class="table table-bordered table-striped table-sm">
                <thead>
                <tr>
                  <th style="text-align: center;width:2.5%">No.</th>
                  <th style="text-align: center;width:7.5%">Kode</th>
                  <th style="text-align: center">Jenis Tes</th>
                  <th style="text-align: center">Status</th>
                  <th style="text-align: center;width:20%">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $qk=$conn->query("SELECT*FROM tbtes");
                    $no=0;
                    while($m=$qk->fetch_array())
                    {
                      $no++;
                      $qu=$conn->query("SELECT status FROM tbujian WHERE idthpel='$idthpel' AND idtes='$m[idtes]'");
                      $u=$qu->fetch_array();
                      if($u['status']=='1'){$stat='Aktif';$btn="btn-success";} else {$stat='Non Aktif';$btn="btn-danger";}
                ?>
                <tr>
                  <td style="text-align:center"><?php echo $no.'.';?></td>
                  <td style="text-align:center"><?php echo $m['aktes'];?></td>
                  <td><?php echo $m['nmtes'];?></td>
                  <td style="text-align:center">
                    <input type="button" class="col-6 btn btn-xs <?php echo $btn;?> btnAktif" value="<?php echo $stat;?>" data-toggle="modal" data-id="<?php echo $m['idtes'];?>" data-target="#myAddUjian">
                  </td>
                  <td style="text-align: center">
                    <a href="#myAddJenisTes" data-toggle="modal" data-id="<?php echo $m['idtes'];?>" class="btn btn-xs btn-success btnUpdate">
                        <i class="fas fa-edit"></i>&nbsp;Edit
                    </a>
                    <button data-id="<?php echo $m['idtes'];?>" class="btn btn-xs btn-danger btnHapus">
                        <i class="fas fa-trash-alt"></i>&nbsp;Hapus
                    </button>
                  </td>
                </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
		$("#myAddUjian").on('hidden.bs.modal', function () {
			window.location.reload();
		})
	})
  $("#btnTambah").click(function(){
    $(".modal-title").html("Tambah Data Jenis Tes");
    $("#simpan").html("<i class='fas fa-save'></i> Simpan");
    $("#idtes").val('');
    $("#nmtes").val('');
    $("#aktes").val('');
  })
  
  $("#simpan").click(function(){
      var id=$("#idtes").val();
      var nm=$("#nmtes").val();
      var ak=$("#aktes").val();
      $.ajax({
        url:"tes_simpan.php",
        type:'POST',
        data:"aksi=simpan&id="+id+"&nmtes="+nm+"&aktes="+ak,
        success:function(data){
          toastr.success(data);
        }
      })
  })

  $("#aktivasi").click(function(){
      var id=$("#gettes").val();
      var th=$("#getth").val();
      $.ajax({
        url:"tes_simpan.php",
        type:'POST',
        data:"aksi=aktif&id="+id+"&th="+th,
        success:function(data){
          toastr.success(data);
        }
      })
  })
  $(".btnAktif").click(function(){
      var id=$(this).data('id');
      $("#aktivasi").removeClass("btn btn-success btn-sm col-4");  
      $.ajax({
        url:'tes_edit.php',
        type:'post',
        dataType:'json',
        data:'aksi=aktif&id='+id,
        success:function(data)
        {
          $("#gettes").val(data.idtes);
          $("#getth").val(data.idthpel);
          $("#aktivasi").html(data.btn);
          $("#aktivasi").addClass(data.kls);
        }
      });
  }) 
  $(".btnUpdate").click(function(){
      $(".modal-title").html("Ubah Data Jenis Tes");
      $("#simpan").html("<i class='fas fa-save'></i> Update");
      var id=$(this).data('id');      
      $.ajax({
        url:'tes_edit.php',
        type:'post',
        dataType:'json',
        data:'aksi=edit&id='+id,
        success:function(data)
        {
          $("#idtes").val(data.idtes);
          $("#nmtes").val(data.nmtes);
          $("#aktes").val(data.aktes);
        }
      })
  })
  $(".btnHapus").click(function(){
    var id=$(this).data('id');
    Swal.fire({
      title: 'Anda Yakin?',
      text: "Menghapus Jenis Tes",
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
						url:"tes_simpan.php",
						data: "aksi=hapus&id="+id,
						success: function(data){					
							toastr.success(data);
						}
				})
        window.location.reload();
      }
    })
  })
  $("#hapusall").click(function(){
    Swal.fire({
      title: 'Anda Yakin?',
      text: "Menghapus Jenis Tes",
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
			url:"tes_simpan.php",
			data: "aksi=kosong",
			success: function(data){					
			toastr.success(data);
			}
		})
      }
    })
	})
  $("#btnRefresh").click(function(){
    window.location.reload();
  })
</script>
<?php else: ?>
<div class="col-sm-12">
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <h4 class="card-title">Aktifkan Tes</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
              <table id="tb_tes" class="table table-bordered table-striped table-sm">
                <thead>
                <tr>
                  <th style="text-align: center;width:2.5%">No.</th>
                  <th style="text-align: center;width:7.5%">Kode</th>
                  <th style="text-align: center">Jenis Tes</th>
                  <th style="text-align: center">Status</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $qk=$conn->query("SELECT*FROM tbtes");
                    $no=0;
                    while($m=$qk->fetch_array())
                    {
                      $no++;
                      $qu=$conn->query("SELECT status FROM tbujian WHERE idthpel='$_COOKIE[c_tahun]' AND idtes='$m[idtes]'");
                      $u=$qu->fetch_array();
                      if($u['status']=='1'){$stat='Aktif';$btn="btn-success";} else {$stat='Non Aktif';$btn="btn-danger";}
                ?>
                <tr>
                  <td style="text-align:center"><?php echo $no.'.';?></td>
                  <td style="text-align:center"><?php echo $m['aktes'];?></td>
                  <td><?php echo $m['nmtes'];?></td>
                  <td style="text-align:center">
                    <input type="button" class="btn btn-xs <?php echo $btn;?> btnAktif" value="<?php echo $stat;?>" data-toggle="modal" data-id="<?php echo $m['idtes'];?>" data-target="#myAddUjian">
                  </td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
		$("#myAddUjian").on('hidden.bs.modal', function () {
			window.location.reload();
		})
	})
  $("#aktivasi").click(function(){
      var id=$("#gettes").val();
      var th=$("#getth").val();
      $.ajax({
        url:"tes_simpan.php",
        type:'POST',
        data:"aksi=aktif&id="+id+"&th="+th,
        success:function(data){
          toastr.success(data);
        }
      })
  })

  $(".btnAktif").click(function(){
      var id=$(this).data('id'); 
      $.ajax({
        url:'tes_edit.php',
        type:'post',
        dataType:'json',
        data:'aksi=aktif&id='+id,
        success:function(data)
        {
          $("#gettes").val(data.idtes);
          $("#getth").val(data.idthpel);
          $("#aktivasi").html(data.btn);
        }
      });
  })
</script>
<?php endif ?>
