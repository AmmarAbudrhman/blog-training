<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Catgories extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'image',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
