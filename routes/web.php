<?php

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

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/form', [App\Http\Controllers\HomeController::class, 'form'])->name('basic-form');
Route::get('/table', [App\Http\Controllers\HomeController::class, 'table'])->name('table');
Route::get('/modal', [App\Http\Controllers\HomeController::class, 'modal'])->name('modal');
Route::get('/icon', [App\Http\Controllers\HomeController::class, 'icon'])->name('icon');


//Auth
Route::group(['/middleware' => ['auth']], function ()
{
    //Create Product measurement
    Route::get('product-measures',                          'ProductMeasurementController@createMeasurement')->name('createMeasurement');
    Route::post('product-measures',                         'ProductMeasurementController@storeMeasurement')->name('saveMeasurement');
    Route::get('product-measures/edit/{rid?}',              'ProductMeasurementController@editRecord')->name('editMeasurement');
    Route::get('product-measures/remove/{rid?}',            'ProductMeasurementController@deleteRecord')->name('deleteMeasurement');

    //Create Product measurement Unit Set-Up
    Route::get('product-measures-unit',                     'ProductMeasurementUnitController@createMeasurementUnit')->name('createMeasurementUnit');
    Route::post('product-measures-unit',                    'ProductMeasurementUnitController@storeMeasurementUnit')->name('saveMeasurementUnit');
    Route::get('product-measures-unit/edit/{rid?}',         'ProductMeasurementUnitController@editRecordUnit')->name('editMeasurementUnit');
    Route::get('product-measures-unit/remove/{rid?}',       'ProductMeasurementUnitController@deleteRecordUnit')->name('deleteMeasurementUnit');

    //Create Product movement In
    Route::get('product-entry',                             'ProductMovementController@createProductMovement')->name('createProductMovement');
    Route::post('product-entry',                            'ProductMovementController@storeProductMovement')->name('saveProductMovement');
    Route::get('product-entry/edit/{rid?}',                 'ProductMovementController@editProductMovement')->name('editProductMovement');
    Route::get('product-entry/remove/{rid?}',               'ProductMovementController@deleteProductMovement')->name('deleteProductMovement');
    Route::get('/get-product-measurement/{productID?}',     'ProductMovementController@getProductMeasurement');
    Route::get('/batch-product',                            'ProductMovementController@createProductMovement')->name('batchItems');
    Route::post('/batch-product',                           'ProductMovementController@batchAllNewProducts')->name('postBatchItems');

    //Create Product movement Out
    Route::get('product-moving-out',                        'ProductMovementOutController@createProductMovement')->name('createProductGoingOut');
    Route::post('product-moving-out',                       'ProductMovementOutController@storeProductMovement')->name('saveProductGoingOut');
    Route::get('product-moving-out/edit/{rid?}',            'ProductMovementOutController@editProductMovement')->name('editProductGoingOut');
    Route::get('product-moving-out/remove/{rid?}',          'ProductMovementOutController@deleteProductMovement')->name('deleteProductGoingOut');
    //Route::get('/get-product-measurement/{productID?}',    'ProductMovementOutController@getProductMeasurement');
    Route::get('/batch-product-out',                        'ProductMovementOutController@createProductMovement')->name('batchItemsGoingOut');
    Route::post('/batch-product-out',                       'ProductMovementOutController@batchAllNewProducts')->name('postBatchItemsGoingOut');

    //Report On Product Movement (In-Out)
    Route::get('report-product-movement',                  'ReportProductMovementInOutController@index')->name('createProductMovementInOut');
    Route::post('report-product-movement',                 'ReportProductMovementInOutController@search')->name('postProductMovementInOut');
    Route::get('refresh-page',                              'ReportProductMovementInOutController@refreshPage')->name('refreshProductReport');
    

    //projects
    Route::get('/projects', [App\Http\Controllers\ProjectController::class, 'launchProject'])->name('launchProject');
    Route::post('/projects', [App\Http\Controllers\ProjectController::class, 'saveProject'])->name('saveProject');
    Route::get('/delete-project/{id}', [App\Http\Controllers\ProjectController::class, 'deleteProject'])->name('deleteProject');
    Route::get('/edit-project/{id}', [App\Http\Controllers\ProjectController::class, 'editProject'])->name('editProject');
    Route::post('/update-project', [App\Http\Controllers\ProjectController::class, 'updateProject'])->name('updateProject');

    //Stores Routes
    Route::get('/create/warehouse', [App\Http\Controllers\StoresController::class, 'create']);
    Route::post('/create/warehouse', [App\Http\Controllers\StoresController::class, 'saveStore']);
    Route::get('/view/stores', [App\Http\Controllers\StoresController::class, 'viewStores']);
    Route::post('/update/store', [App\Http\Controllers\StoresController::class, 'updateStore']);
    Route::get('/delete/store/{id}', [App\Http\Controllers\StoresController::class, 'destroy']);
    Route::get('/create/store-users', [App\Http\Controllers\StoreUsersController::class, 'createStoreUser']);
    Route::post('/create/store-users', [App\Http\Controllers\StoreUsersController::class, 'saveStoreUser']);
    Route::get('/view/store-users', [App\Http\Controllers\StoreUsersController::class, 'viewStoreUsers']);
    Route::post('/update/store-users', [App\Http\Controllers\StoreUsersController::class, 'updateStoreUser']);
    Route::get('/delete/store-user/{id}', [App\Http\Controllers\StoreUsersController::class, 'destroy']);

    //Shelve
    Route::get('/create/shelve', [App\Http\Controllers\ShelvesController::class, 'create']);
    Route::post('/create/shelve', [App\Http\Controllers\ShelvesController::class, 'saveShelve']);
    Route::get('/view/shelves', [App\Http\Controllers\ShelvesController::class, 'viewshelves']);
    Route::post('/update/shelve', [App\Http\Controllers\ShelvesController::class, 'updateShelves']);
    Route::get('/delete/shelve/{id}', [App\Http\Controllers\ShelvesController::class, 'destroy']);
    //clients
    Route::get('/clients', [App\Http\Controllers\ClientController::class, 'launchClient'])->name('launchClient');
    Route::post('/clients', [App\Http\Controllers\ClientController::class, 'saveClient'])->name('saveClient');
    Route::get('/delete-client/{id}', [App\Http\Controllers\ClientController::class, 'deleteClient'])->name('deleteClient');
    Route::get('/edit-client/{id}', [App\Http\Controllers\ClientController::class, 'editClient'])->name('editClient');
    Route::post('/update-client', [App\Http\Controllers\ClientController::class, 'updateClient'])->name('updateClient');

  # tunde
    //product
    Route::get('/product', [App\Http\Controllers\ProductDetailController::class, 'create'])->name('create-product');
    Route::get('/edit-product/{id}/', [App\Http\Controllers\ProductDetailController::class, 'edit'])->name('edit-product');
    Route::post('/edit-product', [App\Http\Controllers\ProductDetailController::class, 'update'])->name('update-product');
    Route::post('/product', [App\Http\Controllers\ProductDetailController::class, 'store'])->name('store-product');
    Route::post('/product-image', [App\Http\Controllers\ProductImageController::class, 'store'])->name('image-product');
    Route::post('/product-image-delete', [App\Http\Controllers\ProductImageController::class, 'delete'])->name('delete-image-product');
    Route::post('/product-edit-image', [App\Http\Controllers\ProductImageController::class, 'update'])->name('edit-image-product');
    Route::post('/product/product-subcategory', [App\Http\Controllers\ProductDetailController::class, 'subcategory'])->name('pr-subcategory');
    Route::post('/product/edit-product-subcategory', [App\Http\Controllers\ProductDetailController::class, 'editsubcategory'])->name('edit-pr-subcategory');
    Route::post('/product/edit-product-subcategory', [App\Http\Controllers\ProductDetailController::class, 'editsubcategory'])->name('edit-pr-subcategory');
    Route::post('/product/edit-product-measurement', [App\Http\Controllers\ProductDetailController::class, 'editMeasurement'])->name('edit-pr-measurement');

  #Tola
    //CATEGORIES
    Route::get('/category',    [App\Http\Controllers\categoryController::class, 'create'])->name('getCategory');
    Route::post('/category/save',   [App\Http\Controllers\categoryController::class, 'saveCategory'])->name('saveCategory');
    Route::get('category/edit/{rid?}',        [App\Http\Controllers\categoryController::class, 'editCategory'])->name('editCategory');
    Route::get('category/remove/{rid?}',      [App\Http\Controllers\categoryController::class, 'deleteCategory'])->name('deleteCategory');
    //SUB CATEGORY
    Route::get('/category/index',    [App\Http\Controllers\categoryController::class, 'create'])->name('getCategoryInd');
    Route::post('/subCategory',   [App\Http\Controllers\categoryController::class, 'SaveSubCategory'])->name('SaveSubCategory');
    Route::get('category/edit/sub/{id?}',        [App\Http\Controllers\categoryController::class, 'editSubCategory'])->name('editSubCategory');
    Route::post('/category/update',       [App\Http\Controllers\categoryController::class, 'saveAndUpdate'])->name('saveAndUpdate');
    Route::get('category/delete/{id?}',      [App\Http\Controllers\categoryController::class, 'deleteSubCategory'])->name('deleteSubCategory');

    //report
    Route::get('/report',    [App\Http\Controllers\MovementOutReport::class, 'launchReport'])->name('launchReport');
    Route::post('/report',    [App\Http\Controllers\MovementOutReport::class, 'getReport'])->name('getReport');
    Route::get('/report/viewall/{id}',    [App\Http\Controllers\MovementOutReport::class, 'viewAll'])->name('viewAll');
  
    Route::get('/product/shelve', [App\Http\Controllers\ShelveReportController::class, 'create'])->name('productInShelve');
    Route::post('/product/shelve', [App\Http\Controllers\ShelveReportController::class, 'getProductShelveReport'])->name('productInShelve');
    
    Route::get('/view/orders-items/{id}',    [App\Http\Controllers\OrdersMovedOutController::class, 'show']);
    Route::get('/view/orders-items',    [App\Http\Controllers\OrdersMovedOutController::class, 'index']);
    Route::post('/cancel/order',    [App\Http\Controllers\OrdersMovedOutController::class, 'cancelOrder']);
    
    //Product movement trail report
    Route::any('/stock-trail',                     'ItemTrailController@Trails')->name('stockTrail');
   
});

