<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeasurementModel;
use App\Models\ProductMovement;
use App\Models\MeasurementUnitModel;
use App\Models\Product;
use App\Models\Project;
use App\Models\Store;
use App\Models\StoreUser;
use App\Models\Shelf;
use Session;
use Auth;


class ProductMovementOutController extends Controller
{
    //class contructor
    public function __construct()
    {
        $this->middleware('auth');
    }


    //create measurement
    public function createProductMovement()
    {
        $data['getRecords']     = [];
        $data['getStore']       = [];
        $data['getShelf']       = [];
        $data['getMeasurement'] = [];
        $data['getProduct']     = [];

        try{
            $data['getRecords']     = $this->getProductEntered();
            $data['getStore']       = $this->getUserStore($userID = null); //you can reuse this function by passing one parameter: userID
            //$data['getShelf']       = Shelf::all();
            $data['getProject']     = Project::all();
            $data['getProduct']     = Product::all();
            (Session::get('editRecord') ? $data['editRecord'] = Session::get('editRecord') : null);
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMovementOutController@createProductMovement', 'Error occurred when fetching records.' );
        }
        Session::forget('editRecord');
        return view('products.productMovement.itemEntryOut', $data);
    }//end fun



    //Get Measurement when a product is selected
    public function getProductMeasurement($productID = null)
    {
        $getMeasurement = [];
        if(Product::find($productID))
        {
            $getMeasurement = MeasurementUnitModel::where('measurement_units.productID', $productID)
                            ->join('measurements', 'measurements.id', '=', 'measurement_units.measurementID')
                            ->select('description', 'measurementID')
                            ->get();
        }
        return $getMeasurement;
    }


    //Get User Store
    public function getUserStore($userID = null)
    {
        $myStore    = [];
        $userID     = ($userID == null ? (Auth::check() ? Auth::user()->id : null) : $userID);
        if(StoreUser::where('userID', $userID))
        {
            $myStore = StoreUser::where('store_users.userID', $userID)
                            ->join('stores', 'stores.id', '=', 'store_users.storeID')
                            ->get();
        }
        return $myStore;
    }


    //Store
    public function storeProductMovement(Request $request)
    {
        //Initialization
        $saved = null;
        //validation
        $this->validate($request,
        [
            'store'             => ['required', 'numeric'],
            'project'           => ['required', 'numeric'],
            'transactionDate'   => ['required', 'date'],
            'product'           => ['required', 'numeric'],
            'measure'           => ['required', 'numeric'],
            'quantity'          => ['required', 'numeric'],
            //'description'       => ['required', 'string'],

        ]);
        $recordID = $request['getRecord'];
        //DB transactions
        try{
            $getProductLeast = MeasurementUnitModel::where('measurementID', $request['measure'])->where('productID', $request['product'])->value('quantity');
            if(ProductMovement::find($recordID))
            {
                $saved = ProductMovement::where('id', $recordID)->update(
                    [
                        'userID'            => (Auth::check() ? Auth::user()->id : null),
                        'storeID'           => $request['store'],
                        'productID'         => $request['product'],
                        'measurementID'     => $request['measure'],
                        'transactionDate'   => date('Y-m-d', strtotime($request['transactionDate'])),
                        'move_out'           => (is_numeric($request['quantity']) ? ($getProductLeast * $request['quantity']) : 1),
                        'quantity'          => $request['quantity'],
                        'projectID'         => $request['project'],
                    ]);
            }else{
                $saved = ProductMovement::create(
                    [
                        'userID'            => (Auth::check() ? Auth::user()->id : null),
                        'storeID'           => $request['store'],
                        'productID'         => $request['product'],
                        'measurementID'     => $request['measure'],
                        'transactionDate'   => date('Y-m-d', strtotime($request['transactionDate'])),
                        'move_out'           => (is_numeric($request['quantity']) ? ($getProductLeast * $request['quantity']) : 1),
                        'quantity'          => $request['quantity'],
                        'projectID'         => $request['project'],
                    ]);
            }
            if($saved)
            {
                Session::forget('editRecord');
                return redirect()->route('createProductGoingOut')->with('message', 'New product was created/updated successfully.');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMovementOutController@storeProductMovement', 'Error occurred when adding/updating product.' );
        }
        return redirect()->route('createProductGoingOut')->with('error', 'Sorry, we cannot create/update your record now. Please try again.');
    }//end fun


    //Delete Record
    public function editProductMovement($recordID)
    {
        //DB transactions
        Session::forget('editRecord');
        try{
            $getRecord      = ProductMovement::find($recordID);
            if($getRecord)
            {
                Session::put('editRecord', $this->getProductEntered($recordID));
                return redirect()->route('createProductGoingOut');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMovementOutController@editProductMovement', 'Error occurred when editting record.' );
        }
        return redirect()->route('createProductGoingOut')->with('error', 'Sorry, we cannot edit this record now. Please try again.');
    }//end fun


    //Delete Record
    public function deleteProductMovement($recordID)
    {
        //DB transactions
        try{
            $getRecord = ProductMovement::find($recordID);
            if($getRecord  && ($getRecord->isConfirmed <= 0))
            {
                $getRecord->delete();
                return redirect()->route('createProductGoingOut')->with('message', 'Your record was deleted successfully.');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMovementOutController@deleteProductMovement', 'Error occurred when deleting record.' );
        }
        return redirect()->route('createProductGoingOut')->with('error', 'Sorry, we cannot delete this record now. Please try again.');
    }//end fun


    public function getProductEntered($recordID = null)
    {
        try{
            if($recordID && is_numeric($recordID))
            {
                return ProductMovement::where('product_movements.id', $recordID)
                    ->where('product_movements.status', 0)
                    ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
                    ->leftjoin('measurements', 'measurements.id', '=', 'product_movements.measurementID')
                    ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                    ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                    ->select('*', 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'measurements.id as measurementID', 'measurements.description as measureName', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                    ->orderBy('product_movements.id', 'Desc')
                    ->first();
            }else{
                return ProductMovement::leftjoin('products', 'products.id', '=', 'product_movements.productID')
                    ->where('product_movements.status', 0)
                    ->leftjoin('measurements', 'measurements.id', '=', 'product_movements.measurementID')
                    ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                    ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                    ->select('*', 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'measurements.id as measurementID', 'measurements.description as measureName', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                    ->orderBy('product_movements.id', 'Desc')
                    ->get();
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'createProductGoingOut@getProductEntered', 'Error occurred when fetching records.' );
        }
        return;
    }


    //Batch Item
    public function batchAllNewProducts(Request $request)
    {
        $isSuccess = 0;
        $this->validate($request,
        [
            'description'       => ['required', 'string'],
        ]);
        try{
            $randomDigit = rand(98979489, 8357373853);
            $isSuccess = ProductMovement::where('status', 0)->update([
                'status'        => 1,
                'orderNo'       => $randomDigit,
                'description'   => $request['description'],
            ]);
        }catch(\Throwable $err){}
        if($isSuccess)
        {
            return redirect()->back()->with('success', 'All unbatched items are now batched successfully as a single entry with the same order number: '. $randomDigit);
        }else{
            return redirect()->back()->with('warning', 'Sorry, we cannot batch you items now or no items to be batched. Please try again.');
        }
    }



}//end class
