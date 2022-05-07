<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class files extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name_file',
        'status',
        'path',
        'time_uploaded',
        'created_at',
        'updated_at'
    ];

    
    public function scopeNotProcessed(Builder $query)
    {
        return $this->where('status', '=', 'pending');
    }
}
