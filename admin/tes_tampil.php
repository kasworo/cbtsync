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
              $qtes = viewdata('tbtes');
              foreach ($qtes as $ts) :
              ?>
                <option value="<?php echo $ts['idtes']; ?>"><?php echo $ts['nmtes']; ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="form-group row mb-2">
            <label class="col-sm-4 offset-sm-1">Tahun Pelajaran</label>
            <select type="text" readonly="true" class="form-control form-control-sm col-sm-6" id="getth" name="getth" disabled>
              <option value="">..Pilih..</option>
              <?php
              $qtp = $conn->query("SELECT idthpel, desthpel FROM tbthpel");
              while ($tp = $qtp->fetch_array()) :
              ?>
                <option value="<?php echo $tp['idthpel']; ?>"><?php echo $tp['desthpel']; ?></option>
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
if ($level == '1') :
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
              <label class="col-sm-4 offset-sm-1">Kode Tes</label>
              <input type="hidden" class="form-control form-control-sm col-sm-6" id="idtes" name="idtes">
              <input type="tes" class="form-control form-control-sm col-sm-6" id="aktes" name="aktes">
            </div>
            <div class="form-group row mb-2">
              <label class="col-sm-4 offset-sm-1">Nama Tes</label>
              <input type="text" class="form-control form-control-sm col-sm-6" id="nmtes" name="nmtes">
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
            $qk = viewdata('tbtes');
            $no = 0;
            foreach ($qk as $m) :
              $no++;
              $qu = "SELECT status FROM tbujian u INNER JOIN tbthpel t USING(idthpel) WHERE t.aktif='1' AND idtes='$m[idtes]'";

              if (cquery($qu) > 0) {
                $u = vquery($qu)[0];
                if ($u['status'] == '1') {
                  $stat = 'Aktif';
                  $btn = "btn-success";
                } else {
                  $stat = 'Non Aktif';
                  $btn = "btn-danger";
                }
              } else {
                $stat = 'Non Aktif';
                $btn = "btn-secondary";
              }
            ?>
              <tr>
                <td style="text-align:center"><?php echo $no . '.'; ?></td>
                <td style="text-align:center"><?php echo $m['aktes']; ?></td>
                <td><?php echo $m['nmtes']; ?></td>
                <td style="text-align:center">
                  <input type="button" class="col-6 btn btn-xs <?php echo $btn; ?> btnAktif" value="<?php echo $stat; ?>" data-toggle="modal" data-id="<?php echo $m['idtes']; ?>" data-target="#myAddUjian">
                </td>
                <td style="text-align: center">
                  <a href="#myAddJenisTes" data-toggle="modal" data-id="<?php echo $m['idtes']; ?>" class="btn btn-xs btn-success btnUpdate">
                    <i class="fas fa-edit"></i>&nbsp;Edit
                  </a>
                  <button data-id="<?php echo $m['idtes']; ?>" class="btn btn-xs btn-danger btnHapus">
                    <i class="fas fa-trash-alt"></i>&nbsp;Hapus
                  </button>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#myAddUjian").on('hidden.bs.modal', function() {
        window.location.reload();
      })
      $("#myAddJenisTes").on('hidden.bs.modal', function() {
        window.location.reload();
      })
    })
    $("#btnTambah").click(function() {
      $(".modal-title").html("Tambah Data Jenis Tes");
      $("#simpan").html("<i class='fas fa-save'></i> Simpan");
      $("#idtes").val('');
      $("#nmtes").val('');
      $("#aktes").val('');
    })

    $("#simpan").click(function() {
      let id = $("#idtes").val();
      let ak = $("#aktes").val();
      let nm = $("#nmtes").val();
      if (ak == '') {
        toastr.error("Kode Tes Tidak Boleh Kosong", "Maaf!");
      } else if (nm == '') {
        toastr.error("Nama Tes Tidak Boleh Kosong", "Maaf!");
      } else {
        let data = new FormData();
        data.append('id', id);
        data.append('kd', ak);
        data.append('nm', nm);
        data.append('aksi', 'simpan');
        $.ajax({
          url: "tes_simpan.php",
          type: 'POST',
          data: data,
          processData: false,
          contentType: false,
          cache: false,
          timeout: 8000,
          success: function(respons) {
            if (respons == 1) {
              $(function() {
                toastr.success('Simpan Jenis Tes Berhasil!!', 'Terima Kasih', {
                  timeOut: 3000,
                  fadeOut: 3000,
                  onHidden: function() {
                    $("#myAddJenisTes").modal('hide');
                  }
                });
              });
            }
            if (respons == 2) {
              $(function() {
                toastr.info('Update Jenis Tes Berhasil!!', 'Informasi', {
                  timeOut: 3000,
                  fadeOut: 3000,
                  onHidden: function() {
                    $("#myAddJenisTes").modal('hide');
                  }
                });
              });
            }
            if (respons == 0) {
              $(function() {
                toastr.error('Gagal Update atau Simpan Data!!', 'Mohon Maaf', {
                  timeOut: 3000,
                  fadeOut: 3000,
                  onHidden: function() {
                    $("#myAddJenisTes").modal('hide');
                  }
                });
              });
            }
          }
        })
      }
    })

    $("#aktivasi").click(function() {
      data = new FormData();
      data.append('id', $("#gettes").val());
      data.append('th', $("#getth").val());
      data.append('aksi', 'aktif');
      $.ajax({
        url: "tes_simpan.php",
        type: 'POST',
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 8000,
        success: function(respons) {
          if (respons == 1) {
            $(function() {
              toastr.success('Aktivasi Tes Berhasil!!', 'Terima Kasih', {
                timeOut: 3000,
                fadeOut: 3000,
                onHidden: function() {
                  $("#myAddUjian").modal('hide');
                }
              });
            });
          }
          if (respons == 2) {
            $(function() {
              toastr.info('Tes Berhasil Diaktifkan Kembali!!', 'Informasi', {
                timeOut: 3000,
                fadeOut: 3000,
                onHidden: function() {
                  $("#myAddUjian").modal('hide');
                }
              });
            });
          }
          if (respons == 0) {
            $(function() {
              toastr.info('Tes Berhasil Dinonaktifkan!!', 'Informasi', {
                timeOut: 3000,
                fadeOut: 3000,
                onHidden: function() {
                  $("#myAddUjian").modal('hide');
                }
              });
            });
          }
        }
      })
    })

    $(".btnAktif").click(function() {
      $("#aktivasi").removeClass("btn btn-success btn-sm col-4");
      let id = $(this).data('id');
      let data = new FormData();
      data.append('id', id);
      data.append('th', "<?php echo $idthpel; ?>");
      data.append('aksi', 'aktif');
      $.ajax({
        url: 'tes_edit.php',
        type: 'post',
        dataType: 'json',
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 8000,
        success: function(e) {
          $("#gettes").val(e.idtes);
          $("#getth").val(e.idthpel);
          $("#aktivasi").html(e.btn);
          $("#aktivasi").addClass(e.kls);
        }
      });
    })

    $(".btnUpdate").click(function() {
      $(".modal-title").html("Ubah Data Jenis Tes");
      $("#simpan").html("<i class='fas fa-save'></i> Update");
      let id = $(this).data('id');
      let data = new FormData();
      data.append('id', id);
      data.append('aksi', 'edit')
      $.ajax({
        url: 'tes_edit.php',
        type: 'post',
        dataType: 'json',
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 8000,
        success: function(e) {
          $("#idtes").val(e.idtes);
          $("#nmtes").val(e.nmtes);
          $("#aktes").val(e.aktes);
        }
      })
    })
    $(".btnHapus").click(function() {
      let id = $(this).data('id');
      Swal.fire({
        title: 'Anda Yakin?',
        text: "Menghapus Jenis Tes",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            type: "POST",
            url: "tes_simpan.php",
            data: "aksi=hapus&id=" + id,
            success: function(data) {
              toastr.success(data);
            }
          })
          window.location.reload();
        }
      })
    })
    $("#hapusall").click(function() {
      Swal.fire({
        title: 'Anda Yakin?',
        text: "Menghapus Jenis Tes",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            type: "POST",
            url: "tes_simpan.php",
            data: "aksi=kosong",
            success: function(data) {
              toastr.success(data);
            }
          })
        }
      })
    })
    $("#btnRefresh").click(function() {
      window.location.reload();
    })
  </script>
