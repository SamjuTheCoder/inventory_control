<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Shelf;
use Session;
use App\Models\ProductMovement;

class ShelvesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function create()
    {
        $data['stores'] = $this->getStores();
        $data['allShelves'] = $this->getShelve();
        return view('shelves.createShelve',$data);
    }

    public function saveShelve(Request $request)
    {
        
        //validation
        $this->validate($request,

        [
            'shelve_name' => 'required',
            'description' => 'required',
            'store'       => 'required',
            
        ]); 
        $check = Shelf::where('storeID','=',$request->store)->where('shelve_name','=',$request->shelve_name)->count();
          if($check > 0)
          {
              return back()->with('err','Entry Already Exist');
          }
            try{
               $shelve = new Shelf;
               $shelve->storeID=$request->store;
               $shelve->shelve_name=$request->shelve_name; 
               $shelve->description=$request->description;
               $shelve->save();
               //Session::flash('msg','Successfully saved');
               return back()->with('msg','Successfully Saved');
            }
            catch(\Exception $e) {
               return back()->with('err',$e->getMessage());
           }
    }

    public function updateShelves(Request $request)
    {
        
        //validation
        $this->validate($request,

        [
            'shelve_name' => 'required',
            'description' => 'required',
            'store'       => 'required',
            
        ]);  
            try{
               $shelve = Shelf::find($request->shelveID);
               $shelve->storeID=$request->store;
               $shelve->shelve_name=$request->shelve_name; 
               $shelve->description=$request->description;
               $shelve->save();
               //Session::flash('msg','Successfully saved');
               return back()->with('msg','Successfully Saved');
            }
            catch(\Exception $e) {
               return back()->with('err',$e->getMessage());
           }
    }

    
    public function destroy($id)
    {
        $count = ProductMovement::where('shelve',$id)->count();
        if($count >0 )
        {
            return back()->with('err','Cannot delete record because Shelve is in use');
        }
        else{
        $shelf = Shelf::find($id);
        $shelf->delete();
        return back()->with('msg','Successfully Deleted');
        }
    }
}
