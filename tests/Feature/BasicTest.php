<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BasicTest extends TestCase
{
    use WithoutMiddleware;

    private const matricule = 999999;

    public function busInsert(){
        $this->postJson('/buses/',
            [
                'matricule' =>  self::matricule,
                'nmbrPlace' => 11,
                'nmbrPlaceDebout' => 8
            ])->assertStatus(200);
    }

    public function busDuplicated(){
        // test duplicated
        $this->postJson('/buses/',
            [
                'matricule' =>  self::matricule,
                'nmbrPlace' => 11,
                'nmbrPlaceDebout' => 8
            ])->assertStatus(422);
    }

    public function busDeleteTest(){
        DB::table("buses")
            ->where('matricule',  self::matricule)
            ->delete();
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        /* route test */
        $response = $this->get('/');
        $response->assertStatus(200);

        $this->busInsert();
        $this->busDuplicated();
        $this->busDeleteTest();
    }
}
