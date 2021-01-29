<?php

namespace Tests\Unit;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPermissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_assign_permissions_to_a_user()
    {
        $user = User::factory()->create();
        $permission = Permission::factory()->create();

        $user->givePermissionTo($permission);

        $this->assertCount(1, $user->fresh()->permissions);
    }

    /** @test */
    public function can_assign_the_same_permissions_twice()
    {
        $user = User::factory()->create();
        $permission = Permission::factory()->create();

        $user->givePermissionTo($permission);
        $user->givePermissionTo($permission);

        $this->assertCount(1, $user->fresh()->permissions);
    }
}
