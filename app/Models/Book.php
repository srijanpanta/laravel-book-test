<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //using factory to populate data
    use Hasfactory;
    protected $fillable = [
        'title',
        'author',
        'tags',
        'description',
        'image',
    ];  
     protected $casts = [
        'tags' => 'array', 
    ];
}
