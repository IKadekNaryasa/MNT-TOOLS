<?php

namespace App\Models;

use CodeIgniter\Model;

class Perawatan extends Model
{
    protected $table            = 'perawatan';
    protected $primaryKey       = 'perawatanId';
    protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    // protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['mntToolsId', 'tanggalPerawatan', 'tanggalSelesai', 'deskripsi', 'statusPerawatan', 'biaya'];

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
        $builder->select('perawatanId,statusPerawatan,mntTools.kodeAlat,tanggalPerawatan,tanggalSelesai,biaya,deskripsi');
        $builder->join('mntTools', 'perawatan.mntToolsId = mntTools.mntToolsId');
        $builder->orderBy('perawatanId', 'DESC');
        return $builder->get()->getResultArray();
    }
    public function getAllDataByRange($awal, $akhir)
    {
        $builder = $this->db->table($this->table);
        $builder->select('kodeAlat,tanggalPerawatan,tanggalSelesai,statusPerawatan,biaya');
        $builder->join('mntTools', 'perawatan.mntToolsId = mntTools.mntToolsId');
        $builder->where('tanggalPerawatan >= ', $awal);
        $builder->where('tanggalPerawatan <= ', $akhir);
        return $builder->get()->getResultArray();
    }
}
