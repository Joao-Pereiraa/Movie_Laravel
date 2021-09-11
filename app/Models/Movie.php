<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use HasFactory, softDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'category_id',
        'sinopse',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

