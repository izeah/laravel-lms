<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueRule extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'max_borrow_day', 'max_borrow_item'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
