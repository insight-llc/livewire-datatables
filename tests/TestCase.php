<?php

namespace Mediconesystems\LivewireDatatables\Tests;

use Livewire\LivewireServiceProvider;
use Maatwebsite\Excel\ExcelServiceProvider;
use Mediconesystems\LivewireDatatables\Commands\ComponentParser;
use Mediconesystems\LivewireDatatables\LivewireDatatablesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Reedware\LaravelRelationJoins\LaravelRelationJoinServiceProvider;

use function Orchestra\Testbench\default_skeleton_path;

class TestCase extends Orchestra
{
    /**
     * The artisan shim roots Testbench at this repository, and `artisan test` passes that path on.
     * The package has no `App\` namespace, so tests always boot Testbench's own skeleton.
     */
    public static function applicationBasePath(): string
    {
        return default_skeleton_path();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            LivewireDatatablesServiceProvider::class,
            ExcelServiceProvider::class,
            LaravelRelationJoinServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function livewireClassesPath(string $path = ''): string
    {
        $classPath = ComponentParser::generatePathFromNamespace(config('livewire.class_namespace'));

        return $classPath . ($path ? '/' . $path : '');
    }
}
