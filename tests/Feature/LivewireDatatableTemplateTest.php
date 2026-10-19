<?php

use Livewire\Livewire;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Tests\Models\DummyModel;

it('mounts from the default template with a model', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create();

    // 🧪 Act
    $component = Livewire::test(LivewireDatatable::class, ['model' => DummyModel::class]);

    // 🧪 Assert
    expect($component->model)->toBe(DummyModel::class);
    expect($component->columns)->toBeArray();
    expect(collect($component->columns)->map->label->toArray())->toBe([
        0 => 'Id',
        1 => 'Subject',
        2 => 'Category',
        3 => 'Body',
        4 => 'Flag',
        5 => 'Expires at',
        6 => 'Created at',
        7 => 'Updated at',
    ]);
});

it('hides the header with a property', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create();

    // 🧪 Act
    $component = Livewire::test(LivewireDatatable::class, [
        'model' => DummyModel::class,
        'hideHeader' => true,
    ]);

    // 🧪 Assert
    $component->assertDontSeeHtml('<button wire:click="sort');
});

it('hides the pagination bar with a property', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create();

    // 🧪 Act
    $component = Livewire::test(LivewireDatatable::class, [
        'model' => DummyModel::class,
        'hidePagination' => true,
    ]);

    // 🧪 Assert
    $component->assertDontSeeHtml('<select name="perPage"');
});

it('sets the page size with a property', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->count(20)->create();

    // 🧪 Act
    $component = Livewire::test(LivewireDatatable::class, [
        'model' => DummyModel::class,
        'perPage' => 20,
    ]);

    // 🧪 Assert
    $component->assertSee('Results 1 - 20');
});

it('includes columns from a property', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create();

    // 🧪 Act
    $component = Livewire::test(LivewireDatatable::class, [
        'model' => DummyModel::class,
        'include' => ['id', 'subject', 'body'],
    ]);

    // 🧪 Assert
    expect($component->columns)->toBeArray();
    expect(collect($component->columns)->map->label->toArray())->toBe([
        0 => 'Id',
        1 => 'Subject',
        2 => 'Body',
    ]);
});

it('excludes columns from a property', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create();

    // 🧪 Act
    $component = Livewire::test(LivewireDatatable::class, [
        'model' => DummyModel::class,
        'exclude' => ['subject'],
    ]);

    // 🧪 Assert
    expect($component->columns)->toBeArray();
    expect(collect($component->columns)->map->label->toArray())->toBe([
        0 => 'Id',
        2 => 'Category',
        3 => 'Body',
        4 => 'Flag',
        5 => 'Expires at',
        6 => 'Created at',
        7 => 'Updated at',
    ]);
});

it('hides columns from a property', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create(['subject' => 'HIDE-THIS']);

    // 🧪 Act
    $component = Livewire::test(LivewireDatatable::class, [
        'model' => DummyModel::class,
        'hide' => ['subject'],
    ]);

    // 🧪 Assert
    $component->assertDontSee('HIDE-THIS');
    expect($component->columns)->toBeArray()->toHaveCount(8);
});

it('formats date columns from a property', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create([
        'expires_at' => '2020-12-31',
        'created_at' => '1978-10-02',
    ]);

    // 🧪 Act
    $component = Livewire::test(LivewireDatatable::class, [
        'model' => DummyModel::class,
        'dates' => ['expires_at', 'created_at|jS F Y'],
    ]);

    // 🧪 Assert
    $component->assertSee('31/12/2020')
        ->assertSee('2nd October 1978');
});

it('formats time columns from a property', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create([
        'expires_at' => '2020-12-31 2:34 PM',
        'created_at' => '1978-10-02 13:45:56',
    ]);

    // 🧪 Act
    $component = Livewire::test(LivewireDatatable::class, [
        'model' => DummyModel::class,
        'times' => ['expires_at', 'created_at|g:i A'],
    ]);

    // 🧪 Assert
    $component->assertSee('14:34')
        ->assertSee('1:45 PM');
});

it('sets the sort from a property', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create();

    // 🧪 Act
    $component = Livewire::test(LivewireDatatable::class, [
        'model' => DummyModel::class,
        'sort' => 'subject|asc',
    ]);

    // 🧪 Assert
    expect($component->model)->toBe(DummyModel::class);
    expect($component->columns)->toBeArray();
    expect($component->sort)->toBe(1);
    expect($component->direction)->toBeTrue();
});
