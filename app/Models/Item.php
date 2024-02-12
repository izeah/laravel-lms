<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'isbn',
        'code',
        'title',
        'year',
        'pages',
        'edition',
        'ebook_available',
        'description',
        'book_cover_url',
        'ebook_url',
        'table_of_contents',
        'total_qty',
        'qty_lost',
        'author_id',
        'category_id',
        'publisher_id',
        'disabled'
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'item_authors');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }

    public function issueItems()
    {
        return $this->hasMany(IssueItem::class, 'book_id');
    }
}
