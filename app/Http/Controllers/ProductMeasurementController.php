<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeasurementModel; 
use App\Models\MeasurementUnitModel;
use Session;


class ProductMeasurementController extends Controller
{
    //class contructor
    public function __construct()
    {
        $this->middleware('auth');
    }


    //create measurement
    public function createMeasurement()
    {
        $data['getRecords'] = MeasurementModel::orderBy('description', 'Asc')->get(); 
        (Session::get('editRecord') ? $data['editRecord'] = Session::get('editRecord') : null);
        Session::forget('editRecord');
        return view('products.measurement.home', $data);
    }//end fun

    public function storeMeasurement(Request $request)
    {   
        //Initialization
        $saved = null;
        $recordID = $request['getRecord'];
        //validation
        if($recordID == null){
            $this->validate($request,
            [
                'measurementName'    => ['required', 'string', 'max:190', 'unique:measurements,description']
            ]);
        }
        //DB transactions
        try{
            $saved = MeasurementModel::updateOrcreate(['id' => $recordID],['description' => $request['measurementName']]);
            if($saved)
            {   
                Session::forget('editRecord');
                return redirect()->route('createMeasurement')->with('message', 'New product measurement was created/updated.');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMeasurementController@storeMeasurement', 'Error occurred when adding/updating new record.' );
        }
        return redirect()->route('createMeasurement')->with('error', 'Sorry, we cannot create your record now. Please try again.');
    }//end fun


    //Delete Record
    public function editRecord($recordID)
    {   
        //DB transactions
        Session::forget('editRecord');
        try{
            $getRecord      = MeasurementModel::find($recordID);
            if($getRecord)
            {
                Session::put('editRecord', $getRecord);
                return redirect()->route('createMeasurement');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMeasurementController@editRecord', 'Error occurred when editting record.' );
        }
        return redirect()->route('createMeasurement')->with('error', 'Sorry, we cannot edit this record now. Please try again.');
    }//end fun


    //Delete Record
    public function deleteRecord($recordID)
    {   
        //DB transactions
        $checkInUse = null;
        try{
            $getRecord      = MeasurementModel::find($recordID);
            $checkInUse     = MeasurementUnitModel::where('measurementID', $recordID)->first();
            if($getRecord && !$checkInUse)
            {
                $getRecord->delete();
                return redirect()->route('createMeasurement')->with('message', 'Your record was deleted successfully.');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMeasurementController@deleteRecord', 'Error occurred when deleting record.' );
        }
        if($checkInUse)
        {
            return redirect()->route('createMeasurement')->with('error', 'Sorry, this record is in use. We cannot delete this record now.');
        }
        return redirect()->route('createMeasurement')->with('error', 'Sorry, we cannot delete this record now. Please try again.');
    }//end fun

    


}//end class
