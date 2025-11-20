<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-school"></i> <?php echo $title?></h1>
  </div>
  
  <a class="btn btn-sm btn-success mb-3" href="<?php echo base_url('admin/data_unit/tambah_unit') ?>">
    <i class="fas fa-plus"></i> Tambah Unit Pendidikan
  </a>
  
  <?php echo $this->session->flashdata('pesan')?>
</div>

<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary">
      <h6 class="m-0 font-weight-bold text-white">Daftar Unit Pendidikan Yayasan</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead class="thead-dark">
            <tr>
              <th class="text-center" width="5%">No</th>
              <th class="text-center" width="10%">Kode Unit</th>
              <th class="text-center">Nama Unit</th>
              <th class="text-center" width="12%">Jenjang</th>
              <th class="text-center">Kepala Unit</th>
              <th class="text-center">Telepon</th>
              <th class="text-center" width="10%">Status</th>
              <th class="text-center" width="15%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; foreach($units as $u) : ?>
            <tr>
              <td class="text-center"><?php echo $no++ ?></td>
              <td class="text-center">
                <span class="badge badge-info" style="font-size: 14px;">
                  <?php echo $u->kode_unit?>
                </span>
              </td>
              <td><?php echo $u->nama_unit?></td>
              <td class="text-center">
                <?php 
                $badge_color = '';
                switch($u->jenjang) {
                  case 'Yayasan': $badge_color = 'badge-dark'; break;
                  case 'SMA': $badge_color = 'badge-primary'; break;
                  case 'SMP': $badge_color = 'badge-success'; break;
                  case 'SD': $badge_color = 'badge-warning'; break;
                  case 'TK': $badge_color = 'badge-info'; break;
                  case 'TPA': $badge_color = 'badge-secondary'; break;
                  case 'KB': $badge_color = 'badge-light'; break;
                  default: $badge_color = 'badge-secondary';
                }
                ?>
                <span class="badge <?php echo $badge_color?>" style="font-size: 12px;">
                  <?php echo $u->jenjang?>
                </span>
              </td>
              <td><?php echo $u->kepala_unit ? $u->kepala_unit : '-'?></td>
              <td class="text-center"><?php echo $u->telepon ? $u->telepon : '-'?></td>
              <td class="text-center">
                <?php if($u->status == 'Aktif') { ?>
                  <span class="badge badge-success">
                    <i class="fas fa-check-circle"></i> Aktif
                  </span>
                <?php } else { ?>
                  <span class="badge badge-danger">
                    <i class="fas fa-times-circle"></i> Nonaktif
                  </span>
                <?php } ?>
              </td>
              <td>
                <center>
                  <a class="btn btn-sm btn-primary" href="<?php echo base_url('admin/data_unit/detail_unit/'.$u->kode_unit) ?>" title="Detail Unit">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a class="btn btn-sm btn-info" href="<?php echo base_url('admin/data_unit/update_unit/'.$u->id_unit) ?>" title="Edit Unit">
                    <i class="fas fa-edit"></i>
                  </a>
                  <?php if($u->kode_unit != 'YYS') { ?>
                  <a onclick="return confirm('Yakin hapus unit ini? Data pegawai di unit ini akan terpengaruh!')" 
                     class="btn btn-sm btn-danger" 
                     href="<?php echo base_url('admin/data_unit/delete_unit/'.$u->id_unit) ?>"
                     title="Hapus Unit">
                    <i class="fas fa-trash"></i>
                  </a>
                  <?php } ?>
                </center>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Info Cards -->
  <div class="row">
    <div class="col-xl-12 col-md-12 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Informasi
              </div>
              <div class="text-sm mb-0 text-gray-800">
                <i class="fas fa-info-circle text-primary"></i> 
                Sistem mendukung manajemen multi-unit pendidikan. Setiap unit memiliki data pegawai, absensi, dan penggajian terpisah.
                <br><br>
                <strong>Kode Unit:</strong> Digunakan untuk identifikasi unik setiap unit (max 10 karakter, huruf kapital)
                <br>
                <strong>Status Aktif:</strong> Hanya unit aktif yang akan muncul di filter dan laporan
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-school fa-3x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>