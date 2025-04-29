<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/profil', 'Home::profil');
$routes->get('/berita', 'Home::berita');
$routes->get('/berita/(:any)', 'Home::berita/$1');
$routes->get('/layanan', 'Home::layanan');
$routes->get('/galeri', 'Home::galeri');
$routes->get('/kontak', 'Home::kontak');

// Authentication Routes
$routes->group('auth', function($routes) {
    $routes->get('/', 'Auth::index');
    $routes->get('login', 'Auth::login');
    $routes->get('admin', 'Auth::adminLogin');
    $routes->post('authenticate', 'Auth::authenticate');
    $routes->get('register', 'Auth::register');
    $routes->post('create-account', 'Auth::createAccount');
    $routes->get('forgot-password', 'Auth::forgotPassword');
    $routes->post('send-reset-link', 'Auth::sendResetLink');
    $routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
    $routes->post('update-password', 'Auth::updatePassword');
    $routes->get('logout', 'Auth::logout');
});

// Dashboard
$routes->get('dashboard', 'Dashboard::index');

// Settings Management
$routes->group('admin/settings', function($routes) {
    $routes->get('/', 'SettingController::index');
    $routes->get('create', 'SettingController::create');
    $routes->post('store', 'SettingController::store');
    $routes->get('edit/(:num)', 'SettingController::edit/$1');
    $routes->post('update/(:num)', 'SettingController::update/$1');
    $routes->get('delete/(:num)', 'SettingController::delete/$1');
    $routes->post('updateProfile', 'SettingController::updateProfile');
});
$routes->get('village-profile', 'SettingController::profile');

// Residents Management
$routes->group('residents', function($routes) {
    $routes->get('/', 'Residents::index');
    $routes->get('new', 'Residents::new');
    $routes->post('create', 'Residents::create');
    $routes->get('edit/(:num)', 'Residents::edit/$1');
    $routes->post('update/(:num)', 'Residents::update/$1');
    $routes->get('delete/(:num)', 'Residents::delete/$1');
    $routes->get('import', 'Residents::import');
    $routes->post('process-import', 'Residents::processImport');
    $routes->get('export-template', 'Residents::exportTemplate');
});

// Letter Types
$routes->group('letter-types', function($routes) {
    $routes->get('/', 'LetterTypes::index');
    $routes->get('new', 'LetterTypes::new');
    $routes->post('create', 'LetterTypes::create');
    $routes->get('edit/(:num)', 'LetterTypes::edit/$1');
    $routes->post('update/(:num)', 'LetterTypes::update/$1');
    $routes->get('delete/(:num)', 'LetterTypes::delete/$1');
});

// Letter Requests
$routes->group('letter-requests', function($routes) {
    $routes->get('/', 'PengajuanSurat::index');
    $routes->get('new', 'PengajuanSurat::new');
    $routes->post('create', 'PengajuanSurat::create');
    $routes->get('view/(:num)', 'PengajuanSurat::view/$1');
    $routes->get('process/(:num)', 'PengajuanSurat::process/$1');
    $routes->post('update-status/(:num)', 'PengajuanSurat::updateStatus/$1');
    $routes->get('download/(:num)', 'PengajuanSurat::download/$1');
    $routes->get('my-requests', 'PengajuanSurat::myRequests');
    $routes->get('getMyRequestsDataTable', 'PengajuanSurat::getMyRequestsDataTable');
    $routes->get('edit/(:num)', 'PengajuanSurat::edit/$1');
    $routes->post('update/(:num)', 'PengajuanSurat::update/$1');
    $routes->get('delete/(:num)', 'PengajuanSurat::delete/$1');
    $routes->get('view-detail/(:num)', 'PengajuanSurat::viewDetail/$1');
    $routes->get('download-pdf/(:num)', 'PengajuanSurat::downloadPdf/$1');
    $routes->get('getDataTable', 'PengajuanSurat::getDataTable');
    $routes->get('create-for-resident', 'PengajuanSurat::createForResident');
    $routes->post('process-create-for-resident', 'PengajuanSurat::processCreateForResident');
});

