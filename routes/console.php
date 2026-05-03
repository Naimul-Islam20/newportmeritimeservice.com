<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:create-user {--email=} {--password=} {--name=}', function (): void {
    $email = (string) ($this->option('email') ?: config('admin.email'));

    User::updateOrCreate(
        ['email' => $email],
        [
            'name' => (string) ($this->option('name') ?: config('admin.name')),
            'password' => (string) ($this->option('password') ?: config('admin.password')),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]
    );

    $this->components->info('Admin user ready.');
    $this->line("  Email: {$email}");
    $this->line('  Role: super_admin');
    $this->newLine();
    $this->comment('If login still fails: php artisan config:clear && check SESSION_DRIVER and DB_* in .env');
})->purpose('Create or update the super admin user (matches DatabaseSeeder)');
