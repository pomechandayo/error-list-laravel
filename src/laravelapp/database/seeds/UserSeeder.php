<?php

use Illuminate\Database\Seeder;
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
            'email' => 'error@iya.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'name' => '解決太郎',
            'email' => 'solve@solve.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        DB::table('users')->insert([
            'name' => '記事太郎',
            'email' => 'article@post.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
       
    }
}
