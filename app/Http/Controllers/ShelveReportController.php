<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Shelf;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductMovement;
use Session;
use Auth;

class ShelveReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function index()
    // {
    
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['productx'] = '';
        $data['stores'] = DB::table('Stores')->get(); 
        $data['allShelves'] = DB::table('Shelves')->get();
        $data['product']    = DB::table('Products')->get();
        return view('Report.ProductInShelve',$data);
    }

    public function getProductShelveReport(Request $request) 
    {
        
        
        $this->validate( $request, [
            'store'     => 'required|string',
            //'project'   => 'required|numeric',
            'product'   => 'required|string',
        ]);    
        $product = $request->get('product');
        $store = $request->get('store');
        
        //$data['getReport'] = DB::table('products_movements')->where('')->first();
        $data['getReport'] = ProductMovement::where('userID','=',Auth::user()->id)
            ->leftjoin('stores', 'product_movements.storeID', '=', 'stores.id')
            ->leftjoin('products', 'product_movements.productID', '=', 'products.id')
            ->leftjoin('shelves', 'product_movements.shelveID', '=', 'shelves.id')
            ->select('*','product_movements.productID as pid','products.productName')
            ->where('ProductID', $product)
            ->where('storeID', $store)
            ->paginate(50);
        return view('Report.ProductInShelve', $data);


    //     //dd($request->date_from);
    //     $data['details'] = ProductMovement::where('userID','=',Auth::user()->id)
	//     ->where('storeID',($request->store?'=':'<>'),$request->store)
	//    // ->where('projectID',($request->project?'=':'<>'),$request->project)
    //     ->where('productID',($request->product?'=':'<>'),$request->product)
    //     ->where('move_out','>',0)
	//     ->whereBetween('transactionDate',[$request->date_from,$request->date_to])
	//     ->leftjoin('stores', 'product_movements.storeID', '=', 'stores.id')
	//    // ->leftjoin('projects', 'product_movements.projectID', '=', 'projects.id')
	//     ->leftjoin('products', 'product_movements.productID', '=', 'products.id')
    //     ->select('*','product_movements.productID as pid','products.productName')
    //     ->groupBy('product_movements.productID')
	//     ->get();

    //     $data['sum'] = ProductMovement::where('userID','=',Auth::user()->id)
	//     ->where('storeID',($request->store?'=':'<>'),$request->store)
	//     ->where('projectID',($request->project?'=':'<>'),$request->project)
    //     ->where('productID',($request->product?'=':'<>'),$request->product)
    //     ->where('move_out','>',0)
	//     ->whereBetween('transactionDate',[$request->date_from,$request->date_to])
	//     ->sum('product_movements.move_out');
       
    //     return view('Report.movementout', $data);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
