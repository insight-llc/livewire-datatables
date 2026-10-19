<?php

use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Tests\Models\DummyBelongsToManyModel;
use Mediconesystems\LivewireDatatables\Tests\Models\DummyHasManyModel;
use Mediconesystems\LivewireDatatables\Tests\Models\DummyHasOneModel;
use Mediconesystems\LivewireDatatables\Tests\Models\DummyModel;

it('creates a query builder for base columns', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create();

    $table = new LivewireDatatable(1);
    $table->mount(DummyModel::class, ['id', 'subject']);

    // 🧪 Act & Assert
    expect($table->getQuery()->toSql())->toBe('select "dummy_models"."id" as "id", "dummy_models"."subject" as "subject" from "dummy_models" order by "id" desc');

    $table->sortColumn(1);

    expect($table->getQuery()->toSql())->toBe('select "dummy_models"."id" as "id", "dummy_models"."subject" as "subject" from "dummy_models" order by "subject" desc');

    $table->sortColumn(1);

    expect($table->getQuery()->toSql())->toBe('select "dummy_models"."id" as "id", "dummy_models"."subject" as "subject" from "dummy_models" order by "subject" asc');
});

it('creates a query builder for has one relation columns', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create()->dummy_has_one()->save(DummyHasOneModel::factory()->make());

    $table = new LivewireDatatable(1);
    $table->mount(DummyModel::class, ['id', 'dummy_has_one.name']);

    // 🧪 Act & Assert
    expect($table->getQuery()->toSql())->toBe('select "dummy_models"."id" as "id", "dummy_has_one_models"."name" as "dummy_has_one.name" from "dummy_models" left join "dummy_has_one_models" on "dummy_has_one_models"."dummy_model_id" = "dummy_models"."id" order by "id" desc');

    $table->sortColumn(1);
    $table->forgetComputed();

    expect($table->getQuery()->toSql())->toBe('select "dummy_models"."id" as "id", "dummy_has_one_models"."name" as "dummy_has_one.name" from "dummy_models" left join "dummy_has_one_models" on "dummy_has_one_models"."dummy_model_id" = "dummy_models"."id" order by dummy_has_one_models.name desc');

    $table->sortColumn(1);
    $table->forgetComputed();

    expect($table->getQuery()->toSql())->toBe('select "dummy_models"."id" as "id", "dummy_has_one_models"."name" as "dummy_has_one.name" from "dummy_models" left join "dummy_has_one_models" on "dummy_has_one_models"."dummy_model_id" = "dummy_models"."id" order by dummy_has_one_models.name asc');
});

it('creates a where query for a has one column', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create()->dummy_has_one()->save(DummyHasOneModel::factory()->make());

    $table = new LivewireDatatable(1);
    $table->mount(DummyModel::class, ['id', 'dummy_has_one.name']);

    // 🧪 Act
    $table->doSelectFilter(1, 'dwight');

    // 🧪 Assert
    expect($table->getQuery()->toSql())->toBe('select "dummy_models"."id" as "id", "dummy_has_one_models"."name" as "dummy_has_one.name" from "dummy_models" left join "dummy_has_one_models" on "dummy_has_one_models"."dummy_model_id" = "dummy_models"."id" where (((dummy_has_one_models.name = ?))) order by "id" desc');
    expect($table->getQuery()->getBindings())->toBe(['dwight']);
});

it('creates a query builder for has many relation columns', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create()->dummy_has_many()->saveMany(DummyHasManyModel::factory()->count(2)->make());

    $table = new LivewireDatatable(1);
    $table->mount(DummyModel::class, ['id', 'dummy_has_many.name']);

    // 🧪 Act & Assert
    expect($table->getQuery()->toSql())->toBe('select (select group_concat(REPLACE(DISTINCT(dummy_has_many_models.name), \'\', \'\') , \', \') from "dummy_has_many_models" where "dummy_models"."id" = "dummy_has_many_models"."dummy_model_id") as `dummy_has_many.name`, "dummy_models"."id" as "id" from "dummy_models" order by "id" desc');

    $table->sortColumn(1);

    expect($table->getQuery()->toSql())->toBe('select (select group_concat(REPLACE(DISTINCT(dummy_has_many_models.name), \'\', \'\') , \', \') from "dummy_has_many_models" where "dummy_models"."id" = "dummy_has_many_models"."dummy_model_id") as `dummy_has_many.name`, "dummy_models"."id" as "id" from "dummy_models" order by "dummy_has_many.name" desc');

    $table->sortColumn(1);

    expect($table->getQuery()->toSql())->toBe('select (select group_concat(REPLACE(DISTINCT(dummy_has_many_models.name), \'\', \'\') , \', \') from "dummy_has_many_models" where "dummy_models"."id" = "dummy_has_many_models"."dummy_model_id") as `dummy_has_many.name`, "dummy_models"."id" as "id" from "dummy_models" order by "dummy_has_many.name" asc');
});

