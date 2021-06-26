<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeasurementModel;
use App\Models\MeasurementUnitModel;
use App\Models\Product;
use Session;


class ProductMeasurementUnitController extends Controller
{
    //class contructor
    public function __construct()
    {
        $this->middleware('auth');
    }


    //create measurement
    public function createMeasurementUnit()
    {
        $data['getRecords']     = [];
        $data['getMeasurement'] = [];
        $data['getProduct']     = [];

        try{
            $data['getRecords']     = $this->getAllMeasurementUnit();
            $data['getMeasurement'] = MeasurementModel::all();
            $data['getProduct']     = Product::all();
            (Session::get('editRecord') ? $data['editRecord'] = Session::get('editRecord') : null);
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMeasurementUnitController@createMeasurementUnit', 'Error occurred when fetching records.' );
        }
        Session::forget('editRecord');
        return view('products.measurementUnit.home', $data);
    }//end fun

    public function storeMeasurementUnit(Request $request)
    {
        //Initialization
        $saved = null;
        //validation
        $this->validate($request,
        [
            'product'           => ['required', 'numeric'],
            'measurementName'   => ['required', 'numeric'],
            'quantity'          => ['required', 'numeric'],
        ]);
        $recordID = $request['getRecord'];
        //DB transactions
        try{
            if(MeasurementUnitModel::find($recordID) && $recordID)
            {
                $saved = MeasurementUnitModel::where('id', $recordID)->update(
                [
                    'productID'     => $request['product'],
                    'measurementID' => $request['measurementName'],
                    'quantity'      => $request['quantity']
                ]);
            }else{
                $saved = MeasurementUnitModel::create(
                [
                    'productID'     => $request['product'],
                    'measurementID' => $request['measurementName'],
                    'quantity'      => $request['quantity']
                ]);
            }
            if($saved)
            {
                Session::forget('editRecord');
                return redirect()->back()->with('message', 'New product measurement unit was created/updated.');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMeasurementUnitController@storeMeasurementUnit', 'Error occurred when adding/updating new record.' );
        }
        return redirect()->back()->with('error', 'Sorry, we cannot create your record now. Please try again.');
    }//end fun


    //Delete Record
    public function editRecordUnit($recordID)
    {
        //DB transactions
        Session::forget('editRecord');
        try{
            $getRecord      = MeasurementUnitModel::find($recordID);
            if($getRecord)
            {
                Session::put('editRecord', $this->getAllMeasurementUnit($recordID));
                return redirect()->route('createMeasurementUnit');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMeasurementUnitController@editRecordUnit', 'Error occurred when editting record.' );
        }
        return redirect()->route('createMeasurementUnit')->with('error', 'Sorry, we cannot edit this record now. Please try again.');
    }//end fun


    //Delete Record
    public function deleteRecordUnit($recordID)
    {
        //DB transactions
        $checkInUse = null;
        try{
            $getRecord      = MeasurementUnitModel::find($recordID);
            $checkInUse     = Product::where('id', $recordID)->first();
            if($getRecord && !$checkInUse)
            {
                $getRecord->delete();
                return redirect()->route('createMeasurementUnit')->with('message', 'Your record was deleted successfully.');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMeasurementUnitController@deleteRecordUnit', 'Error occurred when deleting record.' );
        }
        if($checkInUse)
        {
            return redirect()->route('createMeasurementUnit')->with('error', 'Sorry, this record is in use. We cannot delete this record now.');
        }
        return redirect()->route('createMeasurementUnit')->with('error', 'Sorry, we cannot delete this record now. Please try again.');
    }//end fun


    public function getAllMeasurementUnit($recordID = null)
    {
        try{
            if($recordID && is_numeric($recordID))
            {
                return MeasurementUnitModel::where('measurement_units.id', $recordID)
                    ->leftjoin('products', 'products.id', '=', 'measurement_units.productID')
                    ->leftjoin('measurements', 'measurements.id', '=', 'measurement_units.measurementID')
                    ->select('*', 'measurement_units.id as recordID', 'measurement_units.created_at as dateCreated', 'measurement_units.updated_at as dateUpdated')
                    ->first();
            }else{
                return MeasurementUnitModel::leftjoin('products', 'products.id', '=', 'measurement_units.productID')
                    ->leftjoin('measurements', 'measurements.id', '=', 'measurement_units.measurementID')
                    ->select('*', 'measurement_units.id as recordID', 'measurement_units.created_at as dateCreated', 'measurement_units.updated_at as dateUpdated')
                    ->orderBy('products.productName', 'Asc')
                    ->orderBy('measurement_units.quantity', 'Asc')
                    ->get();
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMeasurementUnitController@getAllMeasurementUnit', 'Error occurred when fetching records.' );
        }
        return;

    }



}//end class

