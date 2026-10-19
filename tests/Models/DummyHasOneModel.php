<?php

namespace Mediconesystems\LivewireDatatables\Tests\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mediconesystems\LivewireDatatables\Tests\Database\Factories\DummyHasOneModelFactory;

#[UseFactory(DummyHasOneModelFactory::class)]
class DummyHasOneModel extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function dummy_models()
    {
        return $this->belongsTo(DummyModel::class);
    }
}
