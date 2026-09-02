<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use App\Models\CustomField;
use App\Models\Product;
use App\Models\ProductAttribute;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function allProducts(Request $request)
    {
        $limit = $request->input('limit', 12);

        // Fetch products with status 1 and paginate
        $products = Product::where('status', 1)->whereActiveRelations()->paginate($limit);

        // Transform the products to include the full URL for the image
        $products->getCollection()->transform(function ($product) {
            $product->image_full_url = asset('uploads/product-images/' . $product->thumbnail);
            $product->company = $product->company;
            $product->price_after_discount =  Helper::priceAfterOffer($product->id);
            return $product;
        });

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $products
        ]);
    }

    public function productDetails($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 1)
            ->whereActiveRelations()
            ->with('brand', 'category', 'sub_category', 'attributes')
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Product not found'
            ], 404);
        }

        $product->image_full_url = asset('uploads/product-images/' . $product->thumbnail);
        $product->company = $product->company;
        $product->company->profile_image = ($product->company->profile_image) ? asset('uploads/user-images/' . $product->company->profile_image) : asset('assets/img/no-img.jpg');
        $product->price_after_discount = Helper::priceAfterOffer($product->id);
        $product->catalogue = Catalogue::where('product_id', $product->id)->where('type', 'catalogue')->first();
        
        $imagesArray = json_decode($product->images, true);
        if (is_array($imagesArray)) {
            foreach ($imagesArray as &$image) {
                if (is_string($image)) {
                    $image = asset('uploads/product-images/' . $image);
                }
            }
            $product->gallery_images = $imagesArray;
        } else {
            $product->gallery_images = [];
        }

        $product->custom_fields = $product->custom_fields;
        $product->catalogue_file = ($product->catalogue) ? asset('uploads/catalogue-files/'.$product->catalogue->file) : null;

        // Related products
        $related_products = Product::where('company_id', $product->company->id)
            ->where('status', 1)
            ->whereActiveRelations()
            ->limit(3)
            ->get();
        $related_products->transform(function ($relatedProduct) {
            $relatedProduct->image_full_url = asset('uploads/product-images/' . $relatedProduct->thumbnail);
            $relatedProduct->company = $relatedProduct->company;
            $relatedProduct->price_after_discount = Helper::priceAfterOffer($relatedProduct->id);
            return $relatedProduct;
        });
        $product->related_products = $related_products;

        // Similar category products
        $similar_category_products = Product::where('category_id', $product->category_id)
            ->where('status', 1)
            ->whereActiveRelations()
            ->limit(20)
            ->get();
        $similar_category_products->transform(function ($similarProduct) {
            $similarProduct->image_full_url = asset('uploads/product-images/' . $similarProduct->thumbnail);
            $similarProduct->company = $similarProduct->company;
            $similarProduct->price_after_discount = Helper::priceAfterOffer($similarProduct->id);
            return $similarProduct;
        });
        $product->similar_category_products = $similar_category_products;

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $product
        ]);
    }

    public function getRecommandProducts(Request $request){
        $products = Product::where('status', 1)->whereActiveRelations()->limit(20)->get();
        $products->transform(function ($product) {
            $product->image_full_url = asset('uploads/product-images/' . $product->thumbnail);
            $product->company = $product->company;
            $product->price_after_discount =  Helper::priceAfterOffer($product->id);
            return $product;
        });

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $products
        ]);
    }

}
