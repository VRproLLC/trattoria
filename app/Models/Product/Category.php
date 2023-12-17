<?php

namespace App\Models\Product;

use App\Models\Organization;
use App\Models\Translations\LocalizableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends LocalizableModel
{
    protected $localizable = ['name', 'description'];

    protected $fillable = [
        'organization_id',
        'iiko_id',
        'isDeleted',
        'isIncludedInMenu',
        'sort',
        'parentGroup',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id')
            ->where('isIncludedInMenu', 1)
            ->where('isDeleted', 0)
            ->whereNotNull('image')
            ->orderBy('sort');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
