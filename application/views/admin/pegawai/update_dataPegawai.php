<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $title?></h1>
  </div>
</div>

<div class="card" style="width: 80%; margin-bottom: 100px">
  <div class="card-header bg-primary text-white">
    <strong><i class="fas fa-user-edit"></i> Form Update Data Pegawai</strong>
  </div>
  <div class="card-body">

  <?php foreach ($pegawai as $p): ?>
  <form method="POST" action="<?php echo base_url('admin/data_pegawai/update_data_aksi')?>" enctype="multipart/form-data">

    <input type="hidden" name="id_pegawai" value="<?php echo $p->id_pegawai?>">

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- BAGIAN 1: DATA AKUN & PENEMPATAN                   -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="card border-left-primary mb-4">
      <div class="card-header bg-light font-weight-bold">
        <i class="fas fa-id-badge text-primary"></i> A. Data Akun & Penempatan
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Unit Pendidikan <span class="text-danger">*</span></label>
              <?php if($my_unit): ?>
                <?php $unit_info = isset($units[0]) ? $units[0] : null; ?>
                <input type="hidden" name="unit" value="<?php echo $my_unit?>">
                <input type="text" class="form-control bg-light"
                  value="<?php echo $unit_info ? $unit_info->nama_unit.' ('.$my_unit.')' : $my_unit?>" readonly>
                <small class="text-muted"><i class="fas fa-lock"></i> Dikunci sesuai unit Anda</small>
              <?php else: ?>
                <select name="unit" class="form-control">
                  <option value="">-- Pilih Unit --</option>
                  <?php foreach($units as $u): ?>
                  <option value="<?php echo $u->kode_unit?>"
                    <?php echo (isset($p->unit) && $p->unit == $u->kode_unit) ? 'selected' : ''?>>
                    <?php echo $u->kode_unit?> - <?php echo $u->nama_unit?>
                  </option>
                  <?php endforeach; ?>
                </select>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Jabatan <span class="text-danger">*</span></label>
              <select name="jabatan" class="form-control">
                <option value="">-- Pilih Jabatan --</option>
                <?php foreach($jabatan as $j): ?>
                <option value="<?php echo $j->nama_jabatan?>"
                  <?php echo ($p->jabatan == $j->nama_jabatan) ? 'selected' : ''?>>
                  <?php echo $j->nama_jabatan?>
                </option>
                <?php endforeach; ?>
              </select>
              <?php echo form_error('jabatan','<div class="text-small text-danger"></div>')?>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Hak Akses</label>
              <select name="hak_akses" class="form-control">
                <?php if($hak_akses == 1): ?>
                <option value="1" <?php echo ($p->hak_akses == 1) ? 'selected' : ''?>>Super Admin (Yayasan)</option>
                <option value="3" <?php echo ($p->hak_akses == 3) ? 'selected' : ''?>>Kepala Unit / Admin Unit</option>
                <?php endif; ?>
                <option value="2" <?php echo ($p->hak_akses == 2) ? 'selected' : ''?>>Pegawai</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Username</label>
              <input type="text" name="username" class="form-control"
                value="<?php echo $p->username?>" placeholder="Username login">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Password</label>
              <input type="password" name="password" class="form-control"
                placeholder="Kosongkan jika tidak ingin mengubah">
              <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" class="form-control"
                value="<?php echo isset($p->email) ? $p->email : ''?>"
                placeholder="contoh@email.com">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Tanggal Masuk <span class="text-danger">*</span></label>
              <input type="date" name="tanggal_masuk" class="form-control"
                value="<?php echo $p->tanggal_masuk?>">
              <?php echo form_error('tanggal_masuk','<div class="text-small text-danger"></div>')?>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Status Karyawan <span class="text-danger">*</span></label>
              <select name="status" class="form-control">
                <option value="">-- Pilih Status --</option>
                <option value="Karyawan Tetap"
                  <?php echo ($p->status == 'Karyawan Tetap') ? 'selected' : ''?>>
                  Karyawan Tetap
                </option>
                <option value="Karyawan Tidak Tetap"
                  <?php echo ($p->status == 'Karyawan Tidak Tetap') ? 'selected' : ''?>>
                  Karyawan Tidak Tetap
                </option>
              </select>
              <?php echo form_error('status','<div class="text-small text-danger"></div>')?>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Photo</label>
              <?php if(isset($p->photo) && $p->photo): ?>
              <div class="mb-2">
                <img src="<?php echo base_url('photo/'.$p->photo)?>" width="80" class="rounded border">
                <small class="text-muted ml-2">Photo saat ini</small>
              </div>
              <?php endif; ?>
              <input type="file" name="photo" class="form-control-file">
              <small class="text-muted">jpg/jpeg/png/tiff, maks 2MB. Kosongkan jika tidak ingin mengubah.</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- BAGIAN 2: IDENTITAS DIRI                           -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="card border-left-success mb-4">
      <div class="card-header bg-light font-weight-bold">
        <i class="fas fa-user text-success"></i> B. Identitas Diri
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-5">
            <div class="form-group">
              <label>Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="nama_pegawai" class="form-control"
                value="<?php echo $p->nama_pegawai?>" placeholder="Nama tanpa gelar">
              <?php echo form_error('nama_pegawai','<div class="text-small text-danger"></div>')?>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Gelar</label>
              <input type="text" name="gelar" class="form-control"
                value="<?php echo isset($p->gelar) ? $p->gelar : ''?>"
                placeholder="S.Pd., M.M., dll">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Nama Panggilan</label>
              <input type="text" name="nama_panggilan" class="form-control"
                value="<?php echo isset($p->nama_panggilan) ? $p->nama_panggilan : ''?>"
                placeholder="Nama panggilan sehari-hari">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>NIK <span class="text-danger">*</span></label>
              <input type="text" name="nik" class="form-control"
                value="<?php echo $p->nik?>" placeholder="16 digit NIK KTP" maxlength="20">
              <?php echo form_error('nik','<div class="text-small text-danger"></div>')?>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>No. Kartu Keluarga (KK)</label>
              <input type="text" name="no_kk" class="form-control"
                value="<?php echo isset($p->no_kk) ? $p->no_kk : ''?>"
                placeholder="16 digit no. KK" maxlength="20">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>NPWP</label>
              <input type="text" name="npwp" class="form-control"
                value="<?php echo isset($p->npwp) ? $p->npwp : ''?>"
                placeholder="No. NPWP" maxlength="20">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Tempat Lahir <span class="text-danger">*</span></label>
              <input type="text" name="tempat_lahir" class="form-control"
                value="<?php echo isset($p->tempat_lahir) ? $p->tempat_lahir : ''?>"
                placeholder="Kota tempat lahir">
              <?php echo form_error('tempat_lahir','<div class="text-small text-danger"></div>')?>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Tanggal Lahir</label>
              <input type="date" name="tanggal_lahir" class="form-control"
                value="<?php echo isset($p->tanggal_lahir) ? $p->tanggal_lahir : ''?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Jenis Kelamin <span class="text-danger">*</span></label>
              <select name="jenis_kelamin" class="form-control">
                <option value="">-- Pilih --</option>
                <option value="Laki-Laki" <?php echo ($p->jenis_kelamin == 'Laki-Laki') ? 'selected' : ''?>>Laki-Laki</option>
                <option value="Perempuan" <?php echo ($p->jenis_kelamin == 'Perempuan') ? 'selected' : ''?>>Perempuan</option>
              </select>
              <?php echo form_error('jenis_kelamin','<div class="text-small text-danger"></div>')?>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Golongan Darah</label>
              <select name="golongan_darah" class="form-control">
                <option value="">-- Pilih --</option>
                <?php foreach(['A','B','AB','O'] as $gd): ?>
                <option value="<?php echo $gd?>"
                  <?php echo (isset($p->golongan_darah) && $p->golongan_darah == $gd) ? 'selected' : ''?>>
                  <?php echo $gd?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Suku</label>
              <input type="text" name="suku" class="form-control"
                value="<?php echo isset($p->suku) ? $p->suku : ''?>"
                placeholder="Gayo, Aceh, Jawa, dll">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Agama</label>
              <select name="agama" class="form-control">
                <option value="">-- Pilih --</option>
                <?php foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag): ?>
                <option value="<?php echo $ag?>"
                  <?php echo (isset($p->agama) && $p->agama == $ag) ? 'selected' : ''?>>
                  <?php echo $ag?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Kewarganegaraan</label>
              <select name="kewarganegaraan" class="form-control">
                <option value="WNI" <?php echo (!isset($p->kewarganegaraan) || $p->kewarganegaraan == 'WNI') ? 'selected' : ''?>>WNI</option>
                <option value="WNA" <?php echo (isset($p->kewarganegaraan) && $p->kewarganegaraan == 'WNA') ? 'selected' : ''?>>WNA</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- BAGIAN 3: ALAMAT                                   -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="card border-left-info mb-4">
      <div class="card-header bg-light font-weight-bold">
        <i class="fas fa-map-marker-alt text-info"></i> C. Alamat Tempat Tinggal
      </div>
      <div class="card-body">
        <div class="form-group">
          <label>Alamat Lengkap</label>
          <textarea name="alamat" class="form-control" rows="2"
            placeholder="Jalan, Dusun/No. rumah"><?php echo isset($p->alamat) ? $p->alamat : ''?></textarea>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Desa / Kelurahan</label>
              <input type="text" name="desa" class="form-control"
                value="<?php echo isset($p->desa) ? $p->desa : ''?>"
                placeholder="Nama desa/kelurahan">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Kecamatan</label>
              <input type="text" name="kecamatan" class="form-control"
                value="<?php echo isset($p->kecamatan) ? $p->kecamatan : ''?>"
                placeholder="Nama kecamatan">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Kabupaten / Kota</label>
              <input type="text" name="kabupaten" class="form-control"
                value="<?php echo isset($p->kabupaten) ? $p->kabupaten : ''?>"
                placeholder="Nama kabupaten/kota">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- BAGIAN 4: DATA KELUARGA                            -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="card border-left-warning mb-4">
      <div class="card-header bg-light font-weight-bold">
        <i class="fas fa-home text-warning"></i> D. Data Keluarga
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Anak ke</label>
              <input type="number" name="anak_ke" class="form-control" min="1"
                value="<?php echo isset($p->anak_ke) ? $p->anak_ke : ''?>"
                placeholder="1">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Dari berapa bersaudara</label>
              <input type="number" name="dari_bersaudara" class="form-control" min="1"
                value="<?php echo isset($p->dari_bersaudara) ? $p->dari_bersaudara : ''?>"
                placeholder="3">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Saat ini tinggal bersama</label>
              <select name="tinggal_bersama" class="form-control">
                <option value="">-- Pilih --</option>
                <?php
                $tinggal_options = ['Ayah/Ibu','Suami','Istri','Sendiri (rumah sendiri)','Sendiri (rumah sewa)','Lainnya'];
                foreach($tinggal_options as $opt): ?>
                <option value="<?php echo $opt?>"
                  <?php echo (isset($p->tinggal_bersama) && $p->tinggal_bersama == $opt) ? 'selected' : ''?>>
                  <?php echo $opt?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Jumlah tanggungan</label>
              <input type="number" name="jumlah_tanggungan" class="form-control" min="0"
                value="<?php echo isset($p->jumlah_tanggungan) ? $p->jumlah_tanggungan : 0?>"
                placeholder="0">
              <small class="text-muted">anak, orang tua, dll</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- BAGIAN 5: REKENING BANK                            -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="card border-left-success mb-4">
      <div class="card-header bg-light font-weight-bold">
        <i class="fas fa-university text-success"></i> E. Rekening Bank
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-5">
            <div class="form-group">
              <label>Rekening Atas Nama</label>
              <input type="text" name="rekening_atas_nama" class="form-control"
                value="<?php echo isset($p->rekening_atas_nama) ? $p->rekening_atas_nama : ''?>"
                placeholder="Nama sesuai buku tabungan">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Nomor Rekening</label>
              <input type="text" name="no_rekening" class="form-control"
                value="<?php echo isset($p->no_rekening) ? $p->no_rekening : ''?>"
                placeholder="No. rekening Bank Aceh">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Bank</label>
              <input type="text" class="form-control bg-light"
                value="Bank Aceh (diutamakan)" readonly>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- BAGIAN 6: KEPRIBADIAN & KEAGAMAAN                  -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="card border-left-primary mb-4">
      <div class="card-header bg-light font-weight-bold">
        <i class="fas fa-heart text-primary"></i> F. Kepribadian & Keagamaan
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Karakter pribadi yang paling menonjol</label>
              <input type="text" name="karakter_pribadi" class="form-control"
                value="<?php echo isset($p->karakter_pribadi) ? $p->karakter_pribadi : ''?>"
                placeholder="Contoh: Ramah, Tegas, Sabar">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Hal yang paling tidak disukai secara umum</label>
              <input type="text" name="hal_tidak_disukai" class="form-control"
                value="<?php echo isset($p->hal_tidak_disukai) ? $p->hal_tidak_disukai : ''?>"
                placeholder="Contoh: Monoton, Kotor">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Ibadah wajib yang masih sering ketinggalan</label>
              <input type="text" name="ibadah_wajib_ketinggalan" class="form-control"
                value="<?php echo isset($p->ibadah_wajib_ketinggalan) ? $p->ibadah_wajib_ketinggalan : ''?>"
                placeholder="Contoh: Sholat Isya, Tidak ada">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Ibadah sunnah yang paling sering diamalkan</label>
              <input type="text" name="ibadah_sunnah" class="form-control"
                value="<?php echo isset($p->ibadah_sunnah) ? $p->ibadah_sunnah : ''?>"
                placeholder="Contoh: Dhuha, Tahajud, Istighfar">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- BAGIAN 7: LITERASI & SOSIAL MEDIA                  -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="card border-left-info mb-4">
      <div class="card-header bg-light font-weight-bold">
        <i class="fas fa-book-open text-info"></i> G. Literasi & Sosial Media
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Jumlah buku yang dimiliki di rumah</label>
              <input type="number" name="jumlah_buku" class="form-control" min="0"
                value="<?php echo isset($p->jumlah_buku) ? $p->jumlah_buku : 0?>"
                placeholder="0">
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>Judul buku yang pernah dibaca</label>
              <input type="text" name="judul_buku_dibaca" class="form-control"
                value="<?php echo isset($p->judul_buku_dibaca) ? $p->judul_buku_dibaca : ''?>"
                placeholder="Sebutkan judul-judul buku">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Akun sosial media yang dimiliki</label>
              <input type="text" name="akun_sosmed" class="form-control"
                value="<?php echo isset($p->akun_sosmed) ? $p->akun_sosmed : ''?>"
                placeholder="Contoh: @nama_akun (Instagram)">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Sosial media yang paling sering diakses</label>
              <select name="sosmed_sering" class="form-control">
                <option value="">-- Pilih --</option>
                <?php
                $sosmed_options = ['WhatsApp','Instagram','Facebook','YouTube','TikTok','Twitter/X','Telegram','Lainnya'];
                foreach($sosmed_options as $sm): ?>
                <option value="<?php echo $sm?>"
                  <?php echo (isset($p->sosmed_sering) && $p->sosmed_sering == $sm) ? 'selected' : ''?>>
                  <?php echo $sm?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Konten yang paling digemari</label>
              <input type="text" name="konten_digemari" class="form-control"
                value="<?php echo isset($p->konten_digemari) ? $p->konten_digemari : ''?>"
                placeholder="Contoh: Islami, Bisnis, Edukasi">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- BAGIAN 8: MINAT, BAKAT & KEAHLIAN                  -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="card border-left-warning mb-4">
      <div class="card-header bg-light font-weight-bold">
        <i class="fas fa-star text-warning"></i> H. Minat, Bakat & Keahlian
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Hobi yang masih sering dilakukan</label>
              <input type="text" name="hobi" class="form-control"
                value="<?php echo isset($p->hobi) ? $p->hobi : ''?>"
                placeholder="Contoh: Membaca, Futsal, Memasak">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Minat bakat yang ingin dikembangkan</label>
              <input type="text" name="minat_bakat" class="form-control"
                value="<?php echo isset($p->minat_bakat) ? $p->minat_bakat : ''?>"
                placeholder="Contoh: Desain grafis, Public speaking">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Keahlian yang dimiliki</label>
              <input type="text" name="keahlian" class="form-control"
                value="<?php echo isset($p->keahlian) ? $p->keahlian : ''?>"
                placeholder="Contoh: Coreldraw, Mengajar, Menjahit">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Tokoh agama/politik/publik figur yang dikagumi</label>
              <input type="text" name="tokoh_dikagumi" class="form-control"
                value="<?php echo isset($p->tokoh_dikagumi) ? $p->tokoh_dikagumi : ''?>"
                placeholder="Contoh: Ust. Adi Hidayat, B.J. Habibie">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- BAGIAN 9: RIWAYAT KESEHATAN                        -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="card border-left-danger mb-4">
      <div class="card-header bg-light font-weight-bold">
        <i class="fas fa-heartbeat text-danger"></i> I. Riwayat Kesehatan
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Riwayat penyakit yang pernah diderita</label>
              <textarea name="riwayat_penyakit" class="form-control" rows="2"
                placeholder="Contoh: Tipes, Patah tulang, Tidak ada"><?php echo isset($p->riwayat_penyakit) ? $p->riwayat_penyakit : ''?></textarea>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Status penyakit</label>
              <select name="status_penyakit" class="form-control">
                <option value="">-- Pilih --</option>
                <?php
                $status_penyakit_options = [
                  'Sudah sembuh total',
                  'Masih dalam penanganan',
                  'Tidak ada riwayat penyakit'
                ];
                foreach($status_penyakit_options as $sp): ?>
                <option value="<?php echo $sp?>"
                  <?php echo (isset($p->status_penyakit) && $p->status_penyakit == $sp) ? 'selected' : ''?>>
                  <?php echo $sp?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Pantangan / hal yang harus dihindari</label>
              <textarea name="pantangan" class="form-control" rows="2"
                placeholder="Isi jika masih dalam penanganan, atau tulis '-' jika tidak ada"><?php echo isset($p->pantangan) ? $p->pantangan : ''?></textarea>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- TOMBOL SUBMIT                                      -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="text-right">
      <a href="<?php echo base_url('admin/data_pegawai')?>" class="btn btn-warning mr-2">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
      <button type="reset" class="btn btn-secondary mr-2">
        <i class="fas fa-undo"></i> Reset
      </button>
      <button type="submit" class="btn btn-success btn-lg">
        <i class="fas fa-save"></i> Simpan Perubahan
      </button>
    </div>

  </form>
  <?php endforeach; ?>
  </div>
</div>