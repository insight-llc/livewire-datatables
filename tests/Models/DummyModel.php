<?php

namespace Mediconesystems\LivewireDatatables\Tests\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mediconesystems\LivewireDatatables\Tests\Database\Factories\DummyModelFactory;

#[UseFactory(DummyModelFactory::class)]
class DummyModel extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function dummy_has_one()
    {
        return $this->hasOne(DummyHasOneModel::class);
    }

    public function dummy_has_many()
    {
        return $this->hasMany(DummyHasManyModel::class);
    }

    public function dummy_belongs_to_many()
    {
        return $this->belongsToMany(DummyBelongsToManyModel::class);
    }
}
