<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\productMovement;

class OrdersMovedOutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $data['orders'] = DB::table('product_movements')
        ->join('stores','stores.id','=','product_movements.storeID')
        ->join('products','products.id','=','product_movements.productID')
        ->join('projects','projects.id','=','product_movements.projectID')
        ->where('move_out','>', 0)
        ->where('orderNo','!=', '')
        ->select('*', 'product_movements.id as prodMovementID')
        ->orderBy('transactionDate','desc')
        ->groupBy('orderNo')
        ->get();
        return view('withdrawOrder.allOrders',$data);
    }

 
    public function show($id)
    {
        $checkOrder = DB::table('product_movements')->where('orderNo','=', $id)->count();
        if($checkOrder == 0)
        {
            return back()->with('err','This order does not exist');
        }

        $data['orders'] = DB::table('product_movements')
        ->leftjoin('stores','stores.id','=','product_movements.storeID')
        ->leftjoin('products','products.id','=','product_movements.productID')
        ->leftjoin('projects','projects.id','=','product_movements.projectID')
        ->join('measurement_units',function($join){
            $join->on('measurement_units.measurementID','=','product_movements.measurementID')->on('product_movements.productID','=','measurement_units.productID');
        })
        ->leftjoin('measurements','measurements.id','=','measurement_units.measurementID')
        ->where('move_out','>', 0)
        ->where('orderNo','=', $id) 
        ->select('*','product_movements.id as prodMovementID','product_movements.measurementID as munit','measurement_units.quantity as qty','product_movements.productID as prodID','product_movements.quantity as outQty','measurements.id as measurementID','measurements.description as desc')
        ->get();
        $data['product'] = DB::table('product_movements')
        ->leftjoin('stores','stores.id','=','product_movements.storeID')
        ->leftjoin('products','products.id','=','product_movements.productID')
        ->leftjoin('projects','projects.id','=','product_movements.projectID')
        ->leftjoin('measurement_units','measurement_units.id','=','product_movements.measurementID')
        ->where('product_movements.orderNo','=', $id)->first();

        $data['isconfirm'] = DB::table('product_movements')->where('orderNo','=', $id)->where('isConfirmed','=', 2)->count();

         foreach($data['orders'] as $list) {
           $list->formatqty = $this->FormatQTY($list->prodMovementID,$list->move_out);
           $data['mesurements'] = DB::table('measurement_units')
           ->join('measurements','measurements.id','=','measurement_units.measurementID')
           ->where('measurement_units.productID','=', $list->prodID)
           ->select('*','measurement_units.id as measureID')
           ->orderBy('measurement_units.quantity','desc')
           ->get();

            
        }
        
        return view('withdrawOrder.itemsInOrder',$data);
    }

    public function orderItems($id)
    {
        $checkOrder = DB::table('product_movements')->where('orderNo','=', $id)->count();
        if($checkOrder == 0)
        {
            return back()->with('err','This order does not exist');
        }

        $data['orders'] = DB::table('product_movements')
        ->leftjoin('stores','stores.id','=','product_movements.storeID')
        ->leftjoin('products','products.id','=','product_movements.productID')
        ->leftjoin('projects','projects.id','=','product_movements.projectID')
        ->leftjoin('measurement_units','measurement_units.id','=','product_movements.measurementID')
        //->where('move_out','>', 0)
        ->where('orderNo','=', $id)
        ->select('*','product_movements.id as prodMovementID','product_movements.measurementID as munit','measurement_units.quantity as qty')
        ->get();
        $data['product'] = DB::table('product_movements')
        ->leftjoin('stores','stores.id','=','product_movements.storeID')
        ->leftjoin('products','products.id','=','product_movements.productID')
        ->leftjoin('projects','projects.id','=','product_movements.projectID')
        ->leftjoin('measurement_units','measurement_units.id','=','product_movements.measurementID')
        ->where('product_movements.orderNo','=', $id)->first();
        return view('withdrawOrder.orderItems',$data);
    }

    public function cancelOrder(Request $request)
    {
        $productMovementID = $request['item'];
        if(empty($productMovementID))
        {
            return back()->with('err','you must select at least one item');
        }
        foreach ($productMovementID as $key => $value) {
            try
            {
         $remove = ProductMovement::where('id','=',$request['item'][$key])->delete();
            return back()->with('msg','Order Cancelled');
            }
            catch(\Exception $e) {
                return back()->with('err',$e->getMessage());
            }

            
        }
    }

    
    public function adjustQuantity(Request $request)
    {
        $db = DB::table('measurement_units')->where('productID','=',$request['productID'])->where('measurementID','=',$request['measure'])->first();

        /*if(empty($db))
        {
            return back()->with('err','This unit is not set for this product');
        }
        else
        {
            $totalQty = $db->quantity * $request['quantity'];
        }
        */
        $update = DB::table('product_movements')->where('id','=',$request['productMovementID'])->update(
            [
                'measurementID' => $request['measure'],
                'quantity'      => $request['quantity'],
                'move_out'      => $totalQty,
            ]);
        return back()->with('msg','Successfully Updated');
    }

    public function resendProduct(Request $request)
    {
         $update = DB::table('product_movements')->where('id','=',$request['productMovementID'])->update(
            [
                'isConfirmed' => 0,
                
            ]);
         return back()->with('msg','Successful');
    }

    public function allConfirmed()
    {
        $data['orders'] = DB::table('product_movements')
        ->join('stores','stores.id','=','product_movements.storeID')
        ->join('products','products.id','=','product_movements.productID')
        ->join('projects','projects.id','=','product_movements.projectID')
        ->where('move_out','>', 0)
        ->where('isConfirmed','=', 1)
        ->where('orderNo','!=', '')
        ->select('*', 'product_movements.id as prodMovementID')
        ->groupBy('orderNo')
        ->get();
        return view('withdrawOrder.allOrders',$data);
    }
    public function eachedConfirmedItem($id)
    {
        $checkOrder = DB::table('product_movements')->where('orderNo','=', $id)->count();
        if($checkOrder == 0)
        {
            return back()->with('err','This order does not exist');
        }

        $data['orders'] = DB::table('product_movements')
        ->leftjoin('stores','stores.id','=','product_movements.storeID')
        ->leftjoin('products','products.id','=','product_movements.productID')
        ->leftjoin('projects','projects.id','=','product_movements.projectID')
        ->join('measurement_units',function($join){
            $join->on('measurement_units.measurementID','=','product_movements.measurementID')->on('product_movements.productID','=','measurement_units.productID');
        })
        ->leftjoin('measurements','measurements.id','=','measurement_units.measurementID')
        ->where('move_out','>', 0)
        ->where('orderNo','=', $id) 
        ->select('*','product_movements.id as prodMovementID','product_movements.measurementID as munit','measurement_units.quantity as qty','product_movements.productID as prodID','product_movements.quantity as outQty','measurements.id as measurementID','measurements.description as desc')
        ->get();
        $data['product'] = DB::table('product_movements')
        ->leftjoin('stores','stores.id','=','product_movements.storeID')
        ->leftjoin('products','products.id','=','product_movements.productID')
        ->leftjoin('projects','projects.id','=','product_movements.projectID')
        ->leftjoin('measurement_units','measurement_units.id','=','product_movements.measurementID')
        ->where('product_movements.orderNo','=', $id)->first();

        $data['isconfirm'] = DB::table('product_movements')->where('orderNo','=', $id)->where('isConfirmed','=', 2)->count();

         foreach($data['orders'] as $list) {
           $list->formatqty = $this->FormatQTY($list->prodMovementID,$list->move_out);
           $data['mesurements'] = DB::table('measurement_units')
           ->join('measurements','measurements.id','=','measurement_units.measurementID')
           ->where('measurement_units.productID','=', $list->prodID)
           ->select('*','measurement_units.id as measureID')
           ->orderBy('measurement_units.quantity','desc')
           ->get();

            
        }
        
        return view('withdrawOrder.itemsInOrder',$data);
    }

}
