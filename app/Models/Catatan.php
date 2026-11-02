<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catatan extends Model
{
    protected $fillable = ['judul', 'isi']; // user_id sengaja tidak dimasukkan

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}