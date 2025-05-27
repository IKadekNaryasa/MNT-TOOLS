<?php

namespace App\Models;

use CodeIgniter\Model;

class Pengembalian extends Model
{
    protected $table            = 'pengembalian';
    protected $primaryKey       = 'pengembalianId';
    protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    // protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['peminjamanCode', 'tanggalKembali', 'keteranganPengembalian', 'byAdmin', 'statusPengembalian'];

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
        $builder->select('pengembalianId,pengembalian.peminjamanCode,pengembalian.tanggalKembali,keteranganPengembalian,pengembalian.byAdmin,statusPengembalian,peminjaman.usersId,users.nama,GROUP_CONCAT(namaAlat SEPARATOR ",") as namaAlat,GROUP_CONCAT(detailpeminjaman.kodeAlat SEPARATOR ",") as kodeAlat');
        $builder->join('peminjaman', 'pengembalian.peminjamanCode = peminjaman.peminjamanCode');
        $builder->join('users', 'pengembalian.byAdmin = users.usersId', 'LEFT');
        $builder->join('detailPeminjaman', 'peminjaman.peminjamanCode = detailPeminjaman.peminjamanCode', 'LEFT');
        $builder->join('mntTools', 'detailPeminjaman.kodeAlat = mntTools.kodeAlat', 'LEFT');
        $builder->groupBy('pengembalianId');
        $builder->orderBy('pengembalianId', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getCountById($usersId, $status)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->join('peminjaman', 'pengembalian.peminjamanCode = peminjaman.peminjamanCode');
        $builder->join('users', 'peminjaman.usersId = users.usersId');
        $builder->where('peminjaman.usersId', $usersId);
        $builder->where('pengembalian.statusPengembalian', $status);
        return $builder->countAllResults();
    }

    public function getDataByUser($usersId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            pengembalianId,
            pengembalian.peminjamanCode,
            pengembalian.tanggalKembali,keteranganPengembalian,users.nama,statusPengembalian,
            GROUP_CONCAT(namaAlat SEPARATOR ",") as namaAlat,
            GROUP_CONCAT(detailPeminjaman.kodeAlat SEPARATOR ",") as kodeAlat
        ');

        $builder->join('peminjaman', 'pengembalian.peminjamanCode = peminjaman.peminjamanCode');
        $builder->join('users', 'peminjaman.byAdmin = users.usersId');
        $builder->join('detailPeminjaman', 'pengembalian.peminjamanCode = detailPeminjaman.peminjamanCode');
        $builder->join('mntTools', 'detailPeminjaman.kodeAlat = mntTools.kodeAlat');
        $builder->where('peminjaman.usersId', $usersId);
        $builder->groupBy('pengembalianId');
        $builder->orderBy('pengembalianId', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getAllDataByRange($awal, $akhir)
    {
        $builder = $this->db->table($this->table);
        $builder->select('nama,kontak,pengembalian.peminjamanCode,pengembalian.tanggalKembali,statusPengembalian');
        $builder->join('peminjaman', 'pengembalian.peminjamanCode = peminjaman.peminjamanCode');
        $builder->join('users', 'peminjaman.usersId = users.usersId');
        $builder->where('pengembalian.tanggalKembali >= ', $awal);
        $builder->where('pengembalian.tanggalKembali <= ', $akhir);
        return $builder->get()->getResultArray();
    }
}
