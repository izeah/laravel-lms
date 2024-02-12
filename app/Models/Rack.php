<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'position'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
