<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoReply extends Model
{
    use HasFactory;

    protected $table = "auto_replies";
    protected $primaryKey = "id";
    protected $fillable = [
        'keyword_id',
        'message',
        'type',
    ];
}
