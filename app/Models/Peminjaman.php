<?php

namespace App\Models;

use CodeIgniter\Model;

class Peminjaman extends Model
{
    protected $table            = 'peminjaman';
    protected $primaryKey       = 'peminjamanCode';
    protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    // protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['peminjamanCode', 'usersId', 'tanggalPinjam', 'tanggalKembali', 'keteranganPeminjaman', 'statusPeminjaman', 'byAdmin'];

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
        $builder->select('
        peminjaman.peminjamanCode,
        peminjam.nama AS namaPeminjam,
        admin.nama AS namaAdmin,
        tanggalPinjam,
        keteranganPeminjaman,
        statusPeminjaman,
        GROUP_CONCAT(namaAlat SEPARATOR ",") as namaAlat,
        GROUP_CONCAT(detailpeminjaman.kodeAlat SEPARATOR ",") as kodeAlat
    ');
        $builder->join('users peminjam', 'peminjaman.usersId = peminjam.usersId');
        $builder->join('users admin', 'peminjaman.byAdmin = admin.usersId', 'LEFT');
        $builder->join('detailPeminjaman', 'peminjaman.peminjamanCode = detailPeminjaman.peminjamanCode', 'LEFT');
        $builder->join('mntTools', 'detailPeminjaman.kodeAlat = mntTools.kodeAlat');
        $builder->groupBy('peminjaman.peminjamanCode');
        $builder->orderBy('peminjaman.peminjamanCode', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getDataByUser($usersIid)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
        peminjaman.peminjamanCode,tanggalPinjam,users.nama,statusPeminjaman,keteranganPeminjaman,
        GROUP_CONCAT(namaAlat SEPARATOR ",") as namaAlat,
        GROUP_CONCAT(detailPeminjaman.kodeAlat SEPARATOR ",") as kodeAlat,
        ');

        $builder->join('users', 'peminjaman.byAdmin = users.usersId');
        $builder->join('detailPeminjaman', 'peminjaman.peminjamanCode = detailPeminjaman.peminjamanCode', 'LEFT');
        $builder->join('mntTools', 'detailPeminjaman.kodeAlat = mntTools.kodeAlat');
        $builder->where('peminjaman.usersId', $usersIid);
        $builder->groupBy('peminjaman.peminjamanCode');
        $builder->orderBy('peminjaman.peminjamanCode', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getAllDataByRange($awal, $akhir)
    {
        $builder = $this->db->table($this->table);
        $builder->select('nama,kontak,peminjamanCode,tanggalPinjam,statusPeminjaman');
        $builder->join('users', 'peminjaman.usersId = users.usersId', 'LEFT');
        $builder->where('tanggalPinjam >= ', $awal);
        $builder->where('tanggalPinjam <= ', $akhir);
        return $builder->get()->getResultArray();
    }
}
