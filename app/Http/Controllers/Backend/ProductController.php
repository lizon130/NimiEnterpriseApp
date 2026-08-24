<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Auth;
use Helper;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductAttribute;
use App\Models\CustomField;
use Session;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('product.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $category = Category::whereNull('parent_category')->where('status', 1)->orderBy('short_number', 'asc')->get();
        $sub_category = Category::whereNotNull('parent_category')->where('status', 1)->orderBy('short_number', 'asc')->get();
        $brands = Brand::all();
        return view('backend.pages.product.index', compact('category', 'brands', 'sub_category'));
    }

    public function getList(Request $request)
    {

        $data = Product::query();
        if (!empty($request->category)) {
            $data->where('category_id', $request->category);
        }

        if ($request->brand) {
            $data->where('brand_id', $request->brand);
        }

        if (!empty($request->name)) {
            $data->where(function ($query) use ($request) {
                $query->where('name', 'like', "%" . $request->name . "%");
            });
        }

        return Datatables::of($data)

            ->editColumn('thumbnail', function ($row) {
                return ($row->thumbnail) ? '<a href="' . asset('uploads/product-images/' . $row->thumbnail) . '" target="_blank"><img class="profile-img" src="' . asset('uploads/product-images/' . $row->thumbnail) . '" alt="profile image" width="50" height="50"></a>' : '<img class="profile-img" src="' . asset('assets/img/no-img.jpg') . '" alt="profile image" width="50" height="50">';
            })

            ->addColumn('checkbox', function ($row) {
                return '<div class="form-check form-check-flat">
                    <label class="form-check-label">
                        <input name="select_all[]" type="checkbox" class="form-check-input checkbox_single_select" value="' . $row->id . '"><i class="input-helper"></i>
                    </label>
                </div>';
            })

            ->editColumn('category_id', function ($row) {
                return ($row->category)->title ?? '-';
            })

            ->editColumn('brand_id', function ($row) {
                return ($row->brand)->title ?? '-';
            })

            ->editColumn('price', function ($row) {
                return '৳' . number_format($row->price, 2);
            })

            ->addColumn('discount_display', function ($row) {
                if ($row->discount > 0) {
                    if ($row->discount_type == 'percent') {
                        return '<span class="badge bg-success">' . $row->discount . '% OFF</span>';
                    } else {
                        return '<span class="badge bg-info">$' . number_format($row->discount, 2) . ' OFF</span>';
                    }
                }
                return '<span class="badge bg-secondary">No Discount</span>';
            })

            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge bg-primary w-80">Active</span>';
                } else {
                    return '<span class="badge bg-danger w-80">Inactive</span>';
                }
            })

            ->addColumn('action', function ($row) {
                $btn = '';
                if (Helper::hasRight('product.edit')) {
                    $btn = $btn . '<a href="" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
                }
                if (Helper::hasRight('product.custom-option')) {
                    $btn = $btn . '<a href="" data-id="' . $row->id . '" class="custom_option_btn btn btn-sm btn-info mx-1"><i class="fa-solid fa-screwdriver-wrench"></i></a>';
                }
                if (Helper::hasRight('product.delete')) {
                    $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger " data-id="' . $row->id . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['thumbnail', 'checkbox', 'category_id', 'brand_id', 'discount_display', 'status', 'action'])->make(true);
    }

    public function exportPdf(Request $request)
    {
        $data = Product::query()->with(['category', 'brand']);

        if (!empty($request->category)) {
            $data->where('category_id', $request->category);
        }

        if ($request->brand) {
            $data->where('brand_id', $request->brand);
        }

        if (!empty($request->name)) {
            $data->where(function ($query) use ($request) {
                $query->where('name', 'like', "%" . $request->name . "%");
            });
        }

        $products = $data->orderBy('short_number', 'asc')->get();

        $filter_category = (!empty($request->category)) ? Category::find($request->category) : null;
        $filter_brand = (!empty($request->brand)) ? Brand::find($request->brand) : null;

        $pdf = Pdf::loadView('backend.pages.product.pdf.product-list', compact('products', 'filter_category', 'filter_brand'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('product-list-' . date('Y-m-d') . '.pdf');
    }

    public function getSubcategory(Request $request)
    {
        $category = Category::find($request->id);
        $subcategories = $category->subcategories()->get();

        $html = '';
        foreach ($subcategories as $subcategory) {
            $html .= '<option value="' . $subcategory->id . '">' . $subcategory->title . '</option>';
            foreach ($subcategory->subcategories as $subCategory) {
                $this->addSubcategoryOptions($subCategory, $html);
            }
        }

        return response()->json(['category' => $html]);
    }

    private function addSubcategoryOptions($subcategory, &$html)
    {
        $html .= '<option value="' . $subcategory->id . '">' . $subcategory->title . '</option>';
        foreach ($subcategory->subcategories as $subCategory) {
            $this->addSubcategoryOptions($subCategory, $html);
        }
    }


    public function store(Request $request)
    {

        $validator = $request->validate([
            'name' => 'required',
            'code' => 'required',
            'category' => 'required',
            'thumbnail' => 'required|image:png,jpg,jpeg,gif,webp,',
        ]);


        $product = new Product();
        $product->name = $request->name;
        $product->code = $request->code;
        $product->slug = $request->slug;
        $product->brand_id = $request->brand;
        $product->category_id = $request->category;
        $product->sub_category_id = $request->sub_category_id;
        $product->price  = $request->price;
        $product->key_features  = $request->key_features;
        $product->further_information  = $request->further_information;
        $product->discount_type  = $request->discount_type;
        $product->discount  = $request->discount;
        $product->status = ($request->status) ? 1 : 0;
        $product->features = ($request->features) ? 1 : 0;
        $product->show_price = ($request->show_price) ? 1 : 0;
        $product->show_price_to_partner = ($request->show_price_to_partner) ? 1 : 0;
        $product->short_number = 0;
        $product->meta_title = $request->meta_title;
        $product->meta_tags = ($request->meta_tag) ? implode(',', $request->meta_tag) : '';
        $product->meta_descriptions = $request->meta_descriptions;

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            // $filename = time().uniqid().$thumbnail->getClientOriginalName();
            $filename = $thumbnail->getClientOriginalName();
            $thumbnail->move(public_path('uploads/product-images'), $filename);
            $product->thumbnail = $filename;
        }

        if ($request->hasFile('gallery')) {
            $gallerys = $request->file('gallery');
            $image_name = [];
            foreach ($gallerys as $image) {
                // $filename = time().uniqid().$image->getClientOriginalName();
                $filename = $image->getClientOriginalName();
                $image->move(public_path('uploads/product-images'), $filename);
                array_push($image_name, $filename);
            }
            $product->images =  json_encode($image_name);
        }

        if ($product->save()) {

            // language
            Helper::insertLanguage(Product::class, $product->id, 'en', 'name', $product->name);
            Helper::insertLanguage(Product::class, $product->id, 'en', 'key_features', $product->key_features);
            Helper::insertLanguage(Product::class, $product->id, 'en', 'further_information', $product->further_information);
            $product->refresh();
            if (isset($request->attributes_name) && count($request->attributes_name) > 0) {
                for ($i = 0; $i < count($request->attributes_name); $i++) {
                    if (!empty($request->attributes_name[$i])) {
                        $filterable_checkbox = explode(',', $request->filterable_checkbox);
                        $attribute = new ProductAttribute();
                        $attribute->product_id = $product->id;
                        $attribute->is_filter = ($filterable_checkbox[$i] == '1') ? 1 : 0;
                        $attribute->type = 'attributes';
                        $attribute->attribute_name = $request->attributes_name[$i];
                        $attribute->value = $request->attributes_value[$i];
                        $attribute->save();
                    }
                }
            }

            // generated order number
            $related_products = Product::where('id', '!=', $product->id)->where('category_id', $product->category_id)->orderBy('short_number', 'asc')->get();
            $i = 2;
            foreach ($related_products as $rproduct) {
                $rproduct->short_number = $i++;
                $rproduct->save();
            }

            return response()->json([
                'type' => 'success',
                'message' => 'Product created successfully.',
            ]);
        }
    }

    public function edit($id)
    {
        $category = Category::whereNull('parent_category')->where('status', 1)->orderBy('short_number', 'asc')->get();
        $sub_category = Category::whereNotNull('parent_category')->where('status', 1)->orderBy('short_number', 'asc')->get();
        $brands = Brand::all();
        $product = Product::find($id);
        return view('backend.pages.product.edit', compact('category', 'brands', 'product', 'sub_category'));
    }

    public function update(Request $request, $id)
    {

        $validator = $request->validate([
            'name' => 'required',
            'code' => 'required',
            'category' => 'required',
            'thumbnail' => 'nullable|image:png,jpg,jpeg,gif,webp,',
        ]);


        $product = Product::find($id);
        if (Session::get('admin_language') == 'en') {
            $product->name = $request->name;
            $product->key_features  = $request->key_features;
            $product->further_information  = $request->further_information;
        }
        $product->code = $request->code;
        $product->brand_id = $request->brand;
        $product->category_id = $request->category;
        $product->sub_category_id = $request->sub_category_id;
        $product->price  = $request->price;
        $product->discount_type  = $request->discount_type;
        $product->discount  = $request->discount;
        $product->status = ($request->status) ? 1 : 0;
        $product->features = ($request->features) ? 1 : 0;
        $product->show_price = ($request->show_price) ? 1 : 0;
        $product->show_price_to_partner = ($request->show_price_to_partner) ? 1 : 0;
        $product->meta_title = $request->meta_title;
        $product->meta_tags = ($request->meta_tag) ? implode(',', $request->meta_tag) : '';
        $product->meta_descriptions = $request->meta_descriptions;

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            // $filename = time().uniqid().$thumbnail->getClientOriginalName();
            $filename = $thumbnail->getClientOriginalName();
            $thumbnail->move(public_path('uploads/product-images'), $filename);
            $product->thumbnail = $filename;
        }

        if ($request->hasFile('gallery')) {
            $gallerys = $request->file('gallery');
            $image_name = ($product->images != null) ? json_decode($product->images) : [];
            foreach ($gallerys as $image) {
                // $filename = time().uniqid().$image->getClientOriginalName();
                $filename = $image->getClientOriginalName();
                $image->move(public_path('uploads/product-images'), $filename);
                array_push($image_name, $filename);
            }
            $product->images =  json_encode($image_name);
        }

        if ($product->save()) {

            // language
            Helper::insertLanguage(Product::class, $product->id, Session::get('admin_language') ?? 'en', 'name', $request->name);
            Helper::insertLanguage(Product::class, $product->id, Session::get('admin_language') ?? 'en', 'key_features', $request->key_features);
            Helper::insertLanguage(Product::class, $product->id, Session::get('admin_language') ?? 'en', 'further_information', $request->further_information);

            if (isset($request->attributes_name) && count($request->attributes_name) > 0) {
                ProductAttribute::where('product_id', $product->id)->where('type', 'attributes')->delete();
                for ($i = 0; $i < count($request->attributes_name); $i++) {
                    if (!empty($request->attributes_name[$i])) {
                        $filterable_checkbox = explode(',', $request->filterable_checkbox);
                        $attribute = new ProductAttribute();
                        $attribute->product_id = $product->id;
                        $attribute->is_filter = ($filterable_checkbox[$i] == '1') ? 1 : 0;
                        $attribute->type = 'attributes';
                        $attribute->attribute_name = $request->attributes_name[$i];
                        $attribute->value = $request->attributes_value[$i];
                        $attribute->save();
                    }
                }
            }
            return response()->json([
                'type' => 'success',
                'message' => 'Product updated successfully.',
            ]);
        }
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if ($product) {
            if ($product->thumbnail != null && file_exists(public_path('uploads/product-images/' . $product->thumbnail))) {
                unlink(public_path('uploads/product-images/' . $product->thumbnail));
            }
            $product->delete();
            return json_encode(['success' => 'Product deleted successfully.']);
        } else {
            return json_encode(['error' => 'Product not found.']);
        }
    }

    public function bulkAction(Request $request)
    {
        if ($request->bulk_action == 'delete') {
            foreach ($request->select_all as $id) {
                $product = Product::find($id);
                if ($product) {
                    if ($product->thumbnail != null && file_exists(public_path('uploads/product-images/' . $product->thumbnail))) {
                        unlink(public_path('uploads/product-images/' . $product->thumbnail));
                    }
                    $product->delete();
                }
            }
            return json_encode(['success' => 'Product deleted successfully.']);
        } else {
            return json_encode(['error' => 'Something went wrong.']);
        }
    }

    public function custom_option($id)
    {
        $product = Product::find($id);
        $custom_fields = CustomField::where('status', 1)->get();
        $custom_options = ProductAttribute::where('product_id', $id)->where('type', "custom value")->where('ancestor_id', null)->get();
        return view('backend.pages.product.custom-options.custom-option', compact('product', 'custom_fields', 'custom_options'));
    }

    public function custom_sub_option($id)
    {
        $custom_sub_options = ProductAttribute::where('type', "custom value")->where('custom_field_id', $id)->where('ancestor_id', null)->groupBy('sub_option')->select('sub_option')->get();
        $html = '<option value="">-- Select --</option>';
        foreach ($custom_sub_options as $row) {
            $html .= '<option value="' . $row->sub_option . '">' . $row->sub_option . '</option>';
        }
        return $html ?? null;
    }

    public function generate_html_for_customoption(Request $request)
    {
        $product = Product::find($request->product_id);
        $custom_fields = CustomField::find($request->custom_field_id);
        $sub_option = $request->sub_option;

        $sub_option_id = ProductAttribute::where('product_id', $product->id)->where('type', 'custom value')->where('custom_field_id', $custom_fields->id)->where('sub_option', $sub_option)->where('ancestor_id', null)->first();

        $attributes = ProductAttribute::where('product_id', $product->id)->where('type', 'custom value')->where('custom_field_id', $custom_fields->id)->where('sub_option', $sub_option)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $sub_option_id->id ?? '')->get();


        return view('backend.pages.product.custom-options.more-features', compact('product', 'custom_fields', 'sub_option', 'attributes'));
    }

    public function update_custom_option(Request $request)
    {
        // return $request->all();
        // exit;

        $product = Product::find($request->id);
        if ($product) {
            if (isset($request->custom_option_name) && count($request->custom_option_name) > 0) {
                $ancestor_id = '';
                $sub_option = ProductAttribute::where('product_id', $request->id)->where('type', 'custom value')->where('custom_field_id', $request->custom_field_id)->where('sub_option', $request->sub_option)->where('ancestor_id', null)->first();
                if ($sub_option) {
                    $ancestor_id = $sub_option->id;
                } else {
                    $new_sub_option = new ProductAttribute();
                    $new_sub_option->product_id = $product->id;
                    $new_sub_option->type = 'custom value';
                    $new_sub_option->custom_field_id = $request->custom_field_id;
                    $new_sub_option->sub_option = $request->sub_option;
                    $new_sub_option->language_code = Session::get('admin_language') ?? 'en';
                    $new_sub_option->save();
                    $ancestor_id = $new_sub_option->id;
                }

                ProductAttribute::where('product_id', $request->id)->where('type', 'custom value')->where('custom_field_id', $request->custom_field_id)->where('sub_option', $request->sub_option)->where('language_code', Session::get('admin_language') ?? 'en')->whereNotNull('ancestor_id')->delete();
                $images = $request->file('custom_option_image');
                for ($i = 0; $i < count($request->custom_option_name); $i++) {
                    if (!empty($request->custom_option_name[$i]) || !empty($request->custom_option_image[$i]) || !empty($request->custom_option_value[$i]) || !empty($request->custom_option_details[$i])) {
                        $attribute = new ProductAttribute();
                        $attribute->product_id = $product->id;
                        $attribute->type = 'custom value';
                        $attribute->language_code = Session::get('admin_language') ?? 'en';
                        $attribute->custom_field_id = $request->custom_field_id;
                        $attribute->sub_option = $request->sub_option;
                        $attribute->title = $request->custom_option_name[$i];
                        $attribute->value = $request->custom_option_value[$i];
                        $attribute->details = $request->custom_option_details[$i];
                        $attribute->ancestor_id = $ancestor_id;
                        //image
                        if ($images) {
                            $filenames = '';
                            foreach ($images as $index => $image) {
                                if ($index == $i) {
                                    $filename = time() . uniqid() . '_' . $image->getClientOriginalName();
                                    $image->move(public_path('uploads/product-custom-files'), $filename);
                                    $filenames = $filename;
                                }
                            }
                            $attribute->image = ($filenames == '') ? $request->old_image[$i] : $filenames;
                        } else {
                            $attribute->image = $request->old_image[$i] ?? '';
                        }

                        $attribute->save();
                    }
                }
                return response()->json([
                    'type' => 'success',
                    'message' => 'Product custom options update successfully.',
                    'product_id' => $product->id,
                ]);
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Product not found.',
            ]);
        }
        // return $request->all();
    }

    public function custom_option_delete(Request $request)
    {
        $sub_option = ProductAttribute::find($request->sub_option);
        if ($sub_option) {
            ProductAttribute::where('product_id', $request->product_id)->where('type', 'custom value')->where('custom_field_id', $request->custom_field_id)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $sub_option->id)->delete();
            $sub_option->delete();
            return response()->json([
                'type' => 'success',
                'message' => 'Custom option deleted successfully.',
            ]);
        }
    }

    public function removeFile($model, $id, $file_name)
    {
        $modelClass = 'App\Models\\' . $model;
        $model = $modelClass::find($id);

        if ($model) {
            $images = json_decode($model->images);

            if (in_array($file_name, $images)) {
                $images = array_diff($images, [$file_name]);
                $model->images = json_encode(array_values($images));
                $model->save();
            }

            $filePath = '';

            if ($model instanceof \App\Models\Product) {
                $filePath = public_path('uploads/product-images/' . $file_name);
            } elseif ($model instanceof \App\Models\Part) {
                $filePath = public_path('uploads/part-images/' . $file_name);
            }

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            return response()->json([
                'type'    => 'success',
                'message' => 'Image deleted successfully.',
            ]);
        } else {
            return response()->json([
                'type'    => 'error',
                'message' => 'Something went wrong.',
            ]);
        }
    }
}