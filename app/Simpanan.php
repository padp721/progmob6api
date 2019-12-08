<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    protected $table ="tb_simpanan";

    protected $fillable = [
        'tanggal', 'jenis_transaksi', 'nominal_transaksi' , 'id_user_nasabah', 'id_user_karyawan', 'status'
    ];

    public $timestamps = false;

    public function anggota()
    {
        return $this->belongsTo('App\User');
    }
    public function jenis()
    {
        return $this->belongsTo('App\JenisTransaksi','jenis_transaksi');
    }
}
