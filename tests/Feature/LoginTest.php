<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->postJson('/buses/',
            [
                'matricule' =>  self::matricule,
                'nmbrPlace' => 11,
                'nmbrPlaceDebout' => 8
            ])->assertStatus(200);
    }
}
