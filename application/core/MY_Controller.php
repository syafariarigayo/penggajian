<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Controller
 * Letakkan di: application/core/MY_Controller.php
 *
 * PENTING: Nama file HARUS MY_Controller.php (persis)
 *          Nama class HARUS MY_Controller (persis)
 *          CodeIgniter otomatis load file ini karena prefix MY_
 */
class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Cek akses admin: izinkan hak_akses 1 (Super Admin) dan 3 (Kepala Unit)
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
                </div>'
            );
            redirect('login');
        }
    }

    /**
     * Cek akses Super Admin saja (hak_akses 1)
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
                </div>'
            );
            redirect('admin/dashboard');
        }
    }

    /**
     * Ambil kode unit user yang login.
     * Return NULL jika Super Admin (boleh akses semua unit).
     * Return kode_unit jika Kepala Unit.
     */
    protected function get_my_unit() {
        $hak = $this->session->userdata('hak_akses');
        if($hak == '1') {
            return null; // Super Admin -> akses semua
        }
        $id_pegawai = $this->session->userdata('id_pegawai');
        if(!$id_pegawai) return null;
        $pegawai = $this->db->get_where('data_pegawai', array('id_pegawai' => $id_pegawai))->row();
        return ($pegawai && isset($pegawai->unit)) ? $pegawai->unit : null;
    }
}