<?php else : ?>
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
            $qk = $conn->query("SELECT*FROM tbtes");
            $no = 0;
            while ($m = $qk->fetch_array()) {
              $no++;
              $qu = $conn->query("SELECT status FROM tbujian WHERE idthpel='$_COOKIE[c_tahun]' AND idtes='$m[idtes]'");
              $u = $qu->fetch_array();
              if ($u['status'] == '1') {
                $stat = 'Aktif';
                $btn = "btn-success";
              } else {
                $stat = 'Non Aktif';
                $btn = "btn-danger";
              }
            ?>
              <tr>
                <td style="text-align:center"><?php echo $no . '.'; ?></td>
                <td style="text-align:center"><?php echo $m['aktes']; ?></td>
                <td><?php echo $m['nmtes']; ?></td>
                <td style="text-align:center">
                  <input type="button" class="btn btn-xs <?php echo $btn; ?> btnAktif" value="<?php echo $stat; ?>" data-toggle="modal" data-id="<?php echo $m['idtes']; ?>" data-target="#myAddUjian">
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#myAddUjian").on('hidden.bs.modal', function() {
        window.location.reload();
      })
    })
    $("#aktivasi").click(function() {
      let id = $("#gettes").val();
      let th = $("#getth").val();
      $.ajax({
        url: "tes_simpan.php",
        type: 'POST',
        data: "aksi=aktif&id=" + id + "&th=" + th,
        success: function(data) {
          toastr.success(data);
        }
      })
    })

    $(".btnAktif").click(function() {
      let id = $(this).data('id');
      $.ajax({
        url: 'tes_edit.php',
        type: 'post',
        dataType: 'json',
        data: 'aksi=aktif&id=' + id,
        success: function(data) {
          $("#gettes").val(data.idtes);
          $("#getth").val(data.idthpel);
          $("#aktivasi").html(data.btn);
        }
      });
    })
  </script>
<?php endif ?>