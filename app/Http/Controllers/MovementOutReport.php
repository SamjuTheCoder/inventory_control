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

        //dd('kk');

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

        $data['details'] = ProductMovement::where('userID','=',Auth::user()->id)
        ->where('move_out','>',0)
        ->where('product_movements.status','=',1)
        ->where('is_adjusted',0)
        ->leftjoin('stores', 'product_movements.storeID', '=', 'stores.id')
        ->leftjoin('products', 'product_movements.productID', '=', 'products.id')
        ->select('*', DB::raw("SUM(move_out) as totalIn"), 'product_movements.productID as pid','products.productName')
        ->groupBy('product_movements.productID')
        ->get();

        foreach($data['details'] as $xyz) {
            $xyz->formatqty=$this->FormatQTY($xyz->productID,($xyz->totalIn) );
        }

        return view('Report.movementout', $data);
    }

    public function getReport(Request $request) {

       // dd($request->date_from);

        $data['projectx'] = $request->project;
        $data['productx'] = $request->product;
        $data['storex'] = $request->store;
        $data['datefrom'] = $request->date_from;
        $data['dateto'] = $request->date_to;

        Session::put('datefrom',$request->date_from);
        Session::put('dateto',$request->date_to);

        $data['project'] = Project::all();
        $data['product'] = Product::all();
        $data['store'] = Store::all();

        if($request->date_from==null) {
            
            $data['details'] = ProductMovement::where('userID','=',Auth::user()->id)
            ->where('storeID',($request->store?'=':'<>'),$request->store)
            ->where('projectID',($request->project?'=':'<>'),$request->project)
            ->where('productID',($request->product?'=':'<>'),$request->product)
            ->where('move_out','>',0)
            ->where('product_movements.status','=',1)
            ->where('is_adjusted',0)
            ->leftjoin('stores', 'product_movements.storeID', '=', 'stores.id')
            ->leftjoin('projects', 'product_movements.projectID', '=', 'projects.id')
            ->leftjoin('products', 'product_movements.productID', '=', 'products.id')
            ->select('*', DB::raw("SUM(move_in) as totalIn"),DB::raw("SUM(move_out) as sum"), 'product_movements.productID as pid','products.productName')
            ->groupBy('product_movements.productID')
            ->get();
            

        }
        else {

            $data['details'] = ProductMovement::where('userID','=',Auth::user()->id)
            ->where('storeID',($request->store?'=':'<>'),$request->store)
            ->where('projectID',($request->project?'=':'<>'),$request->project)
            ->where('productID',($request->product?'=':'<>'),$request->product)
            ->where('move_out','>',0)
            ->where('product_movements.status','=',1)
            ->where('is_adjusted',0)
            ->whereBetween('transactionDate',[date('Y-m-d', strtotime($request->date_from)), date('Y-m-d', strtotime($request->date_to))])
            ->leftjoin('stores', 'product_movements.storeID', '=', 'stores.id')
            ->leftjoin('projects', 'product_movements.projectID', '=', 'projects.id')
            ->leftjoin('products', 'product_movements.productID', '=', 'products.id')
            ->select('*', DB::raw("SUM(move_in) as totalIn"),DB::raw("SUM(move_out) as sum"), 'product_movements.productID as pid','products.productName')
            ->groupBy('product_movements.productID')
            ->get();

        }

        foreach($data['details'] as $xyz) {
            $xyz->formatqty=$this->FormatQTY($xyz->productID,($xyz->sum) );
        }

        return view('Report.movementout', $data);
        
    }

    public function viewAll($id)
    {
        //dd(base64_decode(Session::get('datefrom')));
        $datefrom = Session::get('datefrom');
        $dateto = Session::get('dateto');

        $data['id'] = base64_decode($id);

        if($datefrom==null) {
            
            $data['details'] = ProductMovement::where('userID','=',Auth::user()->id)
            ->where('productID',$data['id'])
            ->where('move_out','>',0)
            ->where('product_movements.status','=',1)
            ->where('is_adjusted',0)
            //->whereBetween('transactionDate',[date('Y-m-d', strtotime($datefrom)), date('Y-m-d', strtotime($dateto))])
            ->leftjoin('stores', 'product_movements.storeID', '=', 'stores.id')
            ->leftjoin('projects', 'product_movements.projectID', '=', 'projects.id')
            ->leftjoin('products', 'product_movements.productID', '=', 'products.id')
            ->get();
        }
        else {

            $data['details'] = ProductMovement::where('userID','=',Auth::user()->id)
            ->where('productID',$data['id'])
            ->where('move_out','>',0)
            ->where('product_movements.status','=',1)
            ->where('is_adjusted',0)
            ->whereBetween('transactionDate',[date('Y-m-d', strtotime($datefrom)), date('Y-m-d', strtotime($dateto))])
            ->leftjoin('stores', 'product_movements.storeID', '=', 'stores.id')
            ->leftjoin('projects', 'product_movements.projectID', '=', 'projects.id')
            ->leftjoin('products', 'product_movements.productID', '=', 'products.id')
            ->get();

        }

        foreach($data['details'] as $xyz) {
           $xyz->formatqty=$this->FormatQTY($xyz->productID,($xyz->move_out) );
        }

        return view('Report.viewall', $data);
    }
}
