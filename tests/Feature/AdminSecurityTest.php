<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_admin_cannot_delete_super_admin_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $superAdmin));

        $response->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $superAdmin->id]);
    }

    public function test_brute_force_login_attempts_are_rate_limited(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'Password@12345',
            'role' => 'admin',
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email' => 'admin@example.com',
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('Too many login attempts', session('errors')->first('email'));
    }

    public function test_viewing_contact_message_marks_it_as_read(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $message = ContactMessage::factory()->create(['status' => 'unread']);

        $response = $this->actingAs($admin)->get(route('admin.contact-messages.show', $message));

        $response->assertOk();
        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'status' => 'read',
        ]);
    }
}
