<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Potongan_Gaji extends MY_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('ModelPotongan_Gaji');
        $this->cek_admin();
    }

    public function index()
    {
        $data['title'] = "Setting Potongan Gaji";
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/potongan_gaji/list_potonganGaji', $data);
        $this->load->view('template_admin/footer');
    }

    public function TampilPotongan()
    {
        $data['hasil'] = $this->ModelPotongan_Gaji->TampilPotongan();
        $this->load->view('admin/potongan_gaji/data_potonganGaji', $data);
    }

    public function tambah_potonganGaji()
    {
        $aksi = $this->input->post('aksi');
        $this->load->view('admin/potongan_gaji/tambah_potonganGaji', $aksi);
    }

    public function edit_potonganGaji()
    {
        $potongan      = $this->input->post('potongan');
        $data['hasil'] = $this->ModelPotongan_Gaji->Getpotongan($potongan);
        $this->load->view('admin/potongan_gaji/edit_potonganGaji', $data);
    }

    public function hapus_potonganGaji()
    {
        $potongan      = $this->input->post('potongan');
        $data['hasil'] = $this->ModelPotongan_Gaji->Getpotongan($potongan);
        $this->load->view('admin/potongan_gaji/hapus_potonganGaji', $data);
    }

    public function simpanPotongan()
    {
        $this->db->insert('potongan_gaji', array(
            'potongan'     => $this->input->post('potongan'),
            'jml_potongan' => $this->input->post('jml_potongan'),
        ));
    }

    public function editPotongan()
    {
        $this->db->where('potongan', $this->input->post('potongan_lama'));
        $this->db->update('potongan_gaji', array(
            'potongan'     => $this->input->post('potongan_baru'),
            'jml_potongan' => $this->input->post('jml_potongan'),
        ));
    }

    public function hapusPotongan()
    {
        $this->db->delete('potongan_gaji', array('potongan' => $this->input->post('potongan')));
    }
}
