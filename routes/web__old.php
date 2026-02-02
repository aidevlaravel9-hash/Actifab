<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\OfferController;

use App\Http\Controllers\InquiryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\MetaDataController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OtherPagesController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\VideoController;
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

// Route::fallback(function () {
//     return view('errors.404'); // Make sure the view path matches your custom 404 page
// });

Route::get('login', fn() => redirect()->route('admin.login'))->name('login');

Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminLoginController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin-login', [AdminLoginController::class, 'adminLogin'])->name('admin.login.post');
    Route::get('/admin-logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    return 'Cache is cleared';
});

// Profile Routes
Route::prefix('profile')->name('profile.')->middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::get('/edit', [HomeController::class, 'EditProfile'])->name('EditProfile');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
});

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Roles
Route::resource('roles', App\Http\Controllers\RolesController::class);

// Permissions
Route::resource('permissions', App\Http\Controllers\PermissionsController::class);

// Users
Route::middleware('auth')->prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{id?}', [UserController::class, 'edit'])->name('edit');
    Route::post('/update/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/delete/{user}', [UserController::class, 'delete'])->name('destroy');
    Route::get('/update/status/{user_id}/{status}', [UserController::class, 'updateStatus'])->name('status');
    Route::post('/password-update/{Id?}', [UserController::class, 'passwordupdate'])->name('passwordupdate');
    Route::get('/import-users', [UserController::class, 'importUsers'])->name('import');
    Route::post('/upload-users', [UserController::class, 'uploadUsers'])->name('upload');
    Route::get('export/', [UserController::class, 'export'])->name('export');
});

//Category Master
Route::prefix('admin')->name('category.')->middleware('auth')->group(function () {
    Route::get('/category/index', [CategoryController::class, 'index'])->name('index');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('store');
    Route::get('/category/edit/{id?}', [CategoryController::class, 'editview'])->name('edit');
    Route::post('/category/update/', [CategoryController::class, 'update'])->name('update');
    Route::delete('/category/delete', [CategoryController::class, 'delete'])->name('delete');

    Route::get('/category/chk/unique/{id?}', [CategoryController::class, 'chk_unique'])->name('chk_unique');
    Route::get('/category/chkunique/edit/{id?}', [CategoryController::class, 'editchk_unique'])->name('editchk_unique');

    Route::get('/category/update/status/{category_id}/{status}', [CategoryController::class, 'updateStatus'])->name('status');
});

//Product Master
Route::prefix('admin')->name('product.')->middleware('auth')->group(function () {
    Route::any('/product/index', [ProductController::class, 'index'])->name('index');
    Route::get('/product/create', [ProductController::class, 'createview'])->name('create');
    Route::post('/product/store', [ProductController::class, 'create'])->name('store');
    Route::get('/product/edit/{id?}', [ProductController::class, 'editview'])->name('edit');
    Route::post('/product/update/{id?}', [ProductController::class, 'update'])->name('update');
    Route::delete('/product/delete', [ProductController::class, 'delete'])->name('delete');

    Route::get('/product/getsubcategory', [ProductController::class, 'getsubcategory'])->name('getsubcategory');
    Route::get('/product/getGST', [ProductController::class, 'getGST'])->name('getGST');
    Route::get('/product/geteditsubcategory', [ProductController::class, 'getEditsubcategory'])->name('getEditsubcategory');
    Route::POST('/product/GetSelectedSubCategory/{Category?}/{SubCategory?}', [ProductController::class, 'GetSelectedSubCategory'])->name('GetSelectedSubCategory');
    Route::post('productimage-delete/{id}', [ProductController::class, 'productimage'])->name('imagedelete');

    Route::get('/product/photos/{id?}', [ProductController::class, 'productphotos'])->name('productphotos');
    Route::delete('/product/photos/delete/{id?}', [ProductController::class, 'productphotosdelete'])->name('productphotosdelete');

    Route::get('/product/attribute/index/{id?}', [ProductAttributeController::class, 'product_attribute'])->name('product_attribute');
    Route::post('/product/attribute', [ProductAttributeController::class, 'product_attribute_store'])->name('product_attribute_store');
    Route::get('/product/attribute/edit/{id?}', [ProductAttributeController::class, 'product_attribute_editview'])->name('product_attribute_editview');
    Route::post('/product/attribute/update', [ProductAttributeController::class, 'product_attribute_update'])->name('product_attribute_update');
    Route::delete('/product/attribute/delete', [ProductAttributeController::class, 'product_attribute_delete'])->name('product_attribute_delete');

    //Inward
    Route::get('/product/inward/index/{id?}', [ProductController::class, 'product_inward'])->name('product_inward');
    Route::post('/product/inward', [ProductController::class, 'product_inward_store'])->name('product_inward_store');
    Route::delete('/product/inward/delete', [ProductController::class, 'product_inward_delete'])->name('product_inward_delete');

    Route::get('/product/update/status/{product_id}/{status}', [ProductController::class, 'updateStatus'])->name('status');

    Route::get('/product/Inward/data/{id?}', [ProductController::class, 'inwardData'])->name('inwardData');
});

