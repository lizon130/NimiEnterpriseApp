<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CustomField extends Model
{
    use HasFactory;

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
}
