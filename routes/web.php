<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CatalogueController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\CustomFieldController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\InquiryController;
use App\Http\Controllers\Backend\NewsController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\PartController;
use App\Http\Controllers\Backend\PartnerController;
use App\Http\Controllers\Backend\PartnerProductController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ResourceController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\ServiceOrderController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\StockController;
use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\WholesaleCalculationController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/testemail', function () {
    $data = [
        'order_details' => [
            [
                "id" => 24,
                "order_id" => "64afcd9c0c55b-ordr-31292818476033451",
                "product_id" => "646b5f28a0232-pprt-33629368432606135",
                "reference_id" => "646b5f28a0232-pprt-33629368432606135",
                "type" => "part",
                "quantity" => 1,
                "unit_price" => "700.00",
                "company_id" => null,
                "status" => 0,
                "ancestor_id" => null,
                "created_at" => "2023-07-13T10:10:36.000000Z",
                "updated_at" => "2023-07-13T10:10:36.000000Z",
                "discount_type" => "percent",
                "discount" => 30,
                "subtotal" => 700,
                "product" => null,
            ],
            [
                "id" => 24,
                "order_id" => "64afcd9c0c55b-ordr-31292818476033451",
                "product_id" => "646b5f28a0232-pprt-33629368432606135",
                "reference_id" => "646b5f28a0232-pprt-33629368432606135",
                "type" => "part",
                "quantity" => 1,
                "unit_price" => "700.00",
                "company_id" => null,
                "status" => 0,
                "ancestor_id" => null,
                "created_at" => "2023-07-13T10:10:36.000000Z",
                "updated_at" => "2023-07-13T10:10:36.000000Z",
                "discount_type" => "percent",
                "discount" => 30,
                "subtotal" => 700,
                "product" => null,
            ]
        ],
        'order' => [
            'total_price' => 100
        ],
    ];
    return view('mails.orderinvoice', compact('data'));
});

Route::get('/store-cache', function () {
    try {
        // Run artisan config:cache command
        Artisan::call('config:cache');

        // Run artisan route:cache command
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        // Optionally, you can cache other things like views
        // Artisan::call('view:cache');

        // Return success message
        return 'Caches stored successfully.';
    } catch (\Exception $e) {
        // Return error message
        return 'Error storing caches: ' . $e->getMessage();
    }
});

Route::get('/clear-cash', function () {
    try {
        // Run artisan optimize:clear command
        Artisan::call('optimize:clear');

        // Return success message
        return redirect()->back();
        return 'Optimization cache cleared successfully.';
    } catch (\Exception $e) {
        // Return error message
        return 'Error clearing optimization cache: ' . $e->getMessage();
    }
});