it('creates a query builder for has many relation column with specific aggregate', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create()->dummy_has_many()->saveMany(DummyHasManyModel::factory()->count(2)->make());

    $table = new LivewireDatatable(1);
    $table->mount(DummyModel::class, ['id', 'dummy_has_many.id:avg']);

    // 🧪 Act & Assert
    expect($table->getQuery()->toSql())->toBe('select (select COALESCE(avg(dummy_has_many_models.id),0) from "dummy_has_many_models" where "dummy_models"."id" = "dummy_has_many_models"."dummy_model_id") as `dummy_has_many.id:avg`, "dummy_models"."id" as "id" from "dummy_models" order by "id" desc');

    $table->sortColumn(1);

    expect($table->getQuery()->toSql())->toBe('select (select COALESCE(avg(dummy_has_many_models.id),0) from "dummy_has_many_models" where "dummy_models"."id" = "dummy_has_many_models"."dummy_model_id") as `dummy_has_many.id:avg`, "dummy_models"."id" as "id" from "dummy_models" order by "dummy_has_many.id:avg" desc');

    $table->sortColumn(1);

    expect($table->getQuery()->toSql())->toBe('select (select COALESCE(avg(dummy_has_many_models.id),0) from "dummy_has_many_models" where "dummy_models"."id" = "dummy_has_many_models"."dummy_model_id") as `dummy_has_many.id:avg`, "dummy_models"."id" as "id" from "dummy_models" order by "dummy_has_many.id:avg" asc');
});

it('creates a where query for has many relation columns', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create()->dummy_has_many()->saveMany(DummyHasManyModel::factory()->count(2)->make());

    $table = new LivewireDatatable(1);
    $table->mount(DummyModel::class, ['id', 'dummy_has_many.name']);

    // 🧪 Act
    $table->doTextFilter(1, 'Pam');

    // 🧪 Assert
    expect($table->getQuery()->toSql())->toBe('select (select group_concat(REPLACE(DISTINCT(dummy_has_many_models.name), \'\', \'\') , \', \') from "dummy_has_many_models" where "dummy_models"."id" = "dummy_has_many_models"."dummy_model_id") as `dummy_has_many.name`, "dummy_models"."id" as "id" from "dummy_models" where ((((select group_concat(REPLACE(DISTINCT(dummy_has_many_models.name), \'\', \'\') , \', \') from "dummy_has_many_models" where "dummy_models"."id" = "dummy_has_many_models"."dummy_model_id") like ?))) order by "id" desc');
    expect($table->getQuery()->getBindings())->toBe(['%Pam%']);
});

it('creates a query builder for belongs to relation columns', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create()->dummy_has_many()->saveMany(DummyHasManyModel::factory()->count(2)->make());

    $table = new LivewireDatatable(1);
    $table->mount(DummyHasManyModel::class, ['id', 'dummy_model.subject']);

    // 🧪 Act & Assert
    expect($table->getQuery()->toSql())->toBe('select "dummy_has_many_models"."id" as "id", "dummy_models"."subject" as "dummy_model.subject" from "dummy_has_many_models" left join "dummy_models" on "dummy_models"."id" = "dummy_has_many_models"."dummy_model_id" order by "id" desc');

    $table->sortColumn(1);
    $table->forgetComputed();

    expect($table->getQuery()->toSql())->toBe('select "dummy_has_many_models"."id" as "id", "dummy_models"."subject" as "dummy_model.subject" from "dummy_has_many_models" left join "dummy_models" on "dummy_models"."id" = "dummy_has_many_models"."dummy_model_id" order by dummy_models.subject desc');

    $table->sortColumn(1);
    $table->forgetComputed();

    expect($table->getQuery()->toSql())->toBe('select "dummy_has_many_models"."id" as "id", "dummy_models"."subject" as "dummy_model.subject" from "dummy_has_many_models" left join "dummy_models" on "dummy_models"."id" = "dummy_has_many_models"."dummy_model_id" order by dummy_models.subject asc');
});

it('creates a where query for belongs to relation columns', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create()->dummy_has_many()->saveMany(DummyHasManyModel::factory()->count(2)->make());

    $table = new LivewireDatatable(1);
    $table->mount(DummyHasManyModel::class, ['id', 'dummy_model.subject']);

    // 🧪 Act & Assert
    $table->doNumberFilterStart(1, 123);
    $table->forgetComputed();

    expect($table->getQuery()->toSql())->toBe('select "dummy_has_many_models"."id" as "id", "dummy_models"."subject" as "dummy_model.subject" from "dummy_has_many_models" left join "dummy_models" on "dummy_models"."id" = "dummy_has_many_models"."dummy_model_id" where (dummy_models.subject >= ?) order by dummy_has_many_models.id desc');

    expect($table->getQuery()->getBindings())->toBe([123]);

    $table->doNumberFilterEnd(1, 456);

    expect($table->getQuery()->getBindings())->toBe([123, 456]);

    $table->doNumberFilterStart(1, null);

    expect($table->getQuery()->getBindings())->toBe([456]);

    $table->doNumberFilterEnd(1, null);

    $table->forgetComputed();
    expect($table->getQuery()->toSql())->toBe('select "dummy_has_many_models"."id" as "id", "dummy_models"."subject" as "dummy_model.subject" from "dummy_has_many_models" left join "dummy_models" on "dummy_models"."id" = "dummy_has_many_models"."dummy_model_id" order by dummy_has_many_models.id desc');
    expect($table->getQuery()->getBindings())->toBe([]);
});

