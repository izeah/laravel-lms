<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'qty',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function issueItems()
    {
        return $this->hasMany(IssueItem::class);
    }
}
