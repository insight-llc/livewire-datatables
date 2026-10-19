<?php

namespace Mediconesystems\LivewireDatatables\Tests\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mediconesystems\LivewireDatatables\Tests\Database\Factories\DummyBelongsToManyModelFactory;

#[UseFactory(DummyBelongsToManyModelFactory::class)]
class DummyBelongsToManyModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function dummy_model()
    {
        return $this->belongsToMany(DummyModel::class);
    }
}
