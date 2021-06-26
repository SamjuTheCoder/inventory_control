<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\StoreUser;
use Session;

class StoreUsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function createStoreUser()
    {
        $data['stores'] = $this->getStores();
        $data['allShelves'] = $this->getShelve();
        $data['allUsers']   = $this->getUsers();
        $data['storeUsers']   = DB::table('store_users')
        ->join('users','users.id','=','store_users.userID')
        ->join('stores','stores.id','=','store_users.storeID')
        ->select('*','store_users.id as storeUserID')
        ->get();
        return view('storeUsers.assignStoreUsers',$data);
    }
    
    public function saveStoreUser(Request $request)
    {
        $this->validate($request,

        [
            'store' => 'required',
            'user' => 'required',
            
        ]);  
        $check = StoreUser::where('storeID','=',$request->store)->where('userID','=',$request->user)->count();
          if($check > 0)
          {
              return back()->with('err','Entry Already Exist');
          }
            try{
               $user = new StoreUser;
               $user->storeID=$request->store;
               $user->userID=$request->user; 
              
               $user->save();
               //Session::flash('msg','Successfully saved');
               return back()->with('msg','Successfully Saved');
            }
            catch(\Exception $e) {
               return back()->with('err',$e->getMessage());
           }
    }
   
    public function updateStoreUser(Request $request)
    {
        $this->validate($request,

        [
            'store' => 'required',
            'user' => 'required',
            
        ]);  
            try{
               $user = StoreUser::find($request->storeUserID);
               $user->storeID=$request->store;
               $user->userID=$request->user; 
              
               $user->save();
               //Session::flash('msg','Successfully saved');
               return back()->with('msg','Successfully Updated');
            }
            catch(\Exception $e) {
               return back()->with('err',$e->getMessage());
           }
    }

    public function destroy($id)
    {
        $storeUser = StoreUser::find($id);
        $storeUser->delete();
        return back()->with('msg','Successfully Deleted');
    }
}
