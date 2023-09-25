<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;
    protected $table = "merchants";
    protected $primaryKey = "id";
    protected $fillable = [
        'image',
        'title',
        'sku_name',
        'price',
        'available',
        'stock',
        'messenger_keywords',
        'auto_reply_comments',
        'auto_reply_comment',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
