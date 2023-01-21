<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'email',
        'token',
    ];
    
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
