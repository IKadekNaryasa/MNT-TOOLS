<?php

namespace App\Models;

use CodeIgniter\Model;

class Request extends Model
{
    protected $table            = 'request';
    protected $primaryKey       = 'permintaanCode';
    // protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    // protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['permintaanCode', 'usersId', 'status', 'tanggalPermintaan'];

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
        $builder->select('request.permintaanCode, GROUP_CONCAT(categories.namaKategori SEPARATOR ", ") as kategori, request.tanggalPermintaan, users.nama,users.usersId, users.kontak, request.status');
        $builder->join('detailRequest', 'request.permintaanCode = detailRequest.permintaanCode');
        $builder->join('categories', 'detailRequest.categoryId = categories.categoryId');
        $builder->join('users', 'request.usersId = users.usersId');
        $builder->orderBy('request.permintaanCode', 'DESC');
        $builder->groupBy('request.permintaanCode');
        return $builder->get()->getResultArray();
    }

    public function getDataByUser($id)
    {
        $builder = $this->db->table($this->table);
        $builder->select('request.permintaanCode,request.status,tanggalPermintaan,GROUP_CONCAT(namaKategori SEPARATOR ",") as kategori');
        $builder->join('users', 'request.usersId = users.usersId', 'LEFT');
        $builder->join('detailrequest', 'request.permintaanCode = detailrequest.permintaanCode', 'LEFT');
        $builder->join('categories', 'detailrequest.categoryId = categories.categoryId', 'LEFT');
        $builder->where('request.usersId', $id);
        $builder->orderBy('request.permintaanCode', 'DESC');
        $builder->groupBy('request.permintaanCode');
        return $builder->get()->getResultArray();
    }
    public function getByRequestCode($RCode)
    {
        $builder = $this->db->table($this->table);
        $builder->select('request.permintaanCode,request.status,tanggalPermintaan,GROUP_CONCAT(categories.categoryId SEPARATOR ",") as category_id');
        $builder->join('detailrequest', 'request.permintaanCode = detailrequest.permintaanCode', 'LEFT');
        $builder->join('categories', 'detailrequest.categoryId = categories.categoryId', 'LEFT');
        $builder->where('request.permintaanCode', $RCode);
        $builder->orderBy('request.permintaanCode', 'DESC');
        $builder->groupBy('request.permintaanCode');
        return $builder->get()->getResultArray();
    }
}
