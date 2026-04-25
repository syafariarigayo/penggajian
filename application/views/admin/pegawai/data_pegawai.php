<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $title?></h1>
  </div>

  <div class="alert alert-info">
    <i class="fas fa-school"></i>
    <strong>Unit:</strong> <?php echo $nama_unit?>
  </div>

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
              <th class="text-center">Jabatan</th>
              <th class="text-center">Unit</th>
              <th class="text-center">Jenis Kelamin</th>
              <th class="text-center">Status</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; foreach($pegawai as $p) : ?>
            <tr>
              <td class="text-center"><?php echo $no++?></td>
              <td class="text-center"><?php echo $p->nik?></td>
              <td><?php echo $p->nama_pegawai?></td>
              <td class="text-center"><?php echo $p->jabatan?></td>
              <td class="text-center">
                <span class="badge badge-info"><?php echo $p->unit?></span>
              </td>
              <td class="text-center"><?php echo $p->jenis_kelamin?></td>
              <td class="text-center">
                <span class="badge badge-<?php echo ($p->status == 'Karyawan Tetap') ? 'success' : 'warning'?>">
                  <?php echo $p->status?>
                </span>
              </td>
              <td class="text-center">
                <a class="btn btn-sm btn-info" href="<?php echo base_url('admin/data_pegawai/update_data/'.$p->id_pegawai) ?>">
                  <i class="fas fa-edit"></i>
                </a>
                <?php if($hak_akses == 1): ?>
                <a onclick="return confirm('Yakin hapus data ini?')"
                   class="btn btn-sm btn-danger"
                   href="<?php echo base_url('admin/data_pegawai/delete_data/'.$p->id_pegawai) ?>">
                  <i class="fas fa-trash"></i>
                </a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>