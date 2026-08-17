<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;
    protected $table = 'news';

	protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->generateSlug();
        });
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
        $slug = Str::slug($this->category.'-'.$this->title);

        $count = static::where('slug', $slug)->where('id', '!=', $this->id)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }
        $this->slug = $slug;
    }
}
