<?php

namespace App\Models;

use CodeIgniter\Model;

class DataInventaris extends Model
{
    protected $table            = 'datainventaris';
    protected $primaryKey       = 'dataInventarisId';
    protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    // protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['categoryId', 'tanggalDI', 'jumlahDI', 'vendor', 'harga', 'total'];

    // protected bool $allowEmptyInserts = false;
    // protected bool $updateOnlyChanged = true;

    // protected array $casts = [];
    // protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'date';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // Validation
    // protected $validationRules      = [];
    // protected $validationMessages   = [];
    // protected $skipValidation       = false;
    // protected $cleanValidationRules = true;

    // Callbacks
    // protected $allowCallbacks = true;
    // protected $beforeInsert   = [];
    // protected $afterInsert    = [];
    // protected $beforeUpdate   = [];
    // protected $afterUpdate    = [];
    // protected $beforeFind     = [];
    // protected $afterFind      = [];
    // protected $beforeDelete   = [];
    // protected $afterDelete    = [];

    public function getAll()
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->join('categories', 'dataInventaris.categoryId = categories.categoryId');
        $builder->orderBy('dataInventarisId', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getDataById($id)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->join('categories', 'dataInventaris.categoryId = categories.categoryId');
        $builder->where('dataInventarisId', $id);
        return $builder->get()->getResultArray();
    }

    public function countJumlah($categoryId)
    {
        return $this->selectSum('jumlahDI')
            ->where('categoryId', $categoryId)
            ->get()
            ->getRow()
            ->jumlahDI;
    }

    public function getAllDataByRange($awal, $akhir)
    {
        $builder = $this->db->table($this->table);
        $builder->select('tanggalDI,jumlahDI,vendor,total,categories.namaKategori');
        $builder->join('categories', 'datainventaris.categoryId = categories.categoryId');
        $builder->where('tanggalDI >= ', $awal);
        $builder->where('tanggalDI <= ', $akhir);
        return $builder->get()->getResultArray();
    }
    public function getAllDataByRangeAndCategory($awal, $akhir, $categoryId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('tanggalDI,jumlahDI,vendor,total,categories.namaKategori');
        $builder->join('categories', 'datainventaris.categoryId = categories.categoryId');
        $builder->where('tanggalDI >= ', $awal);
        $builder->where('tanggalDI <= ', $akhir);
        $builder->where('categories.categoryId', $categoryId);
        return $builder->get()->getResultArray();
    }
}