// Complaints Management
$routes->group('complaints', function($routes) {
    $routes->get('/', 'Complaints::index');
    $routes->get('create', 'Complaints::create');
    $routes->post('store', 'Complaints::store');
    $routes->get('(:num)', 'Complaints::show/$1');
    $routes->get('respond/(:num)', 'Complaints::respond/$1');
    $routes->post('updateResponse/(:num)', 'Complaints::updateResponse/$1');
    $routes->get('process/(:num)', 'Complaints::process/$1');
    $routes->post('processComplaint/(:num)', 'Complaints::processComplaint/$1');
    $routes->post('resolve/(:num)', 'Complaints::resolve/$1');
    $routes->get('delete/(:num)', 'Complaints::delete/$1');
    $routes->get('admin', 'Complaints::admin');
    $routes->get('download/(:num)', 'Complaints::download/$1');
    $routes->get('getDataTable', 'Complaints::getDataTable');
});

// User Profile
$routes->group('profile', function($routes) {
    $routes->get('/', 'Profile::index');
    $routes->post('update', 'Profile::update');
    $routes->post('update-resident-data', 'Profile::updateResidentData');
    $routes->get('change-password', 'Profile::changePassword');
    $routes->post('update-password', 'Profile::updatePassword');
});

// Notifications
$routes->group('notifications', function($routes) {
    $routes->get('/', 'Notifications::index');
    $routes->get('mark-as-read/(:num)', 'Notifications::markAsRead/$1');
    $routes->get('mark-all-as-read', 'Notifications::markAllAsRead');
});

// English Routes - Village Programs (previously Program Desa)
$routes->group('village-programs', function($routes) {
    $routes->get('/', 'ProgramDesa::index');
    $routes->get('new', 'ProgramDesa::new');
    $routes->post('create', 'ProgramDesa::create');
    $routes->get('edit/(:num)', 'ProgramDesa::edit/$1');
    $routes->post('update/(:num)', 'ProgramDesa::update/$1');
    $routes->get('delete/(:num)', 'ProgramDesa::delete/$1');
});

// English Routes - Budget Info (previously Informasi APBD)
$routes->group('budget-info', function($routes) {
    $routes->get('/', 'InformasiAPBD::index');
    $routes->get('new', 'InformasiAPBD::new');
    $routes->post('create', 'InformasiAPBD::create');
    $routes->get('edit/(:num)', 'InformasiAPBD::edit/$1');
    $routes->post('update/(:num)', 'InformasiAPBD::update/$1');
    $routes->get('delete/(:num)', 'InformasiAPBD::delete/$1');
    $routes->get('public', 'InformasiAPBD::public');
});

// English Routes - Village Info (previously Informasi Desa)
$routes->group('village-info', function($routes) {
    $routes->get('/', 'InformasiDesa::index');
    $routes->get('detail/(:num)', 'InformasiDesa::detail/$1');
});

// English Routes - Archives (previously Arsip Data)
$routes->group('archives', function($routes) {
    $routes->get('/', 'ArsipData::index');
    $routes->get('new', 'ArsipData::new');
    $routes->post('create', 'ArsipData::create');
    $routes->get('edit/(:num)', 'ArsipData::edit/$1');
    $routes->post('update/(:num)', 'ArsipData::update/$1');
    $routes->get('delete/(:num)', 'ArsipData::delete/$1');
    $routes->get('download/(:num)', 'ArsipData::download/$1');
    $routes->get('public', 'ArsipDokumen::index');
    $routes->get('public/download/(:num)', 'ArsipDokumen::download/$1');
});

// Keep original Indonesian routes for backward compatibility
// Program Desa Routes (Admin)
$routes->group('program-desa', function($routes) {
    $routes->get('/', 'ProgramDesa::index');
    $routes->get('new', 'ProgramDesa::new');
    $routes->post('create', 'ProgramDesa::create');
    $routes->get('edit/(:num)', 'ProgramDesa::edit/$1');
    $routes->post('update/(:num)', 'ProgramDesa::update/$1');
    $routes->get('delete/(:num)', 'ProgramDesa::delete/$1');
});

// Informasi APBD Routes
$routes->group('informasi-apbd', function($routes) {
    $routes->get('/', 'InformasiAPBD::index');
    $routes->get('new', 'InformasiAPBD::new');
    $routes->post('create', 'InformasiAPBD::create');
    $routes->get('edit/(:num)', 'InformasiAPBD::edit/$1');
    $routes->post('update/(:num)', 'InformasiAPBD::update/$1');
    $routes->get('delete/(:num)', 'InformasiAPBD::delete/$1');
});

