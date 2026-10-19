<?php

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\ColumnSet;
use Mediconesystems\LivewireDatatables\Tests\Models\DummyModel;

it('generates a column for every model attribute', function (): void {
    // 🧪 Arrange
    $model = DummyModel::factory()->create();

    // 🧪 Act
    $columns = ColumnSet::build($model)->columns();

    // 🧪 Assert
    expect($columns)->toHaveCount(8)
        ->each->toBeInstanceOf(Column::class);
});

it('populates each column from the model', function (string $label, int $index, string $name): void {
    // 🧪 Arrange
    $model = DummyModel::factory()->create();

    // 🧪 Act
    $column = ColumnSet::build($model)->columns()[$index];

    // 🧪 Assert
    expect($column)
        ->label->toBe($label)
        ->name->toBe($name)
        ->callback->toBeNull()
        ->filterable->toBeNull()
        ->scope->toBeNull()
        ->scopeFilter->toBeNull()
        ->hidden->toBeNull();
})->with([
    'subject' => ['Subject', 0, 'subject'],
    'category' => ['Category', 1, 'category'],
    'body' => ['Body', 2, 'body'],
    'flag' => ['Flag', 3, 'flag'],
    'expires at' => ['Expires at', 4, 'expires_at'],
    'updated at' => ['Updated at', 5, 'updated_at'],
    'created at' => ['Created at', 6, 'created_at'],
    'id' => ['Id', 7, 'id'],
]);

it('excludes columns', function (): void {
    // 🧪 Arrange
    $model = DummyModel::factory()->create();

    // 🧪 Act
    $columns = ColumnSet::build($model)
        ->exclude(['id', 'body'])
        ->columns();

    // 🧪 Assert
    expect($columns)->toHaveCount(6)
        ->not->toHaveKey(7)
        ->not->toHaveKey(2);
});

it('includes only the listed columns', function (): void {
    // 🧪 Arrange
    $model = DummyModel::factory()->create();

    // 🧪 Act
    $columns = ColumnSet::build($model)
        ->include(['id', 'body'])
        ->columns();

    // 🧪 Assert
    expect($columns)->toHaveCount(2);
    expect($columns[0]->name)->toBe('id');
    expect($columns[1]->name)->toBe('body');
});

it('renames columns', function (): void {
    // 🧪 Arrange
    $model = DummyModel::factory()->create();

    // 🧪 Act
    $columns = ColumnSet::build($model)
        ->include(['id|ident', 'body|main text'])
        ->columns();

    // 🧪 Assert
    expect($columns)->toHaveCount(2);
    expect($columns[0])->name->toBe('id')->label->toBe('ident');
    expect($columns[1])->name->toBe('body')->label->toBe('main text');
});
