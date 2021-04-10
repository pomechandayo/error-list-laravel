<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'エラー嫌太郎',
            'email' => 'error@iya.cop',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'name' => '解決太郎',
            'email' => 'solve@sovle.cop',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        DB::table('users')->insert([
            'name' => '記事太郎',
            'email' => 'article@post.cop',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
       
    }
}
