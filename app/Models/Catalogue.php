<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Catalogue extends Model
{
    use HasFactory;

    protected $table = 'catalog';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = substr(uniqid(), 0, 13).'-ctlg-'.random_int(10000000000000000, 99999999999999999);
            $model->generateSlug();
        });
    }

    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function getTranslation($languageCode, $field)
	{
        $translationValue = $this->translations()
            ->where('language_code', $languageCode)
            ->where('field', $field)
            ->value('value');
        return $translationValue;
	}

    protected function generateSlug(){
        $slug = Str::slug($this->type.'-'.$this->brand->title.'-'.$this->title);

        $count = static::where('slug', $slug)->where('id', '!=', $this->id)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }
        $this->slug = $slug;
    }
}
