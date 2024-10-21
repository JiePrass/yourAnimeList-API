<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'poster',
        'genre',
        'rating',
        'episode',
        'studio',
        'synopsis'
    ];

    // Tambahkan ini untuk mengonversi genre menjadi array secara otomatis
    protected $casts = [
        'genre' => 'array', // Mengonversi genre menjadi array saat diambil
    ];
}
