<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function can_login_with_valid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('api.v1.login'), [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'iPhine de ' . $user->name
        ]);
        $token =$response->json('plain-text-token');

        $this->assertTrue(
            PersonalAccessToken::findToken($token)->exists
        );
    }

    /** @test */
    public function cannot_login_with_invalid_credentials()
    {
        $this->postJson(route('api.v1.login'), [
            'email' => 'fvasquez@local.com',
            'password' => 'wrong-password',
            'device_name' => 'iPhine de Faustino'
        ])->assertJsonValidationErrors('email');

    }

    /** @test */
    public function email_is_required()
    {
        $this->postJson(route('api.v1.login'), [
            'email' => '',
            'password' => 'wrong-password',
            'device_name' => 'iPhine de Faustino'
        ])->assertSee(__('validation.required',['attribute'=>'email']))
            ->assertJsonValidationErrors('email');

    }

    /** @test */
    public function email_must_be_valid()
    {
        $this->postJson(route('api.v1.login'), [
            'email' => 'invalid-email',
            'password' => 'wrong-password',
            'device_name' => 'iPhine de Faustino'
        ])->assertSee(__('validation.email',['attribute'=>'email']))
            ->assertJsonValidationErrors('email');

    }

    /** @test */
    public function password_is_required()
    {
        $this->postJson(route('api.v1.login'), [
            'email' => 'fvasquez@local.com',
            'password' => '',
            'device_name' => 'iPhine de Faustino'
        ])->assertJsonValidationErrors('password');

    }
    /** @test */
    public function device_name_is_required()
    {
        $this->postJson(route('api.v1.login'), [
            'email' => 'fvasquez@local.com',
            'password' => 'password',
            'device_name' => ''
        ])->assertJsonValidationErrors('device_name');

    }

}
