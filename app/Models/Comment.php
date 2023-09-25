<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = "comments";
    protected $primaryKey = "id";
    protected $fillable = [
        'commentable_id',
        'commentable_type',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    // public function commentable(){
    //     return $this->morphTo();
    // }


    public function keywords() {
        return $this->morphToMany(Keyword::class, 'keywordable');
    }


}
