<?php

namespace App\Models;

use CodeIgniter\Model;

class Perbaikan extends Model
{
    protected $table            = 'perbaikan';
    protected $primaryKey       = 'perbaikanId';
    protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    // protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['mntToolsId', 'tanggalPerbaikan', 'tanggalSelesai', 'deskripsi', 'statusPerbaikan', 'biaya'];

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
        $builder->select('perbaikanId,statusPerbaikan,mntTools.kodeAlat,tanggalPerbaikan,tanggalSelesai,biaya,deskripsi');
        $builder->join('mntTools', 'perbaikan.mntToolsId = mntTools.mntToolsId');
        $builder->orderBy('perbaikanId', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getAllDataByRange($awal, $akhir)
    {
        $builder = $this->db->table($this->table);
        $builder->select('kodeAlat,tanggalPerbaikan,tanggalSelesai,statusPerbaikan,biaya');
        $builder->join('mntTools', 'perbaikan.mntToolsId = mntTools.mntToolsId');
        $builder->where('tanggalPerbaikan >= ', $awal);
        $builder->where('tanggalPerbaikan <= ', $akhir);
        return $builder->get()->getResultArray();
    }
}
