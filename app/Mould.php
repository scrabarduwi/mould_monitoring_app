<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mould extends Model
{
    protected $fillable = [
        'client_id', 'mould_image', 'ring_id', 'mould_details', 'rack_no'
    ];
    protected $primaryKey = 'id';
    protected $table = 'mould';
}
