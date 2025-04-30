<?php

namespace App\Controllers;

use App\Models\ResidentModel;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class Residents extends BaseController
{
    protected $residentModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->residentModel = new ResidentModel();
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        
        if ($keyword) {
            $residents = $this->residentModel->like('name', $keyword)
                                             ->orLike('nik', $keyword)
                                             ->findAll();
        } else {
            $residents = $this->residentModel->findAll();
        }
        
        $data = [
            'title' => 'Daftar Penduduk',
            'residents' => $residents,
            'keyword' => $keyword,
        ];
        
        return view('residents/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah Data Penduduk',
        ];
        
        return view('residents/create', $data);
    }

    public function create()
    {
        // Validate input
        if (!$this->validate([
            'nik' => 'required|numeric|exact_length[16]|is_unique[residents.nik]',
            'kk' => 'required|numeric|exact_length[16]',
            'name' => 'required|alpha_numeric_space|min_length[3]|max_length[100]',
            'birth_place' => 'required|alpha_numeric_space|max_length[100]',
            'birth_date' => 'required|valid_date',
            'gender' => 'required|in_list[male,female]',
            'address' => 'required',
            'rt' => 'required|max_length[5]',
            'rw' => 'required|max_length[5]',
            'village' => 'required|max_length[100]',
            'district' => 'required|max_length[100]',
            'religion' => 'required|max_length[20]',
            'marital_status' => 'required|in_list[single,married,divorced,widowed]',
            'occupation' => 'required|max_length[100]',
            'nationality' => 'required|max_length[50]',
            'education' => 'required|max_length[50]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Insert resident data
        $this->residentModel->save([
            'nik' => $this->request->getPost('nik'),
            'kk' => $this->request->getPost('kk'),
            'name' => $this->request->getPost('name'),
            'birth_place' => $this->request->getPost('birth_place'),
            'birth_date' => $this->request->getPost('birth_date'),
            'gender' => $this->request->getPost('gender'),
            'address' => $this->request->getPost('address'),
            'rt' => $this->request->getPost('rt'),
            'rw' => $this->request->getPost('rw'),
            'village' => $this->request->getPost('village'),
            'district' => $this->request->getPost('district'),
            'religion' => $this->request->getPost('religion'),
            'marital_status' => $this->request->getPost('marital_status'),
            'occupation' => $this->request->getPost('occupation'),
            'nationality' => $this->request->getPost('nationality'),
            'education' => $this->request->getPost('education'),
            'father_name' => $this->request->getPost('father_name'),
            'mother_name' => $this->request->getPost('mother_name'),
        ]);
        
        return redirect()->to('/residents')->with('success', 'Data penduduk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $resident = $this->residentModel->find($id);
        
        if (!$resident) {
            return redirect()->to('/residents')->with('error', 'Data penduduk tidak ditemukan.');
        }
        
        $data = [
            'title' => 'Edit Data Penduduk',
            'resident' => $resident,
        ];
        
        return view('residents/edit', $data);
    }

    public function update($id)
    {
        $resident = $this->residentModel->find($id);
        
        if (!$resident) {
            return redirect()->to('/residents')->with('error', 'Data penduduk tidak ditemukan.');
        }
        
        // Validate input
        if (!$this->validate([
            'nik' => "required|numeric|exact_length[16]|is_unique[residents.nik,id,$id]",
            'kk' => 'required|numeric|exact_length[16]',
            'name' => 'required|alpha_numeric_space|min_length[3]|max_length[100]',
            'birth_place' => 'required|alpha_numeric_space|max_length[100]',
            'birth_date' => 'required|valid_date',
            'gender' => 'required|in_list[male,female]',
            'address' => 'required',
            'rt' => 'required|max_length[5]',
            'rw' => 'required|max_length[5]',
            'village' => 'required|max_length[100]',
            'district' => 'required|max_length[100]',
            'religion' => 'required|max_length[20]',
            'marital_status' => 'required|in_list[single,married,divorced,widowed]',
            'occupation' => 'required|max_length[100]',
            'nationality' => 'required|max_length[50]',
            'education' => 'required|max_length[50]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Update resident data
        $this->residentModel->update($id, [
            'nik' => $this->request->getPost('nik'),
            'kk' => $this->request->getPost('kk'),
            'name' => $this->request->getPost('name'),
            'birth_place' => $this->request->getPost('birth_place'),
            'birth_date' => $this->request->getPost('birth_date'),
            'gender' => $this->request->getPost('gender'),
            'address' => $this->request->getPost('address'),
            'rt' => $this->request->getPost('rt'),
            'rw' => $this->request->getPost('rw'),
            'village' => $this->request->getPost('village'),
            'district' => $this->request->getPost('district'),
            'religion' => $this->request->getPost('religion'),
            'marital_status' => $this->request->getPost('marital_status'),
            'occupation' => $this->request->getPost('occupation'),
            'nationality' => $this->request->getPost('nationality'),
            'education' => $this->request->getPost('education'),
            'father_name' => $this->request->getPost('father_name'),
            'mother_name' => $this->request->getPost('mother_name'),
        ]);
        
        return redirect()->to('/residents')->with('success', 'Data penduduk berhasil diperbarui.');
    }

    public function delete($id)
    {
        $resident = $this->residentModel->find($id);
        
        if (!$resident) {
            return redirect()->to('/residents')->with('error', 'Data penduduk tidak ditemukan.');
        }
        
        $this->residentModel->delete($id);
        
        return redirect()->to('/residents')->with('success', 'Data penduduk berhasil dihapus.');
    }

    public function import()
    {
        $data = [
            'title' => 'Import Data Penduduk',
        ];
        
        return view('residents/import', $data);
    }

    public function processImport()
    {
        $file = $this->request->getFile('file');
        
        if (!$file->isValid() || $file->getError() !== 0) {
            return redirect()->to('/residents/import')->with('error', 'Silakan pilih file yang valid.');
        }
        
        $fileExt = $file->getExtension();
        
        if ($fileExt !== 'xlsx' && $fileExt !== 'csv') {
            return redirect()->to('/residents/import')->with('error', 'Format file harus Excel (.xlsx) atau CSV (.csv).');
        }
        
        $reader = ($fileExt === 'xlsx') ? new Xlsx() : new Csv();
        
        try {
            $spreadsheet = $reader->load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            
            // Remove header row
            array_shift($rows);
            
            $data = [];
            foreach ($rows as $row) {
                if (!empty($row[0])) { // Skip empty rows (check NIK column)
                    $data[] = [
                        'nik'           => $row[0],
                        'kk'            => $row[1],
                        'name'          => $row[2],
                        'birth_place'   => $row[3],
                        'birth_date'    => $row[4],
                        'gender'        => $row[5],
                        'address'       => $row[6],
                        'rt'            => $row[7],
                        'rw'            => $row[8],
                        'village'       => $row[9],
                        'district'      => $row[10],
                        'religion'      => $row[11],
                        'marital_status'=> $row[12],
                        'occupation'    => $row[13],
                        'nationality'   => $row[14],
                        'education'     => $row[15],
                        'father_name'   => $row[16] ?? null,
                        'mother_name'   => $row[17] ?? null,
                    ];
                }
            }
            
            $result = $this->residentModel->importFromArray($data);
            
            return redirect()->to('/residents')->with('success', 'Import data berhasil. Berhasil: ' . $result['success_count'] . ', Gagal: ' . $result['error_count']);
        } catch (\Exception $e) {
            return redirect()->to('/residents/import')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $sheet->setCellValue('A1', 'NIK');
        $sheet->setCellValue('B1', 'KK');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Tempat Lahir');
        $sheet->setCellValue('E1', 'Tanggal Lahir (YYYY-MM-DD)');
        $sheet->setCellValue('F1', 'Jenis Kelamin (male/female)');
        $sheet->setCellValue('G1', 'Alamat');
        $sheet->setCellValue('H1', 'RT');
        $sheet->setCellValue('I1', 'RW');
        $sheet->setCellValue('J1', 'Desa/Kelurahan');
        $sheet->setCellValue('K1', 'Kecamatan');
        $sheet->setCellValue('L1', 'Agama');
        $sheet->setCellValue('M1', 'Status Perkawinan (single/married/divorced/widowed)');
        $sheet->setCellValue('N1', 'Pekerjaan');
        $sheet->setCellValue('O1', 'Kewarganegaraan');
        $sheet->setCellValue('P1', 'Pendidikan');
        $sheet->setCellValue('Q1', 'Nama Ayah');
        $sheet->setCellValue('R1', 'Nama Ibu');
        
        // Example row
        $sheet->setCellValue('A2', '1234567890123456');
        $sheet->setCellValue('B2', '9876543210987654');
        $sheet->setCellValue('C2', 'Nama Lengkap');
        $sheet->setCellValue('D2', 'Jakarta');
        $sheet->setCellValue('E2', '1990-01-01');
        $sheet->setCellValue('F2', 'male');
        $sheet->setCellValue('G2', 'Jl. Contoh No. 123');
        $sheet->setCellValue('H2', '001');
        $sheet->setCellValue('I2', '002');
        $sheet->setCellValue('J2', 'Desa Contoh');
        $sheet->setCellValue('K2', 'Kecamatan Contoh');
        $sheet->setCellValue('L2', 'Islam');
        $sheet->setCellValue('M2', 'single');
        $sheet->setCellValue('N2', 'Karyawan Swasta');
        $sheet->setCellValue('O2', 'WNI');
        $sheet->setCellValue('P2', 'S1');
        $sheet->setCellValue('Q2', 'Nama Ayah');
        $sheet->setCellValue('R2', 'Nama Ibu');
        
        // Auto size columns
        foreach (range('A', 'R') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_penduduk.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function getResidents()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Direct access not allowed']);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('residents');

        // Get DataTables parameters
        $draw = $this->request->getPost('draw');
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $search = $this->request->getPost('search')['value'];
        $order = $this->request->getPost('order');

        // Get total records count
        $totalRecords = $builder->countAllResults(false);

        // Apply search
        if (!empty($search)) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('nik', $search)
                ->orLike('address', $search)
                ->orLike('birth_place', $search)
                ->groupEnd();
        }

        // Get filtered records count
        $totalFiltered = $builder->countAllResults(false);

        // Apply ordering
        if (!empty($order)) {
            $columns = ['nik', 'name', 'birth_place', 'gender', 'address', 'rt', 'religion', 'marital_status'];
            $columnIndex = $order[0]['column'];
            $columnName = $columns[$columnIndex];
            $columnDirection = $order[0]['dir'];
            $builder->orderBy($columnName, $columnDirection);
        } else {
            $builder->orderBy('name', 'ASC');
        }

        // Apply pagination
        $builder->limit($length, $start);

        // Get records
        $records = $builder->get()->getResultArray();

        // Prepare response
        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $records
        ];

        return $this->response->setJSON($response);
    }
} 