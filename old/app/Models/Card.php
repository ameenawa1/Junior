<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'profile_image',
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
        'github',
        'template_id'
    ];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