it('creates a query builder for belongs to many relation columns', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create()->dummy_belongs_to_many()->attach(DummyBelongsToManyModel::factory()->create());

    $table = new LivewireDatatable(1);
    $table->mount(DummyModel::class, ['id', 'dummy_belongs_to_many.name']);

    // 🧪 Act & Assert
    expect($table->getQuery()->toSql())->toBe('select (select group_concat(REPLACE(DISTINCT(dummy_belongs_to_many_models.name), \'\', \'\') , \', \') from "dummy_belongs_to_many_models" inner join "dummy_belongs_to_many_model_dummy_model" on "dummy_belongs_to_many_models"."id" = "dummy_belongs_to_many_model_dummy_model"."dummy_belongs_to_many_model_id" where "dummy_models"."id" = "dummy_belongs_to_many_model_dummy_model"."dummy_model_id" and "dummy_belongs_to_many_models"."deleted_at" is null) as `dummy_belongs_to_many.name`, "dummy_models"."id" as "id" from "dummy_models" order by "id" desc');

    $table->sortColumn(1);

    expect($table->getQuery()->toSql())->toBe('select (select group_concat(REPLACE(DISTINCT(dummy_belongs_to_many_models.name), \'\', \'\') , \', \') from "dummy_belongs_to_many_models" inner join "dummy_belongs_to_many_model_dummy_model" on "dummy_belongs_to_many_models"."id" = "dummy_belongs_to_many_model_dummy_model"."dummy_belongs_to_many_model_id" where "dummy_models"."id" = "dummy_belongs_to_many_model_dummy_model"."dummy_model_id" and "dummy_belongs_to_many_models"."deleted_at" is null) as `dummy_belongs_to_many.name`, "dummy_models"."id" as "id" from "dummy_models" order by "dummy_belongs_to_many.name" desc');

    $table->sortColumn(1);

    expect($table->getQuery()->toSql())->toBe('select (select group_concat(REPLACE(DISTINCT(dummy_belongs_to_many_models.name), \'\', \'\') , \', \') from "dummy_belongs_to_many_models" inner join "dummy_belongs_to_many_model_dummy_model" on "dummy_belongs_to_many_models"."id" = "dummy_belongs_to_many_model_dummy_model"."dummy_belongs_to_many_model_id" where "dummy_models"."id" = "dummy_belongs_to_many_model_dummy_model"."dummy_model_id" and "dummy_belongs_to_many_models"."deleted_at" is null) as `dummy_belongs_to_many.name`, "dummy_models"."id" as "id" from "dummy_models" order by "dummy_belongs_to_many.name" asc');
});

it('creates a where query for belongs to many relation columns', function (): void {
    // 🧪 Arrange
    DummyModel::factory()->create()->dummy_belongs_to_many()->attach(DummyBelongsToManyModel::factory()->create());

    $table = new LivewireDatatable(1);
    $table->mount(DummyModel::class, ['id', 'dummy_belongs_to_many.name']);

    // 🧪 Act
    $table->doSelectFilter(1, 'Michael Scott');

    // 🧪 Assert
    expect($table->getQuery()->toSql())->toBe('select (select group_concat(REPLACE(DISTINCT(dummy_belongs_to_many_models.name), \'\', \'\') , \', \') from "dummy_belongs_to_many_models" inner join "dummy_belongs_to_many_model_dummy_model" on "dummy_belongs_to_many_models"."id" = "dummy_belongs_to_many_model_dummy_model"."dummy_belongs_to_many_model_id" where "dummy_models"."id" = "dummy_belongs_to_many_model_dummy_model"."dummy_model_id" and "dummy_belongs_to_many_models"."deleted_at" is null) as `dummy_belongs_to_many.name`, "dummy_models"."id" as "id" from "dummy_models" where ((((select group_concat(REPLACE(DISTINCT(dummy_belongs_to_many_models.name), \'\', \'\') , \', \') from "dummy_belongs_to_many_models" inner join "dummy_belongs_to_many_model_dummy_model" on "dummy_belongs_to_many_models"."id" = "dummy_belongs_to_many_model_dummy_model"."dummy_belongs_to_many_model_id" where "dummy_models"."id" = "dummy_belongs_to_many_model_dummy_model"."dummy_model_id" and "dummy_belongs_to_many_models"."deleted_at" is null) like ?))) order by "id" desc');
});
