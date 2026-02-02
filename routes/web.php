<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;



use App\Http\Controllers\Admin\NoOfPolesController;

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


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('feeder-category', [App\Http\Controllers\Admin\FeederCategoryController::class, 'index'])->name('feeder-category.index');
    Route::post('feeder-category/store', [App\Http\Controllers\Admin\FeederCategoryController::class, 'store'])->name('feeder-category.store');
    Route::post('feeder-category/update', [App\Http\Controllers\Admin\FeederCategoryController::class, 'update'])->name('feeder-category.update');
    Route::post('feeder-category/delete', [App\Http\Controllers\Admin\FeederCategoryController::class, 'destroy'])->name('feeder-category.destroy');
    Route::post('feeder-category/bulk-delete', [App\Http\Controllers\Admin\FeederCategoryController::class, 'bulkDelete'])->name('feeder-category.bulkDelete');
    Route::post('feeder-category/status', [App\Http\Controllers\Admin\FeederCategoryController::class, 'status'])->name('feeder-category.status');
    Route::get('feeder-category/edit/{id}', [App\Http\Controllers\Admin\FeederCategoryController::class, 'edit'])->name('feeder-category.edit');
});


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('feeder-type', [App\Http\Controllers\Admin\FeederTypeController::class, 'index'])->name('feeder-type.index');
    Route::post('feeder-type/store', [App\Http\Controllers\Admin\FeederTypeController::class, 'store'])->name('feeder-type.store');
    Route::post('feeder-type/update', [App\Http\Controllers\Admin\FeederTypeController::class, 'update'])->name('feeder-type.update');
    Route::post('feeder-type/delete', [App\Http\Controllers\Admin\FeederTypeController::class, 'destroy'])->name('feeder-type.destroy');
    Route::post('feeder-type/bulk-delete', [App\Http\Controllers\Admin\FeederTypeController::class, 'bulkDelete'])->name('feeder-type.bulkDelete');
    Route::post('feeder-type/status', [App\Http\Controllers\Admin\FeederTypeController::class, 'status'])->name('feeder-type.status');
    Route::get('feeder-type/edit/{id}', [App\Http\Controllers\Admin\FeederTypeController::class, 'edit'])->name('feeder-type.edit');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('feeder-sub-type', [App\Http\Controllers\Admin\FeederSubTypeController::class, 'index'])->name('feeder-sub-type.index');
    Route::post('feeder-sub-type/store', [App\Http\Controllers\Admin\FeederSubTypeController::class, 'store'])->name('feeder-sub-type.store');
    Route::post('feeder-sub-type/update', [App\Http\Controllers\Admin\FeederSubTypeController::class, 'update'])->name('feeder-sub-type.update');
    Route::post('feeder-sub-type/delete', [App\Http\Controllers\Admin\FeederSubTypeController::class, 'destroy'])->name('feeder-sub-type.destroy');
    Route::post('feeder-sub-type/bulk-delete', [App\Http\Controllers\Admin\FeederSubTypeController::class, 'bulkDelete'])->name('feeder-sub-type.bulkDelete');
    Route::post('feeder-sub-type/status', [App\Http\Controllers\Admin\FeederSubTypeController::class, 'status'])->name('feeder-sub-type.status');
    Route::get('feeder-sub-type/edit/{id}', [App\Http\Controllers\Admin\FeederSubTypeController::class, 'edit'])->name('feeder-sub-type.edit');
});


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('panel-type', [App\Http\Controllers\Admin\PanelTypeController::class, 'index'])->name('panel-type.index');
    Route::post('panel-type/store', [App\Http\Controllers\Admin\PanelTypeController::class, 'store'])->name('panel-type.store');
    Route::post('panel-type/update', [App\Http\Controllers\Admin\PanelTypeController::class, 'update'])->name('panel-type.update');
    Route::post('panel-type/delete', [App\Http\Controllers\Admin\PanelTypeController::class, 'destroy'])->name('panel-type.destroy');
    Route::post('panel-type/bulk-delete', [App\Http\Controllers\Admin\PanelTypeController::class, 'bulkDelete'])->name('panel-type.bulkDelete');
    Route::post('panel-type/status', [App\Http\Controllers\Admin\PanelTypeController::class, 'status'])->name('panel-type.status');
    Route::get('panel-type/edit/{id}', [App\Http\Controllers\Admin\PanelTypeController::class, 'edit'])->name('panel-type.edit');
});


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('parts-category', [App\Http\Controllers\Admin\PartsCategoryController::class, 'index'])->name('parts-category.index');
    Route::post('parts-category/store', [App\Http\Controllers\Admin\PartsCategoryController::class, 'store'])->name('parts-category.store');
    Route::post('parts-category/update', [App\Http\Controllers\Admin\PartsCategoryController::class, 'update'])->name('parts-category.update');
    Route::post('parts-category/delete', [App\Http\Controllers\Admin\PartsCategoryController::class, 'destroy'])->name('parts-category.destroy');
    Route::post('parts-category/bulk-delete', [App\Http\Controllers\Admin\PartsCategoryController::class, 'bulkDelete'])->name('parts-category.bulkDelete');
    Route::post('parts-category/status', [App\Http\Controllers\Admin\PartsCategoryController::class, 'status'])->name('parts-category.status');
    Route::get('parts-category/edit/{id}', [App\Http\Controllers\Admin\PartsCategoryController::class, 'edit'])->name('parts-category.edit');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('parts-master', [App\Http\Controllers\Admin\PartsMasterController::class, 'index'])->name('parts-master.index');
    Route::post('parts-master/store', [App\Http\Controllers\Admin\PartsMasterController::class, 'store'])->name('parts-master.store');
    Route::post('parts-master/update', [App\Http\Controllers\Admin\PartsMasterController::class, 'update'])->name('parts-master.update');
    Route::post('parts-master/delete', [App\Http\Controllers\Admin\PartsMasterController::class, 'destroy'])->name('parts-master.destroy');
    Route::post('parts-master/bulk-delete', [App\Http\Controllers\Admin\PartsMasterController::class, 'bulkDelete'])->name('parts-master.bulkDelete');
    Route::post('parts-master/status', [App\Http\Controllers\Admin\PartsMasterController::class, 'status'])->name('parts-master.status');
    Route::get('parts-master/edit/{id}', [App\Http\Controllers\Admin\PartsMasterController::class, 'edit'])->name('parts-master.edit');
});


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('project', [App\Http\Controllers\Admin\ProjectController::class, 'index'])->name('project.index');
    Route::get('project/add', [App\Http\Controllers\Admin\ProjectController::class, 'create'])->name('project.create');
    Route::get('project/edit/{id}', [App\Http\Controllers\Admin\ProjectController::class, 'edit'])->name('project.edit');
    Route::post('project/store', [App\Http\Controllers\Admin\ProjectController::class, 'store'])->name('project.store');
    Route::post('project/update', [App\Http\Controllers\Admin\ProjectController::class, 'update'])->name('project.update');
    Route::post('project/delete', [App\Http\Controllers\Admin\ProjectController::class, 'destroy'])->name('project.destroy');
    Route::post('project/bulk-delete', [App\Http\Controllers\Admin\ProjectController::class, 'bulkDelete'])->name('project.bulkDelete');
    Route::post('project/status', [App\Http\Controllers\Admin\ProjectController::class, 'status'])->name('project.status');
});


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('system-rating', [App\Http\Controllers\Admin\SystemRatingController::class, 'index'])->name('system-rating.index');
    Route::post('system-rating/store', [App\Http\Controllers\Admin\SystemRatingController::class, 'store'])->name('system-rating.store');
    Route::post('system-rating/update', [App\Http\Controllers\Admin\SystemRatingController::class, 'update'])->name('system-rating.update');
    Route::post('system-rating/delete', [App\Http\Controllers\Admin\SystemRatingController::class, 'destroy'])->name('system-rating.delete');
    Route::post('system-rating/bulk-delete', [App\Http\Controllers\Admin\SystemRatingController::class, 'bulkDelete'])->name('system-rating.bulkDelete');
    Route::post('system-rating/status', [App\Http\Controllers\Admin\SystemRatingController::class, 'status'])->name('system-rating.status');
});



Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('poles', [App\Http\Controllers\Admin\NoOfPolesController::class, 'index'])->name('poles.index');
    Route::post('poles/store', [App\Http\Controllers\Admin\NoOfPolesController::class, 'store'])->name('poles.store');
    Route::post('poles/update', [App\Http\Controllers\Admin\NoOfPolesController::class, 'update'])->name('poles.update');
    Route::post('poles/delete', [App\Http\Controllers\Admin\NoOfPolesController::class, 'destroy'])->name('poles.delete');
    Route::post('poles/bulk-delete', [App\Http\Controllers\Admin\NoOfPolesController::class, 'bulkDelete'])->name('poles.bulkDelete');
    Route::post('poles/status', [App\Http\Controllers\Admin\NoOfPolesController::class, 'status'])->name('poles.status');
});


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('voltage', [App\Http\Controllers\Admin\OperatingVoltageController::class, 'index'])->name('voltage.index');
    Route::post('voltage/store', [App\Http\Controllers\Admin\OperatingVoltageController::class, 'store'])->name('voltage.store');
    Route::post('voltage/update', [App\Http\Controllers\Admin\OperatingVoltageController::class, 'update'])->name('voltage.update');
    Route::post('voltage/delete', [App\Http\Controllers\Admin\OperatingVoltageController::class, 'destroy'])->name('voltage.delete');
    Route::post('voltage/bulk-delete', [App\Http\Controllers\Admin\OperatingVoltageController::class, 'bulkDelete'])->name('voltage.bulkDelete');
    Route::post('voltage/status', [App\Http\Controllers\Admin\OperatingVoltageController::class, 'status'])->name('voltage.status');
});


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('form-type', [App\Http\Controllers\Admin\FormTypeController::class, 'index'])->name('form-type.index');
    Route::post('form-type/store', [App\Http\Controllers\Admin\FormTypeController::class, 'store'])->name('form-type.store');
    Route::post('form-type/update', [App\Http\Controllers\Admin\FormTypeController::class, 'update'])->name('form-type.update');
    Route::post('form-type/delete', [App\Http\Controllers\Admin\FormTypeController::class, 'destroy'])->name('form-type.delete');
    Route::post('form-type/bulk-delete', [App\Http\Controllers\Admin\FormTypeController::class, 'bulkDelete'])->name('form-type.bulkDelete');
    Route::post('form-type/status', [App\Http\Controllers\Admin\FormTypeController::class, 'status'])->name('form-type.status');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('panel-access', [App\Http\Controllers\Admin\PanelAccessController::class, 'index'])->name('panel-access.index');
    Route::post('panel-access/store', [App\Http\Controllers\Admin\PanelAccessController::class, 'store'])->name('panel-access.store');
    Route::post('panel-access/update', [App\Http\Controllers\Admin\PanelAccessController::class, 'update'])->name('panel-access.update');
    Route::post('panel-access/delete', [App\Http\Controllers\Admin\PanelAccessController::class, 'destroy'])->name('panel-access.delete');
    Route::post('panel-access/bulk-delete', [App\Http\Controllers\Admin\PanelAccessController::class, 'bulkDelete'])->name('panel-access.bulkDelete');
    Route::post('panel-access/status', [App\Http\Controllers\Admin\PanelAccessController::class, 'status'])->name('panel-access.status');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('panel-board-colour', [App\Http\Controllers\Admin\PanelBoardColourController::class, 'index'])->name('panel-board-colour.index');
    Route::post('panel-board-colour/store', [App\Http\Controllers\Admin\PanelBoardColourController::class, 'store'])->name('panel-board-colour.store');
    Route::post('panel-board-colour/update', [App\Http\Controllers\Admin\PanelBoardColourController::class, 'update'])->name('panel-board-colour.update');
    Route::post('panel-board-colour/delete', [App\Http\Controllers\Admin\PanelBoardColourController::class, 'destroy'])->name('panel-board-colour.delete');
    Route::post('panel-board-colour/bulk-delete', [App\Http\Controllers\Admin\PanelBoardColourController::class, 'bulkDelete'])->name('panel-board-colour.bulkDelete');
    Route::post('panel-board-colour/status', [App\Http\Controllers\Admin\PanelBoardColourController::class, 'status'])->name('panel-board-colour.status');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('ip-protection', [App\Http\Controllers\Admin\IpProtectionController::class, 'index'])->name('ip-protection.index');
    Route::post('ip-protection/store', [App\Http\Controllers\Admin\IpProtectionController::class, 'store'])->name('ip-protection.store');
    Route::post('ip-protection/update', [App\Http\Controllers\Admin\IpProtectionController::class, 'update'])->name('ip-protection.update');
    Route::post('ip-protection/delete', [App\Http\Controllers\Admin\IpProtectionController::class, 'destroy'])->name('ip-protection.delete');
    Route::post('ip-protection/bulk-delete', [App\Http\Controllers\Admin\IpProtectionController::class, 'bulkDelete'])->name('ip-protection.bulkDelete');
    Route::post('ip-protection/status', [App\Http\Controllers\Admin\IpProtectionController::class, 'status'])->name('ip-protection.status');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('lock-system', [App\Http\Controllers\Admin\LockSystemController::class, 'index'])->name('lock-system.index');
    Route::post('lock-system/store', [App\Http\Controllers\Admin\LockSystemController::class, 'store'])->name('lock-system.store');
    Route::post('lock-system/update', [App\Http\Controllers\Admin\LockSystemController::class, 'update'])->name('lock-system.update');
    Route::post('lock-system/delete', [App\Http\Controllers\Admin\LockSystemController::class, 'destroy'])->name('lock-system.delete');
    Route::post('lock-system/bulk-delete', [App\Http\Controllers\Admin\LockSystemController::class, 'bulkDelete'])->name('lock-system.bulkDelete');
    Route::post('lock-system/status', [App\Http\Controllers\Admin\LockSystemController::class, 'status'])->name('lock-system.status');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('busbar-position', [App\Http\Controllers\Admin\BusbarPositionController::class, 'index'])->name('busbar-position.index');
    Route::post('busbar-position/store', [App\Http\Controllers\Admin\BusbarPositionController::class, 'store'])->name('busbar-position.store');
    Route::post('busbar-position/update', [App\Http\Controllers\Admin\BusbarPositionController::class, 'update'])->name('busbar-position.update');
    Route::post('busbar-position/delete', [App\Http\Controllers\Admin\BusbarPositionController::class, 'destroy'])->name('busbar-position.delete');
    Route::post('busbar-position/bulk-delete', [App\Http\Controllers\Admin\BusbarPositionController::class, 'bulkDelete'])->name('busbar-position.bulkDelete');
    Route::post('busbar-position/status', [App\Http\Controllers\Admin\BusbarPositionController::class, 'status'])->name('busbar-position.status');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('gland-plate-thickness', [App\Http\Controllers\Admin\GlandPlateThicknessController::class, 'index'])->name('gland-plate-thickness.index');
    Route::post('gland-plate-thickness/store', [App\Http\Controllers\Admin\GlandPlateThicknessController::class, 'store'])->name('gland-plate-thickness.store');
    Route::post('gland-plate-thickness/update', [App\Http\Controllers\Admin\GlandPlateThicknessController::class, 'update'])->name('gland-plate-thickness.update');
    Route::post('gland-plate-thickness/delete', [App\Http\Controllers\Admin\GlandPlateThicknessController::class, 'destroy'])->name('gland-plate-thickness.delete');
    Route::post('gland-plate-thickness/bulk-delete', [App\Http\Controllers\Admin\GlandPlateThicknessController::class, 'bulkDelete'])->name('gland-plate-thickness.bulkDelete');
    Route::post('gland-plate-thickness/status', [App\Http\Controllers\Admin\GlandPlateThicknessController::class, 'status'])->name('gland-plate-thickness.status');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('outgoing-cable-position', [App\Http\Controllers\Admin\OutgoingCablePositionController::class, 'index'])->name('outgoing-cable-position.index');
    Route::post('outgoing-cable-position/store', [App\Http\Controllers\Admin\OutgoingCablePositionController::class, 'store'])->name('outgoing-cable-position.store');
    Route::post('outgoing-cable-position/update', [App\Http\Controllers\Admin\OutgoingCablePositionController::class, 'update'])->name('outgoing-cable-position.update');
    Route::post('outgoing-cable-position/delete', [App\Http\Controllers\Admin\OutgoingCablePositionController::class, 'destroy'])->name('outgoing-cable-position.delete');
    Route::post('outgoing-cable-position/bulk-delete', [App\Http\Controllers\Admin\OutgoingCablePositionController::class, 'bulkDelete'])->name('outgoing-cable-position.bulkDelete');
    Route::post('outgoing-cable-position/status', [App\Http\Controllers\Admin\OutgoingCablePositionController::class, 'status'])->name('outgoing-cable-position.status');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('plinth-type', [App\Http\Controllers\Admin\PlinthTypeController::class, 'index'])->name('plinth-type.index');
    Route::post('plinth-type/store', [App\Http\Controllers\Admin\PlinthTypeController::class, 'store'])->name('plinth-type.store');
    Route::post('plinth-type/update', [App\Http\Controllers\Admin\PlinthTypeController::class, 'update'])->name('plinth-type.update');
    Route::post('plinth-type/delete', [App\Http\Controllers\Admin\PlinthTypeController::class, 'destroy'])->name('plinth-type.delete');
    Route::post('plinth-type/bulk-delete', [App\Http\Controllers\Admin\PlinthTypeController::class, 'bulkDelete'])->name('plinth-type.bulkDelete');
    Route::post('plinth-type/status', [App\Http\Controllers\Admin\PlinthTypeController::class, 'status'])->name('plinth-type.status');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('certification', [App\Http\Controllers\Admin\CertificationController::class, 'index'])->name('certification.index');
    Route::post('certification/store', [App\Http\Controllers\Admin\CertificationController::class, 'store'])->name('certification.store');
    Route::post('certification/update', [App\Http\Controllers\Admin\CertificationController::class, 'update'])->name('certification.update');
    Route::post('certification/delete', [App\Http\Controllers\Admin\CertificationController::class, 'destroy'])->name('certification.delete');
    Route::post('certification/bulk-delete', [App\Http\Controllers\Admin\CertificationController::class, 'bulkDelete'])->name('certification.bulkDelete');
    Route::post('certification/status', [App\Http\Controllers\Admin\CertificationController::class, 'status'])->name('certification.status');
});
