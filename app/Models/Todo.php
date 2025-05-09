<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'completed',
        'user_id',
    ];
    
    protected $attributes = [
        'completed' => false,
    ];

    // define relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
