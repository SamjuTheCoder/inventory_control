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
        ->join('stores','stores.id','=','product_movements.storeID')
        ->join('products','products.id','=','product_movements.productID')
        ->join('projects','projects.id','=','product_movements.projectID')
        ->where('move_out','>', 0)
        ->where('orderNo','=', $id)
        ->select('*','product_movements.id as prodMovementID')
        ->get();
        return view('withdrawOrder.itemsInOrder',$data);
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

    
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
