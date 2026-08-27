<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Helper;
use App\Models\User;
use App\Models\Otp;
use App\Models\Service;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Inquiry;
use App\Models\InquiryProduct;
use App\Models\ServiceOrder;
use App\Models\News;
use App\Models\Catalogue;
use App\Models\Company;
use App\Models\ProductPart;
use App\Models\PartAttribute;
use App\Models\Resource;
use App\Models\Contact;
use App\Models\CustomField;
use App\Models\ProductAttribute;
use App\Models\Transaction;
use Hash;
use Illuminate\Support\Facades\DB;

use DateTime;
use DOMDocument;
use GlobalPayments\Api\Entities\Address;
use GlobalPayments\Api\Entities\Enums\AddressType;
use GlobalPayments\Api\Entities\Enums\HppVersion;
use Illuminate\Support\Facades\App;

use GlobalPayments\Api\ServiceConfigs\Gateways\GpEcomConfig;
use GlobalPayments\Api\Services\HostedService;
use GlobalPayments\Api\Entities\Exceptions\ApiException;
use GlobalPayments\Api\Entities\HostedPaymentData;
use GlobalPayments\Api\HostedPaymentConfig;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Builder;

class FrontendController extends Controller
{
	public function __construct()
    {

    }


    public function bot(){
        // $products = Product::all();
        // foreach ($products as $row) {
        //     $slug = Str::slug($row->code.'-'.$row->brand->title.'-'.$row->name);
        //     $count = Product::where('slug', $slug)->where('id', '!=', $row->id)->count();
        //     if ($count > 0) {
        //         $slug = $slug . '-' . ($count + 1);
        //     }
        //     $row->slug = $slug;
        //     $row->save();
        // }

        // $category = Category::all();
        // foreach ($category as $row) {
        //     $slug = Str::slug($row->title );

        //     $count = Category::where('slug', $slug)->where('id', '!=', $row->id)->count();
        //     if ($count > 0) {
        //         $slug = $slug . '-' . ($count + 1);
        //     }
        //     $row->slug = $slug;
        //     $row->save();
        // }

        // $brands = Brand::all();
        // foreach ($brands as $row) {
        //     $slug = Str::slug($row->title);

        //     $count = Brand::where('slug', $slug)->where('id', '!=', $row->id)->count();
        //     if ($count > 0) {
        //         $slug = $slug . '-' . ($count + 1);
        //     }
        //     $row->slug = $slug;
        //     $row->save();
        // }

        // $catalogues = Catalogue::all();
        // foreach ($catalogues as $row) {
        //     $slug = Str::slug($row->type.'-'.$row->brand->title.'-'.$row->title);
        //     $count = Catalogue::where('slug', $slug)->where('id', '!=', $row->id)->count();
        //     if ($count > 0) {
        //         $slug = $slug . '-' . ($count + 1);
        //     }
        //     $row->slug = $slug;
        //     $row->save();
        // }

        // $services = Service::all();
        // foreach ($services as $row) {
        //     $slug = Str::slug($row->code.'-'.$row->title);

        //     $count = Service::where('slug', $slug)->where('id', '!=', $row->id)->count();
        //     if ($count > 0) {
        //         $slug = $slug . '-' . ($count + 1);
        //     }
        //     $row->slug = $slug;
        //     $row->save();
        // }

        // $news = News::all();
        // foreach ($news as $row) {
        //     $slug = Str::slug($row->category.'-'.$row->title);

        //     $count = News::where('slug', $slug)->where('id', '!=', $row->id)->count();
        //     if ($count > 0) {
        //         $slug = $slug . '-' . ($count + 1);
        //     }
        //     $row->slug = $slug;
        //     $row->save();
        // }

        $parts = ProductPart::all();
        foreach ($parts as $row) {
            $slug = Str::slug($row->code.'-'.$row->brand->title.'-'.$row->name);
            $count = ProductPart::where('slug', $slug)->where('id', '!=', $row->id)->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
            $row->slug = $slug;
            $row->save();
        }


        return 'success';
    }

    public function home() {

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        App::setLocale(Session::get('language'));

		$categories =Category::where('show_home', 1)
				->where('status', 1)
				->orderBy('short_number', 'asc')
				->get();

		$services =Service::where('status', 1)->take(6)->get();

		$newses =  News::where('status', 1)->take(6)->get();

		$banners = Resource::where('status', 1)->where('type', 'banner')->orderBy('short_number', 'asc')->get();

		$partners = Brand::where('status', 1)->where('show_home', 1)->get();

		$products = Product::with('category')
            ->where('status', 1)
            ->where('features', 1)
			->orderBy('short_number', 'asc')
            ->get();

        return view('frontend.pages.home',compact('categories', 'services', 'newses', 'products','banners', 'partners'));
    }


    public function registration() {
        App::setLocale(Session::get('language'));
        return view('frontend.pages.registration');
    }

    public function login() {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        App::setLocale(Session::get('language'));
        return view('backend.auth.login');
    }

    public function about() {
        App::setLocale(Session::get('language'));
        return view('frontend.pages.about');
    }
    public function contact() {
        App::setLocale(Session::get('language'));
        $addresses = Contact::where('status', 1)->orderBy('is_default', 'desc')->get();
        return view('frontend.pages.contact', compact('addresses'));
    }

    public function contactPost(Request $request){
		$request->validate([
            'g-recaptcha-response' => 'required|captcha',
			'subject' => 'required|max:50',
			'message' => ['required', 'max:200', function ($attribute, $value, $fail) {
				if (preg_match('/(?:https?|ftp):\/\/\S+/', $value)) {
					$fail('The message should not contain any URLs.');
				}
			}],
			'sender_email' => 'required|email',
            // Add other validation rules for your form fields
        ]);

        $subject = $request->subject ?? 'Contact mail';
        $data = 'Someone trying to contact with you. Here is the details, </br> Name: '.$request->name.', </br> Email: '.$request->email.', Phone: '.$request->phone.', </br> Message: '.$request->message.' .';
        $admin_email = 'info@machinetoolsolutions.ca';
        Helper::sendEmail($admin_email, $subject, $data);

        session()->flash('message', 'Email send successfully! We will contact with you soon.');
        return redirect()->back();
    }

    public function categories() {
        App::setLocale(Session::get('language'));
		$categories = Category::whereNull('parent_category')->where('status', 1)->orderBy('short_number', 'asc')->get();
        return view('frontend.pages.categories',compact('categories'));
    }

    public function subcategory($id) {
        App::setLocale(Session::get('language'));


		// Cache the current category and its subcategories
		$currentCategory = Category::where('slug', $id)->with('subcategories')->first();
        if (!$currentCategory) {
            $currentCategory = Category::with('subcategories')->find($id);
        }
        if (!$currentCategory) {
            return response()->view('errors.404', [], 404);
        }

		$subCategories = $currentCategory->subcategories->where('status', 1);

		if (count($subCategories) > 0) {
			// Cache the products for the current category
			$products = Product::where('category_id', $currentCategory->id)->orderBy('short_number', 'asc')->limit(1)->get();

			return view('frontend.pages.subcategory', compact('currentCategory', 'subCategories', 'products'));
		} else {
			// Cache the categories
			$categories = Category::whereNull('parent_category')->where('status', 1)->orderBy('short_number', 'asc')->get();

			// Cache other data needed for the products view
			$brands = Brand::where('status', 1)->get();

			$filter_attributes =ProductAttribute::select('attribute_name', \DB::raw('MAX(id) as max_id'))
					->where('type', 'attributes')
					->where('is_filter', 1)
					->groupBy('attribute_name')
					->orderBy('max_id')
					->get();

			foreach ($filter_attributes as $row) {
				$row->attributes_values = ProductAttribute::select('value')->where('type', 'attributes')->where('is_filter', 1)->where('attribute_name', $row->attribute_name)->whereNotNull('value')->groupBy('value')->get();
			}

			if ($currentCategory) {
				$current_category = $currentCategory;
				$root_category = $current_category->rootParent();
			} else {
				$current_category = [];
				$root_category = [];
			}

			return view('frontend.pages.products', compact('brands', 'categories', 'filter_attributes', 'current_category', 'root_category', 'currentCategory'));
		}

    }

