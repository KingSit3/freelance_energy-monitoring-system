<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Dpm extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }

    protected $table = "dpm";
    protected $guarded = [];
}
