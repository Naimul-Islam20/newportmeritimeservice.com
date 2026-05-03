<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! $this->app->environment('testing')) {
            return;
        }

        $manifestPath = public_path('build/manifest.json');
        if (is_file($manifestPath)) {
            return;
        }

        File::ensureDirectoryExists(public_path('build/assets'));
        File::put(public_path('build/assets/app-test.css'), '/* tests */');
        File::put(public_path('build/assets/app-test.js'), '/* tests */');
        File::put($manifestPath, json_encode([
            'resources/css/app.css' => [
                'file' => 'assets/app-test.css',
                'src' => 'resources/css/app.css',
                'isEntry' => true,
            ],
            'resources/js/app.js' => [
                'file' => 'assets/app-test.js',
                'src' => 'resources/js/app.js',
                'isEntry' => true,
            ],
        ], JSON_THROW_ON_ERROR));
    }
}
