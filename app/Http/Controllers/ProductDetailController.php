<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Shelf;
use App\Models\MeasurementModel;
use App\Models\MeasurementUnitModel;
use App\Models\ProductImage;

class ProductDetailController extends Controller
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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        # instantiate models
        $modelCategory      =   new Category;
        $modelSubCategory   =   new SubCategory;
        $modelPr            =   new Product;
        $modelMeasurement   =   new MeasurementModel;
        $fetchCategory      =   $modelCategory::get();
        $fetchMeasurement   =   $modelMeasurement::get();
        $fetchSubCategory   =   $modelSubCategory::select('id', 'subcategoryName')->get();
        $fetchProduct   =   $modelPr::leftJoin('categories', 'categories.id', '=', 'products.categoryID')
        ->LeftJoin('sub_categories', 'sub_categories.id', '=', 'subCategoryID')
        ->select('Products.id', 'productName', 'categoryTitle', 'subcategoryName')
        ->get();
        //dd($fetchSubCategory);
        return view('products.product.create-product')->with(['dataCategory' => $fetchCategory, 'dataSubCategory' => $fetchSubCategory, 'dataMeasurement' => $fetchMeasurement, 'dataProducts'  => $fetchProduct]);
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
        $request->validate([
            'product_name'  =>  ['required'],
            'category'  =>  ['required', 'numeric'],
            'subcategory'  =>  ['required', 'numeric'],
            'minimum_measurement'  =>  ['required', 'numeric'],
        ]);

        //dd($request);
        $prName           =   $request->get('product_name');
        $prBarCode        =   $request->get('barcode_number');
        $prCategory       =   $request->get('category');
        $prSubCategory    =   $request->get('subcategory');
        $prMinMeasurement =   $request->get('minimum_measurement');
        
        // model
        $modelProduct   =   new Product;
        $modelMeasurementUnit   =   new MeasurementUnitModel;
        $confirmExist   =   $modelProduct::where('productName', '=', $prName)->count();

        if ($confirmExist >0) {
            # code...
            return back()->with('error', "Oops! This product has already been created.");
        } else {
            # code...
            try {
                //code...
                //dd($prMinMeasurement);
                $productSave = $modelProduct::create(['categoryID' => $prCategory, 'subcategoryID' => $prSubCategory, 'min_measurementID' => $prMinMeasurement, 'productName' => $prName, 'barcode' => $prBarCode]);
                #if statement to confirm if product and quantity exit in measurement unit db
                $countPrMunit   =   $modelMeasurementUnit::where('productID', '=', $productSave->id)->where('measurementID', '=', '0')->count();
                
                if ($countPrMunit > 0) {
                    # code...
                    //return back();
                    return back()->with('error', 'product could not be created');
                } else {
                    # code...
                    $modelMeasurementUnit::create(['productID' => $productSave->id, 'measurementID' => $prMinMeasurement]);
                }
                
                //dd($productSave->id);
                #if statement to confirm if product and quantity exit in measurement unit db

                #$countPrMunit   =   $modelMeasurementUnit::where(['productID' => $productSave->id, 'measurementID' > 0])

            } catch (\Throwable $th) {
                //throw $th;
                return back()->with('error', "Oops! Something went wrong try again later.");
            }
            return back()->with('success', "Great! Record created successfully.");
        }
        
        
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
        $id               =   decrypt($id);
        //dd($id);
        $modelProduct           =   new Product;
        $modelPrImage           =   new ProductImage;
        $modelCategory          =   new Category;
        $modelSubCategory       =   new SubCategory;
        //$modelShelf       =   new Shelf;
        $modelMeasurement       =   new MeasurementModel;
        $modelMeasurementUnit   =   new MeasurementUnitModel;
        $fetchCategory          =   $modelCategory::get();
        $fetchSubCategory       =   $modelSubCategory::select('id', 'subcategoryName')->get();

        $fetchMeasurement       =   $modelMeasurement::get();
        $fetchMeasurementUnit   =   $modelMeasurementUnit::leftJoin('measurements', 'measurements.id', '=', 'measurement_units.productID') ->select('*', 'measurements.description as desc')->where('measurement_units.productID', '=', $id)->get();
        $fetchProduct           =   $modelProduct::where('id', '=', $id)->get();
        $fetchPrImg             =   $modelPrImage::where('productID', '=', $id)->get();
        $fetchAllPr             =   Product::all();
        //dd($fetchPrImg);
        return view('products.product.edit-product')->with(['dataProduct' => $fetchProduct, 'dataCategory' => $fetchCategory, 'dataSubCategory' => $fetchSubCategory, 'dataMeasurement' => $fetchMeasurement, 'dataImg' => $fetchPrImg, 'dataAllPr' => $fetchAllPr, 'dataMeasurementUnit' => $fetchMeasurementUnit]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        //dd($request);
        $request->validate([
            'product_id'  =>  ['required', 'numeric'],
            'product_name'  =>  ['required'],
            'category'  =>  ['required', 'numeric'],
            'subcategory'  =>  ['required', 'numeric'],
        ]);

        $prID                   =   $request->get('product_id');
        $prName                 =   $request->get('product_name');
        $prBarCode              =   $request->get('barcode_number');
        $prCategory             =   $request->get('category');
        $prSubCategory          =   $request->get('subcategory');
        //$prMeasurementID        =   $request->get('measurement');
        // model
        $modelProduct           =   new Product;
        $modelMeasurementUnit   =   new MeasurementUnitModel;

        try {
            //code...
            $modelProduct::where('id', '=', $prID)->update(['categoryID' => $prCategory, 'subcategoryID' => $prSubCategory, 'productName' => $prName, 'barcode' => $prBarCode]);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', "Oops! Something went wrong try again later.");
        }
        return back()->with('success', "Great! Record updated successfully. <a class='btn btn-link' href='".route('create-product')."'>Go Back</a>");
    }

    public function editMeasurement(Request $request)
    {
        //
        //dd($request);
        $request->validate([
            'product_id'  =>  ['required', 'numeric'],
            /*'product_name'  =>  ['required'],
            'category'  =>  ['required', 'numeric'],
            'subcategory'  =>  ['required', 'numeric'],
            */
        ]);

        $prID            =   $request->get('product_id');
        $prMeasurementID =   $request->get('measurement');
        // model
        $modelProduct           =   new Product;
        $modelMeasurementUnit =   new MeasurementUnitModel;
        $countPrMunit   =   $modelMeasurementUnit::where('productID', '=', $prID)->where('measurementID', '>', '1')->count();
        if ($countPrMunit > 0) {
            # code...
            return back()->with('error', "Oops! Measurement quantity is greater than one.");
        } else {
            # code...
            try {
                //code...
                $updatedMeasurementId   =   $modelMeasurementUnit::where('productID', '=', $prID)->update(['measurementID'  =>  $prMeasurementID]);
                if ($updatedMeasurementId) {
                    # code...
                    $modelProduct::where('id', '=', $prID)->update(['min_measurementID' => $prMeasurementID]);
                } else {
                    # code...
                    return back()->with('error', "Oops! Something went wrong.");
                }
                
                
            } catch (\Throwable $th) {
                //throw $th;
                return back()->with('error', "Oops! Something went wrong try again later.");
            }
            return back()->with('success', "Great! Record updated successfully. <a class='btn btn-link' href='".route('create-product')."'>Go Back</a>");
        }
        
        
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

    //
    public function subcategory(Request $request)
    {
        # code...
        $id =   $request->get('id');
        $modelSubCategory  =   new SubCategory;
        $fetchSubCategory =   $modelSubCategory::where('categoryID', '=', $id)->select('id', 'subcategoryName')->get();
        return response()->json($fetchSubCategory);
    }

    public function editsubcategory(Request $request)
    {
        # code...
        $id =   $request->get('id');
        $modelSubCategory  =   new SubCategory;
        $fetchSubCategory =   $modelSubCategory::select('id', 'subcategoryName')->get();
        return response()->json($fetchSubCategory);
    }
}
