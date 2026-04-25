<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * API Controller untuk ZKTeco Push Attendance
 * Letakkan di: application/controllers/Api.php
 * 
 * URL endpoint: http://yourserver/penggajian/api/zkteco
 * Method: POST
 */
class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ModelFingerprint');
        // Nonaktifkan CSRF untuk API endpoint
        $this->security->csrf_verify();
    }

    // =====================================================
    // ENDPOINT UTAMA - ZKTeco Push Attendance
    // URL: POST /api/zkteco
    // =====================================================
    public function zkteco()
    {
        // Hanya terima POST
        if($this->input->method() !== 'post') {
            $this->_response(405, 'Method Not Allowed');
            return;
        }

        // Ambil data dari ZKTeco (bisa JSON atau form-data)
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        // Jika bukan JSON, coba ambil dari POST
        if(!$data) {
            $data = $this->input->post();
        }

        // Log raw data untuk debugging
        log_message('info', 'ZKTeco Push Data: ' . json_encode($data));

        if(empty($data)) {
            $this->_response(400, 'Bad Request - No data received');
            return;
        }

        // Proses data dari ZKTeco
        // ZKTeco biasanya kirim dalam format:
        // {"sn":"device_serial","table":"att_log","data":[{"pin":"NIK","time":"2024-01-15 07:30:00","status":0}]}
        
        if(isset($data['table']) && $data['table'] == 'att_log' && isset($data['data'])) {
            // Format Push Attendance ZKTeco
            $this->_proses_push_attendance($data);
        } elseif(isset($data['pin'])) {
            // Format single record
            $this->_proses_single_record($data);
        } else {
            // Coba proses sebagai array langsung
            $this->_proses_push_attendance(['sn' => 'unknown', 'data' => [$data]]);
        }
    }

    // =====================================================
    // Proses data Push Attendance dari ZKTeco
    // =====================================================
    private function _proses_push_attendance($payload)
    {
        $device_id   = isset($payload['sn']) ? $payload['sn'] : 'unknown';
        $records     = isset($payload['data']) ? $payload['data'] : [];
        $berhasil    = 0;
        $gagal       = 0;

        // Update last_sync device
        $this->ModelFingerprint->update_device_sync($device_id);

        foreach($records as $record) {
            // ZKTeco format: pin=NIK, time=waktu, status=0(masuk)/1(pulang)
            $nik    = isset($record['pin'])    ? trim($record['pin'])    : null;
            $waktu  = isset($record['time'])   ? trim($record['time'])   : null;
            $status = isset($record['status']) ? (int)$record['status'] : 0;

            if(!$nik || !$waktu) {
                $gagal++;
                continue;
            }

            // Validasi NIK ada di database pegawai
            $pegawai = $this->db->get_where('data_pegawai', ['nik' => $nik])->row();
            if(!$pegawai) {
                log_message('error', 'ZKTeco: NIK tidak ditemukan - ' . $nik);
                $gagal++;
                continue;
            }

            // Tentukan tipe: masuk atau pulang
            // ZKTeco status: 0=masuk, 1=pulang, 4=lembur masuk, 5=lembur pulang
            $tipe = ($status == 0 || $status == 4) ? 'masuk' : 'pulang';

            // Simpan ke attendance_log
            $log_data = [
                'nik'         => $nik,
                'device_id'   => $device_id,
                'device_name' => $this->_get_device_name($device_id),
                'waktu'       => date('Y-m-d H:i:s', strtotime($waktu)),
                'tipe'        => $tipe,
                'status'      => 'pending',
            ];

            // Cek duplikat (dalam 1 menit yang sama)
            $cek_duplikat = $this->db->where('nik', $nik)
                                     ->where('waktu', $log_data['waktu'])
                                     ->count_all_results('attendance_log');

            if($cek_duplikat == 0) {
                $this->db->insert('attendance_log', $log_data);
                $berhasil++;

                // Langsung proses rekap harian
                $this->ModelFingerprint->proses_rekap_harian($nik, date('Y-m-d', strtotime($waktu)));
            }
        }

        log_message('info', "ZKTeco: Berhasil=$berhasil, Gagal=$gagal");
        $this->_response(200, 'OK', ['berhasil' => $berhasil, 'gagal' => $gagal]);
    }

    // =====================================================
    // Proses single record
    // =====================================================
    private function _proses_single_record($data)
    {
        $this->_proses_push_attendance([
            'sn'   => isset($data['sn']) ? $data['sn'] : 'unknown',
            'data' => [$data]
        ]);
    }

    // =====================================================
    // Ambil nama device dari database
    // =====================================================
    private function _get_device_name($device_id)
    {
        $device = $this->db->get_where('fingerprint_device', ['device_id' => $device_id])->row();
        return $device ? $device->device_name : 'Unknown Device';
    }

    // =====================================================
    // Response JSON
    // =====================================================
    private function _response($code, $message, $data = [])
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode([
            'code'    => $code,
            'message' => $message,
            'data'    => $data
        ]);
    }

    // =====================================================
    // Test endpoint - cek apakah API aktif
    // URL: GET /api/test
    // =====================================================
    public function test()
    {
        $this->_response(200, 'API Fingerprint Aktif', [
            'server_time' => date('Y-m-d H:i:s'),
            'app'         => 'Sistem Penggajian Yayasan',
        ]);
    }
}
