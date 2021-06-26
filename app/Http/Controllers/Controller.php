<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ErrorCaughtModel;
use App\Models\Store;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


     //Return No Value : Void - Store any error that occurred in try-catch block
     public function storeTryCatchError($getError = null, $getFunctionModuleName = null, $errorDescription = null )
     {
         try{
             return ErrorCaughtModel::create([
                 'throwable_error'       => ($getError <> null ? $getError : 'No error occured'),
                 'function_module_name'  => $getFunctionModuleName,
                 'error_description'     => $errorDescription,
                 'created_at'            => date('Y-m-d h:i:sa'),
                 'updated_at'            => date('Y-m-d h:i:sa')
             ]);
         }catch(\Throwable $errorThrown){}
     }

     public function getStores()
     {
        $stores = Store::get();
        return $stores;
     }
     public function getShelve()
     {
        $shelve = DB::table('shelves')
        ->join('stores','stores.id','=','shelves.storeID')
        ->select('*','shelves.id as shelveID')
        ->get(); 
        return $shelve;
     }
     public function getUsers()
     {
        $users = DB::table('users')
        ->get();
        return $users;
     }


}
