<?php

if (!function_exists('formatDateIndo')) {
    /**
     * Format date to Indonesian format
     * 
     * @param string $date
     * @return string
     */
    function formatDateIndo($date)
    {
        $bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $tanggal = date('d', strtotime($date));
        $bulan = $bulan[(int)date('n', strtotime($date))];
        $tahun = date('Y', strtotime($date));

        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }
}

if (!function_exists('formatDateTimeIndo')) {
    /**
     * Format date with time to Indonesian format
     * 
     * @param string $date
     * @return string
     */
    function formatDateTimeIndo($date)
    {
        $bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $tanggal = date('d', strtotime($date));
        $bulan = $bulan[(int)date('n', strtotime($date))];
        $tahun = date('Y', strtotime($date));
        $waktu = date('H:i', strtotime($date));

        return $tanggal . ' ' . $bulan . ' ' . $tahun . ' ' . $waktu;
    }
}

if (!function_exists('formatDayDateIndo')) {
    /**
     * Format date to Indonesian day and date
     * 
     * @param string $date
     * @return string
     */
    function formatDayDateIndo($date)
    {
        $hari = [
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'
        ];

        $bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $day = $hari[(int)date('w', strtotime($date))];
        $tanggal = date('d', strtotime($date));
        $bulan = $bulan[(int)date('n', strtotime($date))];
        $tahun = date('Y', strtotime($date));

        return $day . ', ' . $tanggal . ' ' . $bulan . ' ' . $tahun;
    }
} 