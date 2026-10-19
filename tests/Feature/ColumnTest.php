<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;

it('generates a column from a table column', function (): void {
    // 🧪 Arrange
    $name = 'table.column';

    // 🧪 Act
    $column = Column::name($name);

    // 🧪 Assert
    expect($column)
        ->name->toBe('table.column')
        ->label->toBe('Column');
});

it('generates a column from a scope', function (): void {
    // 🧪 Arrange
    $scope = 'fakeScope';

    // 🧪 Act
    $column = Column::scope($scope, 'Alias');

    // 🧪 Assert
    expect($column)
        ->scope->toBe('fakeScope')
        ->label->toBe('Alias');
});

it('generates a delete column', function (): void {
    // 🧪 Arrange
    // Column::delete() needs no input.

    // 🧪 Act
    $column = Column::delete();

    // 🧪 Assert
    expect($column)
        ->additionalSelects->toBe(['id'])
        ->label->toBeEmpty()
        ->callback->toBeCallable();
});

it('sets properties and parameters', function (string $method, mixed $value, string $attribute): void {
    // 🧪 Arrange
    $column = Column::name('table.column');

    // 🧪 Act
    $column->$method($value);

    // 🧪 Assert
    expect($column->$attribute)->toBe($value);
})->with([
    'label' => ['label', 'Bob Vance', 'label'],
    'searchable' => ['searchable', true, 'searchable'],
    'filterable' => ['filterable', ['Michael Scott', 'Dwight Shrute'], 'filterable'],
    'hide' => ['hide', true, 'hidden'],
    'additional selects' => ['additionalSelects', ['hello world'], 'additionalSelects'],
]);

it('returns an array from a column', function (): void {
    // 🧪 Arrange
    $column = Column::name('table.column')
        ->label('Column')
        ->filterable(['A', 'B', 'C'])
        ->hide()
        ->linkTo('model', 8);

    // 🧪 Act
    $array = $column->toArray();

    // 🧪 Assert
    expect(Arr::pull($array, 'callback'))->toBeCallable();
    expect(Arr::pull($array, 'exportCallback'))->toBeCallable();
    expect($array)->toEqual([
        'type' => 'string',
        'name' => 'table.column',
        'base' => null,
        'label' => 'Column',
        'filterable' => ['A', 'B', 'C'],
        'hidden' => true,
        'raw' => null,
        'sort' => null,
        'defaultSort' => null,
        'searchable' => null,
        'sortable' => true,
        'params' => [],
        'additionalSelects' => [],
        'scope' => null,
        'scopeFilter' => null,
        'filterView' => null,
        'select' => null,
        'joins' => null,
        'aggregate' => 'group_concat',
        'headerAlign' => 'left',
        'preventExport' => null,
        'width' => null,
        'filterOn' => null,
        'group' => null,
        'hideable' => null,
        'index' => 0,
        'minWidth' => null,
        'maxWidth' => null,
        'tooltip' => null,
        'contentAlign' => 'left',
        'summary' => false,
        'wrappable' => true,
    ]);
});

it('returns an array from a raw column', function (): void {
    // 🧪 Arrange
    $column = DateColumn::raw('SELECT column FROM table AS table_column')
        ->filterable()
        ->defaultSort('asc')
        ->format('yyy-mm-dd');

    // 🧪 Act
    $array = $column->toArray();

    // 🧪 Assert
    expect(Arr::pull($array, 'callback'))->toBeCallable();
    expect($array)->toEqual([
        'type' => 'date',
        'name' => 'table_column',
        'base' => null,
        'label' => 'table_column',
        'filterable' => true,
        'hidden' => null,
        'raw' => 'SELECT column FROM table AS table_column',
        'sort' => 'SELECT column FROM table',
        'defaultSort' => 'asc',
        'searchable' => null,
        'sortable' => null,
        'params' => [],
        'additionalSelects' => [],
        'scope' => null,
        'scopeFilter' => null,
        'filterView' => null,
        'select' => DB::raw('SELECT column FROM table'),
        'joins' => null,
        'aggregate' => null,
        'headerAlign' => 'left',
        'preventExport' => null,
        'width' => null,
        'exportCallback' => null,
        'filterOn' => null,
        'group' => null,
        'hideable' => null,
        'index' => 0,
        'minWidth' => null,
        'maxWidth' => null,
        'tooltip' => null,
        'contentAlign' => 'left',
        'summary' => false,
        'wrappable' => true,
    ]);
});

it('returns the width property from a column', function (): void {
    // 🧪 Arrange
    $column = Column::name('table.column')
        ->label('Column')
        ->width('1em');

    // 🧪 Act
    $array = $column->toArray();

    // 🧪 Assert
    expect($array)->toEqual([
        'type' => 'string',
        'name' => 'table.column',
        'base' => null,
        'label' => 'Column',
        'filterable' => null,
        'hidden' => null,
        'callback' => null,
        'raw' => null,
        'sort' => null,
        'defaultSort' => null,
        'searchable' => null,
        'sortable' => true,
        'params' => [],
        'additionalSelects' => [],
        'scope' => null,
        'scopeFilter' => null,
        'filterView' => null,
        'select' => null,
        'joins' => null,
        'aggregate' => 'group_concat',
        'headerAlign' => 'left',
        'preventExport' => null,
        'width' => '1em',
        'exportCallback' => null,
        'filterOn' => null,
        'group' => null,
        'hideable' => null,
        'index' => 0,
        'minWidth' => null,
        'maxWidth' => null,
        'tooltip' => null,
        'contentAlign' => 'left',
        'summary' => false,
        'wrappable' => true,
    ]);
});

it('normalises the width it is given', function (string $width, ?string $expected): void {
    // 🧪 Arrange
    $column = Column::name('table.column');

    // 🧪 Act
    $column->width($width);

    // 🧪 Assert
    expect($column->width)->toBe($expected);
})->with([
    'unit is kept' => ['1em', '1em'],
    'bare number gets px' => ['5', '5px'],
    'decimal gets px' => ['2.5', '2.5px'],
    'invalid unit is ignored' => ['1laravel', null],
    'empty string is ignored' => ['', null],
]);
