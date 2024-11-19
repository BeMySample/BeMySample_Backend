<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'john_doe',
            'nama_lengkap' => 'John Doe',
            'email' => 'john@example.com',
            'google_id' => 'lorem123tes',
            'avatar' => 'profile.png',
            'password' => Hash::make('password123'),  // Use Hash::make() for password
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'laki-laki',
            'umur' => 34,
            'lokasi' => 'Jakarta',
            'minat' => 'Programming',
            'institusi' => 'XYZ University',
            'poin_saya' => 0,
            'pekerjaan' => 'Software Developer',
            'profilepic' => 'profile.jpg',
        ]);
    }
}