Route::controller(FrontendController::class)->group(function () {
    Route::get('/', 'allProducts')->name('home');
    Route::get('/bot', 'bot')->name('bot');
    Route::get('data', 'data');
    Route::get('login', 'login')->name('login');
    Route::get('registration', 'registration');
    Route::get('about', 'about')->name('about-us');
    Route::get('contact', 'contact')->name('contact-us');
    Route::get('categories', 'categories')->name('categories');
    Route::get('category/{id}', 'subcategory');

    Route::get('brand/{id}', 'brandWiseProduct')->name('brand.products');

    Route::post('search-product-by-category', 'searchProductBycategory')->name('search.product.by.category');

    Route::get('products/{id}', 'products');
    Route::get('products', 'allProducts')->name('products');
    Route::post('search-products', 'searchProducts')->name('search.products');
    Route::get('product-suggest', 'productSuggest')->name('product.suggest');
    Route::get('product/{slug}', 'product_details')->name('product.details');
    Route::get('wishlist', 'wishlist')->name('wishlist');
    Route::get('catalogues', 'catalogues')->name('catalogues');
    Route::get('search-catalogue', 'searchCatalogue')->name('search.catalogue');
    Route::get('download-catalogue/{lang}/{catelogue_id}', 'downloadCatalogue')->name('download.catalogue');
    Route::get('catalogue-view/{catelogue_id}', 'viewCatalogue')->name('view.catalogue');

    Route::get('manual', 'manuals')->name('manuals');
    Route::get('forms', 'forms')->name('forms');
    Route::get('forms-details/{id}', 'formsDetails')->name('forms.details');
    Route::post('forms-submit', 'formsSubmit')->name('forms.submit');

    // parts
    Route::get('parts', 'allParts')->name('parts');
    Route::get('parts/{id}', 'partsDetails')->name('parts.details');
    Route::post('search-parts', 'searchParts')->name('search.parts');

    Route::get('news', 'news')->name('news');
    Route::get('news_details/{id}', 'newsDetails')->name('news.details');
    Route::post('search-news', 'searchNews')->name('search.news');

    // cart
    Route::match(['get', 'post'], 'add-to-cart/{type}/{id}', 'AddToCart')->name('add.to.cart');
    Route::get('remove-cart/{id}', 'removeFromCart')->name('remove.from.cart');
    Route::get('increment-cart/{id}', 'incrementCart')->name('increment.from.cart');
    Route::get('decrement-cart/{id}', 'decrementCart')->name('decrement.from.cart');
    Route::get('cart', 'cart')->name('cart');
    Route::get('getCart', 'getCartCount')->name('get.cart.count');


    Route::get('order', 'order')->name('order');
    Route::get('place-order', 'PlaceOrder')->name('place.order');
    Route::any('proxy-order', 'ProxyOrder')->name('proxy.order');
    Route::any('after-order', 'AfterOrder')->name('after.order');


    // error-handling-view
    Route::post('order-place', 'cashonOrder')->name('place.order.cashon');

    // wishlist
    Route::any('add-to-wishlist/{type}/{id}', 'AddTowishlist')->name('add.to.wishlist');
    Route::get('remove-wishlist/{id}', 'removeFromWishlist')->name('remove.from.wishlist');
    Route::get('increment-wishlist/{id}', 'incrementWishlist')->name('increment.from.wishlist');
    Route::get('decrement-wishlist/{id}', 'decrementWishlist')->name('decrement.from.wishlist');
    Route::get('get-wishlist-count/', 'getWishlistCount')->name('get.wishlist.count');


    // inquiry
    Route::get('add-to-inquiry/{product_id}', 'AddToInquiry')->name('add.to.inquiry');
    Route::get('remove-inquiry/{id}', 'removeFromInquirylist')->name('remove.from.inquiry');
    Route::get('increment-inquiry/{id}', 'incrementInquirylist')->name('increment.from.inquiry');
    Route::get('decrement-inquiry/{id}', 'decrementInquirylist')->name('decrement.from.inquiry');
    Route::get('inquiry', 'inquiry')->name('inquiry');
    Route::get('inquiry/request', 'inquiryRequest');
    Route::post('inquiry/request/send', 'inquiryRequestSend')->name('inquiry.request.send');

    // Service
    Route::get('services', 'services')->name('services');
    Route::get('service-details/{id}', 'serviceDetails')->name('service.details');
    Route::get('add-to-service/{service_id}', 'AddToService')->name('add.to.service');
    Route::get('service-order', 'serviceOrder')->name('service.order');
    Route::post('service-order/send', 'serviceOrderSend')->name('service.order.send');

    Route::get('pdf', 'pdf');
    Route::get('calculator', 'calculator');
    Route::get('reset-password', 'forgotPassword');
    Route::any('reset-otp-send', 'resetOtpSend');
    Route::any('change-password', 'otp');
    Route::post('contact/submit', 'contactPost');

    Route::get('page/{slug}', 'pages')->name('page');

    Route::get('change-language', 'changeLanguage')->name('change.language');

    Route::get('search', 'Search')->name('search');

    Route::get('brands/{brand_id}', 'Brands')->name('brands');

    Route::get('download/{fileName}', 'downloadFile')->name('download.file');
    // Route::any('{any}',[FrontendController::class,'catchAll'])->where('any', '.*');

    Route::get('review/us', 'reviewUs')->name('review.us');
});



// Auth route
Route::post('login-post', [LoginController::class, 'authenticate'])->name('login.post');
Route::post('signup', [LoginController::class, 'signup'])->name('registration.post');

