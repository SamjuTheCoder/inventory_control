<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\StoreUser;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductMovement;
use Session;
use Auth;
use DB;


class ReportProductMovementInOutController extends Controller
{
    //class contructor
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Refresh
    public function refreshPage()
    {
        Session::forget('getStore');
        Session::forget('getProduct');
        Session::forget('getCategory');
        Session::forget('getTransactionDate');

        return redirect()->back();
    }

    //create measurement
    public function index($recordID = null)
    {
        //transaction Date : as at
        //store
        // product
        //category
        //GroupBy : product ID
        $data                       = [];
        $data['store']              = Session::get('getStore');
        $data['product']            = Session::get('getProduct');
        $data['category']           = Session::get('getCategory');
        $data['transactionDate']    = Session::get('getTransactionDate');

        try{
            $data['getStore']       = $this->getUserStore($userID = null); //Store::all();
            $data['getProduct']     = Product::all();
            $data['getCategory']    = Category::all();

            $data['getRecords'] = $this->queryData($data['store'], $data['product'], $data['category'], $data['transactionDate']);
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ReportProductMovementInOutController@index', 'Error occurred when fetching records.' );
        }
        return view('products.report.productMovementInOut', $data);
    }//end fun
    
    
    //Search Record
    public function search(Request $request)
    {
        Session::forget('getStore');
        Session::forget('getProduct');
        Session::forget('getCategory');
        Session::forget('getTransactionDate');

        Session::put('getStore', $request['store']);
        Session::put('getProduct', $request['product']);
        Session::put('getCategory', $request['category']);
        Session::put('getTransactionDate', $request['transactionDate']);

        return redirect()->back();
    }

    //Query Record
    public function queryData($storeID = null, $productID = null, $categoryID = null, $date = null)
    {   
        $getDate = date('Y-m-d', strtotime($date));
        $storeID = $storeID == 'All' ? null : $storeID;
        $productID = $productID == 'All' ? null : $productID;
        $categoryID = $categoryID == 'All' ? null : $categoryID;
        if($storeID == null && $productID == null && $categoryID == null && $date <> null)
        {
            $record = ProductMovement::leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->where('product_movements.transactionDate', '<', $getDate)
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->select('*', DB::raw("SUM(move_in) as totalIn"), DB::raw("SUM(move_out) as totalOut"), 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.id', 'Desc')
                ->groupBy('product_movements.productID')
                ->get();
        }elseif($storeID <> null && $productID == null && $categoryID == null && $date == null)
        {
            $record = ProductMovement::leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->where('product_movements.storeID', $storeID)
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->select('*', DB::raw("SUM(move_in) as totalIn"), DB::raw("SUM(move_out) as totalOut"), 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.id', 'Desc')
                ->groupBy('product_movements.productID')
                ->get();
        }elseif($storeID == null && $productID <> null && $categoryID == null && $date == null)
        {
            $record = ProductMovement::leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->where('product_movements.productID', $productID)
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->select('*', DB::raw("SUM(move_in) as totalIn"), DB::raw("SUM(move_out) as totalOut"), 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.id', 'Desc')
                ->groupBy('product_movements.productID')
                ->get();
        }elseif($storeID == null && $productID == null && $categoryID <> null && $date == null)
        {
            $record = ProductMovement::leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->where('products.categoryID', $categoryID)
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->select('*', DB::raw("SUM(move_in) as totalIn"), DB::raw("SUM(move_out) as totalOut"), 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.id', 'Desc')
                ->groupBy('product_movements.productID')
                ->get();
        }elseif($storeID <> null && $productID <> null && $categoryID == null && $date == null)
        {
            $record = ProductMovement::leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->where('product_movements.storeID', $storeID)
                ->where('product_movements.productID', $productID)
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->select('*', DB::raw("SUM(move_in) as totalIn"), DB::raw("SUM(move_out) as totalOut"), 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.id', 'Desc')
                ->groupBy('product_movements.productID')
                ->get();
        }elseif($storeID <> null && $productID <> null && $categoryID == null && $date <> null)
        {
            $record = ProductMovement::leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->where('product_movements.storeID', $storeID)
                ->where('product_movements.productID', $productID)
                ->where('product_movements.transactionDate', $date)
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->select('*', DB::raw("SUM(move_in) as totalIn"), DB::raw("SUM(move_out) as totalOut"), 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.id', 'Desc')
                ->groupBy('product_movements.productID')
                ->get();
        }elseif($storeID <> null && $productID == null && $categoryID == null && $date <> null)
        {
            $record = ProductMovement::leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->where('product_movements.storeID', $storeID)
                ->where('product_movements.transactionDate', $date)
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->select('*', DB::raw("SUM(move_in) as totalIn"), DB::raw("SUM(move_out) as totalOut"), 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.id', 'Desc')
                ->groupBy('product_movements.productID')
                ->get();
        }elseif($storeID == null && $productID <> null && $categoryID == null && $date <> null)
        {
            $record = ProductMovement::leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->where('product_movements.productID', $productID)
                ->where('product_movements.transactionDate', $date)
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->select('*', DB::raw("SUM(move_in) as totalIn"), DB::raw("SUM(move_out) as totalOut"), 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.id', 'Desc')
                ->groupBy('product_movements.productID')
                ->get();
        }elseif($storeID <> null && $productID == null && $categoryID <> null && $date == null)
        {
            $record = ProductMovement::leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->where('product_movements.storeID', $storeID)
                ->where('products.categoryID', $categoryID)
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->select('*', DB::raw("SUM(move_in) as totalIn"), DB::raw("SUM(move_out) as totalOut"), 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.id', 'Desc')
                ->groupBy('product_movements.productID')
                ->get();
        }elseif($storeID <> null && $productID == null && $categoryID <> null && $date <> null)
        {
            $record = ProductMovement::leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->where('product_movements.storeID', $storeID)
                ->where('products.categoryID', $categoryID)
                ->where('product_movements.transactionDate', $date)
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->select('*', DB::raw("SUM(move_in) as totalIn"), DB::raw("SUM(move_out) as totalOut"), 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.id', 'Desc')
                ->groupBy('product_movements.productID')
                ->get();
        }elseif($storeID == null && $productID == null && $categoryID <> null && $date <> null)
        {
            $record = ProductMovement::leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->where('products.categoryID', $categoryID)
                ->where('product_movements.transactionDate', $date)
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->select('*', DB::raw("SUM(move_in) as totalIn"), DB::raw("SUM(move_out) as totalOut"), 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.id', 'Desc')
                ->groupBy('product_movements.productID')
                ->get();
        }else{
            $record = ProductMovement::leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->select('*', DB::raw("SUM(move_in) as totalIn"), DB::raw("SUM(move_out) as totalOut"), 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.id', 'Desc')
                ->groupBy('product_movements.productID')
                ->get();
        }
        
        return $record;
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



}//end class