// Public APBD Info
$routes->get('informasi-apbd-publik', 'InformasiAPBD::public');

// Pengajuan Surat Routes (Admin)
$routes->group('pengajuan-surat', function($routes) {
    $routes->get('/', 'PengajuanSurat::index');
    $routes->get('view/(:num)', 'PengajuanSurat::view/$1');
    $routes->get('process/(:num)', 'PengajuanSurat::process/$1');
    $routes->post('update-status/(:num)', 'PengajuanSurat::updateStatus/$1');
    $routes->get('download/(:num)', 'PengajuanSurat::download/$1');
    $routes->get('getDataTable', 'PengajuanSurat::getDataTable');
});

// Surat Pengantar Routes (User)
$routes->group('surat-pengantar', function($routes) {
    $routes->get('/', 'SuratPengantar::index');
    $routes->get('new', 'SuratPengantar::new');
    $routes->post('create', 'SuratPengantar::create');
    $routes->get('view/(:num)', 'SuratPengantar::view/$1');
    $routes->get('download/(:num)', 'SuratPengantar::download/$1');
});

// Pengaduan Routes (Admin)
$routes->group('pengaduan', function($routes) {
    $routes->get('/', 'Pengaduan::index');
    $routes->get('view/(:num)', 'Pengaduan::view/$1');
    $routes->get('process/(:num)', 'Pengaduan::process/$1');
    $routes->post('update-status/(:num)', 'Pengaduan::updateStatus/$1');
    $routes->post('respond/(:num)', 'Pengaduan::respond/$1');
});

// Pengaduan Masyarakat Routes (User)
$routes->group('pengaduan-masyarakat', function($routes) {
    $routes->get('/', 'PengaduanMasyarakat::index');
    $routes->get('new', 'PengaduanMasyarakat::new');
    $routes->post('create', 'PengaduanMasyarakat::create');
    $routes->get('view/(:num)', 'PengaduanMasyarakat::view/$1');
});

// Informasi Desa Routes (Public)
$routes->get('informasi-desa', 'InformasiDesa::index');
$routes->get('informasi-desa/detail/(:num)', 'InformasiDesa::detail/$1');

// Arsip Data Routes
$routes->group('arsip-data', function($routes) {
    $routes->get('/', 'ArsipData::index');
    $routes->get('new', 'ArsipData::new');
    $routes->post('create', 'ArsipData::create');
    $routes->get('edit/(:num)', 'ArsipData::edit/$1');
    $routes->post('update/(:num)', 'ArsipData::update/$1');
    $routes->get('delete/(:num)', 'ArsipData::delete/$1');
    $routes->get('download/(:num)', 'ArsipData::download/$1');
});

// Arsip Dokumen (For Public)
$routes->get('arsip-dokumen', 'ArsipDokumen::index');
$routes->get('arsip-dokumen/download/(:num)', 'ArsipDokumen::download/$1');

// Archive Routes
$routes->group('archive', function($routes) {
    $routes->get('/', 'Archive::index');
    $routes->get('view/(:num)', 'Archive::view/$1');
    $routes->post('getDataTable', 'Archive::getDataTable');
});

// Debug routes (remove in production)
$routes->get('debug/table-info', 'DebugTest::index');
$routes->get('database/fix', 'DatabaseFix::index');

// Residents Routes
$routes->get('residents', 'Residents::index');
$routes->get('residents/new', 'Residents::new');
$routes->post('residents/create', 'Residents::create');
$routes->get('residents/edit/(:num)', 'Residents::edit/$1');
$routes->post('residents/update/(:num)', 'Residents::update/$1');
$routes->post('residents/delete/(:num)', 'Residents::delete/$1');
$routes->post('residents/get-residents', 'Residents::getResidents');
$routes->get('residents/import', 'Residents::import');
$routes->post('residents/process-import', 'Residents::processImport');
$routes->get('residents/export-template', 'Residents::exportTemplate');

$routes->get('api/residents/check-nik/(:num)', 'Api\Residents::checkNik/$1');




