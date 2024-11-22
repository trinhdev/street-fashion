<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\Client\ManagerUser;

// use App\Http\Controllers\FileController;
use App\Http\Controllers\VerifyOtpController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\client\formController;
use App\Http\Controllers\client\HomeController;
use App\Http\Controllers\LoginGoogleController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Admin\HomeAdminController;
use App\Http\Controllers\Admin\DashBoard\RoleController;
use App\Http\Controllers\Admin\DashBoard\StockController;
use App\Http\Controllers\Admin\DashBoard\UnitsController;
use App\Http\Controllers\Admin\DashBoard\OrdersController;
use App\Http\Controllers\Admin\DashBoard\CommentController;
use App\Http\Controllers\Admin\DashBoard\ModulesController;
use App\Http\Controllers\Admin\DashBoard\VoucherController;
use App\Http\Controllers\Admin\DashBoard\ProductsController;
use App\Http\Controllers\Admin\DashBoard\SettingsController;
use App\Http\Controllers\Admin\DashBoard\SupplierController;
use App\Http\Controllers\Admin\DashBoard\CustomersController;
use App\Http\Controllers\Admin\DashBoard\WarehouseController;
use App\Http\Controllers\Admin\DashBoard\GroupmoduleController;
use App\Http\Controllers\Admin\DashBoard\ProductUnitsController;
use App\Http\Controllers\Admin\DashBoard\CategoryChildController;
use App\Http\Controllers\Admin\DashBoard\LogactivitiesController;
use App\Http\Controllers\Admin\DashBoard\CategoryParentController;
use App\Http\Controllers\Admin\DashBoard\GeneralSettingsController;
use App\Http\Controllers\Admin\DashBoard\StockTransactionController;
use App\Http\Controllers\Admin\DashBoard\UserController as UserAdminController;


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


