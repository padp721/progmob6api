<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerhitunganBunga extends Model
{
    protected $table = "tb_trx_perhitungan_bunga_simpanan";

    protected $fillable = [
        'trx_bulan', 'trx_tahun', 'tanggal_proses', 'persentase_bunga' , 'id_user_pegawai'
    ];

    public $timestamps = false;
}
