<?php

namespace App\Models;

use CodeIgniter\Model;

class APBDModel extends Model
{
    protected $table            = 'apbd';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tahun', 'jenis', 'kategori', 'uraian', 'jumlah', 
        'status', 'keterangan', 'created_by', 'updated_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Get unique years for filtering
    public function getYears()
    {
        return $this->select('tahun')
                    ->distinct()
                    ->orderBy('tahun', 'DESC')
                    ->findAll();
    }
    
    // Get summary of APBD by year
    public function getSummaryByYear($year)
    {
        $pendapatan = $this->selectSum('jumlah')
                          ->where('tahun', $year)
                          ->where('jenis', 'pendapatan')
                          ->first();
                          
        $belanja = $this->selectSum('jumlah')
                       ->where('tahun', $year)
                       ->where('jenis', 'belanja')
                       ->first();
                       
        $pembiayaan = $this->selectSum('jumlah')
                          ->where('tahun', $year)
                          ->where('jenis', 'pembiayaan')
                          ->first();
                          
        return [
            'pendapatan' => $pendapatan['jumlah'] ?? 0,
            'belanja' => $belanja['jumlah'] ?? 0,
            'pembiayaan' => $pembiayaan['jumlah'] ?? 0,
            'sisa' => ($pendapatan['jumlah'] ?? 0) - ($belanja['jumlah'] ?? 0) + ($pembiayaan['jumlah'] ?? 0)
        ];
    }
    
    // Get APBD data by year and type
    public function getByYearAndType($year, $type)
    {
        return $this->where('tahun', $year)
                    ->where('jenis', $type)
                    ->orderBy('kategori', 'ASC')
                    ->findAll();
    }
} 