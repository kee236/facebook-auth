<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'image',
        'file_type',
        'message',
        'status',
        'fb_id',
        'fb_post_id'
    ];

    public function fbRules()
    {
        return $this->hasMany(FbRule::class);
    }

    public function salePost()
    {
        return $this->hasOne(SalePost::class);
    }

    // public function comments()
    // {
    //     return $this->hasMany(Comment::class);
    // }

    // public function comments(){
    //     return $this->morphMany(Comment::class,'commentable');
    // }


    public function keywords() {
        return $this->morphToMany(Keyword::class, 'keywordable');
    }


}