// admin route start
Route::get('/admin', function () {
    if (Auth::check()) {
        if (Auth()->user()->role == 1) {
            return redirect()->route('admin.index');
        } else {
            return redirect()->route('home');
        }
    }
    return view('backend.auth.login');
})->name('admin');
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('profile', [LoginController::class, 'adminProfile'])->name('admin.profile');
    Route::post('profile/update', [LoginController::class, 'adminProfileUpdate'])->name('admin.profile.update');
    Route::get('profile/setting', [LoginController::class, 'adminProfileSetting'])->name('admin.profile.setting');
    Route::post('profile/change/password', [LoginController::class, 'adminChangePassword'])->name('admin.change.password');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.index');

    // Route::any('{any}',[FrontendController::class,'catchAll'])->where('any', '.*');

    Route::group(['prefix' => '/user'], function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.user');
        Route::get('/get/list', [UserController::class, 'getList']);
        Route::post('/store', [UserController::class, 'store'])->name('admin.user.store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('admin.user.edit');
        Route::any('/update/{id}', [UserController::class, 'update'])->name('admin.user.update');
        Route::get('/delete/{id}', [UserController::class, 'delete'])->name('admin.user.delete');
        Route::post('/change', [UserController::class, 'changePassword'])->name('admin.user.changepassword');
    });

    Route::group(['prefix' => '/role'], function () {
        Route::get('/generate/right/{mdule_name}', [RoleController::class, 'generate'])->name('admin.role.right.generate');

        Route::get('/', [RoleController::class, 'index'])->name('admin.role');
        Route::get('/get/role/list', [RoleController::class, 'getRoleList']);
        Route::get('/create', [RoleController::class, 'create'])->name('admin.role.create');
        Route::post('/store', [RoleController::class, 'store'])->name('admin.role.store');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('admin.role.edit');
        Route::any('/update/{id}', [RoleController::class, 'update'])->name('admin.role.update');
        Route::get('/delete/{id}', [RoleController::class, 'delete'])->name('admin.role.delete');

        Route::get('/right', [RoleController::class, 'right'])->name('admin.role.right');
        Route::get('/get/right/list', [RoleController::class, 'getRightList']);
        Route::post('/right/store', [RoleController::class, 'rightStore'])->name('admin.role.right.store');
        Route::get('/right/edit/{id}', [RoleController::class, 'editRight'])->name('admin.role.right.edit');
        Route::any('/right/update/{id}', [RoleController::class, 'roleRightUpdate'])->name('admin.role.right.update');
        Route::get('/right/delete/{id}', [RoleController::class, 'rightDelete'])->name('admin.role.right.delete');
    });

    Route::group(['prefix' => '/category'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('admin.category');
        Route::get('/get/list', [CategoryController::class, 'getList']);
        Route::post('/store', [CategoryController::class, 'store'])->name('admin.category.store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('admin.category.edit');
        Route::any('/update/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
        Route::get('/delete/{id}', [CategoryController::class, 'delete'])->name('admin.category.delete');
    });

    Route::group(['prefix' => '/brand'], function () {
        Route::get('/', [BrandController::class, 'index'])->name('admin.brand');
        Route::get('/get/list', [BrandController::class, 'getList']);
        Route::post('/store', [BrandController::class, 'store'])->name('admin.brand.store');
        Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('admin.brand.edit');
        Route::any('/update/{id}', [BrandController::class, 'update'])->name('admin.brand.update');
        Route::get('/delete/{id}', [BrandController::class, 'delete'])->name('admin.brand.delete');
    });

    // Add inside admin middleware group
    Route::group(['prefix' => '/stock'], function () {
        Route::get('/', [StockController::class, 'index'])->name('admin.stock');
        Route::get('/get/list', [StockController::class, 'getList'])->name('admin.stock.get.list');
        Route::post('/initialize', [StockController::class, 'initialize'])->name('admin.stock.initialize');
        Route::post('/adjust', [StockController::class, 'adjust'])->name('admin.stock.adjust');
        Route::get('/history/{productId}', [StockController::class, 'history'])->name('admin.stock.history');
    });

    Route::group(['prefix' => '/partner'], function () {
        Route::get('/', [PartnerController::class, 'index'])->name('admin.partner');
        Route::get('/get/list', [PartnerController::class, 'getList']);
        Route::post('/store', [PartnerController::class, 'store'])->name('admin.partner.store');
        Route::get('/edit/{id}', [PartnerController::class, 'edit'])->name('admin.partner.edit');
        Route::any('/update/{id}', [PartnerController::class, 'update'])->name('admin.partner.update');
        Route::get('/delete/{id}', [PartnerController::class, 'delete'])->name('admin.partner.delete');
        Route::get('/view/{id}', [PartnerController::class, 'view'])->name('admin.partner.view');

        Route::get('/status/{id}/{status}', [PartnerController::class, 'status'])->name('admin.partner.status');
    });

    Route::group(['prefix' => '/partner-product'], function () {
        Route::get('/', [PartnerProductController::class, 'index'])->name('admin.partner.product');
        Route::get('/get/list', [PartnerProductController::class, 'getList']);
        Route::post('/store', [PartnerProductController::class, 'store'])->name('admin.partner.product.store');
        Route::get('/edit/{id}', [PartnerProductController::class, 'edit'])->name('admin.partner.product.edit');
        Route::any('/update/{id}', [PartnerProductController::class, 'update'])->name('admin.partner.product.update');
        Route::get('/delete/{id}', [PartnerProductController::class, 'delete'])->name('admin.partner.product.delete');

        Route::get('/row/{number}', [PartnerProductController::class, 'row'])->name('admin.partner.product.row');
        Route::get('/get/partner/{id}', [PartnerProductController::class, 'getPartner']);
        Route::get('/get/subcategory/{id}', [PartnerProductController::class, 'getSubcategory']);
        Route::get('/get/product', [PartnerProductController::class, 'getProduct']);
    });

    Route::group(['prefix' => '/product'], function () {
        Route::get('/', [ProductController::class, 'index'])->name('admin.product');
        Route::get('/get/list', [ProductController::class, 'getList']);
        Route::get('/export/pdf', [ProductController::class, 'exportPdf'])->name('admin.product.export.pdf');
        Route::post('/store', [ProductController::class, 'store'])->name('admin.product.store');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('admin.product.edit');
        Route::any('/update/{id}', [ProductController::class, 'update'])->name('admin.product.update');
        Route::get('/delete/{id}', [ProductController::class, 'delete'])->name('admin.product.delete');
        Route::get('/view/{id}', [ProductController::class, 'view'])->name('admin.product.view');

        Route::get('/status/{id}/{status}', [ProductController::class, 'status'])->name('admin.product.status');

        Route::get('/custom-option/{id}', [ProductController::class, 'custom_option'])->name('admin.product.custom-option');
        Route::get('/custom-option/sub-option/{id}', [ProductController::class, 'custom_sub_option']);
        Route::get('/custom-option-generate-html', [ProductController::class, 'generate_html_for_customoption']);
        Route::any('/update-custom-option/{id}', [ProductController::class, 'update_custom_option'])->name('admin.product.update.custom.option');
        Route::get('/custom-option-delete', [ProductController::class, 'custom_option_delete']);

        Route::post('/bulk/action', [ProductController::class, 'bulkAction'])->name('admin.product.bulk.action');

        Route::get('remove/file/{model}/{id}/{file_name}', [ProductController::class, 'removeFile'])->name('admin.remove.file');

        Route::get('/get/subcategory', [ProductController::class, 'getSubcategory'])->name('admin.product.get.subcategory');
    });

    Route::group(['prefix' => '/wholesale-calculation', 'middleware' => ['auth']], function () {
        Route::get('/', [WholesaleCalculationController::class, 'index'])->name('admin.wholesale-calculation');
        Route::get('/get/list', [WholesaleCalculationController::class, 'getList']);
        Route::post('/store', [WholesaleCalculationController::class, 'store'])->name('admin.wholesale-calculation.store');
        Route::get('/edit/{id}', [WholesaleCalculationController::class, 'edit'])->name('admin.wholesale-calculation.edit');
        Route::any('/update/{id}', [WholesaleCalculationController::class, 'update'])->name('admin.wholesale-calculation.update');
        Route::get('/delete/{id}', [WholesaleCalculationController::class, 'delete'])->name('admin.wholesale-calculation.delete');
    });

    Route::group(['prefix' => '/custom-field'], function () {
        Route::get('/', [CustomFieldController::class, 'index'])->name('admin.product.custom.field');
        Route::post('/store', [CustomFieldController::class, 'store'])->name('admin.product.custom.store');
        Route::get('/edit/{id}', [CustomFieldController::class, 'edit'])->name('admin.product.custom.edit');
        Route::any('/update/{id}', [CustomFieldController::class, 'update'])->name('admin.product.custom.update');
        Route::get('/delete/{id}', [CustomFieldController::class, 'delete'])->name('admin.product.custom.delete');
    });

    Route::group(['prefix' => '/part'], function () {
        Route::get('/', [PartController::class, 'index'])->name('admin.part');
        Route::get('/get/list', [PartController::class, 'getList']);
        Route::post('/store', [PartController::class, 'store'])->name('admin.part.store');
        Route::get('/edit/{id}', [PartController::class, 'edit'])->name('admin.part.edit');
        Route::any('/update/{id}', [PartController::class, 'update'])->name('admin.part.update');
        Route::get('/delete/{id}', [PartController::class, 'delete'])->name('admin.part.delete');

        Route::get('/custom-option/{id}', [PartController::class, 'custom_option'])->name('admin.part.custom-option');
        Route::get('/custom-option/sub-option/{id}', [PartController::class, 'custom_sub_option']);
        Route::get('/custom-option-generate-html', [PartController::class, 'generate_html_for_customoption']);
        Route::any('/update-custom-option/{id}', [PartController::class, 'update_custom_option'])->name('admin.part.update.custom.option');
        Route::get('/custom-option-delete', [PartController::class, 'custom_option_delete']);
    });

    Route::group(['prefix' => '/service'], function () {
        Route::get('/', [ServiceController::class, 'index'])->name('admin.service');
        Route::get('/get/list', [ServiceController::class, 'getList']);
        Route::post('/store', [ServiceController::class, 'store'])->name('admin.service.store');
        Route::get('/edit/{id}', [ServiceController::class, 'edit'])->name('admin.service.edit');
        Route::any('/update/{id}', [ServiceController::class, 'update'])->name('admin.service.update');
        Route::get('/delete/{id}', [ServiceController::class, 'delete'])->name('admin.service.delete');
    });

    Route::group(['prefix' => '/order'], function () {
    Route::get('/', [OrderController::class, 'index'])->name('admin.order');
    Route::get('/get/list', [OrderController::class, 'getList']);
    Route::post('/store', [OrderController::class, 'store'])->name('admin.order.store');
    Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('admin.order.edit');
    Route::any('/update/{id}', [OrderController::class, 'update'])->name('admin.order.update');
    Route::get('/delete/{id}', [OrderController::class, 'delete'])->name('admin.order.delete');
    Route::get('/view/{id}', [OrderController::class, 'view'])->name('admin.order.view');
    Route::get('/row/{number}', [OrderController::class, 'row'])->name('admin.order.product.row');
    Route::get('/status/{id}/{status}', [OrderController::class, 'status'])->name('admin.order.status');

    Route::get('/get/company/{id}', [OrderController::class, 'getCompany']);
    Route::get('/get/product', [OrderController::class, 'getProduct']);

    Route::get('/edit/status/{id}', [OrderController::class, 'editStaus'])->name('admin.order.edit.status');
    Route::any('/update/status/{id}', [OrderController::class, 'updateStatus'])->name('admin.order.update.status');

    // Invoice Routes
    Route::get('/invoice/{id}', [OrderController::class, 'invoice'])->name('admin.order.invoice');
    Route::get('/invoice-pdf/{id}', [OrderController::class, 'invoicePdf'])->name('admin.order.invoice.pdf');
    Route::get('/invoice-view/{id}', [OrderController::class, 'invoiceView'])->name('admin.order.invoice.view');
});

    Route::group(['prefix' => '/transaction'], function () {
        Route::get('/', [TransactionController::class, 'index'])->name('admin.transaction');
        Route::get('/get/list', [TransactionController::class, 'getList']);
    });

    Route::group(['prefix' => '/inquiry'], function () {
        Route::get('/', [InquiryController::class, 'index'])->name('admin.inquiry');
        Route::get('/get/list', [InquiryController::class, 'getList']);
        Route::post('/store', [InquiryController::class, 'store'])->name('admin.inquiry.store');
        Route::get('/edit/{id}', [InquiryController::class, 'edit'])->name('admin.inquiry.edit');
        Route::any('/update/{id}', [InquiryController::class, 'update'])->name('admin.inquiry.update');
        Route::get('/delete/{id}', [InquiryController::class, 'delete'])->name('admin.inquiry.delete');
        Route::get('/view/{id}', [InquiryController::class, 'view'])->name('admin.inquiry.view');
        Route::get('/row/{number}', [InquiryController::class, 'row'])->name('admin.inquiry.product.row');
        Route::get('/status/{id}/{status}', [InquiryController::class, 'status'])->name('admin.inquiry.status');

        Route::any('/sendemail/{id}', [InquiryController::class, 'sendEmail'])->name('admin.inquiry.send.email');

        Route::get('/get/company/{id}', [InquiryController::class, 'getCompany']);
        Route::get('/get/product', [InquiryController::class, 'getProduct']);

        Route::get('/edit/status/{id}', [InquiryController::class, 'editStaus'])->name('admin.inquiry.edit.status');
        Route::any('/update/status/{id}', [InquiryController::class, 'updateStatus'])->name('admin.inquiry.update.status');
    });

    Route::group(['prefix' => '/service-order'], function () {
        Route::get('/', [ServiceOrderController::class, 'index'])->name('admin.service.order');
        Route::get('/get/list', [ServiceOrderController::class, 'getList']);
        Route::post('/store', [ServiceOrderController::class, 'store'])->name('admin.service.order.store');
        Route::get('/edit/{id}', [ServiceOrderController::class, 'edit'])->name('admin.service.order.edit');
        Route::any('/update/{id}', [ServiceOrderController::class, 'update'])->name('admin.service.order.update');
        Route::get('/delete/{id}', [ServiceOrderController::class, 'delete'])->name('admin.service.order.delete');
        Route::get('/view/{id}', [ServiceOrderController::class, 'view'])->name('admin.service.order.view');
        Route::get('/row/{number}', [ServiceOrderController::class, 'row'])->name('admin.service.order.row');

        Route::get('/edit/status/{id}', [ServiceOrderController::class, 'editStaus'])->name('admin.service.order.edit.status');
        Route::any('/update/status/{id}', [ServiceOrderController::class, 'updateStatus'])->name('admin.service.order.update.status');
    });

    Route::group(['prefix' => '/news'], function () {
        Route::get('/', [NewsController::class, 'index'])->name('admin.news');
        Route::get('/get/list', [NewsController::class, 'getList']);
        Route::post('/store', [NewsController::class, 'store'])->name('admin.news.store');
        Route::get('/edit/{id}', [NewsController::class, 'edit'])->name('admin.news.edit');
        Route::any('/update/{id}', [NewsController::class, 'update'])->name('admin.news.update');
        Route::get('/delete/{id}', [NewsController::class, 'delete'])->name('admin.news.delete');
    });

    Route::group(['prefix' => '/catalogue'], function () {
        Route::get('/', [CatalogueController::class, 'index'])->name('admin.catalogue');
        Route::get('/get/list', [CatalogueController::class, 'getList']);
        Route::post('/store', [CatalogueController::class, 'store'])->name('admin.catalogue.store');
        Route::get('/edit/{id}', [CatalogueController::class, 'edit'])->name('admin.catalogue.edit');
        Route::any('/update/{id}', [CatalogueController::class, 'update'])->name('admin.catalogue.update');
        Route::get('/delete/{id}', [CatalogueController::class, 'delete'])->name('admin.catalogue.delete');
        Route::get('/local-delete/{id}', [CatalogueController::class, 'localDelete'])->name('admin.catalogue.local.delete');
    });

    Route::group(['prefix' => '/resource'], function () {
        Route::get('/', [ResourceController::class, 'index'])->name('admin.resource');
        Route::get('/get/list', [ResourceController::class, 'getList']);
        Route::post('/store', [ResourceController::class, 'store'])->name('admin.resource.store');
        Route::get('/edit/{id}', [ResourceController::class, 'edit'])->name('admin.resource.edit');
        Route::any('/update/{id}', [ResourceController::class, 'update'])->name('admin.resource.update');
        Route::get('/delete/{id}', [ResourceController::class, 'delete'])->name('admin.resource.delete');
    });

    Route::group(['prefix' => '/contact'], function () {
        Route::get('/', [ContactController::class, 'index'])->name('admin.contact');
        Route::get('/get/list', [ContactController::class, 'getList']);
        Route::post('/store', [ContactController::class, 'store'])->name('admin.contact.store');
        Route::get('/edit/{id}', [ContactController::class, 'edit'])->name('admin.contact.edit');
        Route::any('/update/{id}', [ContactController::class, 'update'])->name('admin.contact.update');
        Route::get('/delete/{id}', [ContactController::class, 'delete'])->name('admin.contact.delete');
    });

    Route::group(['prefix' => '/setting'], function () {
        Route::get('/general', [SettingController::class, 'general'])->name('admin.setting.general');
        Route::get('/static-content', [SettingController::class, 'staticContent'])->name('admin.setting.static.content');
        Route::get('/legal-content', [SettingController::class, 'legalContent'])->name('admin.setting.legal.content');
        Route::post('/update', [SettingController::class, 'update'])->name('admin.setting.update');

        Route::get('/change-language', [SettingController::class, 'changeLanguage'])->name('admin.setting.change.language');
        Route::get('/reorder', [SettingController::class, 'reorder'])->name('admin.setting.reorder');
        Route::post('/reorder/post', [SettingController::class, 'reorderPost'])->name('admin.setting.reorder.post');
    });
});

Route::get('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');


// admin route end
