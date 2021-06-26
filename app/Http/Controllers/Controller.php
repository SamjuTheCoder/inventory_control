<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ErrorCaughtModel;
use App\Models\Store;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //get product balance.
    public function getProductQtyBalance($productID = null, $quantity = null, $storeID = null) 
    {  
        $data = null;
        $data['validationPass']     = false;
        $data['totalProductOut']    = 0;
        $data['totalProductIn']     = 0;
        $data['productAvailable']   = 0;
        $data['success']            = 0;
        $data['getAllProducts']     = null;
        $totalInStore   = 0;
        $totalOutStore  = 0;

        $inProduct = DB::table('product_movements')
                        ->select(DB::raw("SUM(move_in) as totalIn"))
                        ->where('product_movements.productID', $productID)
                        ->where('product_movements.status', 1)
                        ->where('product_movements.storeID', ($storeID ? '=' : '<>'), $storeID)
                        ->first();

        $getAllProducts = DB::table('product_movements')
                        ->select(DB::raw("SUM(move_in) as totalIn"), DB::raw("SUM(move_out) as totalOut"), 'move_in','move_out')
                        ->where('product_movements.productID', $productID)
                        ->where('product_movements.storeID', ($storeID ? '=' : '<>'), $storeID)
                        //->where('product_movements.status', 1)
                        //->where('product_movements.is_accepted', 1)
                        //->where('product_movements.is_adjusted', 0)
                        ->first();
        if($getAllProducts)
        {
            $totalInStore   = $inProduct->totalIn;
            $totalOutStore  = $getAllProducts->totalOut;
            $productAvailable = ((int)$totalInStore - (int)$totalOutStore);
            if(((int)$quantity <= $productAvailable) && $productAvailable > 0)
            {
                $data['validationPass'] = true;
            }
            $data['totalProductOut']    = (int)$totalOutStore;
            $data['totalProductIn']     = (int)$totalInStore;
            $data['productAvailable']   = ($productAvailable < 0 ? '('. $productAvailable .')' : $productAvailable );
            $data['success']            = ($productAvailable > 0 ? 1 : 0);
        }
        $data['getAllProducts'] = $getAllProducts;
        $data['getQuantity']    = $quantity;

        return $data;
   }



     //Return No Value : Void - Store any error that occurred in try-catch block
     public function storeTryCatchError($getError = null, $getFunctionModuleName = null, $errorDescription = null )
     {
         try{
             return ErrorCaughtModel::create([
                 'throwable_error'       => ($getError <> null ? $getError : 'No error occured'),
                 'function_module_name'  => $getFunctionModuleName,
                 'error_description'     => $errorDescription,
                 'created_at'            => date('Y-m-d h:i:sa'),
                 'updated_at'            => date('Y-m-d h:i:sa')
             ]);
         }catch(\Throwable $errorThrown){}
     }

     public function getStores()
     {
        $stores = Store::get();
        return $stores;
     }
     public function getShelve()
     {
        $shelve = DB::table('shelves')
        ->join('stores','stores.id','=','shelves.storeID')
        ->select('*','shelves.id as shelveID')
        ->get(); 
        return $shelve;
     }
     public function getUsers()
     {
        $users = DB::table('users')
        ->get();
        return $users;
     }

     //Function To convert product/Item to max. units ever.
     function FormatQTY($item, $qty) 
     {
         
        $q1=db::table('measurement_units')->select('measurement_units.*','measurements.description')
        ->leftJoin('measurements','measurements.id','measurement_units.measurementID')
        ->orderby('quantity', 'desc')->where('productID',$item)->get();
        $qty1=$qty;
	    $data='';
        foreach ($q1 as $b){
	        $formatqty= $b->quantity;
	        if($formatqty==0)$formatqty=1;
    	    $q = intval($qty / $formatqty);
            $qty = $qty % $formatqty;
        	if($q<>0){
        	   $data.= ' '.Abs($q).$b->description;
        	 }    
	    }
	   return (int)$qty1 <0 ? '('.$data.')':$data;
    }

}
