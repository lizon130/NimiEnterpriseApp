<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('active', function (Builder $builder) {
            $request = request();
            if ($request && !$request->is('admin*') && !$request->is('api/admin*')) {
                $builder->where('status', 1);
            }
        });

        static::creating(function ($model) {
            $model->id = uniqid().'-ctgr-'.random_int(10000000000000000, 99999999999999999);
            $model->generateSlug();
        });
    }

    public function parent(){
        return $this->belongsTo(Category::class, 'parent_category', 'id');
    }

    public function subcategories(){
        return $this->hasMany(Category::class, 'parent_category', 'id')->orderby('short_number', 'asc');
    }

    public function rootParent()
    {
        return $this->parent ? $this->parent->rootParent() : $this;
    }

    public function subcategoriesRecursive()
    {
        return $this->hasMany(Category::class, 'parent_category', 'id')->with('subcategoriesRecursive');
    }
	
	public function products()
    {
        return $this->hasMany(Product::class, 'sub_category_id', 'id')->orderby('short_number', 'asc');
    }
    
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function getTranslation($languageCode, $field)
	{
		$cacheKey = "product_translation_{$this->id}_{$languageCode}_{$field}";

		return Cache::remember($cacheKey, now()->addHours(1), function () use ($languageCode, $field) {
			// Retrieve the translation from the database
			$translationValue = $this->translations()
				->where('language_code', $languageCode)
				->where('field', $field)
				->value('value');

			// If translation exists, return it; otherwise, return the default field value
			return $translationValue ?? $this->$field;
		});
	}

    protected function generateSlug(){
        $slug = Str::slug($this->title);

        $count = static::where('slug', $slug)->where('id', '!=', $this->id)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }
        $this->slug = $slug;
    }
}