// ==========================================================================
$routes->group('general-request', function($routes) {
    $routes->get('/', 'LetterRequest\GeneralRequestController::index');
    $routes->get('create', 'LetterRequest\GeneralRequestController::create');
    $routes->post('store', 'LetterRequest\GeneralRequestController::store');
    $routes->get('edit/(:num)', 'LetterRequest\GeneralRequestController::edit/$1');
    $routes->post('update/(:num)', 'LetterRequest\GeneralRequestController::update/$1');
    $routes->get('view/(:num)', 'LetterRequest\GeneralRequestController::show/$1');
    $routes->get('process/(:num)', 'LetterRequest\GeneralRequestController::process/$1');
    $routes->post('update-status/(:num)', 'LetterRequest\GeneralRequestController::updateStatus/$1');
    $routes->get('download/(:num)', 'LetterRequest\GeneralRequestController::download/$1');
    $routes->get('getDataTable', 'LetterRequest\GeneralRequestController::getDataTable');

    $routes->get('delete/(:num)', 'LetterRequest\GeneralRequestController::delete/$1');

    $routes->group('my-request', function($routes) {        
        $routes->get('/', 'LetterRequest\GeneralRequestController::myRequest');        
        $routes->get('create', 'LetterRequest\GeneralRequestController::createRequest');
        $routes->post('store', 'LetterRequest\GeneralRequestController::storeRequest');
        $routes->get('edit/(:num)', 'LetterRequest\GeneralRequestController::editRequest/$1');
        $routes->post('update/(:num)', 'LetterRequest\GeneralRequestController::updateRequest/$1');
        $routes->get('view/(:num)', 'LetterRequest\GeneralRequestController::show/$1');
        $routes->get('delete/(:num)', 'LetterRequest\GeneralRequestController::delete/$1');
        $routes->get('download/(:num)', 'LetterRequest\GeneralRequestController::download/$1');
    });
});


$routes->group('domicile-request', function($routes) {
    $routes->get('/', 'LetterRequest\DomicileRequestController::index');
    $routes->get('create', 'LetterRequest\DomicileRequestController::create');
    $routes->post('store', 'LetterRequest\DomicileRequestController::store');
    $routes->get('edit/(:num)', 'LetterRequest\DomicileRequestController::edit/$1');
    $routes->post('update/(:num)', 'LetterRequest\DomicileRequestController::update/$1');
    $routes->get('view/(:num)', 'LetterRequest\DomicileRequestController::show/$1');
    $routes->get('process/(:num)', 'LetterRequest\DomicileRequestController::process/$1');
    $routes->post('update-status/(:num)', 'LetterRequest\DomicileRequestController::updateStatus/$1');
    $routes->get('download/(:num)', 'LetterRequest\DomicileRequestController::download/$1');
    $routes->get('getDataTable', 'LetterRequest\DomicileRequestController::getDataTable');

    $routes->get('delete/(:num)', 'LetterRequest\DomicileRequestController::delete/$1');

    $routes->group('my-request', function($routes) {        
        $routes->get('/', 'LetterRequest\DomicileRequestController::myRequest');        
        $routes->get('create', 'LetterRequest\DomicileRequestController::createRequest');
        $routes->post('store', 'LetterRequest\DomicileRequestController::storeRequest');
        $routes->get('edit/(:num)', 'LetterRequest\DomicileRequestController::editRequest/$1');
        $routes->post('update/(:num)', 'LetterRequest\DomicileRequestController::updateRequest/$1');
        $routes->get('view/(:num)', 'LetterRequest\DomicileRequestController::show/$1');
        $routes->get('delete/(:num)', 'LetterRequest\DomicileRequestController::delete/$1');
        $routes->get('download/(:num)', 'LetterRequest\DomicileRequestController::download/$1');

    });
});