//Offer Master
Route::prefix('admin')->name('offer.')->middleware('auth')->group(function () {
    Route::get('/offer/index', [OfferController::class, 'index'])->name('index');
    Route::get('/offer/create', [OfferController::class, 'create'])->name('create');
    Route::post('/offer/store', [OfferController::class, 'store'])->name('store');
    Route::get('/offer/edit/{id?}', [OfferController::class, 'editview'])->name('edit');
    Route::post('/offer/update', [OfferController::class, 'update'])->name('update');
    Route::delete('/offer/delete', [OfferController::class, 'delete'])->name('delete');
});

//Courier Master
Route::prefix('admin')->name('courier.')->middleware('auth')->group(function () {
    Route::get('/courier/index', [CourierController::class, 'index'])->name('index');
    Route::post('/courier/store', [CourierController::class, 'create'])->name('store');
    Route::get('/courier/edit/{id?}', [CourierController::class, 'editview'])->name('edit');
    Route::post('/courier/update', [CourierController::class, 'update'])->name('update');
    Route::delete('/courier/delete', [CourierController::class, 'delete'])->name('delete');

    Route::get('courier/validate', [CourierController::class, 'validatename'])->name('validatename');
    Route::get('courier/Edit/validate', [CourierController::class, 'validateeditname'])->name('validateeditname');
});

//Faq Master
Route::prefix('admin')->name('faq.')->middleware('auth')->group(function () {
    Route::get('/faq/index', [FaqController::class, 'index'])->name('index');
    Route::post('/faq/store', [FaqController::class, 'create'])->name('store');
    Route::get('/faq/edit/{id?}', [FaqController::class, 'editview'])->name('edit');
    Route::post('/faq/update', [FaqController::class, 'update'])->name('update');
    Route::delete('/faq/delete', [FaqController::class, 'delete'])->name('delete');
});

//Testimonial Master
Route::prefix('admin')->name('testimonial.')->middleware('auth')->group(function () {
    Route::get('/testimonial/index', [TestimonialController::class, 'index'])->name('index');
    Route::post('/testimonial/store', [TestimonialController::class, 'create'])->name('store');
    Route::get('/testimonial/edit/{id?}', [TestimonialController::class, 'editview'])->name('edit');
    Route::post('/testimonial/update', [TestimonialController::class, 'update'])->name('update');
    Route::delete('/testimonial/delete', [TestimonialController::class, 'delete'])->name('delete');
});

//Shipping Master
Route::prefix('admin')->name('shipping.')->middleware('auth')->group(function () {
    Route::get('/shipping/index', [ShippingController::class, 'index'])->name('index');
    Route::get('/shipping/edit/{id?}', [ShippingController::class, 'editview'])->name('edit');
    Route::post('/shipping/update', [ShippingController::class, 'update'])->name('update');
});

//inquiry
Route::prefix('admin')->name('Inquiry.')->middleware('auth')->group(function () {
    Route::get('Inquiry/index', [InquiryController::class, 'index'])->name('index');
    Route::delete('/Inquiry-delete', [InquiryController::class, 'delete'])->name('delete');
    Route::get('Inquiry/view/detail/{id?}', [InquiryController::class, 'viewdetail'])->name('viewdetail');
});

