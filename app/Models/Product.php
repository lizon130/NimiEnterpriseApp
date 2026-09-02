<?php

namespace App\Models;

use App\Models\ProductStock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = substr(uniqid(), 0, 13).'-prod-'.random_int(10000000000000000, 99999999999999999);
            $model->generateSlug();
            // $lastShortNumber = Product::max('short_number') ?? 0;
            // $model->short_number = $lastShortNumber + 1;
        });

		static::deleting(function ($product) {
            // Delete related attributes
            $product->attributes()->delete();

            // Delete related custom options
            $product->custom_options()->delete();

            // Delete related services
            $product->servies()->delete();

            // Delete related parts
            $product->parts()->delete();

            // Delete related translations
            $product->translations()->delete();
        });
    }

    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function company(){
        return $this->belongsTo(User::class, 'company_id', 'id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sub_category(){
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function scopeWhereActiveRelations($query)
    {
        return $query
            ->where(function ($q) {
                $q->whereNull('category_id')
                  ->orWhereHas('category', function ($cq) {
                      $cq->where('status', 1);
                  });
            })
            ->where(function ($q) {
                $q->whereNull('sub_category_id')
                  ->orWhereHas('sub_category', function ($scq) {
                      $scq->where('status', 1);
                  });
            })
            ->where(function ($q) {
                $q->whereNull('brand_id')
                  ->orWhereHas('brand', function ($bq) {
                      $bq->where('status', 1);
                  });
            });
    }

    public function attributes(){
        return $this->hasMany(ProductAttribute::class, 'product_id')->where('type', 'attributes');
    }

    public function custom_options(){
        return $this->hasMany(ProductAttribute::class, 'product_id')->where('type', 'custom value');
    }

    public function catalogue(){
        return $this->hasOne(Catalogue::class, 'product_id', 'id');
    }

    public function custom_fields(){
        return $this->hasMany(ProductAttribute::class, 'product_id')
                    ->where('type', 'custom value')
                    ->whereNull('ancestor_id')
                    ->with('field_details', 'custom_values');
    }

    public function servies(){
        return $this->hasMany(Service::class, 'product_id', 'id');
    }

    public function parts(){
        return $this->hasMany(ProductPart::class, 'product_id', 'id');
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function getTranslation($languageCode, $field)
	{
		$cacheKey = "product_translation_{$this->id}_{$languageCode}_{$field}";

		// Check if the translation is already in the cache
		return Cache::remember($cacheKey, now()->addHours(1), function () use ($languageCode, $field) {
			return $this->translations()
				->where('language_code', $languageCode)
				->where('field', $field)
				->value('value') ?? $this->$field;
		});
	}

    // Add this to your existing Product model

public function stock()
{
    return $this->hasOne(ProductStock::class, 'product_id', 'id');
}

// Helper method to get current stock
public function getStock()
{
    return $this->stock ? $this->stock->quantity : 0;
}

// Helper to check stock
public function inStock($quantity = 1)
{
    return $this->getStock() >= $quantity;
}

    protected function generateSlug(){
		if(optional($this->brand)->title){
			$brand = $this->brand->title;
		}else{
			$brand = 'other';
		}
        $slug = Str::slug($this->code.'-'.$brand.'-'.$this->name);

        $count = static::where('slug', $slug)->where('id', '!=', $this->id)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }
        $this->slug = $slug;
    }

}
