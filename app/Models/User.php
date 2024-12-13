<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'username',
        'status',
        'nama_lengkap',
        'email',
        'google_id',
        'avatar',
        'password',
        'tanggal_lahir',
        'jenis_kelamin',
        'umur',
        'lokasi',
        'minat',
        'institusi',
        'poin_saya',
        'pekerjaan'
        // 'profilepic'
    ];
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    protected $table = 'user';
}
