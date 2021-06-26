<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Project;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductMovement;
use App\Repository\ConfirmInterface;

use DB;
use Auth;
use Session;

class ConfirmationReportController extends Controller
{
    private $confirmRepository;

    public function __construct(ConfirmInterface $confirmRepository) 
    {
        $this->middleware('auth');
        $this->confirmRepository = $confirmRepository;
    }

    public function confirmReport() {

        $data['statusx'] = '';
        $data['details']=null;
        
        return view('Report.ConfirmationReport', $data);
    }

    public function getconfirmReport(Request $request) {

        
        $data['statusx'] = $request->status;

        $data['status'] = $request->status;
        $userID = Auth::user()->id;
        $store = DB::table('store_users')->where('userID',$userID)->get();

        foreach($store  as $user) {

            $data['details'] = $this->confirmRepository->search($user->storeID,$data['status']);
        }
        //dd($data['details']);
        foreach($data['details'] as $xyz) {

            $xyz->formatqty=$this->FormatQTY($xyz->productID,($xyz->move_in) );
        }

        return view('Report.ConfirmationReport', $data);
        
    }

   
}
