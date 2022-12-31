<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $fillable = [
        'displayname',
        'job_title',
        'about',
        'email',
        'address',
        'phone_num1',
        'phone_num2',
        'facebook',
        'instagram',
        'linkedin',
        'github'
    ];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
