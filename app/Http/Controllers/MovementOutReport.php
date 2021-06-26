<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Project;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductMovement;

use DB;
use Auth;
use Session;

class MovementOutReport extends Controller
{
    //
    public function __construct() 
    {
        $this->middleware('auth');
    }

    public function launchReport() {

        $data['details']=null;
        
        $data['projectx'] = '';
        $data['productx'] = '';
        $data['storex'] = '';
        $data['datefrom'] = '';
        $data['dateto'] = '';

        $data['project'] = Project::all();
        $data['product'] = Product::all();
        $data['store'] = Store::all();

        $data['projects'] = DB::table('projects')
        ->leftjoin('clients','projects.clientID','=','clients.id')
        ->select('*','projects.id as pid','projects.location as plocation')
        ->get();

        return view('Report.movementout', $data);
    }

    public function getReport(Request $request) {

        $data['projectx'] = $request->project;
        $data['productx'] = $request->product;
        $data['storex'] = $request->store;
        $data['datefrom'] = $request->date_from;
        $data['dateto'] = $request->date_to;

        $data['project'] = Project::all();
        $data['product'] = Product::all();
        $data['store'] = Store::all();

        
        $data['details'] = ProductMovement::where('userID','=',Auth::user()->id)
	    ->where('storeID',($request->store?'=':'<>'),$request->store)
	    ->where('projectID',($request->project?'=':'<>'),$request->project)
        ->where('productID',($request->product?'=':'<>'),$request->product)
        ->where('move_out','>',0)
	    ->orwhereBetween('transactionDate',[$request->date_from,$request->date_to])
	    ->leftjoin('stores', 'product_movements.storeID', '=', 'stores.id')
	    ->leftjoin('projects', 'product_movements.projectID', '=', 'projects.id')
	    ->leftjoin('products', 'product_movements.productID', '=', 'products.id')
        ->select('*','product_movements.productID as pid','products.productName')
        ->groupBy('product_movements.productID')
	    ->get();

        $data['sum'] = ProductMovement::where('userID','=',Auth::user()->id)
	    ->where('storeID',($request->store?'=':'<>'),$request->store)
	    ->where('projectID',($request->project?'=':'<>'),$request->project)
        ->where('productID',($request->product?'=':'<>'),$request->product)
        ->where('move_out','>',0)
	    ->orwhereBetween('transactionDate',[$request->date_from,$request->date_to])
	    ->sum('product_movements.move_out');
       
        return view('Report.movementout', $data);
        
    }

    public function viewAll($id)
    {
        $data['id'] = base64_decode($id);

        $data['details'] = ProductMovement::where('userID','=',Auth::user()->id)
        ->where('productID',$data['id'])
        ->where('move_out','>',0)
	    ->leftjoin('stores', 'product_movements.storeID', '=', 'stores.id')
	    ->leftjoin('projects', 'product_movements.projectID', '=', 'projects.id')
	    ->leftjoin('products', 'product_movements.productID', '=', 'products.id')
	    ->get();

        return view('Report.viewall', $data);
    }
}
