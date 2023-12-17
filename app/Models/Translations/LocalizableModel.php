<?php

namespace App\Models\Translations;

use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

abstract class LocalizableModel extends Model
{

    /**
     * Localized attributes
     *
     * @var array
     */
    protected $localizable = [];

    /**
     * Whether or not to eager load translations
     *
     * @var boolean
     */
    protected $eagerLoadTranslations = true;

    /**
     * Whether or not to hide translations
     *
     * @var boolean
     */
    protected $hideTranslations = false;

    /**
     * Whether or not to append translatable attributes to array output
     *
     * @var boolean
     */
    protected $appendLocalizedAttributes = true;

    /**
     * Make a new translatable model
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        if ($this->eagerLoadTranslations) {
            $this->with[] = 'translations';
        }
        if ($this->hideTranslations) {
            $this->hidden[] = 'translations';
        }
        if ($this->appendLocalizedAttributes) {
            foreach ($this->localizable as $localizableAttribute) {
                $this->appends[] = $localizableAttribute;
            }
        }
        parent::__construct($attributes);
    }

    /**
     * This model's translations
     *
     */
    public function translations(): HasMany
    {
        $modelName = class_basename(get_class($this));

        return $this->hasMany("App\\Models\\Translations\\{$modelName}Translation");
    }

    /**
     * Magic method for retrieving a missing attribute
     *
     * @param string $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        if (in_array($attribute, $this->localizable)) {
            $translation = $this->translations
                ->where('language_id', self::getCurrentLocale())
                ->first();
            if ($translation == null) {
                $translation = $this->translations
                    ->where('language_id', config('app.fallback_locale_id'))
                    ->first();
            }
            return $translation ? $translation->{$attribute} : '-';
        }
        return parent::__get($attribute);
    }

    /**
     * Magic method for calling a missing instance method
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        foreach ($this->localizable as $localizableAttribute) {
            if ($method === 'get' . Str::studly($localizableAttribute) . 'Attribute') {
                return $this->{$localizableAttribute};
            }
        }
        return parent::__call($method, $arguments);
    }

    public function getCurrentLocale()
    {
        $language = Language::where('value', app()->getLocale())->first();

        if(!$language){
            return 1;
        }
        return $language->id;
    }

}
