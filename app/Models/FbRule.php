<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbRule extends Model
{
    use HasFactory;

 protected $table = "fb_rules";
 protected $primaryKey = "id";
 protected $fillable = [
    'user_id',
    'post_id',
    'rule_name',
     'page_name', '
     location',
     'currency',
     'images'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

}