Route::prefix('admin')->name('metaData.')->middleware('auth')->group(function () {
    Route::get('/seo/index', [MetaDataController::class, 'index'])->name('index');
    Route::get('seo/{id}/edit', [MetaDataController::class, 'edit'])->name('edit');
    Route::put('seo/{id}', [MetaDataController::class, 'update'])->name('update');
});

//Reports
Route::prefix('admin')->name('report.')->middleware('auth')->group(function () {
    Route::any('/Payment/Report', [ReportController::class, 'paymentReport'])->name('paymentReport');
    Route::any('Order/Tracking/', [ReportController::class, 'orderTracking'])->name('orderTracking');

    Route::any('Search/Customer/', [ReportController::class, 'searchCustomer'])->name('searchCustomer');
});

//Order Master
Route::prefix('admin')->name('order.')->middleware('auth')->group(function () {
    Route::any('/order/pending', [OrderController::class, 'pending'])->name('pending');
    Route::any('/user/order/pending', [OrderController::class, 'userpending'])->name('userpending');
    Route::any('/order/dispatched', [OrderController::class, 'dispatched'])->name('dispatched');
    Route::any('/order/cancel', [OrderController::class, 'cancel'])->name('cancel');

    Route::get('/order/to/cancel/{id?}', [OrderController::class, 'statustocancel'])->name('statustocancel');
    Route::post('/order/to/dispatch/{id?}', [OrderController::class, 'statustodispatched'])->name('statustodispatched');
    Route::get('/order/to/pending/{id?}', [OrderController::class, 'statustopending'])->name('statustopending');

    Route::get('/order/detail/{id?}', [OrderController::class, 'orderdetail'])->name('orderdetail');

    Route::get('/order/pdf/{id?}', [OrderController::class, 'DetailPDF'])->name('DetailPDF');

    Route::get('/order/dispatch/pdf/{id?}', [OrderController::class, 'DispatchPDF'])->name('DispatchPDF');

    Route::get('/Tirupati/{id?}', [OrderController::class, 'tirupati'])->name('tirupati');

    Route::get('/Delivery/{id?}', [OrderController::class, 'delivery'])->name('delivery');

    Route::any('/order/moved/to/courier', [OrderController::class, 'orderMovedToCourier'])->name('orderMovedToCourier');

    Route::any('/payment/pending/order', [OrderController::class, 'pendingOrder'])->name('pendingOrder');

    Route::get('/success/order', [OrderController::class, 'successOrder'])->name('successOrder');

    Route::get('/link/send/customer/{id?}', [OrderController::class, 'linkSendToCustomer'])->name('linkSendToCustomer');

    Route::post('/dispatch/through/payment/pending/order/{id?}', [OrderController::class, 'dispatchThroughPaymentPendingOrder'])->name('dispatchThroughPaymentPendingOrder');
});

//Attribute
Route::prefix('admin')->name('attribute.')->middleware('auth')->group(function () {
    Route::get('/attribute/index', [AttributeController::class, 'index'])->name('index');
    Route::post('/attribute/store', [AttributeController::class, 'create'])->name('store');
    Route::get('/attribute/edit/{id?}', [AttributeController::class, 'editview'])->name('edit');
    Route::post('/attribute/update', [AttributeController::class, 'update'])->name('update');
    Route::delete('/attribute/delete', [AttributeController::class, 'delete'])->name('delete');
});

//Setting
Route::prefix('admin')->name('setting.')->middleware('auth')->group(function () {
    Route::get('/setting/index', [SettingController::class, 'index'])->name('index');
    Route::post('/setting/store', [SettingController::class, 'create'])->name('store');
    Route::get('/setting/edit/{id?}', [SettingController::class, 'editview'])->name('edit');
    Route::post('/setting/update', [SettingController::class, 'update'])->name('update');
    Route::delete('/setting/delete', [SettingController::class, 'delete'])->name('delete');
});

//Banner
Route::prefix('admin')->name('banner.')->middleware('auth')->group(function () {
    Route::get('/banner/index', [BannerController::class, 'index'])->name('index');
    Route::post('/banner/store', [BannerController::class, 'store'])->name('store');
    Route::delete('/banner/delete', [BannerController::class, 'delete'])->name('delete');
});

