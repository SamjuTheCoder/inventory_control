<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use DB;
use Auth;
use Session;

class ItemTrailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function Trails(Request $request)
    {
        
        $data['store']=$request->input('store');
        $data['product']=$request->input('product');
        $data['fromdate']= $request->input('fromdate'); date('Y-m-d', strtotime($request->input('fromdate'))) ;
        //$data['fromdate']= date('Y-m-d', strtotime($request->input('fromdate'))) ;
        $data['todate']= $request->input('todate'); //date('Y-m-d', strtotime($request->input('todate'))) ;
        if($data['todate']==""){$data['todate']=date("d-m-Y");}
        if($data['fromdate']==""){$data['fromdate']=date("d-m-Y");}
        $modelProduct           =   new Product;
        $data['getProduct'] = $modelProduct::orderby('productName')->get();
        $modelCategory    =   new Category;
        $data['Category']=$modelCategory::Get();

        $data['opening'] = $this->FormatQTY($data['product'], $this->Opening($data['product'],$data['store'],date('Y-m-d', strtotime($data['fromdate']))));
        $data['TrailRecords'] = $this->TrailRecords($data['product'],$data['store'],date('Y-m-d', strtotime($data['fromdate'])),date('Y-m-d', strtotime($data['todate'])));
        //$data['TrailRecords'] = $this->TrailRecords(1,$data['store'],'2021-01-01',$data['todate']);
        $data['getStore'] = DB::table('stores')->get();
        //dd($data['TrailRecords']);
     return view('Report.stock_trails', $data);
         
    }
    
    Public function Opening($stockid,$warehouse,$from) {
		$q_warehouse=($warehouse)?"`storeID`='$warehouse'":"1";
		return DB::Select("SELECT Sum(`move_in`-`move_out`)as Opening FROM `product_movements` WHERE DATE_FORMAT(`transactionDate`,'%Y-%m-%d')<'$from' and $q_warehouse and `productID`='$stockid' and `status`=1")[0]->Opening;	
	}
    Public function TrailRecords($stockid,$warehouse,$from,$to) {
		$q_warehouse=($warehouse)?"`storeID`='$warehouse'":"1";
		$opening = DB::Select("SELECT Sum(`move_in`-`move_out`)as Opening FROM `product_movements` WHERE DATE_FORMAT(`transactionDate`,'%Y-%m-%d')<'$from' and $q_warehouse and `productID`='$stockid' and `status`=1")[0]->Opening;
		$timedate= "(DATE_FORMAT(`transactionDate`,'%Y-%m-%d') BETWEEN '$from' AND '$to')";
		$q=DB::Select(" SELECT *
		,  (@csum := @csum +`move_in`-`move_out`) as `current`  
		FROM `product_movements` 
		JOIN (SELECT @csum := '$opening') r WHERE  $timedate and $q_warehouse and `productID`='$stockid' and `status`=1
		order by DATE_FORMAT(`transactionDate`,'%Y-%m-%d') ,`id`");
		foreach ($q as  $value) {
			$value->inQTY = $this->FormatQTY($stockid, $value->move_in);
			$value->outQTY = $this->FormatQTY($stockid, $value->move_out);
			$value->curQTY = $this->FormatQTY($stockid, $value->current);	
		}
		return $q;
	}
    function FormatQTY($item, $qty) {
        $q1=db::table('measurement_units')->select('measurement_units.*','measurements.description')
        ->leftJoin('measurements','measurements.id','measurement_units.measurementID')
        ->orderby('quantity', 'desc')->where('productID',$item)->get();
        //dd($q1);
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
