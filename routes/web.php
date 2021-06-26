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
    Route::get('/get-shelf-from-store/{storeID?}',          'ProductMovementController@getShelfFromStore');

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
    Route::get('refresh-page',                             'ReportProductMovementInOutController@refreshPage')->name('refreshProductReport');
    Route::get('get-product-from-category/{categoryID?}',  'ReportProductMovementInOutController@getProductFromCategory');

     //Transfer Item to another Store
     Route::get('transafer-product',                        'TransferProductOutController@createProductTransfer')->name('transferProduct');
     Route::post('transafer-product',                       'TransferProductOutController@saveProductTransfer')->name('postTransferProduct');
     Route::get('/batch-transafer-product',                 'TransferProductOutController@createProductTransfer');
     Route::post('/batch-transafer-product',                'TransferProductOutController@batchAllProductsToBeTransferred')->name('batchTransferProduct');
     Route::get('/transfer-product-get-product-measurement/{productID?}',    'TransferProductOutController@getProductMeasurement');
     Route::get('product-transfer/edit/{rid?}',            'TransferProductOutController@editProductMovement')->name('editProductTransfer');
     Route::get('product-transfer/remove/{rid?}',          'TransferProductOutController@deleteProductMovement')->name('deleteProductTransfer');
    //Transfer Report
    Route::get('/report-product-transferred',              'TransferProductOutController@indexReportTransfer')->name('viewTransaferReport');
    Route::post('/report-product-transferred',             'TransferProductOutController@resendProductTransferred')->name('postTransaferReport');
    Route::post('/query-report',                           'TransferProductOutController@queryTransferredReport')->name('changeQueryReport');
    Route::get('/rejected-product-transferred',            'TransferProductOutController@indexRejectedProductTransferOut')->name('createEditTransaferReport');
    Route::post('/rejected-product-transferred',           'TransferProductOutController@saveEditProductTransferOut')->name('postEditTransaferReport');
    Route::get('/rejected-product-transfer/edit/{rid?}',   'TransferProductOutController@editRejectedProductMovement')->name('editRejectedProductTransfer');
    Route::get('/report-product-transferred-details/{orderNo?}',      'TransferProductOutController@indexReportTransferDetails')->name('viewTransaferReportDetails');




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
    Route::get('/product',                           [App\Http\Controllers\ProductDetailController::class, 'create'])->name('create-product');
    Route::get('/edit-product/{id}/',                [App\Http\Controllers\ProductDetailController::class, 'edit'])->name('edit-product');
    Route::post('/edit-product',                     [App\Http\Controllers\ProductDetailController::class, 'update'])->name('update-product');
    Route::post('/delete-product',                   [App\Http\Controllers\ProductDetailController::class, 'delete'])->name('delete-product');
    Route::post('/product',                          [App\Http\Controllers\ProductDetailController::class, 'store'])->name('store-product');
    Route::post('/product-image',                    [App\Http\Controllers\ProductImageController::class, 'store'])->name('image-product');
    Route::post('/fetch-pr-image',                    [App\Http\Controllers\ProductImageController::class, 'getImage'])->name('pr-image');
    Route::post('/product-image-delete',             [App\Http\Controllers\ProductImageController::class, 'delete'])->name('delete-image-product');
    Route::post('/product-edit-image',               [App\Http\Controllers\ProductImageController::class, 'update'])->name('edit-image-product');
    Route::post('/product/product-subcategory',      [App\Http\Controllers\ProductDetailController::class, 'subcategory'])->name('pr-subcategory');
    Route::post('/product/edit-product-subcategory', [App\Http\Controllers\ProductDetailController::class, 'editsubcategory'])->name('edit-pr-subcategory');
    Route::post('/product/edit-product-subcategory', [App\Http\Controllers\ProductDetailController::class, 'editsubcategory'])->name('edit-pr-subcategory');
    Route::post('/product/edit-product-measurement', [App\Http\Controllers\ProductDetailController::class, 'editMeasurement'])->name('edit-pr-measurement');
    Route::post('/product-measures-unit-frm-edit',   [App\Http\Controllers\ProductDetailController::class, 'storeMeasurementUnitFrmEdit'])->name('saveMeasurementUnitFrmEdit');

    //confirm in
    Route::get('/product-confirmation-in',                    [App\Http\Controllers\ProductConfirmationController::class, 'index'])->name('display-confirm-in');
    Route::get('/{id}/agree-confirmation-in',                 [App\Http\Controllers\ProductConfirmationController::class, 'process'])->name('post-process-confirm-in');
    Route::get('/{id}/process-confirmation-in',               [App\Http\Controllers\ProductConfirmationController::class, 'listConfirmIn'])->name('process-confirm-in');
    Route::post('/process-confirmation-in-multi-rejection',   [App\Http\Controllers\ProductConfirmationController::class, 'multiRejection'])->name('process-confirm-in-multi-rejection');
    Route::post('/process-confirmation-in-single-rejection',  [App\Http\Controllers\ProductConfirmationController::class, 'singleRejection'])->name('process-confirm-in-single-rejection');
    Route::post('/process-confirmation-in-multi-approval',    [App\Http\Controllers\ProductConfirmationController::class, 'multiAproval'])->name('process-confirm-in-multi-approval');

    //confirm-out
    Route::get('/product-confirmation-out',       [App\Http\Controllers\ProductConfirmationController::class, 'indexOut'])->name('display-confirm-out');
    Route::get('/{id}/process-confirmation-out',  [App\Http\Controllers\ProductConfirmationController::class, 'listConfirmOut'])->name('process-confirm-out');
    Route::get('/{id}/agree-confirmation-out',    [App\Http\Controllers\ProductConfirmationController::class, 'processOut'])->name('post-process-confirm-out');
  // tunde
    Route::post('/update-measurement-quantity', [App\Http\Controllers\ProductDetailController::class, 'updateMeasurementUnitQuantity'])->name('update-measurement-quantity');
    Route::post('/delete-measurement-quantity', [App\Http\Controllers\ProductDetailController::class, 'deleteMeasurementUnitQuantity'])->name('delete-measurement-quantity');

  #Tola
    //CATEGORIES
    Route::get('/category',    [App\Http\Controllers\categoryController::class, 'create'])->name('getCategory');
    Route::post('/category/save',   [App\Http\Controllers\categoryController::class, 'saveCategory'])->name('saveCategory');
    Route::get('category/edit/{rid?}',        [App\Http\Controllers\categoryController::class, 'editCategory'])->name('editCategory');
    Route::get('category/remove/{rid?}',      [App\Http\Controllers\categoryController::class, 'deleteCategory'])->name('deleteCategory');
    Route::any('category/forget', [App\Http\Controllers\categoryController::class,'forgetCategory'])->name('forgetCategory');
    //SUB CATEGORY
    Route::get('/category/index',    [App\Http\Controllers\categoryController::class, 'create'])->name('getCategoryInd');
    Route::post('/subCategory',   [App\Http\Controllers\categoryController::class, 'SaveSubCategory'])->name('SaveSubCategory');
    Route::get('category/edit/sub/{id?}',        [App\Http\Controllers\categoryController::class, 'editSubCategory'])->name('editSubCategory');
    Route::post('/category/update',       [App\Http\Controllers\categoryController::class, 'saveAndUpdate'])->name('saveAndUpdate');
    Route::get('category/delete/{id?}',      [App\Http\Controllers\categoryController::class, 'deleteSubCategory'])->name('deleteSubCategory');
    Route::any('category/forget/sub', [App\Http\Controllers\categoryController::class,'forgetSubCategory'])->name('forgetSubCategory');

    Route::any('/product/shelve',   [App\Http\Controllers\ShelveReportController::class, 'create'])->name('productInShelve');
    //Product Search
    Route::any('/product/search',   [App\Http\Controllers\searchProductController::class, 'create'])->name('productSearch');
    Route::post('/product/submit/search', [App\Http\Controllers\searchProductController::class, 'submitSearch'])->name('submitSearch');
    Route::post('/product/search/ajax', [App\Http\Controllers\searchProductController::class, 'AjaxproductSearchForSubcategory'])->name('AjaxproductSearchForSubcategory');
    //movement-out report
    Route::get('/report',    [App\Http\Controllers\MovementOutReport::class, 'launchReport'])->name('launchReport');
    Route::post('/report',    [App\Http\Controllers\MovementOutReport::class, 'getReport'])->name('getReport');
    Route::get('/report/viewall/{id}',    [App\Http\Controllers\MovementOutReport::class, 'viewAll'])->name('viewAll');

    //movement-in report
    Route::get('/in-report',    [App\Http\Controllers\MovementInReport::class, 'launchInReport'])->name('launchInReport');
    Route::post('/in-report',    [App\Http\Controllers\MovementInReport::class, 'getInReport'])->name('getInReport');
    Route::get('/in-report/viewall/{id}',    [App\Http\Controllers\MovementInReport::class, 'viewAllIn'])->name('viewAllIn');

    //store movement confirmation
    Route::get('/confirm-movement',    [App\Http\Controllers\StoreConfirmationController::class, 'confirmMovement'])->name('confirmMovement');
    Route::post('/confirm-movement',   [App\Http\Controllers\StoreConfirmationController::class, 'getconfirmMovement'])->name('getconfirmMovement');

    Route::post('/post-confirm-batch',      [App\Http\Controllers\StoreConfirmationController::class, 'confirmBatch'])->name('confirmBatch');
    Route::post('/post-reject-batch',       [App\Http\Controllers\StoreConfirmationController::class, 'rejectBatch'])->name('rejectBatch');
    Route::post('/post-single-reject-batch',       [App\Http\Controllers\StoreConfirmationController::class, 'rejectSingleBatch'])->name('rejectSingleBatch');
    Route::get('/view-batch/{id}',     [App\Http\Controllers\StoreConfirmationController::class, 'viewBatch'])->name('viewBatch');
    Route::get('/{id}/post-confirmation-in',    [App\Http\Controllers\StoreConfirmationController::class, 'confirmProcess'])->name('post-confirm-in');

    //store movement confirmation report
    Route::get('/confirm-movement-report',      [App\Http\Controllers\ConfirmationReportController::class, 'confirmReport'])->name('confirmReport');
    Route::post('/confirm-movement-report',     [App\Http\Controllers\ConfirmationReportController::class, 'getconfirmReport'])->name('getconfirmReport');

    Route::get('/view/orders-items/{id}',       [App\Http\Controllers\OrdersMovedOutController::class, 'show']);
    Route::get('/view/orders-items',            [App\Http\Controllers\OrdersMovedOutController::class, 'index']);
    Route::post('/cancel/order',                [App\Http\Controllers\OrdersMovedOutController::class, 'cancelOrder']);
    Route::get('/order-items/{id}',             [App\Http\Controllers\OrdersMovedOutController::class, 'orderItems']);
    Route::post('/adjust/quantity',             [App\Http\Controllers\OrdersMovedOutController::class, 'adjustQuantity']);


    //Orders In
    Route::get('/view/orders-in/{id}',    [App\Http\Controllers\OrdersMovedInController::class, 'show']);
    Route::get('/view/orders-in',         [App\Http\Controllers\OrdersMovedInController::class, 'index']);
    Route::post('/cancel/order-in',       [App\Http\Controllers\OrdersMovedInController::class, 'cancelOrder']);
    Route::get('/order-items-in/{id}',    [App\Http\Controllers\OrdersMovedInController::class, 'orderItems']);
    Route::post('/adjust/quantity-in',    [App\Http\Controllers\OrdersMovedInController::class, 'adjustQuantity']);

    //Orders In ends

    Route::get('/quantity-control',    'QuantityController@show')->name('quantityControlShow');
    Route::get('/quantity-control-report',    'QuantityController@showReport')->name('quantityControlReportShow');
    Route::post('/quantity-control-report',    'QuantityController@getQuery')->name('quantityControlQuery');
    Route::post('/quantity-control',                       'QuantityController@storeProductMovement')->name('quantityControl');
    Route::get('quantity-control/edit/{rid?}',            'QuantityController@editProductMovement')->name('editQuantity');
    Route::get('report/quantity-control/{id?}',            'QuantityController@quantityControlView')->name('quantityControlView');
    Route::post('quantity-control-edit/{',            'QuantityController@editQC')->name('editQuantity');
    Route::get('quantity-control/remove/{rid?}',          'QuantityController@deleteProductMovement')->name('deleteQC');
    Route::post('/get-reasons',   'QuantityController@getReasons')->name('getReasons');
    Route::get('/batch-quantity-control',                        'QuantityController@createProductMovement')->name('batchQC');
    Route::post('/batch-quantity-control',                       'QuantityController@batchAllNewProducts')->name('postBatchQC');
    //Product movement trail report
    Route::any('/stock-trail',                     'ItemTrailController@Trails')->name('stockTrail');

    Route::post('/resend/product',    'OrdersMovedInController@resendProduct');


});

