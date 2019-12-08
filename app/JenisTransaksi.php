<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisTransaksi extends Model
{
    protected $table = "tb_jenis_transaksi";

    protected $fillable = [
        'transaksi', 'tipe'
    ];

    public $timestamps = false;

    public function simpanan()
    {
        return $this->hasMany('App\Simpanan','jenis_transaksi');
    }
}
