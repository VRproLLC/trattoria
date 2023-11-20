<?php

namespace App\Models\Product;

use App\Models\Organization;
use App\Models\Translations\LocalizableModel;
use Illuminate\Database\Eloquent\Model;

class Category extends LocalizableModel
{

    /**
     * Localized attributes
     *
     * @var array
     */
    protected $localizable = ['name', 'description'];

    protected $fillable = [
        'organization_id',
        'iiko_id',
        'isDeleted',
        'isIncludedInMenu',
        'sort',
        'parentGroup',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id')->where('isIncludedInMenu', 1)->where('isDeleted', 0)->orderBy('sort');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