$routes->group('heir-request', function($routes) {
    $routes->get('/', 'LetterRequest\HeirRequestController::index');
    $routes->get('create', 'LetterRequest\HeirRequestController::create');
    $routes->post('store', 'LetterRequest\HeirRequestController::store');
    $routes->get('edit/(:num)', 'LetterRequest\HeirRequestController::edit/$1');
    $routes->post('update/(:num)', 'LetterRequest\HeirRequestController::update/$1');
    $routes->get('view/(:num)', 'LetterRequest\HeirRequestController::show/$1');
    $routes->get('process/(:num)', 'LetterRequest\HeirRequestController::process/$1');
    $routes->post('update-status/(:num)', 'LetterRequest\HeirRequestController::updateStatus/$1');
    $routes->get('download/(:num)', 'LetterRequest\HeirRequestController::download/$1');
    $routes->get('getDataTable', 'LetterRequest\HeirRequestController::getDataTable');

    $routes->get('delete/(:num)', 'LetterRequest\HeirRequestController::delete/$1');

    $routes->group('my-request', function($routes) {        
        $routes->get('/', 'LetterRequest\HeirRequestController::myRequest');        
        $routes->get('create', 'LetterRequest\HeirRequestController::createRequest');
        $routes->post('store', 'LetterRequest\HeirRequestController::storeRequest');
        $routes->get('edit/(:num)', 'LetterRequest\HeirRequestController::editRequest/$1');
        $routes->post('update/(:num)', 'LetterRequest\HeirRequestController::updateRequest/$1');
        $routes->get('view/(:num)', 'LetterRequest\HeirRequestController::show/$1');
        $routes->get('delete/(:num)', 'LetterRequest\HeirRequestController::delete/$1');
        $routes->get('download/(:num)', 'LetterRequest\HeirRequestController::download/$1');

    });
});

$routes->group('relocation-request', function($routes) {
    $routes->get('/', 'LetterRequest\RelocationRequestController::index');
    $routes->get('create', 'LetterRequest\RelocationRequestController::create');
    $routes->post('store', 'LetterRequest\RelocationRequestController::store');
    $routes->get('edit/(:num)', 'LetterRequest\RelocationRequestController::edit/$1');
    $routes->post('update/(:num)', 'LetterRequest\RelocationRequestController::update/$1');
    $routes->get('view/(:num)', 'LetterRequest\RelocationRequestController::show/$1');
    $routes->get('process/(:num)', 'LetterRequest\RelocationRequestController::process/$1');
    $routes->post('update-status/(:num)', 'LetterRequest\RelocationRequestController::updateStatus/$1');
    $routes->get('download/(:num)', 'LetterRequest\RelocationRequestController::download/$1');
    $routes->get('getDataTable', 'LetterRequest\RelocationRequestController::getDataTable');

    $routes->get('delete/(:num)', 'LetterRequest\RelocationRequestController::delete/$1');

    $routes->group('my-request', function($routes) {        
        $routes->get('/', 'LetterRequest\RelocationRequestController::myRequest');        
        $routes->get('create', 'LetterRequest\RelocationRequestController::createRequest');
        $routes->post('store', 'LetterRequest\RelocationRequestController::storeRequest');
        $routes->get('edit/(:num)', 'LetterRequest\RelocationRequestController::editRequest/$1');
        $routes->post('update/(:num)', 'LetterRequest\RelocationRequestController::updateRequest/$1');
        $routes->get('view/(:num)', 'LetterRequest\RelocationRequestController::show/$1');
        $routes->get('delete/(:num)', 'LetterRequest\RelocationRequestController::delete/$1');
        $routes->get('download/(:num)', 'LetterRequest\RelocationRequestController::download/$1');

    });
});

// User Management Routes
$routes->group('users', function($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('create', 'UserController::create');
    $routes->post('store', 'UserController::store');
    $routes->get('(:num)', 'UserController::show/$1');
    $routes->get('edit/(:num)', 'UserController::edit/$1');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->get('show/(:num)', 'UserController::show/$1');
    $routes->get('delete/(:num)', 'UserController::delete/$1');
    $routes->get('getDataTable', 'UserController::getDataTable');
});

$routes->group('news', function($routes) {
    $routes->get('/', 'NewsController::index');
    $routes->get('create', 'NewsController::create');
    $routes->post('store', 'NewsController::store');
    $routes->get('edit/(:num)', 'NewsController::edit/$1');
    $routes->post('update/(:num)', 'NewsController::update/$1');
    $routes->get('delete/(:num)', 'NewsController::delete/$1');
    $routes->get('view/(:num)', 'NewsController::view/$1');
    $routes->get('getDataTable', 'NewsController::getDataTable');
});