Route::group(['middleware' => ['auth'], 'prefix'=>'admin'], function () {
    Auth::routes();
    Route::get('/', [HomeAdminController::class, 'index'])->name('admin');
    Route::prefix('file')->controller(FileController::class)->group(function () {
        Route::any('/uploadImageExternal', 'uploadImageExternal')->name('uploadImageExternalB');
        Route::post('/importPhone', 'importPhone')->name('file.importPhone');

    });
        Route::prefix('setting')->group(function () {
            Route::get('/', [GeneralSettingsController::class, 'index'])->name('general_settings.index');
            Route::post('/edit', [GeneralSettingsController::class, 'postEdit'])->name('general_settings.edit');
            Route::post('/saveUriSetting',  [GeneralSettingsController::class, 'saveUriSetting'])->name('general_settings.saveUriSetting');
            Route::post('/sendMailManually',  [GeneralSettingsController::class, 'sendMailManually'])->name('general_settings.sendMailManually');
        });

       
        Route::prefix('user')->group(function () {
            Route::get('/', [UserAdminController::class, 'index'])->name('user.index');
            Route::get('/edit/{id}', [UserAdminController::class, 'edit'])->name('user.edit');
            Route::get('/create', [UserAdminController::class, 'create'])->name('user.create');
            Route::put('/update', [UserAdminController::class, 'update'])->name('user.update');
            Route::post('/store', [UserAdminController::class, 'store'])->name('user.store');
            Route::post('/login', [UserAdminController::class, 'login'])->name('user.login');

            Route::put('/update/{id}', [UserAdminController::class, 'update'])->name('user.update');
            Route::post('/destroy', [UserAdminController::class, 'destroy'])->name('user.destroy');
            
        });

        Route::prefix('products')->group(function () {
            Route::get('/import-view', [ProductsController::class, 'importView'])->name('products.import.view');
            Route::post('/import', [ProductsController::class, 'import'])->name('products.import');
            Route::get('/', [ProductsController::class, 'index'])->name('admin.products.index');
            Route::get('/edit/{id}', [ProductsController::class, 'edit'])->name('products.edit');
            Route::get('/create', [ProductsController::class, 'create'])->name('products.create');
            Route::post('/store', [ProductsController::class, 'store'])->name('products.store');
            Route::post('/login', [ProductsController::class, 'login'])->name('products.login');
            Route::put('/update/{id}', [ProductsController::class, 'update'])->name('products.update');
            Route::post('/destroy', [ProductsController::class, 'destroy'])->name('products.destroy');
        });

        Route::prefix('category-child')->group(function () {
            Route::get('/', [CategoryChildController::class, 'index'])->name('category-child.index');
            Route::get('/edit/{id}', 'CategoriesController@edit')->name('category-child.edit');
            Route::get('/create', 'CategoriesController@create')->name('category-child.create');
            Route::post('/show', [CategoryChildController::class, 'show'])->name('category-child.show');
            Route::post('/store', [CategoryChildController::class, 'store'])->name('category-child.store');
            Route::post('/login', 'CategoriesController@login')->name('categories.login');
            Route::put('/update/{id}', 'CategoriesController@update')->name('category-child.update');
            Route::post('/destroy', 'CategoriesController@destroy')->name('category-child.destroy');
        });

        Route::prefix('category-parent')->group(function () {
            Route::get('/', [CategoryParentController::class, 'index'])->name('category-parent.index');
            Route::get('/edit/{id}', [CategoryParentController::class, 'edit'])->name('category-parent.edit');
            Route::post('/show', [CategoryParentController::class, 'show'])->name('category-parent.show');
            Route::get('/create', [CategoryParentController::class, 'create'])->name('category-parent.create');
            Route::post('/store', [CategoryParentController::class, 'store'])->name('category-parent.store');
            Route::put('/update/{id}', [CategoryParentController::class, 'update'])->name('category-parent.update');
            Route::post('/destroy', [CategoryParentController::class, 'destroy'])->name('category-parent.destroy');
            Route::post('/change-status', [CategoryParentController::class, 'changeStatus'])->name('category-parent.change-status');
            Route::get('/initDatatable', [CategoryParentController::class, 'initDatatable'])->name('category-parent.initDatatable');
        });
        
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrdersController::class, 'index'])->name('orders.index');
            Route::get('/import-view', [OrdersController::class, 'importView'])->name('orders.import.view');
            Route::post('/import', [OrdersController::class, 'import'])->name('orders.import');
            Route::get('/edit/{id}', [OrdersController::class, 'edit'])->name('orders.edit');
            Route::post('/show', [OrdersController::class, 'show'])->name('orders.show');
            Route::get('/create', [OrdersController::class, 'create'])->name('orders.create');
            Route::post('/store', [OrdersController::class, 'store'])->name('orders.store');
            Route::put('/update/{id}', [OrdersController::class, 'update'])->name('orders.update');
            Route::post('/destroy', [OrdersController::class, 'destroy'])->name('orders.destroy');
            Route::post('/orders/update-status', [OrdersController::class, 'updateStatus'])->name('orders.updateStatus');

            Route::get('/initDatatable', [OrdersController::class, 'initDatatable'])->name('orders.initDatatable');
        });

        Route::prefix('comment')->group(function () {
            Route::get('/', [CommentController::class, 'index'])->name('comment.index');
            Route::post('/show', [CommentController::class, 'show'])->name('comment.show');
            Route::post('/destroy', [CommentController::class, 'destroy'])->name('comment.destroy');
            Route::post('/change-status', [CommentController::class, 'changeStatus'])->name('comment.change-status');
            Route::get('/initDatatable', [CommentController::class, 'initDatatable'])->name('comment.initDatatable');
        });

        // Route::prefix('stock-transaction')->group(function () {
        //     Route::get('/', [StockTransactionController::class, 'index'])->name('stock-transaction.index');
        //     Route::get('/import-view', [StockTransactionController::class, 'importView'])->name('stock-transaction.import.view');
        //     Route::post('/import-excel', [StockTransactionController::class, 'importExcel'])->name('stock-transaction.import.excel');
        //     Route::post('/storeImport', [StockTransactionController::class, 'storeImport'])->name('stock-transaction.storeImport');
        //     Route::post('/storeExport', [StockTransactionController::class, 'storeExport'])->name('stock-transaction.storeExport');
        //     Route::get('/get-product-units/{productId}', [StockTransactionController::class, 'getProductUnits']);
        //     Route::post('/show', [StockTransactionController::class, 'show'])->name('stock-transaction.show');
        //     Route::get('/import', [StockTransactionController::class, 'import'])->name('stock-transaction.import');
        //     Route::get('/list', [StockTransactionController::class, 'list'])->name('stock-transaction.list');
        //     Route::get('/export', [StockTransactionController::class, 'export'])->name('stock-transaction.export');
        //     Route::get('/initDatatable', [StockTransactionController::class, 'initDatatable'])->name('comment.initDatatable');
        // });

        Route::prefix('vouchers')->group(function () {
            Route::get('/', [VoucherController::class, 'index'])->name('vouchers.index');
            Route::get('/edit/{id}', [VoucherController::class, 'edit'])->name('vouchers.edit');
            Route::post('/show', [VoucherController::class, 'show'])->name('vouchers.show');
            Route::get('/create', [VoucherController::class, 'create'])->name('vouchers.create');
            Route::post('/store', [VoucherController::class, 'store'])->name('vouchers.store');
            Route::put('/update/{id}', [VoucherController::class, 'update'])->name('vouchers.update');
            Route::post('/destroy', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
            Route::post('/change-status', [VoucherController::class, 'changeStatus'])->name('vouchers.change-status');
            Route::get('/initDatatable', [VoucherController::class, 'initDatatable'])->name('vouchers.initDatatable');
        });

        

    

    

        
        Route::prefix('modules')->group(function () {
            Route::get('/',  [ModulesController::class, 'index'])->name('modules.index');
            Route::get('/edit/{id}',  [ModulesController::class, 'edit'])->name('modules.edit');
            Route::post('/show',  [ModulesController::class, 'show'])->name('modules.show');
            Route::get('/create',  [ModulesController::class, 'create'])->name('modules.create');
            Route::post('/store',  [ModulesController::class, 'store'])->name('modules.store');
            Route::post('/update/{id}',  [ModulesController::class, 'update'])->name('modules.update');
            Route::post('/destroy',  [ModulesController::class, 'destroy'])->name('modules.destroy');
            Route::get('/initDatatable',  [ModulesController::class, 'initDatatable'])->name('modules.initDatatable');
        });

        Route::prefix('groupmodule')->group(function () {
            Route::get('/', [GroupmoduleController::class, 'index'])->name('groupmodule.index');
            Route::get('/edit/{id}', [GroupmoduleController::class, 'edit'])->name('groupmodule.edit');
            Route::get('/create', [GroupmoduleController::class, 'create'])->name('groupmodule.create');
            Route::post('/store', [GroupmoduleController::class, 'store'])->name('groupmodule.store');
            Route::put('/update/{id}', [GroupmoduleController::class, 'update'])->name('groupmodule.update');
            Route::post('/destroy', [GroupmoduleController::class, 'destroy'])->name('groupmodule.destroy');
            Route::get('/initDatatable', [GroupmoduleController::class, 'initDatatable'])->name('groupmodule.initDatatable');
        });

        Route::prefix('role')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('role.index');
            Route::get('/edit/{id?}', [RoleController::class, 'edit'])->name('role.edit');
            Route::post('/update/{id?}', [RoleController::class, 'update'])->name('role.update');
            Route::get('/create', [RoleController::class, 'create'])->name('role.create');
            Route::post('/store', [RoleController::class, 'store'])->name('role.store');
            Route::post('/destroy', [RoleController::class, 'destroy'])->name('role.destroy');
        });

        Route::prefix('logactivities')->group(function () {
            Route::get('/', [LogactivitiesController::class, 'index'])->name('logactivities.index');
            Route::post('/clearLog', [LogactivitiesController::class, 'clearLog'])->name('logactivities.clearLog');
            Route::delete('/destroy/{id}', [LogactivitiesController::class, 'destroy'])->name('logactivities.destroy');
            Route::get('/initDatatable', [LogactivitiesController::class, 'initDatatable'])->name('logactivities.initDatatable');
        });

    
    Route::prefix('profile')->group(function () {
        Route::post('/changePassword', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
        Route::post('/updateprofile', [ProfileController::class, 'updateprofile'])->name('profile.updateprofile');
    });

   
    
}
);





Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/forgot', [ManagerUser::class, 'Forgot'])->name('forgotView');
Route::post('/forgotpassword', [UserController::class, 'sendOtp'])->name('forgotpassword');
Route::get('/verify_otp', [UserController::class, 'showVerifyOtpForm'])->name('verifyOtpForm');
Route::post('/verify_otp', [VerifyOtpController::class, 'verifyOtpForm'])->name('verifyOtpForm'); 
Route::get('/reset_password', [UserController::class, 'showResetPasswordForm'])->name('resetPasswordForm');
Route::post('/reset_password', [UserController::class, 'resetPassword'])->name('resetPassword');

Route::get('login/google', [LoginGoogleController::class, 'redirectToGoogle'])->name('google-login');
Route::get('login/google/callback', [LoginGoogleController::class, 'handleGoogleCallback']);
Route::get('/login', [ManagerUser::class, 'Login'])->name('login');
Route::get('/register', [ManagerUser::class, 'Register']);
Route::post('/login', [formController::class, 'Checkdn'])->name('login');
Route::post('/register', [formController::class, 'Checkdk'])->name('register');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

Route::post('/checkPayment', [OrderController::class, 'checkPayment'])->name('order.checkpayment');
Route::post('/thueapi-hooks', [OrderController::class, 'thueapi_hooks']);
Route::post('/apply-voucher', [OrderController::class, 'applyVoucher'])->name('apply.voucher');
Route::post('/cart/add', [CartController::class, 'addToCarts'])->middleware('auth');
Route::get('/cart', [CartController::class, 'getCarts'])->middleware('auth');
