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
        $settingModel = new \App\Models\SettingModel();
        $residentModel = new \App\Models\ResidentModel();

        $strukturDesa =  $settingModel->where('category', 'struktur_organisasi')
                                        ->where('is_public', 1)
                                      ->orderBy('order', 'ASC')
                                      ->findAll();

        $demografis = $residentModel->countAll();
        $male = $residentModel->where('gender', 'male')->countAllResults();
        $female = $residentModel->where('gender', 'female')->countAllResults();
        $kk = $residentModel->select('kk')->distinct()->groupBy('kk')->countAllResults();

        $geografis = $settingModel->where('category', 'geografis')
                                  ->where('is_public', 1)
                                  ->orderBy('order', 'ASC')
                                  ->findAll();
        return view('pages/profil', [
            'title' => 'Profil Desa',
            'namaDesa' => $namaDesa,
            'sejarahDesa' => $sejarahDesa,
            'visiDesa' => $visiDesa,
            'misiDesa' => $misiDesa,
            'logoDesa' => $logoDesa,
            'strukturDesa' => $strukturDesa,
            'demografis' => $demografis,
            'male' => $male,
            'female' => $female,
            'kk' => $kk,
            'geografis' => $geografis
        ]);
    }

    public function berita($id = null): string
    {
        $newsModel = new \App\Models\NewsModel();
        
        // Get categories for both listing and detail pages
        $category = $newsModel->where('status', 'published')
                             ->select('category')
                             ->distinct()
                             ->findAll();

        if ($id) {
            // Handle detail page
            $id = explode('-', $id);
            $id = $id[0];
            
            // Get the news detail
            $news = $newsModel->join('users', 'users.id = news.user_id')
                             ->where('news.id', $id)
                             ->first();

            // Get recent news for sidebar
            $recent_news = $newsModel->join('users', 'users.id = news.user_id')
                                    ->where('news.status', 'published')
                                    ->where('news.id !=', $id)
                                    ->orderBy('news.created_at', 'DESC')
                                    ->limit(5)
                                    ->findAll();

            return view('pages/detail-berita', [
                'title' => $news['title'] ?? 'Berita Desa',
                'news' => $news,
                'category' => $category,
                'recent_news' => $recent_news
            ]);
        } else {
            // Handle listing page with pagination and search
            $perPage = 6; // Number of items per page
            $currentPage = $this->request->getGet('page') ?? 1;
            $search = $this->request->getGet('search');
            $categoryFilter = $this->request->getGet('category');
            
            // Build query
            $builder = $newsModel->join('users', 'users.id = news.user_id')
                                ->where('news.status', 'published');
            
            // Apply search if exists
            if ($search) {
                $builder->groupStart()
                        ->like('news.title', $search)
                        ->orLike('news.content', $search)
                        ->orLike('news.category', $search)
                        ->groupEnd();
            }
            
            // Apply category filter if exists
            if ($categoryFilter && $categoryFilter !== 'Semua Kategori') {
                $builder->where('news.category', $categoryFilter);
            }
            
            // Get total count for pagination
            $total = $builder->countAllResults(false);
            
            // Get paginated news
            $news = $builder->orderBy('news.created_at', 'DESC')
                           ->paginate($perPage, 'default', $currentPage);
            
            // Get pager
            $pager = $newsModel->pager;
            
            return view('pages/berita', [
                'title' => 'Berita & Informasi Desa',
                'news' => $news,
                'category' => $category,
                'pager' => $pager,
                'search' => $search,
                'categoryFilter' => $categoryFilter
            ]);
        }
    }

    public function layanan(): string
    {
        $layananModel = new \App\Models\LetterTypeModel();
        $setting  = new \App\Models\SettingModel();
        
        $layanan = $layananModel->findAll();
        $textLayanan =  $setting->where('category', 'layanan')->where('key', 'layanan_desa')->first();
        $jamLayanan = get_setting('layanan','jam_layanan', false);
        $phone = get_setting('kontak','telepon_desa', false);
        return view('pages/layanan', [
            'title' => 'Layanan Desa',
            'layanan' => $layanan,
            'textLayanan' => $textLayanan,
            'jamLayanan' => $jamLayanan,
            'phone' => $phone
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
        $namaDesa = get_setting('informasi_desa','nama_desa', false);
        $address = get_setting('kontak','alamat_desa', false);
        $phone = get_setting('kontak','telepon_desa', false);
        $email = get_setting('kontak','email_desa', false);
        $informasi = [
            'name' => $namaDesa,
            'address' => $address,
            'phone' => $phone,
            'email' => $email
        ];
        return view('pages/kontak', [
            'title' => 'Kontak Desa',
            'informasi' => $informasi
        ]);
    }
}
