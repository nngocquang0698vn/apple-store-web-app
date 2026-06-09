<?php

namespace App\Models;

use Database\Factories\StorageOptionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['label', 'capacity_gb', 'is_active', 'sort_order'])]
class StorageOption extends Model
{
    /** @use HasFactory<StorageOptionFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'capacity_gb' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}
