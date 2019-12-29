<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'id_simpanan', 'id_user', 'title', 'body'
    ];
}