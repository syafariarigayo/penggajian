<?php
// ============================================================
// application/controllers/admin/Data_Absensi.php
// ============================================================
class Data_Absensi extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->cek_admin();
    }

    public function index()
    {
        $data['title'] = "Data Absensi Pegawai";
        $my_unit       = $this->get_my_unit();

        if(isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != '') {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        $bulantahun  = $bulan . $tahun;
        $unit_filter = $my_unit ? "AND dp.unit = '$my_unit'" : "";

        $data['absensi'] = $this->db->query(
            "SELECT dk.*, dp.nama_pegawai, dp.jenis_kelamin, dp.jabatan
             FROM data_kehadiran dk
             INNER JOIN data_pegawai dp ON dk.nik = dp.nik
             INNER JOIN data_jabatan dj ON dp.jabatan = dj.nama_jabatan
             WHERE dk.bulan = '$bulantahun' $unit_filter
             ORDER BY dp.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/absensi/data_absensi', $data);
        $this->load->view('template_admin/footer');
    }

    public function input_absensi()
    {
        if($this->input->post('submit', TRUE) == 'submit') {
            $post   = $this->input->post();
            $simpan = array();
            foreach ($post['bulan'] as $key => $value) {
                if($post['bulan'][$key] != '' || $post['nik'][$key] != '') {
                    $simpan[] = array(
                        'bulan'         => $post['bulan'][$key],
                        'nik'           => $post['nik'][$key],
                        'nama_pegawai'  => $post['nama_pegawai'][$key],
                        'jenis_kelamin' => $post['jenis_kelamin'][$key],
                        'nama_jabatan'  => $post['nama_jabatan'][$key],
                        'hadir'         => $post['hadir'][$key],
                        'sakit'         => $post['sakit'][$key],
                        'alpha'         => $post['alpha'][$key],
                    );
                }
            }
            $this->ModelPenggajian->insert_batch('data_kehadiran', $simpan);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Data berhasil ditambahkan!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            redirect('admin/data_absensi');
        }

        $data['title'] = "Form Input Absensi";
        $my_unit       = $this->get_my_unit();

        if(isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != '') {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        $bulantahun  = $bulan . $tahun;
        $unit_filter = $my_unit ? "AND dp.unit = '$my_unit'" : "";

        $data['input_absensi'] = $this->db->query(
            "SELECT dp.*, dj.nama_jabatan
             FROM data_pegawai dp
             INNER JOIN data_jabatan dj ON dp.jabatan = dj.nama_jabatan
             WHERE NOT EXISTS (
                 SELECT * FROM data_kehadiran
                 WHERE bulan = '$bulantahun' AND dp.nik = data_kehadiran.nik
             ) $unit_filter
             ORDER BY dp.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/absensi/tambah_dataAbsensi', $data);
        $this->load->view('template_admin/footer');
    }
}


// ============================================================
// application/controllers/admin/Data_Jabatan.php
// ============================================================
class Data_Jabatan extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->cek_admin();
    }

    public function index()
    {
        $data['title']   = "Data Jabatan";
        $data['jabatan'] = $this->ModelPenggajian->get_data('data_jabatan')->result();
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/jabatan/data_jabatan', $data);
        $this->load->view('template_admin/footer');
    }

    public function tambah_data()
    {
        $data['title'] = "Tambah Data Jabatan";
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/jabatan/tambah_dataJabatan', $data);
        $this->load->view('template_admin/footer');
    }

    public function tambah_data_aksi()
    {
        $this->_rules();
        if($this->form_validation->run() == FALSE) {
            $this->tambah_data();
        } else {
            $this->ModelPenggajian->insert_data(array(
                'nama_jabatan' => $this->input->post('nama_jabatan'),
                'gaji_pokok'   => $this->input->post('gaji_pokok'),
                'tj_transport' => $this->input->post('tj_transport'),
                'uang_makan'   => $this->input->post('uang_makan'),
            ), 'data_jabatan');
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Data berhasil ditambahkan!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            redirect('admin/data_jabatan');
        }
    }

    public function update_data($id)
    {
        $data['jabatan'] = $this->db->query("SELECT * FROM data_jabatan WHERE id_jabatan = '$id'")->result();
        $data['title']   = "Update Data Jabatan";
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/jabatan/update_dataJabatan', $data);
        $this->load->view('template_admin/footer');
    }

    public function update_data_aksi()
    {
        $this->_rules();
        if($this->form_validation->run() == FALSE) {
            $this->update_data($this->input->post('id_jabatan'));
        } else {
            $this->ModelPenggajian->update_data('data_jabatan', array(
                'nama_jabatan' => $this->input->post('nama_jabatan'),
                'gaji_pokok'   => $this->input->post('gaji_pokok'),
                'tj_transport' => $this->input->post('tj_transport'),
                'uang_makan'   => $this->input->post('uang_makan'),
            ), array('id_jabatan' => $this->input->post('id_jabatan')));
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Data berhasil diupdate!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            redirect('admin/data_jabatan');
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nama_jabatan', 'Nama Jabatan', 'required');
        $this->form_validation->set_rules('gaji_pokok', 'Gaji Pokok', 'required');
        $this->form_validation->set_rules('tj_transport', 'Tunjangan Transport', 'required');
        $this->form_validation->set_rules('uang_makan', 'Uang Makan', 'required');
    }

    public function delete_data($id)
    {
        $this->ModelPenggajian->delete_data(array('id_jabatan' => $id), 'data_jabatan');
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Data berhasil dihapus!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        redirect('admin/data_jabatan');
    }
}


// ============================================================
// application/controllers/admin/Data_Penggajian.php
// ============================================================
class Data_Penggajian extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->cek_admin();
    }

    public function index()
    {
        $data['title'] = "Data Gaji Pegawai";
        $my_unit       = $this->get_my_unit();

        if(isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != '') {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        $bulantahun  = $bulan . $tahun;
        $unit_filter = $my_unit ? "AND dp.unit = '$my_unit'" : "";

        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        $data['gaji']     = $this->db->query(
            "SELECT dp.nik, dp.nama_pegawai, dp.jenis_kelamin,
                    dj.nama_jabatan, dj.gaji_pokok, dj.tj_transport, dj.uang_makan,
                    dk.alpha
             FROM data_pegawai dp
             INNER JOIN data_kehadiran dk ON dk.nik = dp.nik
             INNER JOIN data_jabatan dj ON dj.nama_jabatan = dp.jabatan
             WHERE dk.bulan = '$bulantahun' $unit_filter
             ORDER BY dp.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/gaji/data_gaji', $data);
        $this->load->view('template_admin/footer');
    }

    public function cetak_gaji()
    {
        $data['title'] = "Cetak Data Gaji Pegawai";
        $my_unit       = $this->get_my_unit();

        if(isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != '') {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        $bulantahun  = $bulan . $tahun;
        $unit_filter = $my_unit ? "AND dp.unit = '$my_unit'" : "";

        $data['potongan']   = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        $data['cetak_gaji'] = $this->db->query(
            "SELECT dp.nik, dp.nama_pegawai, dp.jenis_kelamin,
                    dj.nama_jabatan, dj.gaji_pokok, dj.tj_transport, dj.uang_makan,
                    dk.alpha
             FROM data_pegawai dp
             INNER JOIN data_kehadiran dk ON dk.nik = dp.nik
             INNER JOIN data_jabatan dj ON dj.nama_jabatan = dp.jabatan
             WHERE dk.bulan = '$bulantahun' $unit_filter
             ORDER BY dp.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('admin/gaji/cetak_gaji', $data);
    }
}


// ============================================================
// application/controllers/admin/Laporan_Absensi.php
// ============================================================
class Laporan_Absensi extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->cek_admin();
    }

    public function index()
    {
        $data['title'] = "Laporan Absensi Pegawai";
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/absensi/laporan_absensi');
        $this->load->view('template_admin/footer');
    }

    public function cetak_laporan_absensi()
    {
        $data['title'] = "Cetak Laporan Absensi Pegawai";
        $my_unit       = $this->get_my_unit();

        if(isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != '') {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        $bulantahun  = $bulan . $tahun;
        $unit_filter = $my_unit ? "AND dp.unit = '$my_unit'" : "";

        $data['lap_kehadiran'] = $this->db->query(
            "SELECT dk.*
             FROM data_kehadiran dk
             INNER JOIN data_pegawai dp ON dk.nik = dp.nik
             WHERE dk.bulan = '$bulantahun' $unit_filter
             ORDER BY dk.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('admin/absensi/cetak_absensi', $data);
    }
}


// ============================================================
// application/controllers/admin/Laporan_Gaji.php
// ============================================================
class Laporan_Gaji extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->cek_admin();
    }

    public function index()
    {
        $data['title'] = "Laporan Gaji Pegawai";
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/gaji/laporan_gaji');
        $this->load->view('template_admin/footer');
    }

    public function cetak_laporan_gaji()
    {
        $data['title'] = "Cetak Laporan Gaji Pegawai";
        $my_unit       = $this->get_my_unit();

        if(isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != '') {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        $bulantahun  = $bulan . $tahun;
        $unit_filter = $my_unit ? "AND dp.unit = '$my_unit'" : "";

        $data['potongan']   = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        $data['cetak_gaji'] = $this->db->query(
            "SELECT dp.nik, dp.nama_pegawai, dp.jenis_kelamin,
                    dj.nama_jabatan, dj.gaji_pokok, dj.tj_transport, dj.uang_makan,
                    dk.alpha
             FROM data_pegawai dp
             INNER JOIN data_kehadiran dk ON dk.nik = dp.nik
             INNER JOIN data_jabatan dj ON dj.nama_jabatan = dp.jabatan
             WHERE dk.bulan = '$bulantahun' $unit_filter
             ORDER BY dp.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('admin/gaji/cetak_gaji', $data);
    }
}


// ============================================================
// application/controllers/admin/Penilaian_Karyawan.php
// ============================================================
class Penilaian_Karyawan extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->cek_admin();
    }

    public function index()
    {
        $data['title'] = "Data Penilaian Karyawan";
        $my_unit       = $this->get_my_unit();

        if(isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != '') {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        $bulantahun  = $bulan . $tahun;
        $unit_filter = $my_unit ? "AND dp.unit = '$my_unit'" : "";

        $data['penilaian'] = $this->db->query(
            "SELECT dpn.*
             FROM data_penilaian dpn
             INNER JOIN data_pegawai dp ON dpn.nik = dp.nik
             WHERE dpn.bulan = '$bulantahun' $unit_filter
             ORDER BY dpn.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/penilaian/data_penilaian', $data);
        $this->load->view('template_admin/footer');
    }

    public function input_penilaian()
    {
        if($this->input->post('submit', TRUE) == 'submit') {
            $post   = $this->input->post();
            $simpan = array();
            foreach ($post['bulan'] as $key => $value) {
                if($post['bulan'][$key] != '' || $post['nik'][$key] != '') {
                    $total = $post['kedisiplinan'][$key] + $post['kerjasama'][$key]
                           + $post['tanggung_jawab'][$key] + $post['kualitas_kerja'][$key];
                    if($total >= 340)     $kategori = "Sangat Baik";
                    elseif($total >= 260) $kategori = "Baik";
                    elseif($total >= 180) $kategori = "Cukup";
                    else                  $kategori = "Kurang";

                    $simpan[] = array(
                        'bulan'          => $post['bulan'][$key],
                        'nik'            => $post['nik'][$key],
                        'nama_pegawai'   => $post['nama_pegawai'][$key],
                        'jabatan'        => $post['jabatan'][$key],
                        'kedisiplinan'   => $post['kedisiplinan'][$key],
                        'kerjasama'      => $post['kerjasama'][$key],
                        'tanggung_jawab' => $post['tanggung_jawab'][$key],
                        'kualitas_kerja' => $post['kualitas_kerja'][$key],
                        'total_nilai'    => $total,
                        'kategori'       => $kategori,
                    );
                }
            }
            $this->ModelPenggajian->insert_batch('data_penilaian', $simpan);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Data penilaian berhasil ditambahkan!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            redirect('admin/penilaian_karyawan');
        }

        $data['title'] = "Form Input Penilaian Karyawan";
        $my_unit       = $this->get_my_unit();

        if(isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != '') {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        $bulantahun  = $bulan . $tahun;
        $unit_filter = $my_unit ? "AND dp.unit = '$my_unit'" : "";

        $data['input_penilaian'] = $this->db->query(
            "SELECT dp.*, dj.nama_jabatan
             FROM data_pegawai dp
             INNER JOIN data_jabatan dj ON dp.jabatan = dj.nama_jabatan
             WHERE NOT EXISTS (
                 SELECT * FROM data_penilaian
                 WHERE bulan = '$bulantahun' AND dp.nik = data_penilaian.nik
             ) $unit_filter
             ORDER BY dp.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/penilaian/tambah_penilaian', $data);
        $this->load->view('template_admin/footer');
    }

    public function edit_penilaian($id)
    {
        $data['title']     = "Edit Penilaian Karyawan";
        $data['penilaian'] = $this->db->query("SELECT * FROM data_penilaian WHERE id_penilaian = '$id'")->result();
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/penilaian/edit_penilaian', $data);
        $this->load->view('template_admin/footer');
    }

    public function edit_penilaian_aksi()
    {
        $id             = $this->input->post('id_penilaian');
        $kedisiplinan   = $this->input->post('kedisiplinan');
        $kerjasama      = $this->input->post('kerjasama');
        $tanggung_jawab = $this->input->post('tanggung_jawab');
        $kualitas_kerja = $this->input->post('kualitas_kerja');
        $total          = $kedisiplinan + $kerjasama + $tanggung_jawab + $kualitas_kerja;

        if($total >= 340)     $kategori = "Sangat Baik";
        elseif($total >= 260) $kategori = "Baik";
        elseif($total >= 180) $kategori = "Cukup";
        else                  $kategori = "Kurang";

        $this->ModelPenggajian->update_data('data_penilaian', array(
            'kedisiplinan'   => $kedisiplinan,
            'kerjasama'      => $kerjasama,
            'tanggung_jawab' => $tanggung_jawab,
            'kualitas_kerja' => $kualitas_kerja,
            'total_nilai'    => $total,
            'kategori'       => $kategori,
        ), array('id_penilaian' => $id));

        $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Data penilaian berhasil diupdate!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        redirect('admin/penilaian_karyawan');
    }

    public function delete_penilaian($id)
    {
        $this->ModelPenggajian->delete_data(array('id_penilaian' => $id), 'data_penilaian');
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Data penilaian berhasil dihapus!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        redirect('admin/penilaian_karyawan');
    }

    public function laporan_penilaian()
    {
        $data['title'] = "Laporan Penilaian Karyawan";
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/penilaian/laporan_penilaian');
        $this->load->view('template_admin/footer');
    }

    public function cetak_laporan_penilaian()
    {
        $data['title'] = "Cetak Laporan Penilaian Karyawan";
        $my_unit       = $this->get_my_unit();

        if(isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != '') {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }
        $bulantahun  = $bulan . $tahun;
        $unit_filter = $my_unit ? "AND dp.unit = '$my_unit'" : "";

        $data['lap_penilaian'] = $this->db->query(
            "SELECT dpn.*
             FROM data_penilaian dpn
             INNER JOIN data_pegawai dp ON dpn.nik = dp.nik
             WHERE dpn.bulan = '$bulantahun' $unit_filter
             ORDER BY dpn.nama_pegawai ASC"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('admin/penilaian/cetak_penilaian', $data);
    }
}


// ============================================================
// application/controllers/admin/Slip_Gaji.php
// ============================================================
class Slip_Gaji extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->cek_admin();
    }

    public function index()
    {
        $data['title'] = "Slip Gaji Pegawai";
        $my_unit       = $this->get_my_unit();

        if($my_unit) {
            $data['pegawai'] = $this->db->where('unit', $my_unit)->get('data_pegawai')->result();
        } else {
            $data['pegawai'] = $this->ModelPenggajian->get_data('data_pegawai')->result();
        }

        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/gaji/slip_gaji', $data);
        $this->load->view('template_admin/footer');
    }

    public function cetak_slip_gaji()
    {
        $data['title']    = "Cetak Slip Gaji Pegawai";
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        $nama             = $this->input->post('nama_pegawai');
        $bulan            = $this->input->post('bulan');
        $tahun            = $this->input->post('tahun');
        $bulantahun       = $bulan . $tahun;

        $data['print_slip'] = $this->db->query(
            "SELECT dp.nik, dp.nama_pegawai, dj.nama_jabatan,
                    dj.gaji_pokok, dj.tj_transport, dj.uang_makan,
                    dk.alpha, dk.bulan
             FROM data_pegawai dp
             INNER JOIN data_kehadiran dk ON dk.nik = dp.nik
             INNER JOIN data_jabatan dj ON dj.nama_jabatan = dp.jabatan
             WHERE dk.bulan = '$bulantahun' AND dk.nama_pegawai = '$nama'"
        )->result();

        $this->load->view('template_admin/header', $data);
        $this->load->view('admin/gaji/cetak_slip_gaji', $data);
    }
}


// ============================================================
// application/controllers/admin/Potongan_Gaji.php
// ============================================================
class Potongan_Gaji extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('ModelPotongan_Gaji');
        $this->cek_admin();
    }

    function index()
    {
        $data['title'] = "Setting Potongan Gaji";
        $this->load->view('template_admin/header');
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/potongan_gaji/list_potonganGaji', $data);
        $this->load->view('template_admin/footer');
    }

    function TampilPotongan()
    {
        $data['hasil'] = $this->ModelPotongan_Gaji->TampilPotongan();
        $this->load->view('admin/potongan_gaji/data_potonganGaji', $data);
    }

    function tambah_potonganGaji()
    {
        $aksi = $this->input->post('aksi');
        $this->load->view('admin/potongan_gaji/tambah_potonganGaji', $aksi);
    }

    function edit_potonganGaji()
    {
        $potongan      = $this->input->post('potongan');
        $data['hasil'] = $this->ModelPotongan_Gaji->Getpotongan($potongan);
        $this->load->view('admin/potongan_gaji/edit_potonganGaji', $data);
    }

    function hapus_potonganGaji()
    {
        $potongan      = $this->input->post('potongan');
        $data['hasil'] = $this->ModelPotongan_Gaji->Getpotongan($potongan);
        $this->load->view('admin/potongan_gaji/hapus_potonganGaji', $data);
    }

    function simpanPotongan()
    {
        $this->db->insert('potongan_gaji', array(
            'potongan'     => $this->input->post('potongan'),
            'jml_potongan' => $this->input->post('jml_potongan'),
        ));
    }

    function editPotongan()
    {
        $this->db->where('potongan', $this->input->post('potongan_lama'));
        $this->db->update('potongan_gaji', array(
            'potongan'     => $this->input->post('potongan_baru'),
            'jml_potongan' => $this->input->post('jml_potongan'),
        ));
    }

    function hapusPotongan()
    {
        $this->db->delete('potongan_gaji', array('potongan' => $this->input->post('potongan')));
    }
}