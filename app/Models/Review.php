<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'text' => 'required',
        'user_id' => 'required',
        'book_id' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rating',
        'text',
        'user_id',
        'book_id',
        'comment',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'rating' => 'double',
        'user_id' => 'integer',
        'book_id' => 'integer',
    ];
}
