<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Fingerprint
 * Letakkan di: application/models/ModelFingerprint.php
 */
class ModelFingerprint extends CI_Model {

    // =====================================================
    // Proses rekap harian dari attendance_log
    // Dipanggil setiap ada data baru masuk dari ZKTeco
    // =====================================================
    public function proses_rekap_harian($nik, $tanggal)
    {
        // Ambil semua log tap pada tanggal tersebut
        $logs = $this->db
                     ->where('nik', $nik)
                     ->where('DATE(waktu)', $tanggal)
                     ->order_by('waktu', 'ASC')
                     ->get('attendance_log')
                     ->result();

        if(empty($logs)) return false;

        // Ambil data pegawai
        $pegawai = $this->db->get_where('data_pegawai', ['nik' => $nik])->row();
        if(!$pegawai) return false;

        $unit = isset($pegawai->unit) ? $pegawai->unit : 'ALL';

        // Ambil setting jam kerja
        $hari_indo  = $this->_get_hari_indo($tanggal);
        $setting    = $this->_get_setting_jam($unit, $hari_indo);

        // Jika hari libur, skip
        if($setting && $setting->is_libur == 1) return false;

        // Tap pertama = jam masuk, tap terakhir = jam pulang
        $jam_masuk  = date('H:i:s', strtotime($logs[0]->waktu));
        $jam_pulang = count($logs) > 1 ? date('H:i:s', strtotime(end($logs)->waktu)) : null;

        // Hitung durasi kerja
        $durasi_kerja = null;
        if($jam_pulang) {
            $diff = strtotime($jam_pulang) - strtotime($jam_masuk);
            $jam  = floor($diff / 3600);
            $menit = floor(($diff % 3600) / 60);
            $durasi_kerja = $jam . 'j ' . $menit . 'm';
        }

        // Tentukan status kehadiran
        $status_kehadiran = 'Hadir';
        $menit_terlambat  = 0;
        $jam_masuk_normal = $setting ? $setting->jam_masuk : '07:30:00';
        $toleransi        = $setting ? $setting->toleransi_menit : 15;

        $selisih_menit = (strtotime($jam_masuk) - strtotime($jam_masuk_normal)) / 60;

        if($selisih_menit > $toleransi) {
            $status_kehadiran = 'Terlambat';
            $menit_terlambat  = (int)$selisih_menit;
        }

        // Bulan format: mmYYYY
        $bulan = date('mY', strtotime($tanggal));

        // Data rekap
        $rekap = [
            'nik'              => $nik,
            'nama_pegawai'     => $pegawai->nama_pegawai,
            'unit'             => $unit,
            'tanggal'          => $tanggal,
            'bulan'            => $bulan,
            'jam_masuk'        => $jam_masuk,
            'jam_pulang'       => $jam_pulang,
            'jam_masuk_normal' => $jam_masuk_normal,
            'durasi_kerja'     => $durasi_kerja,
            'status_kehadiran' => $status_kehadiran,
            'menit_terlambat'  => $menit_terlambat,
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        // Insert atau update rekap harian
        $existing = $this->db->get_where('rekap_absensi', [
            'nik'     => $nik,
            'tanggal' => $tanggal
        ])->row();

        if($existing) {
            $this->db->where('nik', $nik)
                     ->where('tanggal', $tanggal)
                     ->update('rekap_absensi', $rekap);
        } else {
            $rekap['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('rekap_absensi', $rekap);
        }

        // Update status di attendance_log jadi processed
        $this->db->where('nik', $nik)
                 ->where('DATE(waktu)', $tanggal)
                 ->update('attendance_log', ['status' => 'processed']);

        // Sinkronisasi ke tabel data_kehadiran (yang sudah ada)
        $this->_sync_ke_data_kehadiran($nik, $bulan, $unit);

        return true;
    }

    // =====================================================
    // Sinkronisasi rekap ke tabel data_kehadiran
    // Agar data fingerprint muncul di fitur penggajian
    // =====================================================
    private function _sync_ke_data_kehadiran($nik, $bulan, $unit)
    {
        // Hitung total hadir, terlambat, alpha dalam bulan ini
        $rekap_bulan = $this->db
                           ->where('nik', $nik)
                           ->where('bulan', $bulan)
                           ->get('rekap_absensi')
                           ->result();

        $hadir  = 0;
        $alpha  = 0;
        $sakit  = 0;

        foreach($rekap_bulan as $r) {
            if($r->status_kehadiran == 'Hadir' || $r->status_kehadiran == 'Terlambat') {
                $hadir++;
            } elseif($r->status_kehadiran == 'Alpha') {
                $alpha++;
            } elseif($r->status_kehadiran == 'Sakit') {
                $sakit++;
            }
        }

        // Ambil data pegawai
        $pegawai = $this->db->get_where('data_pegawai', ['nik' => $nik])->row();
        if(!$pegawai) return;

        // Cek apakah sudah ada di data_kehadiran
        $existing = $this->db->get_where('data_kehadiran', [
            'nik'   => $nik,
            'bulan' => $bulan
        ])->row();

        $data_kehadiran = [
            'nik'           => $nik,
            'bulan'         => $bulan,
            'nama_pegawai'  => $pegawai->nama_pegawai,
            'jenis_kelamin' => $pegawai->jenis_kelamin,
            'nama_jabatan'  => $pegawai->jabatan,
            'hadir'         => $hadir,
            'sakit'         => $sakit,
            'alpha'         => $alpha,
            'sumber'        => 'fingerprint',
        ];

        if($existing) {
            $this->db->where('nik', $nik)
                     ->where('bulan', $bulan)
                     ->update('data_kehadiran', $data_kehadiran);
        } else {
            $this->db->insert('data_kehadiran', $data_kehadiran);
        }
    }

    // =====================================================
    // Get rekap absensi per bulan
    // =====================================================
    public function get_rekap_bulanan($bulan, $unit = null)
    {
        if($unit) {
            $this->db->where('unit', $unit);
        }
        return $this->db->where('bulan', $bulan)
                        ->order_by('nama_pegawai', 'ASC')
                        ->get('rekap_absensi')
                        ->result();
    }

    // =====================================================
    // Get rekap absensi harian
    // =====================================================
    public function get_rekap_harian($tanggal, $unit = null)
    {
        if($unit) {
            $this->db->where('unit', $unit);
        }
        return $this->db->where('tanggal', $tanggal)
                        ->order_by('nama_pegawai', 'ASC')
                        ->get('rekap_absensi')
                        ->result();
    }

    // =====================================================
    // Get log fingerprint mentah
    // =====================================================
    public function get_attendance_log($nik, $tanggal)
    {
        return $this->db->where('nik', $nik)
                        ->where('DATE(waktu)', $tanggal)
                        ->order_by('waktu', 'ASC')
                        ->get('attendance_log')
                        ->result();
    }

    // =====================================================
    // Get semua device terdaftar
    // =====================================================
    public function get_devices()
    {
        return $this->db->get('fingerprint_device')->result();
    }

    // =====================================================
    // Update last sync device
    // =====================================================
    public function update_device_sync($device_id)
    {
        $this->db->where('device_id', $device_id)
                 ->update('fingerprint_device', ['last_sync' => date('Y-m-d H:i:s')]);
    }

    // =====================================================
    // Statistik absensi hari ini
    // =====================================================
    public function get_statistik_hari_ini($unit = null)
    {
        $today = date('Y-m-d');
        $query = $this->db->where('tanggal', $today);
        if($unit) $query = $this->db->where('unit', $unit);

        $data = $this->db->get('rekap_absensi')->result();

        $stats = [
            'hadir'     => 0,
            'terlambat' => 0,
            'alpha'     => 0,
            'total'     => count($data),
        ];

        foreach($data as $d) {
            if($d->status_kehadiran == 'Hadir')     $stats['hadir']++;
            if($d->status_kehadiran == 'Terlambat') $stats['terlambat']++;
            if($d->status_kehadiran == 'Alpha')     $stats['alpha']++;
        }

        return $stats;
    }

    // =====================================================
    // Helper: Nama hari dalam bahasa Indonesia
    // =====================================================
    private function _get_hari_indo($tanggal)
    {
        $hari_en = date('l', strtotime($tanggal));
        $map = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        return isset($map[$hari_en]) ? $map[$hari_en] : 'Senin';
    }

    // =====================================================
    // Helper: Ambil setting jam kerja
    // =====================================================
    private function _get_setting_jam($unit, $hari)
    {
        // Coba ambil setting spesifik unit dulu
        $setting = $this->db->get_where('setting_jam_kerja', [
            'unit'   => $unit,
            'hari'   => $hari,
            'status' => 'aktif'
        ])->row();

        // Jika tidak ada, pakai setting ALL
        if(!$setting) {
            $setting = $this->db->get_where('setting_jam_kerja', [
                'unit'   => 'ALL',
                'hari'   => $hari,
                'status' => 'aktif'
            ])->row();
        }

        return $setting;
    }
}
