<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Datas extends Model
{
    protected $table = 'datas';
    protected $fillable = [
        'unique_key',
        'product_title',
        'product_description'
    ];
   

}
