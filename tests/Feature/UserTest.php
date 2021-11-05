<?php

namespace Tests\Feature;

use App\User;
use Modules\Online\Entities\OnlineCourse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    use WithoutMiddleware;

    public function testExample()
    {
        $this->withoutMiddleware();
        $response = $this->post(route('login'), [
            'username' => 'asdasd', 'password' => 'asdasd'
        ]);
        $response->assertStatus(200);

    }

    public function testLogin() {


        $response = $this->post(route('login'), [
            'username' => 'admin', 'password' => 'Abc123@@'
        ]);
        $response->assertStatus(302);
    }
}
