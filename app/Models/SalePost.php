<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePost extends Model
{
    use HasFactory;
    protected $table = "sale_posts";
    protected $primaryKey = "id";
    protected $fillable = [
        'post_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

}