//Tearm & Condition
Route::prefix('admin')->name('otherpages.')->middleware('auth')->group(function () {
    Route::get('/otherpages/index', [OtherPagesController::class, 'index'])->name('index');
    Route::post('/otherpages/store', [OtherPagesController::class, 'create'])->name('store');
    Route::get('/otherpages/edit/{id?}', [OtherPagesController::class, 'editview'])->name('edit');
    Route::post('/otherpages/update', [OtherPagesController::class, 'update'])->name('update');
    Route::delete('/otherpages/delete', [OtherPagesController::class, 'delete'])->name('delete');
    Route::get('/otherpages/view/detail/{id?}', [OtherPagesController::class, 'viewdetail'])->name('viewdetail');
});


//Video
Route::prefix('admin')->name('video.')->middleware('auth')->group(function () {
    Route::get('/video/index', [VideoController::class, 'index'])->name('index');
    Route::get('/video/edit/{id?}', [VideoController::class, 'edit'])->name('edit');
    Route::post('/video/update', [VideoController::class, 'update'])->name('update');
});

//Blog Category Master
Route::prefix('admin')->name('blog_category.')->middleware('auth')->group(function () {
    Route::any('/blog/category/index', [BlogCategoryController::class, 'index'])->name('index');
    Route::get('/blog/category/create', [BlogCategoryController::class, 'createview'])->name('create');
    Route::post('/blog/category/store', [BlogCategoryController::class, 'store'])->name('store');
    Route::get('/blog/category/edit/{id?}', [BlogCategoryController::class, 'editview'])->name('edit');
    Route::put('/blog/category/update/{id?}', [BlogCategoryController::class, 'update'])->name('update');
    Route::delete('/blog/category/delete/{id?}', [BlogCategoryController::class, 'delete'])->name('delete');
});

//Blog Master
Route::prefix('admin')->name('blog.')->middleware('auth')->group(function () {
    Route::any('/blog/index', [BlogController::class, 'index'])->name('index');
    Route::get('/blog/create', [BlogController::class, 'createview'])->name('create');
    Route::post('/blog/store', [BlogController::class, 'store'])->name('store');
    Route::get('/blog/edit/{id?}', [BlogController::class, 'editview'])->name('edit');
    Route::post('/blog/update/{id?}', [BlogController::class, 'update'])->name('update');
    Route::delete('/blog/delete', [BlogController::class, 'delete'])->name('delete');
});

//Testimonial Master
Route::prefix('admin')->name('testimonial.')->middleware('auth')->group(function () {
    Route::get('/testimonial/index', [TestimonialController::class, 'index'])->name('index');
    Route::get('/testimonial/create', [TestimonialController::class, 'create'])->name('create');
    Route::post('/testimonial/store', [TestimonialController::class, 'store'])->name('store');
    Route::get('/testimonial/edit/{id?}', [TestimonialController::class, 'editview'])->name('edit');
    Route::post('/testimonial/update', [TestimonialController::class, 'update'])->name('update');
    Route::delete('/testimonial/delete', [TestimonialController::class, 'delete'])->name('delete');
});




//=======================================Front Start=============================================

Route::any('/', [FrontController::class, 'index'])->name('front.index');
Route::any('/about', [FrontController::class, 'about'])->name('front.about');

Route::get('/blog', [FrontController::class, 'blog'])->name('front.blog');
Route::get('/blog/{slugname?}', [FrontController::class, 'blog_detail'])->name('front.blog_detail');

Route::get('/contact-us', [FrontController::class, 'contactus'])->name('front.contact_us');
Route::post('/contact-us', [FrontController::class, 'contact_us_store'])->name('front.contact_us_store');
Route::get('refresh_captcha', [FrontController::class, 'refreshCaptcha'])->name('refresh_captcha');


