<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
  use HasFactory;
  protected $fillable = [
'user_id', 'external_id', 'slug', 'title', 'body', 'is_published', 'is_draft' 
  ];
   public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
