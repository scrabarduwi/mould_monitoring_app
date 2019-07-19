<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RingCategory extends Model
{
    protected $fillable = [
        'ring_name', 'ring_image','ring_code'
    ];
    protected $primaryKey = 'id';
    protected $table = 'ring_category';
}
