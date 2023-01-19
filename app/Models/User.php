<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use App\Models\Card;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function contacts()
    {
        return $this->belongsToMany(User::class, 'contacts', 'user_id', 'contact_id');
    }

    public static function mycontactshelper($obj){

        $cards = Card::where('user_id','=',$obj->id)->first();
        //dd($card);

        return $obj->contacts;
    }

    public function toArray()
    {

        return
        [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'email' => $this->email,
            'card' => $this->card
            /*'contacts' => $contacts*/
        ];


    }

    public function card()
    {
        return $this->hasOne(Card::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function emailverification()
    {
        return $this->hasone(EmailVerification::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
