<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bunga extends Model
{
    protected $table = "tb_master_bunga_simpanan";


    protected $fillable = [
        'persentase', 'tanggal_mulai_berlaku'
    ];

    public $timestamps = false;
}