	public function brandWiseProduct($id) {
        App::setLocale(Session::get('language'));
        // Cache the current brand
        $current_brand = Brand::where('slug', $id)->first();
        if (!$current_brand) {
            $current_brand = Brand::find($id);
        }
        if (!$current_brand) {
            return response()->view('errors.404', [], 404);
        }


		// Cache the products for the current category
		$products = Product::where('category_id', $current_brand->id)->orderBy('short_number', 'asc')->get();

		// Cache the categories
		$categories =Category::whereNull('parent_category')->where('status', 1)->orderBy('short_number', 'asc')->get();

		// Cache the brands
		$brands = Brand::where('status', 1)->get();

		// Cache the filter attributes
		$filter_attributes = ProductAttribute::select('attribute_name', \DB::raw('MAX(id) as max_id'))
				->where('type', 'attributes')
				->where('is_filter', 1)
				->groupBy('attribute_name')
				->orderBy('max_id')
				->get();

		// Cache the attributes values
		foreach ($filter_attributes as $row) {
			$row->attributes_values =  ProductAttribute::select('value')->where('type', 'attributes')->where('is_filter', 1)->where('attribute_name', $row->attribute_name)->whereNotNull('value')->groupBy('value')->get();
		}

		$current_category = [];
		$root_category = [];
		$currentCategory = [];

		return view('frontend.pages.products', compact('brands', 'categories', 'filter_attributes', 'current_category', 'root_category', 'currentCategory', 'current_brand'));
    }

    public function searchProductBycategory(Request $request){
        App::setLocale(Session::get('language'));
        $products = Product::where('status', 1);

        if ($request->category_id) {
            $categoryId = $request->category_id;

            function getAllDescendantIds($category)
            {
                $ids = [$category->id];

                foreach ($category->subcategories as $subcategory) {
                    $ids = array_merge($ids, getAllDescendantIds($subcategory));
                }

                return $ids;
            }

            // Fetch the category with its subcategories
            $category = Category::with('subcategories')->find($categoryId);

            // Get all descendant subcategory IDs
            $subcategoryIds = getAllDescendantIds($category);

            // Filter products based on main category and its subcategories
            $products->where(function (Builder $query) use ($categoryId, $subcategoryIds) {
                $query->where('category_id', $categoryId)
                    ->orWhereIn('sub_category_id', $subcategoryIds);
            });
        }

        if ($request->name) {
            $products->where('name','like', "%" .$request->name ."%" );
        }

        if ($request->model) {
            $products->where('code','like', "%" .$request->model ."%" );
        }

        $products = $products->orderBy('short_number', 'asc')->paginate(24);
        $productsHtml = view('frontend.pages.search.category-products', compact('products'))->render();
        $paginationHtml = json_decode(json_encode($products));

		$pagination = '';
		$visiblePages = 3;

		for ($i = 1; $i <= $paginationHtml->last_page; $i++) {
			if ($i == $paginationHtml->current_page) {
				$pagination .= '<li class="page-item active"><a class="page-link pagination_btn" href="#">'.$i.'</a></li>';
			} else {
				if ($i <= $visiblePages || $i > $paginationHtml->last_page - $visiblePages || abs($i - $paginationHtml->current_page) < floor($visiblePages / 2)) {
					$pagination .= '<li class="page-item"><a class="page-link pagination_btn" href="'.$paginationHtml->path.'?page='.$i.'">'.$i.'</a></li>';
				} elseif ($i == $visiblePages + 1 || $i == $paginationHtml->last_page - $visiblePages) {
					$pagination .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
				}
			}
		}

		// Add Previous Page link
		if ($paginationHtml->current_page > 1) {
			$pagination = '<li class="page-item"><a class="page-link pagination_btn" href="'.$paginationHtml->path.'?page='.($paginationHtml->current_page - 1).'">Previous</a></li>' . $pagination;
		}

		// Add Next Page link
		if ($paginationHtml->current_page < $paginationHtml->last_page) {
			$pagination .= '<li class="page-item"><a class="page-link pagination_btn" href="'.$paginationHtml->path.'?page='.($paginationHtml->current_page + 1).'">Next</a></li>';
		}

        return response()->json([
            'products_html' => $productsHtml,
            'pagination_html' => ($productsHtml) ? $pagination : ''
        ]);
    }

    public function products($id) {
        App::setLocale(Session::get('language'));
        // Cache the products for the current category
		$products = Product::where('category_id', $id)->orderBy('short_number', 'asc')->orderBy('short_number', 'asc')->get();

		// Cache the brands
		$brands = Brand::where('status', 1)->get();

		// Cache the categories
		$categories = Category::whereNull('parent_category')->where('status', 1)->orderBy('short_number', 'asc')->get();

		// Cache the filter attributes
		$filter_attributes = ProductAttribute::select('attribute_name', \DB::raw('MAX(id) as max_id'))
				->where('type', 'attributes')
				->where('is_filter', 1)
				->groupBy('attribute_name')
				->orderBy('max_id')
				->get();

		// Cache the attributes values
		foreach ($filter_attributes as $row) {
			$row->attributes_values = ProductAttribute::select('value')
					->where('type', 'attributes')
					->where('is_filter', 1)
					->where('attribute_name', $row->attribute_name)
					->whereNotNull('value')
					->groupBy('value')
					->orderBy('value', 'asc')
					->get();

		}
        return view('frontend.pages.products', compact('products', 'brands','categories', 'filter_attributes'));
    }

    public function allProducts(Request $request) {
        App::setLocale(Session::get('language'));
        // Cache the brands
		$brands = cache()->remember('brands', now()->addHours(1), function () {
			return Brand::where('status', 1)->get();
		});

		// Cache the categories
		$categories = cache()->remember('categories', now()->addHours(1), function () {
			return Category::whereNull('parent_category')->where('status', 1)->orderBy('short_number', 'asc')->get();
		});

		// Cache the filter attributes
		$filter_attributes = ProductAttribute::select('attribute_name', \DB::raw('MAX(id) as max_id'))
				->where('type', 'attributes')
				->where('is_filter', 1)
				->groupBy('attribute_name')
				->orderBy('max_id')
				->get();

		// Cache the attributes values
		foreach ($filter_attributes as $row) {
			$row->attributes_values = ProductAttribute::select('value')
					->where('type', 'attributes')
					->where('is_filter', 1)
					->where('attribute_name', $row->attribute_name)
					->whereNotNull('value')
					->groupBy('value')
					->orderByRaw("CASE WHEN value LIKE '0 - %' THEN CAST(SUBSTRING_INDEX(value, ' ', -1) AS UNSIGNED) ELSE CAST(value AS UNSIGNED) END, value")
					->get();

		}

		// Cache the current category and related data
		if (session()->has('currentCategory')) {
			$current_category = session('currentCategory');

			$root_category = $current_category->rootParent();

			$sub_categories = Category::find($root_category->id)->subcategoriesRecursive;

		} else {
			$current_category = [];
			$root_category = [];
			$sub_categories = [];
		}

        return view('frontend.pages.products', compact('brands', 'categories', 'filter_attributes', 'current_category', 'root_category'));
    }

