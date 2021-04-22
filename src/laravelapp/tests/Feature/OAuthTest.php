<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OAuthTest extends TestCase
{
    use RefreshDatabase;
   
    public function setUp(): void
    {
        parent::setUp();
        $this->providerName = 'google';
    }

    public function testshowGoogleAuth()
    { 
        $this->get(route('login.google',['provider' => $this->providerName]))->assertStatus(302);
    }

    // public function testGoogleAcountRegister()
    // {
       
    //     $this->get(route('login.google.callback',['provider' => $this->providerName]))->assertStatus(200);
    // }
}
