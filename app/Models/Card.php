<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'qr_code',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function phonenumbers()
    {
        return $this->hasMany(PhoneNumber::class);
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }
}
