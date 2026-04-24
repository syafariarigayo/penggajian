<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Controller
 * Base controller — semua controller admin EXTEND kelas ini
 * agar cek hak akses cukup ditulis satu kali.
 *
 * Letakkan file ini di: application/core/MY_Controller.php
 */
class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Cek apakah user boleh akses halaman admin.
     * Hak akses yang diizinkan: 1 (Super Admin) atau 3 (Kepala Unit).
     * Jika tidak, redirect ke login.
     */
    protected function cek_admin() {
        $hak = $this->session->userdata('hak_akses');
        if($hak != '1' && $hak != '3') {
            $this->session->set_flashdata('pesan',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Anda Belum Login!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>');
            redirect('login');
        }
    }

    /**
     * Khusus Super Admin saja (hak_akses == 1).
     */
    protected function cek_super_admin() {
        $hak = $this->session->userdata('hak_akses');
        if($hak != '1') {
            $this->session->set_flashdata('pesan',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Akses Ditolak!</strong> Fitur ini hanya untuk Super Admin.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>');
            redirect('admin/dashboard');
        }
    }

    /**
     * Kembalikan kode unit user yang sedang login.
     * Super Admin (hak 1) -> null (artinya akses semua unit).
     * Kepala Unit (hak 3) -> kode unitnya.
     */
    protected function get_my_unit() {
        $hak = $this->session->userdata('hak_akses');
        if($hak == '1') return null;
        $id = $this->session->userdata('id_pegawai');
        $p  = $this->db->get_where('data_pegawai', array('id_pegawai' => $id))->row();
        return $p ? $p->unit : null;
    }
}