<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Mediconesystems\LivewireDatatables\Commands\MakeDatatableCommand;

beforeEach(function (): void {
    File::deleteDirectory($this->livewireClassesPath());
});

afterEach(function (): void {
    File::deleteDirectory($this->livewireClassesPath());
});

it('creates a component class from the given name', function (string $name, string $path): void {
    // 🧪 Arrange
    $expectedPath = $this->livewireClassesPath($path);

    // 🧪 Act
    Artisan::call('make:livewire-datatable', ['name' => $name]);

    // 🧪 Assert
    expect(File::exists($expectedPath))->toBeTrue();
})->with([
    'single word' => ['foo', 'Foo.php'],
    'dot nested' => ['foo.bar', 'Foo/Bar.php'],
    'forward slash nested' => ['foo/bar', 'Foo/Bar.php'],
    'multiword' => ['foo-bar', 'FooBar.php'],
    'pascal case' => ['FooBar.FooBar', 'FooBar/FooBar.php'],
    'snake case' => ['text_replace', 'TextReplace.php'],
    'snake case nested' => ['TextManager.text_replace', 'TextManager/TextReplace.php'],
]);

it('sets the model named by the model option', function (): void {
    // 🧪 Arrange
    $arguments = ['name' => 'foo', '--model' => 'bar'];

    // 🧪 Act
    Artisan::call(MakeDatatableCommand::class, $arguments);

    // 🧪 Assert
    expect(File::get($this->livewireClassesPath('Foo.php')))
        ->toContain('public $model = Bar::class;');
});

it('refuses a reserved class name', function (): void {
    // 🧪 Arrange
    $arguments = ['name' => 'component'];

    // 🧪 Act
    Artisan::call('make:livewire-datatable', $arguments);

    // 🧪 Assert
    expect(File::exists($this->livewireClassesPath('Component.php')))->toBeFalse();
});
