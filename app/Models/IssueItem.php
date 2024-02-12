<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
    ];

    public function book()
    {
        return $this->belongsTo(Item::class);
    }

    public function issue()
    {
        return $this->belongsTo(Issue::class, 'issue_id');
    }
}
