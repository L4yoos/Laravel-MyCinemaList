<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tvshows_Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'tvshow_id',
        'name',
        'img',
        'genres',
        'number_of_episodes',
        'watched_episodes',
        'status',
        'score',
    ];
}
