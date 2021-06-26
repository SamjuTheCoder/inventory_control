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
use App\Models\ProductMovement;
use Session;

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
        $fetchCategory      =   $modelCategory::orderBy('categoryTitle', 'asc')->get();
        $fetchMeasurement   =   $modelMeasurement::orderBy('description', 'asc')->get();
        $fetchSubCategory   =   $modelSubCategory::select('id', 'subcategoryName')->get();
        $fetchProduct   =   $modelPr::leftJoin('categories', 'categories.id', '=', 'products.categoryID')
        ->LeftJoin('sub_categories', 'sub_categories.id', '=', 'subCategoryID')
        /*->LeftJoin('product_images', 'product_images.productID', '=', 'products.id')*/
        ->select('Products.id', 'productName', 'categoryTitle', 'subcategoryName')
        ->orderBy('products.id', 'desc')
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
            'product_name'        =>  ['required'],
            'category'            =>  ['required', 'numeric'],
            'subcategory'         =>  ['required', 'numeric'],
            'minimum_measurement' =>  ['required', 'numeric'],
            'barcode'             =>  ['nullable', 'unique:products,barcode']
        ]);


        $prName           =   $request->get('product_name');
        $prBarCode        =   $request->get('barcode_number');
        $prCategory       =   $request->get('category');
        $prSubCategory    =   $request->get('subcategory');
        $prMinMeasurement =   $request->get('minimum_measurement');
        
        
        
        // model
        $modelProduct   =   new Product;
        $modelMeasurementUnit   =   new MeasurementUnitModel;

        if (!empty($prBarCode)) {
            # code...
            $barcodeExist = $modelProduct::where('barcode', '=', $prBarCode)->count();
            if ($barcodeExist > 0) {
                # code...
                return back()->withInput()->with('error', "Oops! This barcode has already been entered.");
            } else {
                # code...
                $confirmExist   =   $modelProduct::where('productName', '=', $prName)->count();

                if ($confirmExist >0) {
                    # code...
                    return back()->withInput()->with('error', "Oops! This product has already been created.");
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
                            return back()->withInput()->with('error', 'product could not be created');
                        } else {
                            # code...
                            $modelMeasurementUnit::create(['productID' => $productSave->id, 'measurementID' => $prMinMeasurement]);
                        }
                    
                    } catch (\Throwable $th) {
                        //throw $th;
                        return back()->withInput()->with('error', "Oops! Something went wrong try again later.");
                    }
                    return back()->with('success', "Great! Record created successfully.");
                }
            }
            
        } else {
            # code...
            $confirmExist   =   $modelProduct::where('productName', '=', $prName)->count();

        if ($confirmExist >0) {
            # code...
            return back()->withInput()->with('error', "Oops! This product has already been created.");
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
                    return back()->withInput()->with('error', 'product could not be created');
                } else {
                    # code...
                    $modelMeasurementUnit::create(['productID' => $productSave->id, 'measurementID' => $prMinMeasurement]);
                }

            } catch (\Throwable $th) {
                //throw $th;
                return back()->withInput()->with('error', "Oops! Something went wrong try again later.");
            }
            return back()->with('success', "Great! Record created successfully.");
        }
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
        $id                     =   decrypt($id);
        //dd($id);
        $modelProduct           =   new Product;
        $modelPrImage           =   new ProductImage;
        $modelCategory          =   new Category;
        $modelSubCategory       =   new SubCategory;
        //$modelShelf       =   new Shelf;
        $modelMeasurement       =   new MeasurementModel;
        $modelMeasurementUnit   =   new MeasurementUnitModel;
        $fetchCategory          =   $modelCategory::orderBy('categoryTitle', 'asc')->get();
        $fetchSubCategory       =   $modelSubCategory::select('id', 'subcategoryName')->get();

        $fetchMeasurement               =   $modelMeasurement::get();
        $fetchMeasurementUnitQuantity   =   $modelMeasurementUnit::Join('measurements', 'measurements.id', '=', 'measurement_units.measurementID') ->select('*', 'measurement_units.id as measurementID', 'measurements.description as desc')->where('measurement_units.productID', '=', $id)->where('quantity', '=', '1')->get();
        $fetchMeasurementUnit           =   $modelMeasurementUnit::Join('measurements', 'measurements.id', '=', 'measurement_units.measurementID') ->select('*', 'measurement_units.id as measurementID', 'measurements.description as desc')->where('measurement_units.productID', '=', $id)->orderBy('measurement_units.quantity', 'asc')->get();
        $fetchProduct                   =   $modelProduct::where('id', '=', $id)->get();
        $fetchPrImg                     =   $modelPrImage::where('productID', '=', $id)->get();
        $fetchAllPr                     =   Product::all();
        //dd($fetchPrImg);
        return view('products.product.edit-product')->with(['dataProduct' => $fetchProduct, 'dataCategory' => $fetchCategory, 'dataSubCategory' => $fetchSubCategory, 'dataMeasurement' => $fetchMeasurement, 'dataImg' => $fetchPrImg, 'dataAllPr' => $fetchAllPr, 'dataMeasurementUnit' => $fetchMeasurementUnit, 'dataMeasurementQuantity' => $fetchMeasurementUnitQuantity]);
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
            'barcode'   =>  ['nullable', 'unique:products,barcode']
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
            'measurement'  =>  ['required', 'numeric'],
        ]);

        $prID            =   $request->get('product_id');
        $prMeasurementID =   $request->get('measurement');
        // model
        //dd($prMeasurementID);
        $modelProduct         =   new Product;
        $modelMeasurementUnit =   new MeasurementUnitModel;
        $findMeasurmentID   = $modelMeasurementUnit::find($prMeasurementID);
        //$countPrMunit         =   $modelMeasurementUnit::where('productID', '=', $prID)->where('measurementID', '>', '1')->count();
        $MUQuantity = $findMeasurmentID->quantity;
        $MUMeasurementId = $findMeasurmentID->measurementID;
        //dd($MUQuantity);
        if ($MUQuantity > 1) {
            # code...
            return back()->with('error', "Oops! Measurement quantity is greater than one.");
        } else {
            # code...
            try {
                //code...
                $modelProduct::where('id', '=', $prID)->update(['min_measurementID' => $MUMeasurementId]);
                
                
            } catch (\Throwable $th) {
                //throw $th;
                return back()->with('error', "Oops! Something went wrong try again later.");
            }
            return back()->with('success', "Great! Record updated successfully. <a class='btn btn-link' href='".route('create-product')."'>Go Back</a>");
        }
        
        
    }

    public function updateMeasurementUnitQuantity(Request $request)
    {
        # code...
        $request->validate([
            'product_id'            =>  ['required', 'numeric'],
            'measurement_unit_id'   =>  ['required', 'numeric'],
            'measurement_quantity'  =>  'required|numeric|min:1|not_in:0',
        ]);

        //get all input
        $prId     =   $request->get('product_id');
        $id       =   $request->get('measurement_unit_id');
        $quantity =   $request->get('measurement_quantity');
        //dd($id);
        try {
            //code...
            $fetchProduct   =   Product::find($prId);
            $fetchMeasurement   =   MeasurementUnitModel::find($id);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', "Oops! Product does not exist.");
        }
        // check if product exist
        //dd($fetchMeasurement->measurementID);
            if ($fetchProduct->min_measurementID == $fetchMeasurement->measurementID) {
                # code...
                return back()->with('warning', "Oops! Default measurement, quantity can't be updated.");
            } else {
                # code...
                try {
                    //code...
                    $updatedMeasurementQuantity   =   MeasurementUnitModel::where('id', '=', $id)->update(['quantity'  =>  $quantity]);
                                
                } catch (\Throwable $th) {
                    //throw $th;
                    return back()->with('error', "Oops! Something went wrong try again later.");
                }
                return back()->with('success', "Great! Record updated successfully.");
            }
            
    }

    public function deleteMeasurementUnitQuantity(Request $request)
    {
        # code...
        $request->validate([
            'product_id'            =>  ['required', 'numeric'],
            'measurement_unit_id'   =>  ['required', 'numeric'],
        ]);

        //get all input
        $prId     =   $request->get('product_id');
        $id       =   $request->get('measurement_unit_id');
        //dd($quantity);
        try {
            //code...
            $fetchProduct   =   Product::find($prId);
            $fetchMeasurement   =   MeasurementUnitModel::find($id);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', "Oops! Product does not exist.");
        }
        if ($fetchProduct->min_measurementID == $fetchMeasurement->measurementID) {
            # code...
            return back()->with('warning', "Oops! Default measurement, quantity can't be updated.");
        } else {
            # code...
        try {
            //code...
            $deleteMeasurementQuantity   =   MeasurementUnitModel::where('id', '=', $id)->delete();
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', "Oops! Something went wrong try again later.");
        }
        return back()->with('success', "Great! Record deleted successfully.");
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        //
        $request->validate([
            'product_id'   =>  ['required', 'numeric'],
        ]);

        //get all input
        $id       =   $request->get('product_id');
        //dd($quantity);
        try {
            //code...
            $checkProduct  =   ProductMovement::where('productID', '=', $id)->count();

            if($checkProduct > 0){ return back()->with('error', "Oops! This product is on transit. Product Can't be deleted." );} else{ $deleteProduct = Product::where('id', '=', $id)->delete(); }
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', "Oops! Something went wrong try again later.");
        }
        if ($deleteProduct) {
            # code...
            return back()->with('success', "Great! Product deleted successfully.");
        }
    }

    // tunde
public function storeMeasurementUnitFrmEdit(Request $request)
{
    # code...
    $this->validate($request,
        [
            'productID'           => ['required', 'numeric'],
            'measurementName'   => ['required', 'numeric'],
            'quantity'          => 'required|numeric|min:1|not_in:0',
        ]);

        $checkExist =   MeasurementUnitModel::where('productID', '=', $request['productID'])
                        ->where('measurementID', '=', $request['measurementName'])
                        ->count();

        if ($checkExist > 0) {
            # code...
            return back()->with('error', 'This record already exist.');
        } else {
            # code...
            try {
                //code...
                $saved = MeasurementUnitModel::create(
                    [
                        'productID'     => $request['productID'],
                        'measurementID' => $request['measurementName'],
                        'quantity'      => $request['quantity']
                    ]);
            } catch (\Throwable $getError) {
                //throw $th;
                $this->storeTryCatchError($getError, 'ProductMeasurementUnitController@storeMeasurementUnitFrmEdit', 'Error occurred when adding/updating new record.' );
            }
        }
        if($saved)
            {
                Session::forget('editRecord');
                return redirect()->back()->with('message', 'New product measurement unit was created/updated.');
            }

}


    //
    public function subcategory(Request $request)
    {
        # code...
        $id =   $request->get('id');
        $modelSubCategory  =   new SubCategory;
        $fetchSubCategory =   $modelSubCategory::where('categoryID', '=', $id)->select('id', 'subcategoryName')->orderBy('subcategoryName', 'asc')->get();
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
