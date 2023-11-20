<?php

namespace App\Models\Product;

use App\Models\Organization;
use App\Models\Translations\LocalizableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;

class Product extends LocalizableModel
{

    /**
     * Localized attributes
     *
     * @var array
     */
    protected $localizable = ['name', 'description'];

    protected $fillable = [
        'organization_id',
        'category_id',
        'iiko_id',
        'isIncludedInMenu',
        'isDeleted',
        'code',
        'price',
        'parentGroup',
        'energyAmount',
        'energyFullAmount',
        'fatAmount',
        'fatFullAmount',
        'fiberAmount',
        'fiberFullAmount',
        'weight',
        'image',
        'sort',
    ];

    /**
     * Weight to gram attributes
     *
     * @return mixed
     */
    public function getWeightAttribute()
    {
//        if(is_numeric($this->attributes['weight'])){
//            return $this->attributes['weight'] * 100;
//        }
        return $this->attributes['weight'];
    }

    public function getIsFavoriteAttribute()
    {
        if(auth()->check()){
            $favorite = Favorite::where('product_id', $this->attributes['id'])->where('organization_id', Cookie::get('organization_id', 0))->where('user_id', auth()->id())->first();
            if($favorite){
                return 1;
            }
        }

        return 0;
    }

    public function getAssetImageAttribute()
    {
        if(empty($this->attributes['image'])){
            return asset('image/dish1.png');
        }
        return asset($this->attributes['image']);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
