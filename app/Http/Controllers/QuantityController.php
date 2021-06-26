<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
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
use DB;


class QuantityController extends Controller
{
    //class contructor
    public function __construct()
    {
        $this->middleware('auth');
    }


    //create measurement
    public function show()
    {
        $reasons = db::table('adjustment_reasons')->get();
        //dd($reasons);
        Session::forget('editRecord');
        return view('quantitycontrol.display', $this->indexData())->with('reasons',$reasons);
    }//end fun

    function indexData()
    {
        $data['getRecords']     = [];
        $data['getStore']       = [];
        $data['getShelf']       = [];
        $data['getMeasurement'] = [];
        $data['getProduct']     = [];

        try{ 
            $data['getRecords']     = $this->getProductEntered();
            $data['getStore']       = $this->getUserStore(); //you can reuse this function by passing one parameter: userID
            //$data['getShelf']       = Shelf::all();
            $data['getProject']     = Project::all();
            $data['getProduct']     = Product::all();
            (Session::get('editRecord') ? $data['editRecord'] = Session::get('editRecord') : null);
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMovementOutController@createProductMovement', 'Error occurred when fetching records.' );
        }
        return $data;
    }
    public function getProductMeasurement($productID = null)
    {
        $getMeasurement = [];
        if(Product::find($productID))
        {
            $getMeasurement = MeasurementUnitModel::where('measurement_units.productID', $productID)
                            ->join('measurements', 'measurements.id', '=', 'measurement_units.measurementID')
                            ->select('description', 'measurementID','quantity')
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
    public function getProductEntered($recordID = null)
    {
        try{
            if($recordID && is_numeric($recordID))
            {
                return ProductMovement::where('product_movements.id', $recordID)
                    ->where('product_movements.status', 0)
                    ->where('product_movements.is_adjusted', 1)
                    //->where('move_in_out_type', 2)
                    ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
                    ->leftjoin('adjustment_reasons', 'adjustment_reasons.reasonID', '=', 'product_movements.adjust_reason')
                    ->leftjoin('measurements', 'measurements.id', '=', 'product_movements.measurementID')
                    ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                    ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                    ->select('*', 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'measurements.id as measurementID', 'measurements.description as measureName', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated',
                    'adjustment_reasons.description as reason')
                    ->orderBy('product_movements.id', 'Desc')
                    ->first();
            }else{
                return ProductMovement::leftjoin('products', 'products.id', '=', 'product_movements.productID')
                    ->where('product_movements.status', 0)
                    ->where('product_movements.is_adjusted', 1)
                    //->where('move_in_out_type', 2)
                    ->leftjoin('adjustment_reasons', 'adjustment_reasons.reasonID', '=', 'product_movements.adjust_reason')
                    ->leftjoin('measurements', 'measurements.id', '=', 'product_movements.measurementID')
                    ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                    ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                    ->select('*', 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'measurements.id as measurementID', 'measurements.description as measureName', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated',
                    'adjustment_reasons.description as reason')
                    ->orderBy('product_movements.id', 'Desc')
                    ->get();
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'createProductGoingOut@getProductEntered', 'Error occurred when fetching records.' );
        }
        return;
    }
    public function getReasons(Request $request)
    {
        $type = $request->type;
        $reasons = db::table('adjustment_reasons')->where('reasonType',$type)->get();
        return($reasons);

    }
    public function storeProductMovement(Request $request)
    {
        //Initialization
        $saved = null;
        //validation
        $this->validate($request,
        [
            'store'             => ['required', 'numeric'],
            //'project'           => ['required', 'numeric'],
            'type'          =>['required'],
            'reason'        =>['required'],
            'product'           => ['required', 'numeric'],
            'measure'           => ['required', 'numeric'],
            'quantity'          => ['required', 'numeric', 'min: 1'],

        ]);
        $store = $request->store;
        
        $type = $request->type;
        $current_batch = db::table('product_movements')->where('is_adjusted',1)->where('status',0)->first();
        if($current_batch==null){
            $current_store = $store;
            $current_move = $type;
        }
        else{
            $current_store = $current_batch->storeID; 
            $current_move = $current_batch->move_in_out_type;
        }
        if($store!=$current_store){
            return back()->withInput($request->input())->with('error', 'Please batch items in current store before processing another store');

        }
        if($type!=$current_move){
            return back()->withInput($request->input())->with('error', 'Please batch items with current movement type before processsing another type');

        }
        
        if($type==2){
        $getProductLeast = MeasurementUnitModel::where('measurementID', $request['measure'])->where('productID', $request['product'])->value('quantity');
        $cur_quantity = $getProductLeast * $request->quantity;
        
        $totalfit = db::table('product_movements')->where('productID',$request->product)->where('storeID',$request->store)->where('status',1)->get();
        $nexttotalfit = db::table('product_movements')->where('productID',$request->product)->where('storeID',$request->store)->where('status',0)->where('is_adjusted',1)->get();
        
        $sum = 0;
        $nextsum = 0;
        foreach($totalfit as $totals){
            $sum = $sum + $totals->move_in;
            $sum = $sum - $totals->move_out;
        }
        
        foreach($nexttotalfit as $nexttotals){
            $nextsum = $nextsum + $nexttotals->move_in;
            
            $nextsum = $nextsum - $nexttotals->move_out;
            
        }
        
        $sum = $sum + $nextsum;
        $getMeasure = $this->FormatQTY($request->product,$sum);
        //$getMeasure = $sum;
        if($cur_quantity>$sum){
            return back()->withInput($request->input())->with('error', 'Sorry, the quantity you want to move out is more than the available quantity. Available quantity is '.$getMeasure)->with('getLeast',$getProductLeast);
        }
        }
        $recordID = $request['getRecord'];
       
        //DB transactions
        try{
            //Check product availability
            $totalIn = ProductMovement::where('move_in_out_type', 1)->where('status', 1)->select(DB::raw("SUM(move_in) as totalIn"))->first();
            //$totalOut = ProductMovement::where('move_in_out_type', 2)->where('status', 1)->select(DB::raw("SUM(move_out) as totalOut"))->first();
          /*  if($request['quantity'] > $totalIn->totalIn)
            {
                return redirect('/quantity-control')->with('error', 'Sorry, the quantity you want to move out is more than the available quantity. Available quantity is '. ($totalIn ? $totalIn->totalIn : 0.0) .'.');
            } */

           
            if(ProductMovement::find($recordID))
            {
                if($type==2){
                $saved = ProductMovement::where('id', $recordID)->update(
                    [
                        'userID'            => (Auth::check() ? Auth::user()->id : null),
                        'storeID'           => $request['store'],
                        'productID'         => $request['product'],
                        'measurementID'     => $request['measure'],
                        'adjust_reason'     => $request['reason'],
                        'is_adjusted'         => "1",
                        'move_out'           => (is_numeric($request['quantity']) ? ($getProductLeast * $request['quantity']) : 1),
                        'quantity'          => $request['quantity'],
                        //'projectID'         => $request['project'],
                        'move_in_out_type'  => $type,
                        
                    ]);
                }
                else{
                    
                    $saved = ProductMovement::where('id', $recordID)->update(
                        [
                            'userID'            => (Auth::check() ? Auth::user()->id : null),
                            'storeID'           => $request['store'],
                            'productID'         => $request['product'],
                            'measurementID'     => $request['measure'],
                            'adjust_reason'     => $request['reason'],
                            'is_adjusted'          => "1",
                            'move_out'           => (is_numeric($request['quantity']) ? ($getProductLeast * $request['quantity']) : 1),
                            'quantity'          => $request['quantity'],
                            //'projectID'         => $request['project'],
                            'move_in_out_type'  => $type,
                            
                        ]);

                }
            }else{
                if($type==2){
                $saved = ProductMovement::create(
                    [
                        'userID'            => (Auth::check() ? Auth::user()->id : null),
                        'storeID'           => $request['store'],
                        'productID'         => $request['product'],
                        'measurementID'     => $request['measure'],
                        'adjust_reason'     => $request['reason'],
                        'is_adjusted'          => 1,
                        'move_out'           => (is_numeric($request['quantity']) ? ($getProductLeast * $request['quantity']) : 1),
                        'quantity'          => $request['quantity'],
                        //'projectID'         => $request['project'],
                        'move_in_out_type'  => $type,
                        
                    ]);
                }
                else{
                    $getProductLeast = MeasurementUnitModel::where('measurementID', $request['measure'])->where('productID', $request['product'])->value('quantity');
                    $saved = ProductMovement::create(
                        [
                            'userID'            => (Auth::check() ? Auth::user()->id : null),
                            'storeID'           => $request['store'],
                            'productID'         => $request['product'],
                            'measurementID'     => $request['measure'],
                            'adjust_reason'     => $request['reason'],
                            'is_adjusted'          => 1,
                            'move_in'           => (is_numeric($request['quantity']) ? ($getProductLeast * $request['quantity']) : 1),
                            'quantity'          => $request['quantity'],
                            //'projectID'         => $request['project'],
                            'move_in_out_type'  => $type,
                            
                        ]);
                       

                }
                
            }
        
            $data['store']   = $request['store'];
            $data['project'] = $request['project'];
            if($saved)
            {
                Session::forget('editRecord');
                return redirect('/quantity-control')->withInput($request->only('store','type'))->with('message', 'Update Completed successfully');
                //return view('products.productMovement.itemEntryOut', $data, $this->indexData())->with('message', 'New product was created/updated successfully.');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMovementOutController@storeProductMovement', 'Error occurred when adding/updating product.' );
        }
        return redirect('/quantity-control')->withInput($request->all)->with('error', 'Sorry, we cannot create/update your record now. Please try again.')->with('getLeast',$getProductLeast);
    }//end fun

    public function editProductMovement($recordID)
    {
        //DB transactions
        Session::forget('editRecord');
        try{
            $getRecord      = ProductMovement::find($recordID);
           
            if($getRecord)
            {
                
                Session::put('editRecord', $this->getProductEntered($recordID));
                return redirect('/quantity-control');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'QuantityController@editProductMovement', 'Error occurred when editting record.' );
        }
        return redirect('/quantity-control')->with('error', 'Sorry, we cannot edit this record now. Please try again.');
    }//end fun
    public function editQC(Request $request)
    {
        $this->validate($request,
        [
            'id'             => ['required', 'numeric'],
            'quantity'          =>['required', 'numeric'],

        ]);
        $product= ProductMovement::find($request->id);
        if($product->move_in_out_type==2){
            $getProductLeast = MeasurementUnitModel::where('measurementID', $product->measurementID)->where('productID',  $product->productID)->value('quantity');
            $cur_quantity = $getProductLeast * $request->quantity;
            
            $totalfit = db::table('product_movements')->where('productID',$product->productID)->where('storeID',$product->storeID)->where('status',1)->get();
            $nexttotalfit = db::table('product_movements')->where('productID',$product->productID)->where('storeID',$product->storeID)->where('status',0)->where('is_adjusted',1)->get();
            
            $sum = 0;
            $nextsum = 0;
            foreach($totalfit as $totals){
                $sum = $sum + $totals->move_in;
                $sum = $sum - $totals->move_out;
            }
            
            foreach($nexttotalfit as $nexttotals){
                $nextsum = $nextsum + $nexttotals->move_in;
                $nextsum = $nextsum - $nexttotals->move_out;
            }
            $sum = $sum + $nextsum + $product->move_out;
            $getMeasure = $this->FormatQTY($request->product,$sum);
            //$getMeasure = $sum/$getProductLeast;
            if($cur_quantity>$sum){
                return redirect('/quantity-control')->with('error', 'Sorry, the quantity you want to move out is more than the available quantity. Available quantity is '.$getMeasure)->with('getLeast',$getProductLeast);
            }
            }
        ProductMovement::find($request->id)->update(
            [
                'quantity'            => $request->quantity,
                'move_out'            => $getProductLeast * $request->quantity
            ]);
        return redirect('/quantity-control')->with('success', 'Quantity Edited Succesfully');
    }//end fun

    public function deleteProductMovement($recordID)
    {
        //DB transactions
        try{
            
            $getRecord = ProductMovement::find($recordID);
            if($getRecord  && ($getRecord->isConfirmed <= 0))
            {
                $getRecord->delete();
                return redirect('/quantity-control')->with('message', 'Your record was deleted successfully.');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMovementOutController@deleteProductMovement', 'Error occurred when deleting record.' );
        }
        return redirect('/quantity-control')->with('error', 'Sorry, we cannot delete this record now. Please try again.');
    }//end fun

    public function batchAllNewProducts(Request $request)
    {
        $isSuccess = 0;
        $this->validate($request,
        [
            'transactionDate'   => ['required', 'date'],
            'reason'            =>['required']
        ]);
        //try{
            $randomDigit = rand(98979489, 8357373853);
            $isSuccess = ProductMovement::where('status', 0)->where('is_adjusted', 1)->update([
                'status'            => 1,
                'orderNo'           => $randomDigit,
                'description'       => $request->reason,
                'transactionDate'   => date('Y-m-d', strtotime($request['transactionDate']))
            ]);
        //}catch(\Throwable $err){}
        if($isSuccess)
        {
            return redirect()->back()->with('success', 'All unbatched items are now batched successfully as a single entry with the same order number: '. $randomDigit);
        }else{
            return redirect()->back()->with('warning', 'Sorry, we cannot batch you items now or no items to be batched. Please try again.');
        }
    }

    public function showReport()
    {

        $movements = db::table('product_movements')->join('stores','stores.id','=','product_movements.storeID')
        ->join('products','products.id','=','product_movements.productID')
        ->join('measurements','measurements.id','=','product_movements.measurementID')
        ->where('product_movements.status','1')
        ->where('product_movements.is_adjusted','1')
        ->select('product_movements.*','measurements.description as measureName','stores.store_name as storeName',
        'products.productName as productName')
        ->get();
        $batches=[];
        foreach($movements as $key=>$newBatch){
            if($key==0){

                array_push($batches,$newBatch);
                
            }
            else{
                foreach($batches as $newKey=>$batch){
                    if($newBatch->orderNo==$batch->orderNo){
    
                    }
                    else{
                        if($newKey==count($batches)-1){
                            array_push($batches,$newBatch);
                        }
                        else{
    
                        }
                    }
                }
            }
          }
        
        return view('quantitycontrol.displayReport',$this->indexData())->with('products',$batches)->with('batches',$batches);
    }//end fun

    public function getQuery(Request $request)
    {
        $this->validate($request,
        [
            'store'   => ['required'],
        ]);
      $query = db::table('product_movements')->where('product_movements.storeID','=',$request->store);
      if($request->type!=null){
          $query = $query->where('product_movements.move_in_out_type','=',$request->type);
      }
      if($request->dateFrom!=null){
         $dateFrom = date("Y-m-d",strtotime($request->dateFrom));
        $query = $query->where('product_movements.transactionDate','>=',$dateFrom);
    }
    if($request->dateTo!=null){
        $dateTo = date("Y-m-d",strtotime($request->dateTo));
       $query = $query->where('product_movements.transactionDate','<=',$dateTo);
   }
      $query = $query ->join('products','products.id','=','product_movements.productID')
      ->join('stores','stores.id','=','product_movements.storeID')
      ->join('measurements','measurements.id','=','product_movements.measurementID')
      ->where('product_movements.status','1')
      ->where('product_movements.is_adjusted','1')
      ->select('product_movements.*','measurements.description as measureName','stores.store_name as storeName',
      'products.productName as productName')
      ->get();
      $batches = [];
      foreach($query as $key=>$newBatch){
        if($key==0){
            array_push($batches,$newBatch);
        }
        else{
            foreach($batches as $newKey=>$batch){
                if($newBatch->orderNo==$batch->orderNo){

                }
                else{
                    if($newKey==count($batches)-1){
                        array_push($batches,$newBatch);
                    }
                    else{

                    }
                }
            }
        }
      }
    
      $request->flash();
      return view('quantitycontrol.displayReport',$this->indexData())->withInput($request->input())->with('products',$batches)->with('batches',$batches);
    }//end fun

    public function quantityControlView($id){
            $id = base64_decode($id);
            
            $product = db::table('product_movements')->join('stores','stores.id','=','product_movements.storeID')
        ->join('products','products.id','=','product_movements.productID')
        ->join('measurements','measurements.id','=','product_movements.measurementID')
        ->where('product_movements.orderNo',$id)
        ->select('product_movements.*','measurements.description as measureName','stores.store_name as storeName',
        'products.productName as productName')
        ->get();
        foreach($product as $p){
            if($product[0]->move_in_out_type==1){
                $getMeasure = $this->FormatQTY($p->productID,$p->move_in);
            }
            else{
                $getMeasure = $this->FormatQTY($p->productID,$p->move_out);
            }
            $p->moved = $getMeasure;
            
        }
        return view('quantitycontrol.displayProduct',$this->indexData())->with('products',$product);
    }
}