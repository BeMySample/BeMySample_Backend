<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';

    protected $fillable = [
        'username',
        'nama_lengkap',
        'email',
        'password',
        'tanggal_lahir',
        'jenis_kelamin',
        'umur',
        'lokasi',
        'minat',
        'institusi',
        'poin_saya',
        'pekerjaan',
        'profilepic'
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
}
