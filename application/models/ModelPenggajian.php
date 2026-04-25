<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelPenggajian extends CI_Model {

    public function get_data($table) {
        return $this->db->get($table);
    }

    public function insert_data($data, $table) {
        $this->db->insert($table, $data);
    }

    public function update_data($table, $data, $where) {
        $this->db->update($table, $data, $where);
    }

    public function delete_data($where, $table) {
        $this->db->where($where);
        $this->db->delete($table);
    }

    public function insert_batch($table = null, $data = array()) {
        if (count($data) > 0) {
            $this->db->insert_batch($table, $data);
        }
    }

    /**
     * PERBAIKAN: gunakan $this->input->post() bukan set_value()
     * set_value() adalah form helper untuk mengisi ulang form, bukan untuk membaca POST
     */
    public function cek_login($username = null, $password = null)
    {
        // Terima parameter langsung dari controller (lebih baik)
        if($username === null) {
            $username = $this->input->post('username');
        }
        if($password === null) {
            $password = $this->input->post('password');
        }

        $result = $this->db
                       ->where('username', $username)
                       ->where('password', md5($password))
                       ->limit(1)
                       ->get('data_pegawai');

        if($result->num_rows() > 0) {
            return $result->row();
        }
        return FALSE;
    }
}
