<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $title?></h1>
  </div>

  <!-- Info Unit (untuk kepala unit) -->
  <?php if($my_unit): ?>
  <div class="alert alert-info">
    <i class="fas fa-school"></i>
    <strong>Unit Anda:</strong> <?php echo $nama_unit?> 
    <span class="badge badge-info ml-1"><?php echo $my_unit?></span>
    — Anda hanya dapat mengelola pegawai di unit ini.
  </div>
  <?php else: ?>
  <div class="alert alert-secondary">
    <i class="fas fa-users"></i>
    <strong>Menampilkan:</strong> <?php echo $nama_unit?>
  </div>
  <?php endif; ?>

  <a class="btn btn-sm btn-success mb-3" href="<?php echo base_url('admin/data_pegawai/tambah_data') ?>">
    <i class="fas fa-plus"></i> Tambah Pegawai
  </a>
  <?php echo $this->session->flashdata('pesan')?>
</div>

<div class="container-fluid">
  <div class="card shadow mb-4">
   <div class="card-body">
     <div class="table-responsive">
       <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
         <thead class="thead-dark">
           <tr>
              <th class="text-center">No</th>
              <th class="text-center">NIK</th>
              <th class="text-center">Nama Pegawai</th>
              <th class="text-center">Jenis Kelamin</th>
              <th class="text-center">Jabatan</th>
              <th class="text-center">Unit</th>
              <th class="text-center">Tanggal Masuk</th>
              <th class="text-center">Status</th>
              <th class="text-center">Hak Akses</th>
              <th class="text-center">Photo</th>
              <th class="text-center">Actions</th>
           </tr>
         </thead>
         <tbody>
           <?php $no=1; foreach($pegawai as $p): ?>
            <tr>
              <td class="text-center"><?php echo $no++ ?></td>
              <td class="text-center"><?php echo $p->nik ?></td>
              <td><?php echo $p->nama_pegawai ?></td>
              <td class="text-center"><?php echo $p->jenis_kelamin ?></td>
              <td class="text-center"><?php echo $p->jabatan ?></td>
              <td class="text-center">
                <?php if(isset($p->unit) && $p->unit): ?>
                  <span class="badge badge-info"><?php echo $p->unit?></span>
                <?php else: ?>
                  <span class="badge badge-secondary">-</span>
                <?php endif; ?>
              </td>
              <td class="text-center"><?php echo $p->tanggal_masuk ?></td>
              <td class="text-center">
                <span class="badge <?php echo ($p->status == 'Karyawan Tetap') ? 'badge-success' : 'badge-warning'?>">
                  <?php echo $p->status ?>
                </span>
              </td>
              <td class="text-center">
                <?php
                switch($p->hak_akses) {
                  case 1: echo '<span class="badge badge-danger">Super Admin</span>'; break;
                  case 3: echo '<span class="badge badge-primary">Kepala Unit</span>'; break;
                  default: echo '<span class="badge badge-secondary">Pegawai</span>'; break;
                }
                ?>
              </td>
              <td class="text-center">
                <img src="<?php echo base_url().'photo/'.$p->photo?>" width="45" class="rounded">
              </td>
              <td>
                <center>
                  <a class="btn btn-sm btn-info" href="<?php echo base_url('admin/data_pegawai/update_data/'.$p->id_pegawai) ?>">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a onclick="return confirm('Yakin hapus pegawai ini?')" class="btn btn-sm btn-danger" href="<?php echo base_url('admin/data_pegawai/delete_data/'.$p->id_pegawai) ?>">
                    <i class="fas fa-trash"></i>
                  </a>
                </center>
              </td>
            </tr>
          <?php endforeach; ?>
         </tbody>
       </table>
     </div>
   </div>
  </div>
</div>