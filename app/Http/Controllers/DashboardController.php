<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Statistics()
    {
        $data['warehouse'] = [];
        $storeCount = [];

        $data['warehouse'] = DB::table('stores')->get();

        foreach($data['warehouse'] as $s) {

            $storeCount=DB::table('product_movements')
            ->where('product_movements.storeID',$s->id)
            ->leftjoin('stores','product_movements.storeID','=','stores.id')
            ->select('*', DB::raw("SUM(move_in) as totalIn"), DB::raw("SUM(move_out) as totalOut"))
            ->get();
        
            foreach($storeCount as $s) { 

                return $s->totalIn-$s->totalOut;
            }
        }
    }


}
