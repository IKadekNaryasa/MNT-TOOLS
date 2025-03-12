<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPermintaan extends Model
{
    protected $table            = 'detailrequest';
    protected $primaryKey       = 'detailRequestId';
    protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    // protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['permintaanCode', 'categoryId'];

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
        $builder->select('request.permintaanCode, GROUP_CONCAT(categories.namaKategori SEPARATOR ", ") as kategori, request.tanggalPermintaan, users.nama, users.kontak, request.status');
        $builder->join('request', 'detailRequest.permintaanCode = request.permintaanCode');
        $builder->join('categories', 'detailRequest.categoryId = categories.categoryId');
        $builder->join('users', 'request.usersId = users.usersId');
        $builder->orderBy('request.permintaanCode', 'DESC');
        $builder->groupBy('request.permintaanCode');
        return $builder->get()->getResultArray();
    }
}
