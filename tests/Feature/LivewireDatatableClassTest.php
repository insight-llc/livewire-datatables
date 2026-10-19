<?php

use Livewire\Livewire;
use Mediconesystems\LivewireDatatables\Tests\Classes\DummyTable;
use Mediconesystems\LivewireDatatables\Tests\Models\DummyModel;

it('mounts using the class', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create(['subject' => 'How to sell paper in Scranton PA']);

    // 🧪 Act
    $component = Livewire::test(DummyTable::class);

    // 🧪 Assert
    $component->assertSee('How to sell paper in Scranton PA');
    expect($component->columns)->toBeArray();
    expect(collect($component->columns)->map->label->toArray())->toBe([
        0 => 'ID',
        1 => 'Subject',
        2 => 'Category',
        3 => 'Body',
        4 => 'Flag',
        5 => 'Expiry',
        6 => 'Relation',
        7 => 'BooleanFilterableSubject',
    ]);
});

it('sets a default sort', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create();

    // 🧪 Act
    $component = Livewire::test(DummyTable::class);

    // 🧪 Assert
    expect($component->columns)->toBeArray();
    expect($component->sort)->toBe(0);
    expect($component->direction)->toBeFalse();
});

it('shows and hides a column', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create(['subject' => 'Beet growing for noobs']);
    $component = Livewire::test(DummyTable::class)
        ->assertSee('Beet growing for noobs');

    // 🧪 Act & Assert
    $component->call('toggle', 1)
        ->assertDontSee('Beet growing for noobs')
        ->call('toggle', 1)
        ->assertSee('Beet growing for noobs')
        ->call('toggle', 1)
        ->assertDontSee('Beet growing for noobs');
});

it('orders results', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create(['subject' => 'Beet growing for noobs']);
    DummyModel::factory()->create(['subject' => 'Advanced beet growing']);
    $table = new DummyTable(1);

    expect($table->results->getCollection()->pluck('subject')->all())
        ->toBe(['Beet growing for noobs', 'Advanced beet growing']);

    // 🧪 Act
    $table->forgetComputed();
    $table->sort = 1;
    $table->direction = true;

    // 🧪 Assert
    expect($table->results->getCollection()->pluck('subject')->all())
        ->toBe(['Advanced beet growing', 'Beet growing for noobs']);
});

it('filters results on text', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create(['subject' => 'Beet growing for noobs']);
    DummyModel::factory()->create(['subject' => 'Advanced beet growing']);
    $component = Livewire::test(DummyTable::class)->assertSee('Results 1 - 2');

    // 🧪 Act
    $component->call('doTextFilter', 1, 'Advance');

    // 🧪 Assert
    $component->assertSee('Results 1 - 1');
});

it('filters results on a boolean', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create(['flag' => true]);
    DummyModel::factory()->create(['flag' => false]);
    $component = Livewire::test(DummyTable::class)->assertSee('Results 1 - 2');

    // 🧪 Act
    $component->call('doBooleanFilter', 4, '1');

    // 🧪 Assert
    $component->assertSee('Results 1 - 1');
});

it('filters strings as a boolean', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create(['subject' => 'has contents']);
    DummyModel::factory()->create(['subject' => '']);
    $component = Livewire::test(DummyTable::class)->assertSee('Results 1 - 2');

    // 🧪 Act
    $component->call('doBooleanFilter', 7, '1');

    // 🧪 Assert
    $component->assertSee('Results 1 - 1');
});

it('filters results on a select', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create(['category' => 'Schrute']);
    DummyModel::factory()->create(['category' => 'Scott']);
    $component = Livewire::test(DummyTable::class)->assertSee('Results 1 - 2');

    // 🧪 Act
    $component->call('doSelectFilter', 2, 'Scott');

    // 🧪 Assert
    $component->assertSee('Results 1 - 1');
});

it('filters results on a number range', function (): void {
    // 🧪 Arrange
    foreach (range(1, 5) as $id) {
        DummyModel::factory()->create(['id' => $id]);
    }

    $component = Livewire::test(DummyTable::class)
        ->set('columns.0.numberFilter.0.min', 0)
        ->set('columns.0.numberFilter.0.max', 1000000)
        ->assertSee('Results 1 - 5');

    // 🧪 Act & Assert
    $component->call('doNumberFilterStart', 0, 2)
        ->assertSee('Results 1 - 4')
        ->call('doNumberFilterEnd', 0, 3)
        ->assertSee('Results 1 - 2')
        ->call('removeNumberFilter', 0)
        ->assertSee('Results 1 - 5');
});