    public function searchProducts(Request $request){
        App::setLocale(Session::get('language'));
        $subcategory = null;
        $products = Product::where('status', 1);
        if ($request->name) {
            $products->where(function ($query) use ($request) {
                $query->where('name', 'like', "%" . $request->name . "%")
                    ->orWhereHas('brand', function ($q) use ($request) {
                        $q->where('title', 'like', "%" . $request->name . "%");
                    });
            });
        }
        if ($request->model) {
            $products->where('code','like', "%" .$request->model ."%" );
        }
        if($request->brands_for_filter){
            $products->where(function($products) use ($request){
                $products->whereIn('brand_id', $request->brands_for_filter);
            });
        }

        if($request->category_for_filter){
            $products->where(function($products) use ($request){
                $products->whereIn('category_id', $request->category_for_filter);
            });
        }

        if($request->current_category){
            $current_category =  Category::find($request->current_category);
            if ($current_category) {
                $subcategory = $current_category;
                $products->where('sub_category_id', $current_category->id);
            }
        }

        if($request->attributes_for_filter){
            $products->whereHas('attributes', function ($query) use ($request) {
                $query->whereIn('value', $request->attributes_for_filter);
            });
        }

        // Relevance ordering: names starting with the search term first
        if ($request->name) {
            $namePrefix = strtolower($request->name) . '%';
            $products->orderByRaw("CASE WHEN LOWER(name) LIKE ? THEN 0 ELSE 1 END, short_number ASC", [$namePrefix]);
        } else {
            $products->orderBy('short_number', 'asc');
        }

        $products = $products->paginate(20);
		$productsHtml = view('frontend.pages.search.products', compact('products', 'subcategory'))->render();
		$paginationHtml = json_decode(json_encode($products));

		if ($products->isEmpty()) {
			return response()->json([
				'products_html' => $productsHtml,
				'pagination_html' => '',
			]);
		}

		$pagination = '';
		$visiblePages = 2;

		for ($i = 1; $i <= $paginationHtml->last_page; $i++) {
			if ($i == $paginationHtml->current_page) {
				$pagination .= '<li class="page-item active"><a class="page-link pagination_btn" href="#">'.$i.'</a></li>';
			} else {
				if ($i <= $visiblePages || $i > $paginationHtml->last_page - $visiblePages || abs($i - $paginationHtml->current_page) < floor($visiblePages / 2)) {
					$pagination .= '<li class="page-item"><a class="page-link pagination_btn" href="'.$paginationHtml->path.'?page='.$i.'">'.$i.'</a></li>';
				} elseif ($i == $visiblePages + 1 || $i == $paginationHtml->last_page - $visiblePages) {
					$pagination .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
				}
			}
		}

		// Add Previous Page link
		if ($paginationHtml->current_page > 1) {
			$pagination = '<li class="page-item"><a class="page-link pagination_btn" href="'.$paginationHtml->path.'?page='.($paginationHtml->current_page - 1).'">Previous</a></li>' . $pagination;
		}

		// Add Next Page link
		if ($paginationHtml->current_page < $paginationHtml->last_page) {
			$pagination .= '<li class="page-item"><a class="page-link pagination_btn" href="'.$paginationHtml->path.'?page='.($paginationHtml->current_page + 1).'">Next</a></li>';
		}

        return response()->json([
            'products_html' => $productsHtml,
            'pagination_html' => ($productsHtml) ? $pagination : ''
        ]);

    }

