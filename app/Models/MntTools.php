<?php

namespace App\Models;

use CodeIgniter\Model;

class MntTools extends Model
{
    protected $table            = 'mnttools';
    protected $primaryKey       = 'mntToolsId';
    protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    // protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['namaAlat', 'kodeAlat', 'kondisi', 'status', 'categoryId'];

    // protected bool $allowEmptyInserts = false;
    // protected bool $updateOnlyChanged = true;

    // protected array $casts = [];
    // protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'date';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

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
        $builder->join('categories', 'mntTools.categoryId = categories.categoryId');
        $builder->orderBy('mntToolsId', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getStatusAlat($kodeAlatArray)
    {
        if (empty($kodeAlatArray)) {
            return [];
        }


        $builder = $this->db->table($this->table);
        $builder->select('kodeAlat, status');
        $builder->whereIn('kodeAlat', $kodeAlatArray);
        $result = $builder->get()->getResultArray();


        $statusAlat = [];
        foreach ($result as $item) {
            $statusAlat[$item['kodeAlat']] = $item['status'];
        }

        return $statusAlat;
    }

    public function getDataByToolId($mntToolsId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('mntToolsId,namaAlat,kodeAlat,kondisi,status,categories.categoryId,namaKategori');
        $builder->join('categories', 'mntTools.categoryId = categories.categoryId');
        $builder->where('mntTools.mntToolsId', $mntToolsId);
        return $builder->get()->getResultArray();
    }
}
