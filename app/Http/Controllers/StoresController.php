<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Store;
use Session;
use App\Models\ProductMovement;

class StoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function create()
    {
        $data['allStores'] = Store::get();
        return view('warehouse.create',$data);
    }
    public function saveStore(Request $request)
    {
        
         //validation
         $this->validate($request,

         [
             'store_name' => 'required|unique:stores',
             'location' => 'required',
             'address' => 'required',
             
         ]);  
             try{
                $warehouse = new Store;
                $warehouse->store_name=$request->store_name;
                $warehouse->location=$request->location;
                $warehouse->address=$request->address;
                $warehouse->save();
                //Session::flash('msg','Successfully saved');
                return back()->with('msg','Successfully Saved');
             }
             catch(\Exception $e) {
                return back()->with('err',$e->getMessage());
            }

    }

    public function viewStores()
    {
        $data['allStores'] = Store::get();
        return view('warehouse.listWarehouse',$data);
    }

    public function updateStore(Request $request)
    {
        //dd(2);
        //validation
        $this->validate($request,

            array(
                'store_name' => 'required',
                'location' => 'required',
                'address' => 'required',
                                
                ));
        try
        {
           
        $warehouse = Store::find($request->storeID);
        $warehouse->store_name=$request->store_name;
        $warehouse->location=$request->location;
        $warehouse->address=$request->address;
        $warehouse->save();

        return back()->with('msg','Successfully Updated');
        }
        catch(\Exception $e) {
            return back()->with('err',$e->getMessage());
        }

    }

    public function destroy($id)
    {
        $count = ProductMovement::where('storeID',$id)->count();
        if($count >0 )
        {
            return back()->with('err','Cannot delete record because store is in use');
        }
        else{
        $storeUser = Store::find($id);
        $storeUser->delete();
        return back()->with('msg','Successfully Deleted');
        }
    }
}