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

class StoreConfirmationController extends Controller
{
    private $confirmRepository;

    public function __construct(ConfirmInterface $confirmRepository) 
    {
        $this->middleware('auth');
        $this->confirmRepository = $confirmRepository;
    }

    public function confirmMovement() {

        $data['statusx'] = '';
        $data['details']=null;
        Session::put('statusID',0);

        $storeID = [];
        $userID = Auth::user()->id;
        $store = DB::table('store_users')->where('userID',$userID)->get();

        foreach($store  as $user) {
            $data['details'] = $this->confirmRepository->all($user->storeID);
        }

        foreach($data['details'] as $xyz) {
            $xyz->formatqty=$this->FormatQTY($xyz->productID,($xyz->totalIn) );
        }

        return view('storeConfirmation.confirm', $data);
    }

    public function getconfirmMovement(Request $request) {

        
        $data['statusx'] = $request->status;

        $data['status'] = $request->status;
        Session::put('statusID',$data['status']);
        $userID = Auth::user()->id;
        $store = DB::table('store_users')->where('userID',$userID)->get();

        foreach($store  as $user) {

            $data['details'] = $this->confirmRepository->search($user->storeID,$data['status']);
        }

        foreach($data['details'] as $xyz) {

            $xyz->formatqty=$this->FormatQTY($xyz->productID,($xyz->move_in) );
        }

        return view('storeConfirmation.confirm', $data);
        
    }

    public function viewBatch($id)
    {
        $data['status'] = Session::get('statusID');
        $data['id'] = base64_decode($id);
        $data['details'] = $this->confirmRepository->view(base64_decode($id),$data['status']);
        
        foreach($data['details'] as $xyz) {
           $xyz->formatqty=$this->FormatQTY($xyz->productID,($xyz->move_in) );
        }

        return view('storeConfirmation.view-batch', $data);
    }

    public function confirmBatch(Request $request)
    {
            foreach ($request->get('dataToUpdate') as $key => $value) {

                $getID = $this->confirmRepository->getProductRefID($value);
                $this->confirmRepository->updateReceivedProductIsaccepted($getID->transfer_refID);

                $data['success'] = $this->confirmRepository->confirm($request->input('productID'),$value);
        }

    }

    public function rejectBatch(Request $request)
    {

        foreach ($request->get('dataToUpdate') as $key => $value) { 

            $this->confirmRepository->reject($request->input('productID'),$value);
            $getID = $this->confirmRepository->getProductRefID($value);
            $this->confirmRepository->updateRejectProduct($getID->transfer_refID);

            $this->confirmRepository->saveToCommentTable([
                'orderNo'=>$getID->orderNo,
                'item_id'=>$value,
                'comment'=>$request->comment,
                'rejected_by'=>Auth::user()->id,
                'rejected_date'=>date('d-m-Y')
                
            ]);

        }

    }

    public function confirmProcess($id)
    {
        $this->confirmRepository->singleProductConfirmation(decrypt($id));

        return back();
    }

    public function rejectSingleBatch(Request $request)
    {
        $this->confirmRepository->singleReject($request->prodID,$request->productID);
        $getID = $this->confirmRepository->getProductRefID($request->prodID);
        $this->confirmRepository->updateRejectProduct($getID->transfer_refID);

        $this->confirmRepository->saveToCommentTable([
            'orderNo'=>$request->orderNo,
            'item_id'=>$getID->id,
            'comment'=>$request->comment,
            'rejected_by'=>Auth::user()->id,
            'rejected_date'=>date('d-m-Y')
            
            ]);
        
        

    }

}