//===================================Cart routes start============================
Route::get('cart', [CartController::class, 'cartList'])->name('cart.list');
Route::post('cart', [CartController::class, 'addToCart'])->name('cart.store');
Route::post('update-cart', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('remove', [CartController::class, 'removeCart'])->name('cart.remove');
Route::post('clear', [CartController::class, 'clearAllCart'])->name('cart.clear');
Route::post('/coupon', [FrontController::class, 'couponcodeapply'])->name('couponcodeapply');
Route::post('/remove-coupon', [FrontController::class, 'removeCoupon'])->name('couponcoderemove');
//===================================Cart routes end============================


//===============================Check-Out start=============================
Route::get('checkout', [FrontController::class, 'checkout'])->name('front.checkout');
Route::post('checkout/store', [FrontController::class, 'checkoutstore'])->name('checkoutstore');
Route::get('/get/user/data', [FrontController::class, 'get_userdata'])->name('front.get_userdata');
//===============================Check-Out end=============================


//=================================LOGIN==================================================
//login
Route::get('/Login', [FrontController::class, 'frontlogin'])->name('front.login');
Route::post('login/store', [FrontController::class, 'frontloginstore'])->name('front.login_store');

//OTP
Route::get('otp/{guid?}', [FrontController::class, 'otp'])->name('front.otp');
Route::any('otp/submit', [FrontController::class, 'verifyOtp'])->name('front.otp_store');
Route::get('/resend-otp', [FrontController::class, 'resendOtp'])->name('front.resend_otp');


//Log-Out
Route::get('front/logout', [FrontController::class, 'Frontlogout'])->name('front.logout');


//==========================after login tab view start=============================
//personal information
Route::get('My-Account', [FrontController::class, 'myaccount'])->name('myaccount');
Route::post('My-Account', [FrontController::class, 'myaccountedit'])->name('myaccountedit');

Route::get('Profile', [FrontController::class, 'profile'])->name('front.profile');

//my orders
Route::get('My-Order', [FrontController::class, 'myorders'])->name('front.myorders');
//my orders detail
Route::get('My-Order/detail/{id}', [FrontController::class, 'myordersdetails'])->name('myordersdetails');

//customer order
Route::get('Order/{ORDER_ID?}/{guid?}', [FrontController::class, 'customerorder'])->name('customerorder');

//My-WishList page
Route::get('My-WishList', [FrontController::class, 'mywish_list'])->name('front.mywishlist');
//add to My-WishList
Route::any('WishList', [FrontController::class, 'wishlist_store'])->name('wishlist.store');
Route::any('wish-list/delete', [FrontController::class, 'wishlist_delete'])->name('wishlist.delete');
//==========================after login tab view end=============================

//privacy policy
Route::get('Cancellation-and-Refund', [FrontController::class, 'CancellationandRefund'])->name('CancellationandRefund');
Route::get('Shipping-and-Delivery', [FrontController::class, 'ShippingandDelivery'])->name('ShippingandDelivery');
Route::get('Term-&-Condition', [FrontController::class, 'termandcondition'])->name('termandcondition');
Route::get('Privacy-Policy', [FrontController::class, 'privacypolicy'])->name('privacypolicy');
Route::get('No-Return/No-Exchange', [FrontController::class, 'noReturnNoExchange'])->name('noReturnNoExchange');


Route::get('/weight-bind', [FrontController::class, 'weightBind'])->name('productweight.weightBind');

Route::get('/Page-Not-Available', [FrontController::class, 'ordernotavailable'])->name('ordernotavailable');


Route::get('/Thank-You', [FrontController::class, 'contactthankyou'])->name('contactthankyou');


//payment
Route::get('card-payment/{id}', [RazorpayController::class, 'index'])->name('razorpay.index')->where(['id' => '[0-9]+']);
Route::post('paysuccess', [RazorpayController::class, 'razorPaySuccess'])->name('razprpay.success');
Route::get('payment/success/{id?}', [RazorpayController::class, 'payment_success'])->name('razorpay.thankyou');
Route::any('payment/cancel/by/user', [RazorpayController::class, 'payment_cancel_by_user'])->name('razorpay.payment_cancel_by_user');
Route::get('payment/fail', [RazorpayController::class, 'RazorFail'])->name('razorpay.RazorFail');
Route::get('thank-you', [RazorpayController::class, 'thank_you'])->name('razorpay.thank_you');


//product listing
Route::get('/{categoryid?}', [FrontController::class, 'product_list'])->name('front.product_list');
//product detail
Route::get('/product/{category_id?}/{product_id?}', [FrontController::class, 'product_detail'])->name('front.product_detail');

//cms pages
Route::get('/page/{slugname?}', [FrontController::class, 'cms_pages'])->name('cms_pages');
