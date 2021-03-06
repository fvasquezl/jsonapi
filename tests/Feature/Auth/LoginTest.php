<?php

namespace Tests\Feature\Auth;

use App\Models\Permission;
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
            'device_name' => 'iPhone de ' . $user->name
        ]);
        $token = $response->json('plain-text-token');

        $this->assertNotNull(
            PersonalAccessToken::findToken($token),
            "The plain text token is invalid"
        );

    }

    /** @test */
    public function user_permissions_are_assigned_as_abilities_to_the_token_response()
    {
        $user = User::factory()->create();

        $permission = Permission::factory()->create([
            'name' => $articlesCreatePermission = 'articles:create'
        ]);
        $permission2 = Permission::factory()->create([
            'name' => $articlesUpdatePermission = 'articles:update'
        ]);

        $user->givePermissionTo($permission);
        $user->givePermissionTo($permission2);

        $response = $this->postJson(route('api.v1.login'), [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'iPhone de ' . $user->name
        ]);


        $dbToken = PersonalAccessToken::findToken(
            $response->json('plain-text-token')
        );

        $this->assertTrue($dbToken->can($articlesCreatePermission));
        $this->assertTrue($dbToken->can($articlesUpdatePermission));
        $this->assertFalse($dbToken->can('articles:delete'));
    }


    /** @test */
    public function cannot_login_twice()
    {
        $user = User::factory()->create();

        $token = $user->createToken($user->name)->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson(route('api.v1.login'))
            ->assertStatus(204) //empty
        ;
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
        ])->assertSee(__('validation.required', ['attribute' => 'email']))
            ->assertJsonValidationErrors('email');

    }

    /** @test */
    public function email_must_be_valid()
    {
        $this->postJson(route('api.v1.login'), [
            'email' => 'invalid-email',
            'password' => 'wrong-password',
            'device_name' => 'iPhine de Faustino'
        ])->assertSee(__('validation.email', ['attribute' => 'email']))
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
