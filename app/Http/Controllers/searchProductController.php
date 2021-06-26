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

class searchProductController extends Controller
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
        $data['stores']          = DB::table('Stores')->get(); 
        $data['products']        = DB::table('Products')->get();
        $data['categories']      = DB::table('categories')->get();
        $data['Subcategories']   = DB::table('sub_categories')->get();
    
        $data['Category']        = $request->get('Category');
        $data['store']           = $request->get('store');
        $data['subCategory']     = $request->get('subCategory');
        
        $CategoryID = $request->input('Category');

        $data['getReport'] = DB::table('products')
                            ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                            ->leftjoin('sub_categories', 'sub_categories.id', '=', 'products.subcategoryID')                           
                            ->where('products.categoryID', ($CategoryID ? '=' : '<>'),   $CategoryID)
                            ->select('*', 'products.id as pid', ) 
                            ->orderby('products.categoryID')->orderby('products.subcategoryID')->orderby('productName')
                            ->paginate(10);
        //return view('Report.SearchProduct',$data)->with('success', 'Report generated below!');
            
        foreach($data['getReport'] as $xyz) {

            $xyz->mformat = DB::table('measurement_units')->where('productID',$xyz->pid)->orderBy('quantity')
            ->leftjoin('measurements','measurements.id','measurement_units.measurementID')
            ->select('measurement_units.*','measurements.description')->get();
        }
            // if ($data['subCategory'] == true) {
            //      $data['getReportByQuery'] =  DB::table('products')->where('categoryID', $data['Category'])->where('subcategoryID', $data['subCategory'])->dd();
            // }
           


        return view('Report.SearchProduct', $data);     
    }
    
    
    //To Populate SubCategory field
    public function AjaxproductSearchForSubcategory(Request $request){
        
        $d = $request->get('Category');

        $b = DB::table('sub_categories')->where('categoryID', $d)->get();
        
        return response()->json($b);

    }




    //     public function submitSearch(Request $request) 
    //     {     
            
    //         $CategoryID = $request->input('Category');
    //         //dd($d);
        
    //         // if($data['Category'] == null ){
    //             $data['Category'] = DB::table('products')
    //                                 ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
    //                                 ->leftjoin('sub_categories', 'sub_categories.id', '=', 'products.subcategoryID')
    //                                 ->leftjoin('measurement_units', 'measurement_units.productID', '=', 'products.id')
    //                                 ->leftjoin('measurements', 'measurements.id', '=', 'measurement_units.measurementID')
    //                                 ->where('products.categoryID', ($CategoryID ? '=' : '<>'),   $CategoryID)
    //                                 ->select('*', 'measurement_units.quantity as quantity', )   
    //                                 ->orderby('products.categoryID')->orderby('products.subcategoryID')->orderby('productName')
    //                                 ->get();
    //                                 //->paginate(50);

    //                                // dd($data['Category']);
    //             return view('Report.SearchProduct',$data)->with('success', 'Report generated below!');
    //         // }else{
    //         //     dd('collect data for query');
    //         //  return view('Report.SearchProduct',$data);
    //         // }       

            
    //         //$data['getReport'] = DB::table('products_movements')->where('')->first();
            
    //             //return back()->with('success', 'Report generated below!');
    //             //dd($data['getReport']);
    //            // return redirect()->action([ShelveReportController::class, 'create']);
    //         //return back()->with($data)->with('success', 'check');
    //         // return view('Report.ProductInShelve', $data)->with('success', 'Report generated below!');
            
    //    }

        //THIS FUNCTION CAN BE USED TO SEARCH SUBCATEGORY BY WAREHOUSE AND CATEGORY 
        // Public function submitSearch(Request $request){
        //     $data['Category']        = $request->get('Category');
        //     $data['store']           = $request->get('store');
        //     $data['subCategory']     = $request->get('subCategory');
        //     $d  =   Product::where('categoryID', '=', $data['Category'])->where('subcategoryID', '=', $data['subCategory'])->get();
            
        //     $data['getReport'] = ProductMovement::where('userID','=',Auth::user()->id)
        //         ->wherein('product_movements.ProductID', $d)
        //         ->where('product_movements.storeID', $data['store'])
        //         ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
        //         ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
        //           //->leftjoin('shelves', 'shelves.id', '=', 'product_movements.shelveID')
        //         ->select('*','product_movements.productID as pid','products.productName', 'product_movements.storeID as storeID', 'product_movements.shelveID as shelvesID')
        //      ->groupBy('storeID')
        //         ->simplePaginate(10);
        // //dd($data['getReport']);  
        //     return redirect()->route('productSearch')->with($data);
        // }
}