    /**
     * Lightweight live-search endpoint for the products page autocomplete.
     * Returns the most relevant matches (name prefix match first, then name/code/brand contains).
     */
    public function productSuggest(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        if ($q === '' || mb_strlen($q) < 1) {
            return response()->json(['suggestions' => []]);
        }

        $locale = Session::get('language') ?? 'en';
        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], mb_strtolower($q));
        $like = '%' . $escaped . '%';
        $prefix = $escaped . '%';

        $suggestions = Product::query()
            ->where('status', 1)
            ->where(function ($query) use ($like) {
                $query->whereRaw('LOWER(name) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(code) LIKE ?', [$like])
                    ->orWhereHas('brand', function ($b) use ($like) {
                        $b->whereRaw('LOWER(title) LIKE ?', [$like]);
                    });
            })
            ->select('id', 'name', 'slug', 'thumbnail', 'code', 'price', 'discount', 'discount_type', 'short_number')
            ->with('brand:id,title')
            ->orderByRaw("CASE WHEN LOWER(name) LIKE ? THEN 0 WHEN LOWER(code) LIKE ? THEN 1 ELSE 2 END, short_number ASC", [$prefix, $prefix])
            ->limit(8)
            ->get();

        return response()->json([
            'suggestions' => $suggestions->map(function ($product) use ($locale) {
                $price = (float) $product->price;
                $discount = (float) ($product->discount ?? 0);

                if ($discount > 0) {
                    $price = ($product->discount_type === 'amount')
                        ? $price - $discount
                        : $price - ($price * $discount) / 100;
                }

                return [
                    'name' => $product->getTranslation($locale, 'title') ?? $product->name,
                    'code' => $product->code,
                    'url' => url('product/' . $product->slug),
                    'thumbnail' => $product->thumbnail ? asset('uploads/product-images/' . $product->thumbnail) : null,
                    'brand' => $product->brand?->title,
                    'price' => $price > 0 ? number_format($price, 2) : null,
                ];
            })->values(),
        ]);
    }

    public function product_details($id) {
        App::setLocale(Session::get('language'));
        $product = Product::where('slug', $id)->first();
        if (!$product) {
            $product = Product::find($id);
        }
        if (!$product) {
            return response()->view('errors.404', [], 404);
        }
        $imagesArray = json_decode($product->images, true);
        $product->images = $imagesArray;


		$releted_products =Product::where('sub_category_id', $product->sub_category_id)
				->where('id', '!=', $product->id)
				->where('status', 1)
				->get();


		$catalogue =  Catalogue::where('product_id', $product->id)->where('type', 'catalogue')->first();

		$custom_fields =  CustomField::where('status', 1)->get();
        return view('frontend.pages.new-product-details',compact('product', 'releted_products','catalogue','custom_fields'));
        // return view('frontend.pages.product_details',compact('product', 'releted_parts','catalogue'));
    }

    public function allParts(){
        App::setLocale(Session::get('language'));
        $brands = Brand::where('status', 1)->get();
        $parts = ProductPart::where('status', 1)->get();
        $categories = Category::whereNull('parent_category')->where('status', 1)->orderBy('short_number', 'asc')->get();
        $filter_attributes = PartAttribute::select('attribute_name', \DB::raw('MAX(id) as max_id'))
            ->where('type', 'attributes')
            ->where('is_filter', 1)
            ->groupBy('attribute_name')
            ->orderBy('max_id')
            ->get();

        foreach($filter_attributes as $row){
            $row->attributes_values = PartAttribute::select('value')->where('type', 'attributes')->where('is_filter', 1)->where('attribute_name', $row->attribute_name)->whereNotNull('value')->groupBy('value')->get();
        }

        return view('frontend.pages.parts', compact('parts', 'brands','categories', 'filter_attributes'));
    }

    public function searchParts(Request $request){
        App::setLocale(Session::get('language'));

        $parts = ProductPart::where('status', 1);

        if ($request->name) {
            $parts->where('name','like', "%" .$request->name ."%" );
        }

        if ($request->model) {
            $parts->where('code','like', "%" .$request->model ."%" );
        }

        if($request->brands_for_filter){
            $parts->where(function($parts) use ($request){
                $parts->whereIn('brand_id', $request->brands_for_filter);
            });
        }

        if($request->category_for_filter){
            $parts->where(function($parts) use ($request){
                $parts->whereIn('category_id', $request->category_for_filter);
            });
        }

        if($request->attributes_for_filter){
            $parts->whereHas('attributes', function ($query) use ($request) {
                $query->whereIn('value', $request->attributes_for_filter);
            });
        }

        $parts = $parts->orderBy('short_number', 'asc')->paginate(20);

		$partsHtml = view('frontend.pages.search.parts', compact('parts'))->render();
		$paginationHtml = json_decode(json_encode($parts));

		if ($parts->isEmpty()) {
			return response()->json([
				'products_html' => $partsHtml,
				'pagination_html' => '',
			]);
		}

		$pagination = '';
		$visiblePages = 2;

		for ($i = 1; $i <= $paginationHtml->last_page; $i++) {
			if ($i == $paginationHtml->current_page) {
				$pagination .= '<li class="page-item active"><a class="page-link pagination_btn" href="#">'.$i.'</a></li>';
			} else {
				if ($i <= $visiblePages || $i > $paginationHtml->last_page - $visiblePages || abs($i - $paginationHtml->current_page) < floor($visiblePages / 2)) {
					$pagination .= '<li class="page-item"><a class="page-link pagination_btn" href="'.$paginationHtml->path.'?page='.$i.'">'.$i.'</a></li>';
				} elseif ($i == $visiblePages + 1 || $i == $paginationHtml->last_page - $visiblePages) {
					$pagination .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
				}
			}
		}

		// Add Previous Page link
		if ($paginationHtml->current_page > 1) {
			$pagination = '<li class="page-item"><a class="page-link pagination_btn" href="'.$paginationHtml->path.'?page='.($paginationHtml->current_page - 1).'">Previous</a></li>' . $pagination;
		}

		// Add Next Page link
		if ($paginationHtml->current_page < $paginationHtml->last_page) {
			$pagination .= '<li class="page-item"><a class="page-link pagination_btn" href="'.$paginationHtml->path.'?page='.($paginationHtml->current_page + 1).'">Next</a></li>';
		}

        return response()->json([
            'products_html' => $partsHtml,
            'pagination_html' => ($partsHtml) ? $pagination : ''
        ]);

    }

    public function partsDetails($id) {
        App::setLocale(Session::get('language'));

        $part = ProductPart::where('slug', $id)->first();
        if (!$part) {
            $part = ProductPart::find($id);
        }
        if (!$part) {
            return response()->view('errors.404', [], 404);
        }

        $imagesArray = json_decode($part->images, true);
        $part->images = $imagesArray;
        $custom_fields = CustomField::where('status', 1)->get();
        return view('frontend.pages.part-details',compact('part','custom_fields'));
    }

    public function AddToCart($type, $id, Request $request) {
        $productId = $id;
        $quantity = 1;
        $isAjax = $request->ajax() || $request->wantsJson();

        if ($request->session()->has('cartlist')) {
            $cartlist = $request->session()->get('cartlist');

            if (isset($cartlist[$productId])) {
                if ($isAjax) {
                    return response()->json(['status' => 'error', 'message' => 'Product is already in your cart!']);
                }
                return redirect()->route('cart')->with('error', 'Product is already in your cart!');
            }

            $cartlist[$productId] = [
                'quantity' => $quantity,
                'type' => $type
            ];
        } else {
            $cartlist = [
                $productId => [
                    'quantity' => $quantity,
                    'type' => $type
                ]
            ];
        }

        $request->session()->put('cartlist', $cartlist);

        $cartCount = count($cartlist);

        if ($isAjax) {
            return response()->json(['status' => 'success', 'message' => 'Product added to cart!', 'cartCount' => $cartCount]);
        }
        return redirect()->route('cart')->with('message', 'Product added to cart!');
    }


        public function getCartCount(Request $request) {
            $cartCount = count($request->session()->get('cartlist', []));
            return response()->json($cartCount);
        }






    public function removeFromCart($product_id, Request $request){
        $productId = $product_id;

        if ($request->session()->has('cartlist')) {
            $cartlist = $request->session()->get('cartlist');

            if (isset($cartlist[$productId])) {
                unset($cartlist[$productId]);
                $request->session()->put('cartlist', $cartlist);
            }
        }

        return redirect()->route('cart')->with('message', 'Product remove from cart!');
    }

    public function incrementCart($product_id, Request $request){
        $productId = $product_id;
        if ($request->session()->has('cartlist')) {
            $cartlist = $request->session()->get('cartlist');

            if (isset($cartlist[$productId])) {
                $cartlist[$productId]['quantity']++;
                $request->session()->put('cartlist', $cartlist);
                return redirect()->route('cart')->with('message', 'Cart quantity increment!');
            }
        }
        return redirect()->route('cart')->with('message', 'Cart updated!');
    }

    public function decrementCart($product_id, Request $request){
        $productId = $product_id;
        if ($request->session()->has('cartlist')) {
            $cartlist = $request->session()->get('cartlist');

            if (isset($cartlist[$productId])) {
                if ($cartlist[$productId]['quantity'] > 1) {
                    $cartlist[$productId]['quantity']--;
                    $request->session()->put('cartlist', $cartlist);
                    return redirect()->route('cart')->with('message', 'Cart quantity decrement!');
                } else {
                    // If the quantity is already 1, you can consider removing the item from the cart
                    unset($cartlist[$productId]);
                    $request->session()->put('cartlist', $cartlist);
                    return redirect()->route('cart')->with('message', 'Product removed from cart!');
                }
            }
        }
        return redirect()->route('cart')->with('message', 'Cart updated!');
    }

    public function cart(Request $request) {
        App::setLocale(Session::get('language'));

        $cartlist = $request->session()->get('cartlist', []);

        $productIds = array_keys($cartlist);

        // Retrieve the product models based on the cartlist product IDs
        $products = Product::whereIn('id', $productIds)->get();
        $parts = ProductPart::whereIn('id', $productIds)->get();
        $mergedArray = $products->concat($parts);
        // Combine the product models with their respective quantities and types
        $cartlistItems = [];
        foreach ($mergedArray as $product) {
            $productId = $product->id;
            if (isset($cartlist[$productId]) && is_array($cartlist[$productId])) {
                $quantity = $cartlist[$productId]['quantity'];
                $type = $cartlist[$productId]['type'];
                $cartlistItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'type' => $type,
                ];
            }
        }

        $carts = $cartlistItems;
        // return $carts;
        return view('frontend.pages.cart', compact('carts'));
    }

    public function cashonOrder(Request $request){
        $validator = $request->validate([
			'name' => 'required',
			'phone' => 'required',
			'email' => 'required',
			'address' => 'required',
			'post_code' => 'nullable',
			'city' => 'nullable',
			'state' => 'nullable',
			'country' => 'nullable',
		]);

        if(!Auth::user()){
            return redirect()->route('login')->withErrors(['msg' => 'You need to login first']);
        }

        $cartlist = $request->session()->get('cartlist', []);
        $productIds = array_keys($cartlist);
        // Retrieve the product models based on the cartlist product IDs
        $products = Product::whereIn('id', $productIds)->get();
        $parts = ProductPart::whereIn('id', $productIds)->get();
        $mergedArray = $products->concat($parts);
        // Combine the product models with their respective quantities and types
        $cartlistItems = [];
        foreach ($mergedArray as $product) {
            $productId = $product->id;
            if (isset($cartlist[$productId]) && is_array($cartlist[$productId])) {
                $quantity = $cartlist[$productId]['quantity'];
                $type = $cartlist[$productId]['type'];
                $cartlistItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'type' => $type,
                ];
            }
        }

        $total_price = 0;
        foreach ($cartlistItems as $item) {
            if ($item['type'] == 'product') {
                $total_price = $total_price + $item['quantity'] * (Helper::priceAfterOffer($item['product']['id']));
            }else{
                $total_price = $total_price + ($item['quantity'] * (Helper::partPriceFaterOffer($item['product']['id'])));
            }
        }

        $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->date = date('Y-m-d');
        $order->total_price = $total_price;
        $order->payment_status = 0;
        $order->payment_method = '';
        $order->note = $responseValues['COMMENT1'] ?? '';
        $company = Company::where('user_id', Auth::user()->id)->first();
        $billing_information = [
            'company' => $company->name ?? '',
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'city' => $request->city,
            'state' => $request->state,
            'country' => Helper::getCountrynameByCode($request->country)
        ];

        $order->billing_information = json_encode($billing_information);

        if ($order->save()) {
            $order->refresh();
            foreach ($cartlistItems as $item) {
                $order_detail = new OrderDetail();
                $order_detail->order_id  = $order->id;
                $order_detail->product_id = $item['product']['id'];
                $order_detail->reference_id = $item['product']['id'];
                $order_detail->type = $item['type'];
                $order_detail->quantity = $item['quantity'];

                // unit_price = MRP, discount = the applied offer, subtotal = net amount.
                // This lets the invoice show MRP / Discount / Rate correctly.
                if ($item['type'] == 'product') {
                    $net_price = Helper::priceAfterOffer($item['product']['id']);
                    $order_detail->unit_price = $item['product']['price'];

                    $offer = Helper::productOffer($item['product']['id'], $order->user_id);
                    $order_detail->discount_type = $offer['discount'] > 0 ? $offer['type'] : '';
                    $order_detail->discount = $offer['discount'];
                }else{
                    $net_price = Helper::partPriceFaterOffer($item['product']['id']);
                    $order_detail->unit_price = $item['product']['price'];
                    $order_detail->discount_type = ($item['product']->discount ?? 0) > 0 ? ($item['product']['discount_type'] ?? '') : '';
                    $order_detail->discount = $item['product']->discount ?? 0;
                }

                $order_detail->subtotal = $item['quantity'] * $net_price;
                $order_detail->save();
            }
            $request->session()->forget('cartlist');

            return redirect()->back()->with('message', 'Order place successfully!');
        }else{
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function PlaceOrder(Request $request){

        // get cart
        $cartlist = $request->session()->get('cartlist', []);
        $productIds = array_keys($cartlist);
        // Retrieve the product models based on the cartlist product IDs
        $products = Product::whereIn('id', $productIds)->get();
        $parts = ProductPart::whereIn('id', $productIds)->get();
        $mergedArray = $products->concat($parts);
        // Combine the product models with their respective quantities and types
        $cartlistItems = [];
        foreach ($mergedArray as $product) {
            $productId = $product->id;
            if (isset($cartlist[$productId]) && is_array($cartlist[$productId])) {
                $quantity = $cartlist[$productId]['quantity'];
                $type = $cartlist[$productId]['type'];
                $cartlistItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'type' => $type,
                ];
            }
        }

        $total_price = 0;
        foreach ($cartlistItems as $item) {
            if ($item['type'] == 'product') {
                $total_price = $total_price + $item['quantity'] * (Helper::priceAfterOffer($item['product']['id']));
            }else{
                $total_price = $total_price + ($item['quantity'] * (Helper::partPriceFaterOffer($item['product']['id'])));
            }
        }

        $actual_price = ($total_price/ 100);


        $user = Company::where('user_id',Auth::user()->id)->first();

        $config = new GpEcomConfig();
        $config->merchantId = "dev288251102910164081";
        $config->accountId = "";
        $config->sharedSecret = "BZwPfAhuBs";
        $config->serviceUrl = "https://pay.sandbox.realexpayments.com/pay";

        $config->hostedPaymentConfig = new HostedPaymentConfig();
        $config->hostedPaymentConfig->version = HppVersion::VERSION_2;
        $service = new HostedService($config);

        // Add 3D Secure 2 Mandatory and Recommended Fields
        $hostedPaymentData = new HostedPaymentData();
        $hostedPaymentData->customerFirstName = $request->name;
        $hostedPaymentData->customerEmail = $request->email;



        $hostedPaymentData->customerPhoneMobile = $request->phone;
        $hostedPaymentData->addressesMatch = false;

        $billingAddress = new Address();
        $billingAddress->streetAddress1 = $request->address;
        $billingAddress->streetAddress2 = "";
        $billingAddress->streetAddress3 = "";
        $billingAddress->city = $user->city;
        $billingAddress->postalCode = $user->post_code;
        $billingAddress->country = $request->country;

        $shippingAddress = new Address();
        $shippingAddress->streetAddress1 = $request->address;
        $shippingAddress->streetAddress2 = "";
        $shippingAddress->streetAddress3 = "";
        $shippingAddress->city = $request->city;
        $shippingAddress->state = $request->state;
        $shippingAddress->postalCode = $request->post_code;
        $shippingAddress->country = $request->country;

        try {
        $hppJson = $service->charge(0.0)
            ->withCurrency("USD")
            ->withAmount($actual_price)
            ->withDescription($request->note)
            ->withOrderId(substr(uniqid(), 0, 13).'-ordr-'.random_int(10000000000000000, 99999999999999999))
            ->withHostedPaymentData($hostedPaymentData)
            ->withAddress($billingAddress, AddressType::BILLING)
            ->withAddress($shippingAddress, AddressType::SHIPPING)
            ->serialize();

            return $hppJson;
        // TODO: pass the HPP JSON to the client-side
        } catch (ApiException $e) {
        // TODO: Add your error handling here
        }
    }

    public function AfterOrder(Request $request){
        // configure client settings
        $config = new GpEcomConfig();
        $config->merchantId = "dev288251102910164081";
        $config->accountId = "";
        $config->sharedSecret = "BZwPfAhuBs";
        $config->serviceUrl = "https://pay.sandbox.realexpayments.com/pay";

        $service = new HostedService($config);

        /*
        * TODO: grab the response JSON from the client-side.
        * sample response JSON (values will be Base64 encoded):
        * $responseJson ='{"MERCHANT_ID":"MerchantId","ACCOUNT":"internet","ORDER_ID":"GTI5Yxb0SumL_TkDMCAxQA","AMOUNT":"1999",' .
        * '"TIMESTAMP":"20170725154824","SHA1HASH":"843680654f377bfa845387fdbace35acc9d95778","RESULT":"00","AUTHCODE":"12345",' .
        * '"CARD_PAYMENT_BUTTON":"Place Order","AVSADDRESSRESULT":"M","AVSPOSTCODERESULT":"M","BATCHID":"445196",' .
        * '"MESSAGE":"[ test system ] Authorised","PASREF":"15011597872195765","CVNRESULT":"M","HPP_FRAUDFILTER_RESULT":"PASS"}";
        */
        $responseJson = isset($_POST['hppResponse']) ? $_POST['hppResponse'] : "";
        try {
            $parsedResponse = $service->parseResponse($responseJson, true);


            $orderId = $parsedResponse->orderId; // GTI5Yxb0SumL_TkDMCAxQA
            $responseCode = $parsedResponse->responseCode; // 00
            $responseMessage = $parsedResponse->responseMessage; // [ test system ] Authorised
            $responseValues = $parsedResponse->responseValues; // get values accessible by key
            $transactionReference = $parsedResponse->transactionReference; // get values accessible by key
            $authorizedAmount = $parsedResponse->authorizedAmount; // get values of amount


            $variable = json_encode($transactionReference);
            $variable = json_decode($variable);



            // order insert
            // get cart
            $cartlist = $request->session()->get('cartlist', []);
            $productIds = array_keys($cartlist);
            // Retrieve the product models based on the cartlist product IDs
            $products = Product::whereIn('id', $productIds)->get();
            $parts = ProductPart::whereIn('id', $productIds)->get();
            $mergedArray = $products->concat($parts);
            // Combine the product models with their respective quantities and types
            $cartlistItems = [];
            foreach ($mergedArray as $product) {
                $productId = $product->id;
                if (isset($cartlist[$productId]) && is_array($cartlist[$productId])) {
                    $quantity = $cartlist[$productId]['quantity'];
                    $type = $cartlist[$productId]['type'];
                    $cartlistItems[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'type' => $type,
                    ];
                }
            }

            $total_price = 0;
            foreach ($cartlistItems as $item) {
                if ($item['type'] == 'product') {
                    $total_price = $total_price + $item['quantity'] * (Helper::priceAfterOffer($item['product']['id']));
                }else{
                    $total_price = $total_price + ($item['quantity'] * (Helper::partPriceFaterOffer($item['product']['id'])));
                }
            }

            $order = new Order();
            $order->id = $responseValues['ORDER_ID'];
            $order->user_id = Auth::user()->id;
            $order->date = date('Y-m-d');
            $order->total_price = $total_price;
            $order->payment_status = 0;
            $order->payment_method = '';
            $order->note = $responseValues['COMMENT1'] ?? '';
            $company = Company::where('user_id', Auth::user()->id)->first();
            $billing_information = [
                'company' => $company->name ?? '',
                'name' => $responseValues['HPP_CUSTOMER_FIRSTNAME'],
                'phone' => $responseValues['HPP_CUSTOMER_PHONENUMBER_MOBILE'],
                'email' => $responseValues['HPP_CUSTOMER_EMAIL'],
                'address' => $responseValues['HPP_BILLING_STREET1'],
                'post_code' => $responseValues['HPP_SHIPPING_POSTALCODE'],
                'city' => $responseValues['HPP_SHIPPING_CITY'],
                'state' => $responseValues['HPP_SHIPPING_STATE'],
                'country' => Helper::getCountrynameByCode($responseValues['HPP_SHIPPING_COUNTRY'])
            ];

            $order->billing_information = json_encode($billing_information);

            if ($order->save()) {
                $order->refresh();
                foreach ($cartlistItems as $item) {
                    $order_detail = new OrderDetail();
                    $order_detail->order_id  = $order->id;
                    $order_detail->product_id = $item['product']['id'];
                    $order_detail->reference_id = $item['product']['id'];
                    $order_detail->type = $item['type'];
                    $order_detail->quantity = $item['quantity'];

                    // unit_price = MRP, discount = the applied offer, subtotal = net amount.
                    // This lets the invoice show MRP / Discount / Rate correctly.
                    if ($item['type'] == 'product') {
                        $net_price = Helper::priceAfterOffer($item['product']['id']);
                        $order_detail->unit_price = $item['product']['price'];

                        $offer = Helper::productOffer($item['product']['id'], $order->user_id);
                        $order_detail->discount_type = $offer['discount'] > 0 ? $offer['type'] : '';
                        $order_detail->discount = $offer['discount'];
                    }else{
                        $net_price = Helper::partPriceFaterOffer($item['product']['id']);
                        $order_detail->unit_price = $item['product']['price'];
                        $order_detail->discount_type = ($item['product']->discount ?? 0) > 0 ? ($item['product']['discount_type'] ?? '') : '';
                        $order_detail->discount = $item['product']->discount ?? 0;
                    }

                    $order_detail->subtotal = $item['quantity'] * $net_price;
                    $order_detail->save();
                }
                $request->session()->forget('cartlist');

                if($responseCode == '00'){
                    $update_order = Order::find($order->id);
                    $update_order->payment_method = 'Online payment';
                    $update_order->transaction_id = $variable->transactionId;
                    $update_order->payment_status = 1;
                    $update_order->save();

                    $transaction = new Transaction();
                    $transaction->order_id = $order->id;
                    $transaction->transaction_id = $variable->transactionId;
                    $transaction->amount = $authorizedAmount;
                    $transaction->response = $responseJson;
                    $transaction->save();
                }else{
                    $update_order = Order::find($order->id);
                    $update_order->payment_method = 'Online payment';
                    $update_order->transaction_id = $variable->transactionId;
                    $update_order->save();

                    $transaction = new Transaction();
                    $transaction->order_id = $order->id;
                    $transaction->transaction_id = $variable->transactionId;
                    $transaction->amount = $authorizedAmount;
                    $transaction->status = 0;
                    $transaction->response = $responseJson;
                    $transaction->save();
                }

                return redirect()->back()->with('message', 'Order place successfully!');
            }

        } catch (ApiException $e) {
            return $e;
            // For example if the SHA1HASH doesn't match what is expected
            // TODO: add your error handling here
        }
    }

    private function generateHash($data, $secret)
    {
        $toHash = [];
        $timeStamp           = !isset($data['TIMESTAMP']) ? "" : $data['TIMESTAMP'];
        $merchantId          = !isset($data['MERCHANT_ID']) ? "" : $data['MERCHANT_ID'];
        $orderId             = !isset($data['ORDER_ID']) ? "" : $data['ORDER_ID'];
        $amount              = !isset($data['AMOUNT']) ? "" : $data['AMOUNT'];
        $currency            = !isset($data['CURRENCY']) ? "" : $data['CURRENCY'];
        $payerReference      = !isset($data['PAYER_REF']) ? "" : $data['PAYER_REF'];
        $paymentReference    = !isset($data['PMT_REF']) ? "" : $data['PMT_REF'];
        $hppSelectStoredCard = !isset($data['HPP_SELECT_STORED_CARD']) ? "" : $data['HPP_SELECT_STORED_CARD'];
        $payRefORStoredCard  = empty($hppSelectStoredCard) ?  $payerReference : $hppSelectStoredCard;

        if (isset($data['CARD_STORAGE_ENABLE']) && $data['CARD_STORAGE_ENABLE'] === '1') {
            $toHash = [
                $timeStamp,
                $merchantId,
                $orderId,
                $amount,
                $currency,
                $payerReference,
                $paymentReference,
            ];
        } elseif ($payRefORStoredCard && empty($paymentReference)) {
            $toHash = [
                $timeStamp,
                $merchantId,
                $orderId,
                $amount,
                $currency,
                $payRefORStoredCard,
                ""
            ];
        } elseif ($payRefORStoredCard && !empty($paymentReference)) {
            $toHash = [
                $timeStamp,
                $merchantId,
                $orderId,
                $amount,
                $currency,
                $payRefORStoredCard,
                $paymentReference,
            ];
        } else {
            $toHash = [
                $timeStamp,
                $merchantId,
                $orderId,
                $amount,
                $currency,
            ];
        }

        return sha1(sha1(implode('.', $toHash)) . '.' . $secret);
    }


    public function wishlist(Request $request) {
        App::setLocale(Session::get('language'));
        $wishlist = $request->session()->get('wishlist', []);
        $productIds = array_keys($wishlist);
        // Retrieve the product models based on the wishlist product IDs
        $products = Product::whereIn('id', $productIds)->get();
        $parts = ProductPart::whereIn('id', $productIds)->get();
        $mergedArray = $products->concat($parts);
        // Combine the product models with their respective quantities and types
        $wishlistItems = [];
        foreach ($mergedArray as $product) {
            $productId = $product->id;
            if (isset($wishlist[$productId]) && is_array($wishlist[$productId])) {
                $quantity = $wishlist[$productId]['quantity'];
                $type = $wishlist[$productId]['type'];
                $wishlistItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'type' => $type,
                ];
            }
        }
        $carts = $wishlistItems;
        return view('frontend.pages.wishlist', compact('wishlistItems'));
    }

    public function AddTowishlist($type, $id, Request $request) {
        $productId = $id;
        $quantity = 1;
        $isAjax = $request->ajax() || $request->wantsJson();

        if ($request->session()->has('cartlist')) {
            $cartlist = $request->session()->get('cartlist');

            if (isset($cartlist[$productId])) {
                if ($isAjax) {
                    return response()->json(['status' => 'error', 'message' => 'Product is already in your cart!']);
                }
                return redirect()->back()->with('error', 'Product is already in your cart!');
            }
        }

        if ($request->session()->has('wishlist')) {
            $wishlist = $request->session()->get('wishlist');

            if (isset($wishlist[$productId])) {
				unset($wishlist[$productId]);
                $request->session()->put('wishlist', $wishlist);
                if ($isAjax) {
                    return response()->json(['status' => 'removed', 'message' => 'Product remove from wishlist!']);
                }
                return redirect()->route('wishlist')->with('message', 'Product remove from wishlist!');
            }

            // Add the product to the wishlist
            $wishlist[$productId] = [
                'quantity' => $quantity,
                'type' => $type
            ];
        } else {
            $wishlist = [
                $productId => [
                    'quantity' => $quantity,
                    'type' => $type
                ]
            ];
        }

        $request->session()->put('wishlist', $wishlist);

        if ($isAjax) {
            return response()->json(['status' => 'success', 'message' => 'Product added to wishlist!']);
        }
        return redirect()->route('wishlist')->with('message', 'Product added to wishlist!');
    }

    // wishlist-badge

    public function getWishlistCount(Request $request) {
        $wishlistCount = count($request->session()->get('wishlist', []));
        return response()->json($wishlistCount);
    }


    public function removeFromWishlist($product_id, Request $request){
        $productId = $product_id;

        if ($request->session()->has('wishlist')) {
            $wishlist = $request->session()->get('wishlist');

            if (isset($wishlist[$productId])) {
                unset($wishlist[$productId]);
                $request->session()->put('wishlist', $wishlist);
            }
        }

        return redirect()->route('wishlist')->with('message', 'Product remove from wishlist!');
    }

    public function incrementWishlist($product_id, Request $request){
        $productId = $product_id;
        if ($request->session()->has('wishlist')) {
            $wishlist = $request->session()->get('wishlist');

            if (isset($wishlist[$productId])) {
                $wishlist[$productId]['quantity']++;
                $request->session()->put('wishlist', $wishlist);
            }
        }
        return redirect()->route('wishlist')->with('message', 'Quantity updated!');
    }

	public function decrementWishlist($product_id, Request $request){
        $productId = $product_id;
        if ($request->session()->has('wishlist')) {
            $wishlist = $request->session()->get('wishlist');

            if (isset($wishlist[$productId])) {
                if ($wishlist[$productId]['quantity'] > 1) {
                    $wishlist[$productId]['quantity']--;
                    $request->session()->put('wishlist', $wishlist);
                    return redirect()->route('wishlist')->with('message', 'Wishlist quantity decrement!');
                } else {
                    // If the quantity is already 1, you can consider removing the item from the wishlist
                    unset($wishlist[$productId]);
                    $request->session()->put('wishlist', $wishlist);
                    return redirect()->route('wishlist')->with('message', 'Product removed from wishlist!');
                }
            }
        }
        return redirect()->route('wishlist')->with('message', 'Wishlist updated!');
    }

    public function AddToInquiry($product_id, Request $request){
        $productId = $product_id;
        $quantity = 1;

        if ($request->session()->has('inquirylist')) {
            $inquirylist = $request->session()->get('inquirylist');

            if (isset($inquirylist[$productId])) {
                return redirect()->route('inquiry')->with('message', 'Product is already in your inquiry list!');
            }

            $inquirylist[$productId] = $quantity;
        } else {
            $inquirylist = [$productId => $quantity];
        }

        $request->session()->put('inquirylist', $inquirylist);

        return redirect()->route('inquiry')->with('message', 'Product added to inquiry list!');
    }

    public function inquiry(Request $request) {
        App::setLocale(Session::get('language'));
        $inquirylist = $request->session()->get('inquirylist', []);

        $productIds = array_keys($inquirylist);

        $products = Product::whereIn('id', $productIds)->get();

        // Combine the product models with their respective quantities
        $inquirylistItems = [];
        foreach ($products as $product) {
            $productId = $product->id;
            $quantity = $inquirylist[$productId];
            $inquirylistItems[] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }

        return view('frontend.pages.inquiry', compact('inquirylistItems'));
    }

    public function removeFromInquirylist($product_id, Request $request){
        $productId = $product_id;

        if ($request->session()->has('inquirylist')) {
            $inquirylist = $request->session()->get('inquirylist');

            if (isset($inquirylist[$productId])) {
                unset($inquirylist[$productId]);
                $request->session()->put('inquirylist', $inquirylist);
            }
        }

        return redirect()->route('inquiry')->with('message', 'Product remove from inquiry list!');
    }

    public function incrementInquirylist($product_id, Request $request){
        $productId = $product_id;
        if ($request->session()->has('inquirylist')) {
            $inquirylist = $request->session()->get('inquirylist');

            if (isset($inquirylist[$productId])) {
                $inquirylist[$productId]++;
                $request->session()->put('inquirylist', $inquirylist);
            }
        }
        return redirect()->route('inquiry')->with('message', 'Quantity updated!');
    }

    public function decrementInquirylist($product_id, Request $request){
        $productId = $product_id;
        if ($request->session()->has('inquirylist')) {
            $inquirylist = $request->session()->get('inquirylist');

            if (isset($inquirylist[$productId])) {
                $inquirylist[$productId]--;

                if ($inquirylist[$productId] <= 0) {
                    unset($inquirylist[$productId]);
                }

                $request->session()->put('inquirylist', $inquirylist);
            }
        }
        return redirect()->route('inquiry')->with('message', 'Quantity updated!');
    }

    public function inquiryRequest() {
        App::setLocale(Session::get('language'));
        return view('frontend.pages.inquiryRequest');
    }

    public function inquiryRequestSend(Request $request){

        $validator = $request->validate([
			'company' => 'required',
			'name' => 'required',
			'phone' => 'required',
			'email' => 'required',
			'address' => 'required',
			'post_code' => 'required',
			'city' => 'required',
			'state' => 'required',
			'country' => 'required',
		]);

        $inquiry = new Inquiry();
        $inquiry->user_id = Auth::user()->id ?? '';
        $inquiry->date = date('Y-m-d');
        $inquiry->request_by = $request->name;
        $inquiry->note = $request->note ?? '';

        $address_information = [
            'company' => $request->company,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country
        ];

        $inquiry->address_information = json_encode($address_information);

        if ($inquiry->save()) {
            $inquiry->refresh();

            $inquirylist = $request->session()->get('inquirylist', []);
            $productIds = array_keys($inquirylist);
            $products = Product::whereIn('id', $productIds)->get();
            $inquirylistItems = [];
            foreach ($products as $product) {
                $productId = $product->id;
                $quantity = $inquirylist[$productId];
                $inquirylistItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            }

            foreach ($inquirylistItems as $item) {
                $inquiry_products = new InquiryProduct();
                $inquiry_products->inquiry_id  = $inquiry->id;
                $inquiry_products->product_id = $item['product']['id'];
                $inquiry_products->quantity = $item['quantity'];
                $inquiry_products->unit_price = $item['product']['price'];
                $inquiry_products->note = '';
                $inquiry_products->subtotal = $item['product']['price'] * $item['quantity'];
                $inquiry_products->save();
            }

            $request->session()->forget('inquirylist');

            return redirect()->back()->with('message', 'Inquiry request submited!');
        }

    }

    public function catalogues() {
        App::setLocale(Session::get('language'));
        $brands = Cache::remember('brands', now()->addHours(1), function () {
			return Brand::where('status', 1)->get();
		});

		$categories = Cache::remember('categories', now()->addHours(1), function () {
			return Category::where('status', 1)->where('is_parent', 1)->get();
		});
        return view('frontend.pages.catalogues', compact('brands', 'categories'));
    }

    public function searchCatalogue(Request $request){
        App::setLocale(Session::get('language'));
        $catalogues = Catalogue::where('status', 1)->where('type', 'catalogue');

        if($request->brand){
            $catalogues->where(function($catalogues) use ($request){
                $catalogues->whereIn('brand_id', $request->brand);
            });
        }

        if($request->category){
            $catalogues->where(function($catalogues) use ($request){
                $catalogues->whereIn('category_id', $request->category);
            });
        }

		if ($request->title) {
            $catalogues->where('title','like', "%" .$request->title ."%" );
        }

        $catalogues = $catalogues->orderBy('brand_id', 'asc')->get();
        return view('frontend.pages.search.catalogues', compact('catalogues'));
    }

    public function downloadCatalogue($lang, $catalogue_id){

        $catalogue = Catalogue::find($catalogue_id);
        $fileName = $catalogue->getTranslation($lang, 'file');
        if (file_exists(public_path('uploads/catalogue-files/'.$fileName))) {
            return response()->download(public_path('uploads/catalogue-files/'.$fileName));
        }else{
            return redirect()->back();
        }
    }

    public function viewCatalogue($catalogue_id){
        App::setLocale(Session::get('language'));
        $catalogue = Catalogue::where('slug',$catalogue_id)->first();
        $catalogue_files = '';
        if ($catalogue) {
            // $catalogue = Catalogue::find($catalogue_id);
            $catalogue_files = $catalogue->getTranslation(Session::get('language') ?? 'en', 'file') ?? $catalogue->file;
        }
        if (!$catalogue) {
            return response()->view('errors.404', [], 404);
        }
        return view('frontend.pages.catalogue-view', compact('catalogue','catalogue_files'));
    }

	public function manuals() {
        App::setLocale(Session::get('language'));
        $brands = Cache::remember('brands', now()->addHours(1), function () {
			return Brand::where('status', 1)->get();
		});

		$categories = Cache::remember('categories', now()->addHours(1), function () {
			return Category::where('status', 1)->where('is_parent', 1)->get();
		});
        return view('frontend.pages.manuals', compact('brands', 'categories'));
    }

	public function forms() {
        App::setLocale(Session::get('language'));
        $brands = Cache::remember('brands', now()->addHours(1), function () {
			return Brand::where('status', 1)->get();
		});

		$categories = Cache::remember('categories', now()->addHours(1), function () {
			return Category::where('status', 1)->where('is_parent', 1)->get();
		});
        return view('frontend.pages.forms', compact('brands', 'categories'));
    }

	public function formsDetails($id){
        $catalogue = Catalogue::where('slug',$id)->first();
        if (!$catalogue) {
            $catalogue = Catalogue::find($id);
        }
        if (!$catalogue) {
            return response()->view('errors.404', [], 404);
        }
		return view('frontend.pages.form-details', compact('catalogue'));
	}

	public function formsSubmit(Request $request){
		$validator = $request->validate([
			'name' => 'required',
			'file' => 'required|mimes:pdf',
			'email' => 'required',
		]);

		$subject = 'Form request';
        $data = $request->name.'Uploaded a new form files. Here is the details, </br> Name: '.$request->name.', </br> Email: '.$request->email.', </br> Message: '.$request->note.' .';
        $admin_email = 'abusaid.nexkraft@gmail.com';
        Helper::sendEmail($admin_email, $subject, $data);

        return redirect()->back()->with('success', 'Email send successfully! We will contact with you soon.');
	}

    public function news() {
        App::setLocale(Session::get('language'));
		$months = DB::select(DB::raw("SELECT
                                CONCAT(month, '-', year) AS month_year,
                                news_count
                            FROM (
                                SELECT
                                    MONTH(publish_date) AS month,
                                    YEAR(publish_date) AS year,
                                    COUNT(*) AS news_count
                                FROM
                                    news
                                GROUP BY
                                    MONTH(publish_date), YEAR(publish_date)
                            ) AS subquery
                            ORDER BY
                                year, month"));
        return view('frontend.pages.news', compact('months'));
    }
    public function newsDetails($news_id) {
        App::setLocale(Session::get('language'));

        $news = News::where('slug', $news_id)->first();
        if (!$news) {
            $news = News::findOrFail($news_id);
        }
        if (!$news) {
            return response()->view('errors.404', [], 404);
        }

		$imagesArray = json_decode($news->gallery_images, true);
        $news->gallery_images = $imagesArray;

        return view('frontend.pages.newsDetails', compact('news'));
    }
    public function searchNews(Request $request){
        App::setLocale(Session::get('language'));
        $news = News::where('status', 1);

		if($request->year){
            $news->where(function($news) use ($request){
				foreach ($request->year as $date) {
					$year = date('Y', strtotime($date));
					$month = date('m', strtotime($date));

					$news->orWhere(function ($news) use ($year, $month) {
						$news->whereYear('publish_date', $year)->whereMonth('publish_date', $month);
					});
				}
            });
        }

		if($request->category){
            $news->where(function($news) use ($request){
                $news->whereIn('category', $request->category);
            });
        }

        if ($request->title) {
            $news->where('title','like', "%" .$request->title ."%" );
        }


        $news = $news->get();
        return view('frontend.pages.search.news', compact('news'));
    }



    public function services() {
        App::setLocale(Session::get('language'));
        $services = Service::where('status', 1)->get();
        return view('frontend.pages.services', compact('services'));
    }
    public function serviceDetails($id) {
        App::setLocale(Session::get('language'));
        $service = Service::where('slug', $id)->first();
        if (!$service) {
            $service = Service::find($id);
        }
        if (!$service) {
            return response()->view('errors.404', [], 404);
        }

        return view('frontend.pages.serviceDetails', compact('service'));
    }

    public function AddToService($service_id, Request $request){
        if ($request->session()->has('servicelist')) {
            $servicelist = $request->session()->get('servicelist');

            if (in_array($service_id, $servicelist)) {
                return redirect()->route('service.order');
            }

            $servicelist[] = $service_id;
        } else {
            $servicelist = [$service_id];
        }
        $request->session()->put('servicelist', $servicelist);

        return redirect()->route('service.order');
    }

    public function serviceOrder(){
        App::setLocale(Session::get('language'));
        return view('frontend.pages.service-order');
    }

    public function serviceOrderSend(Request $request){
        $validator = $request->validate([
			'name' => 'required',
			'email' => 'required',
			'address' => 'required',
		]);

        $order = new ServiceOrder();
        $order->user_id = Auth::user()->id ?? null;
        $order->date = date('Y-m-d');
        $order->name = $request->name;
        $order->company_name = $request->company_name ?? '';
        $order->email = $request->email;
        $order->address = $request->address;
        $order->message = $request->note;

        $service_information =[];


        $servicelist = $request->session()->get('servicelist', []);
        $service = Service::whereIn('id', $servicelist)->get();

        foreach ($service as $row) {
            $service = Service::find($row->id);

            if($request->hasFile('thumbnail')){
                $thumbnail = $request->file('thumbnail');
                $filename = time().uniqid().$thumbnail->getClientOriginalName();
                $thumbnail->move(public_path('uploads/service-order'), $filename);
                $file = $filename;
            }else{
                $file = '';
            }

            $services = [
                'service_id' => $service->id,
                'name' => $service->title,
                'code' => $service->code,
                'file' => $file
            ];

            array_push($service_information, $services);
        }
        $order->service_information = json_encode($service_information);

        if ($order->save()) {
            $request->session()->forget('servicelist');

            return redirect()->back()->with('message', 'Order place successfully!');
        }else{
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

        public function order() {
        App::setLocale(Session::get('language'));
        $countrycodes = Helper::getCountryCodes();

        // Fetch the company associated with the logged-in user
        $company = null;
        if(Auth::check()){
            $company = Company::where('user_id', Auth::user()->id)->first();
        }

        return view('frontend.pages.order', compact('countrycodes', 'company'));
    }
    public function pdf() {
        App::setLocale(Session::get('language'));
        return view('frontend.pages.pdf');
    }
    public function calculator() {
        App::setLocale(Session::get('language'));
        return view('frontend.pages.calculator');
    }
    public function forgotPassword() {
        App::setLocale(Session::get('language'));
        return view('frontend.pages.forgotPassword');
    }

    public function resetOtpSend(Request $request){

        if(User::where('email', $request->email)->exists()){
            $email = $request->email;
            $otps = random_int(100000, 999999);
            $subject = 'Password Reset';
            $data['user'] = User::where('email', $request->email)->first();
            $data['otp'] = $otps;
            $data['message'] = 'Your confirmation code is below — enter it in your open browser window and we will help you get changed password. Please do not share the code others';
            Helper::sendEmail($email, $subject, $data, 'resetpassword');

            $otp = new Otp();
            $otp->email = $request->email;
            $otp->otp = $otps;
            $otp->save();

            return view('frontend.pages.otp', compact('email'));
        }else{
            return redirect()->back()->withErrors(['message' => 'There is no account with this email!']);
        }
    }

    public function otp(Request $request) {
        App::setLocale(Session::get('language'));
        if ($request->email && $request->otp) {
            Validator::make($request->all(), [
                'email' => 'required',
                'otp' => 'required',
                'password' => 'required',
                'password_confirmation' => 'required_with:password|same:password|min:6',
            ]);

            if (Helper::checkotp($request->email, $request->otp)) {
                $email = $request->email;
                $user = User::where('email', $request->email)->first();
                $user->password = Hash::make($request->password);
                if ($user->save()) {
                    $otp = Otp::where('email', $request->email)->where('otp', $request->otp)->first();
                    $otp->status = 1;
                    $otp->save();
                    return redirect()->route('admin')->with(['message' => 'Password changed successfully!']);
                }else{
                    return view('frontend.pages.otp', compact('email'))->with(['message' => 'OTP invalid or expaired!']);
                }
            }else{
                return view('frontend.pages.otp', compact('email'))->with(['message' => 'OTP invalid or expaired!']);
            }
        }else{
            return view('frontend.pages.otp');
        }
    }

    public function pages($slug){
        App::setLocale(Session::get('language'));
        $content = '';
        if($slug == 'terms-and-conditions'){
            $content = Helper::getSettings('terms_and_conditions');
        }else if ($slug == 'privacy-policy') {
            $content = Helper::getSettings('privacy_policy');
        }else if ($slug == 'return-policy') {
            $content = Helper::getSettings('return_policy');
        }
        return view('frontend.pages.page', compact('slug', 'content'));
    }

    public function changeLanguage(Request $request){

        $language = $request->input('language');

        Session::put('language', $language);

        return true;
    }

    public function Search(Request $request){
        App::setLocale(Session::get('language'));
        $search_text = $request->search_text;
        $products = Product::where('status', 1);
        if (!empty($search_text)) {
            $products->where(function($query) use ($search_text){
                $query->where('name', 'like', "%" . $search_text . "%")
                ->orWhere('code', 'like', "%" . $search_text . "%")
                ->orWhereHas('category', function($q) use ($search_text) {
                    $q->where('title', 'like', "%" . $search_text . "%");
                });
            });
        }
        //$products = $products->get();
		$products = $products->orderBy('short_number', 'asc')->get();

		$parts = ProductPart::where('status', 1);
        if (!empty($search_text)) {
            $parts->where(function($query) use ($search_text){
                $query->where('name', 'like', "%" . $search_text . "%")
                ->orWhere('code', 'like', "%" . $search_text . "%")
                ->orWhereHas('brand', function($q) use ($search_text) {
                    $q->where('title', 'like', "%" . $search_text . "%");
                });
            });
        }
        //$products = $products->get();
		$parts = $parts->orderBy('short_number', 'asc')->get();

        return view('frontend.pages.search.search', compact('search_text', 'products', 'parts'));
    }

    public function Brands($brand_id){
        App::setLocale(Session::get('language'));
        $brand = Brand::find($brand_id);
        $products = Product::where('brand_id', $brand_id)->where('status', 1)->get();
        return view('frontend.pages.brand-product',compact('brand','products'));
    }

	public function downloadFile($fileName)
    {
        $filePath = public_path('uploads/product-custom-files/' . $fileName);

        if (file_exists($filePath)) {
            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
            $contentType = 'application/octet-stream';
            if ($fileExtension === 'pdf') {
                $contentType = 'application/pdf';
            }
            return response()->download($filePath, $fileName, ['Content-Type' => $contentType]);
        } else {
            return redirect()->back()->with('error', 'File not found.');
        }
    }

    public function catchAll(){
        return view('frontend.pages.error');
    }

	public function reviewUs(){
        return view('frontend.pages.review-us');
    }

}

