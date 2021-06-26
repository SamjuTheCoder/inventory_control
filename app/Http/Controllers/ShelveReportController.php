<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['productx']   = '';
        $data['stores']     = DB::table('Stores')->get(); 
        $data['products']   = DB::table('Products')->get();
        $data['categories']   = DB::table('categories')->get();
        $data['Subcategories']   = DB::table('sub_categories')->get();
        $data['getShelf']   = Shelf::get();

        $data['product']    = $request->get('product');
        $data['store']      = $request->get('store');
        //dd($request->all());
        if(($data['store'] == true) && ($data['product'] == null)){
        
            $data['getReport'] = DB::table('product_movements')->where('product_movements.storeID', ($data['store'] ? '=' : '<>'),   $data['store'])
            ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
            ->leftjoin('stores', 'stores.id','=', 'product_movements.storeID')
            ->leftjoin('shelves', 'shelves.id', '=', 'product_movements.shelveID')
            ->select('*','product_movements.productID as pid','products.productName', 'product_movements.storeID as storeID', 'product_movements.shelveID as shelvesID')          
            ->orderby('productName')
            ->groupBy('productID')//->dd();
            ->simplePaginate(15);
    
        }else{
              
            $data['getReport'] = ProductMovement::where('userID','=',Auth::user()->id)
            ->where('product_movements.ProductID', $data['product'])
            ->where('product_movements.storeID', $data['store'])
            ->leftjoin('stores', 'stores.id','=', 'product_movements.storeID')
            ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
            ->leftjoin('shelves', 'shelves.id', '=', 'product_movements.shelveID')
            ->select('*','product_movements.productID as pid','products.productName', 'product_movements.storeID as storeID', 'product_movements.shelveID as shelvesID')          
            ->orderby('productName')           
            ->groupBy('productID')//->dd();
            ->simplePaginate(10);

        }

        //ORIGINAL QUERY THAT WORKS
        // $data['getReport'] = ProductMovement::where('userID','=',Auth::user()->id)
        //     ->where('product_movements.ProductID', $data['product'])
        //     ->where('product_movements.storeID', $data['store'])
        //     ->leftjoin('stores', 'stores.id','=', 'product_movements.storeID')
        //     ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
        //     ->leftjoin('shelves', 'shelves.id', '=', 'product_movements.shelveID')
        //     //->leftjoin('categories', 'shelves.id', '=', 'product_movements.shelveID')
        //     ->select('*','product_movements.productID as pid','products.productName', 'product_movements.storeID as storeID', 'product_movements.shelveID as shelvesID')          
        //     ->orderby('productName')          
        //     ->groupBy('productID')
        //     ->simplePaginate(10);
        //dd($data['getReport']);
            
        return view('Report.ProductInShelve',$data);
    }

// THIS FUNCTION IS JUST A DUMMY
    // public function getProductShelveReport(Request $request) 
    // {     
    //     if(($data['store'] == true) && ($data['product'] == null)){
        
    //         $data['getReport'] = DB::table('product_movements')->where('product_movements.storeID', ($data['store'] ? '=' : '<>'),   $data['store'])
    //         ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
    //         ->leftjoin('stores', 'stores.id','=', 'product_movements.storeID')
    //         ->leftjoin('shelves', 'shelves.id', '=', 'product_movements.shelveID')
    //         ->select('*','product_movements.productID as pid','products.productName', 'product_movements.storeID as storeID', 'product_movements.shelveID as shelvesID')          
    //         ->orderby('productName')
    //         ->groupBy('shelvesID')//->dd();
    //         ->simplePaginate(15);
    
    //     }else{
              
    //         $data['getReport'] = ProductMovement::where('userID','=',Auth::user()->id)
    //         ->where('product_movements.ProductID', $data['product'])
    //         ->where('product_movements.storeID', $data['store'])
    //         ->leftjoin('stores', 'stores.id','=', 'product_movements.storeID')
    //         ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
    //         ->leftjoin('shelves', 'shelves.id', '=', 'product_movements.shelveID')
    //         ->select('*','product_movements.productID as pid','products.productName', 'product_movements.storeID as storeID', 'product_movements.shelveID as shelvesID')          
    //         ->orderby('productName')           
    //         ->groupBy('shelvesID')//->dd();
    //         ->simplePaginate(10);

    //     }


        
    //     //$data['getReport'] = DB::table('products_movements')->where('')->first();
        
    //         return back()->with('success', 'Report generated below!');
    //         //dd($data['getReport']);
    //        // return redirect()->action([ShelveReportController::class, 'create']);
    //     //return back()->with($data)->with('success', 'check');
    //     // return view('Report.ProductInShelve', $data)->with('success', 'Report generated below!');
        
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate( $request, [
            'store'     => 'required|string',
            'product'   => 'required|string',
        ]);    
        $product = $request->get('product');
        $store = $request->get('store');

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
