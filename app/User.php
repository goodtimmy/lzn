<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Hash;

class User extends Authenticatable
{
    use Notifiable;

    const TYPE_CLIENT = 'client';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'usertype',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getUserIdByPhoneOrEmail($email,$phone)
    {
        return self
            ::select('id')
            ->where('email', '=', $email)
            ->orWhere('phone', '=', $phone)
            ->first();
    }

    public static function createUser($name, $email, $phone)
    {
        $now = Carbon::now();

        $user = new self;
        $user->email = $email;
        $user->phone = $phone;
        $user->name = $name;
        $user->password = Hash::make( str_random(10) );
        $user->usertype = self::TYPE_CLIENT;

        $user->save();

        return $user->id;
    }

    public static function updateUserData($id, $data)
    {
        self::where('id', $id)->update($data);
    }
}
