<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    protected $table = "keywords";
    protected $primaryKey = "id";
    protected $fillable = [
        'body',
    ];



    public function posts() {
        return $this->morphedByMany(Post::class, 'keywordable');
    }

    public function comments() {
        return $this->morphedByMany(Comment::class, 'keywordable');
    }
}
