<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actors_Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'actor_id',
        'img'
    ];
}
