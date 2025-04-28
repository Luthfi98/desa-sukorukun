<?php

namespace App\Controllers;

class Home extends BaseController
{
    
    public function index(): string
    {
        $namaDesa = get_setting('informasi_desa','nama_desa', false);
        $visiDesa = get_setting('informasi_desa','visi_desa', false);

        return view('landing', [
            'title' => 'Desa Sejahtera - Selamat Datang',
            'namaDesa' => $namaDesa,
            'visiDesa' => $visiDesa
        ]);
    }

    public function profil(): string
    {
        $namaDesa = get_setting('informasi_desa','nama_desa', false);
        $sejarahDesa = get_setting('informasi_desa','sejarah_desa', false);
        $visiDesa = get_setting('informasi_desa','visi_desa', false);
        $misiDesa = get_setting('informasi_desa','misi_desa', false);
        $logoDesa = get_setting('website','logo', false);
        return view('pages/profil', [
            'title' => 'Profil Desa',
            'namaDesa' => $namaDesa,
            'sejarahDesa' => $sejarahDesa,
            'visiDesa' => $visiDesa,
            'misiDesa' => $misiDesa,
            'logoDesa' => $logoDesa
        ]);
    }

    public function berita(): string
    {
        return view('pages/berita', [
            'title' => 'Berita Desa'
        ]);
    }

    public function layanan(): string
    {
        return view('pages/layanan', [
            'title' => 'Layanan Desa'
        ]);
    }

    public function galeri(): string
    {
        return view('pages/galeri', [
            'title' => 'Galeri Desa'
        ]);
    }

    public function kontak(): string
    {
        return view('pages/kontak', [
            'title' => 'Kontak Desa'
        ]);
    }
}
