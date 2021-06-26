<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductMovement;
use App\Models\MeasurementUnitModel;
use App\Models\RejectedComment;
use Illuminate\Support\Facades\Auth;
use Session;

class ProductConfirmationController extends Controller
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
        
        try {
            //code...
            
            $fetchProductIn = ProductMovement::leftJoin('users', 'users.id', '=', 'product_movements.userID')
            ->leftJoin('stores', 'stores.id', '=', 'product_movements.storeID')
            ->leftJoin('products', 'products.id', '=', 'product_movements.productID')
            ->leftJoin('projects', 'projects.id', '=', 'product_movements.projectID')
            ->select('product_movements.id', 'users.name as users_name', 'projects.projectName', 'store_name', 'productName', 'product_movements.orderNo', 'move_in', 'product_movements.transactionDate', 'product_movements.description')
            ->where('product_movements.move_in', '>', '0')
            ->where('product_movements.move_out', '=', '0')
            ->where('product_movements.is_transferred', '=', '0')
            ->where('product_movements.is_adjusted', '=', '0')
            ->where('product_movements.status', '=', '1')
            ->orderBy('product_movements.transactionDate', 'Asc')
            ->groupBy('orderNo')
            ->get();
            
            
            
        } catch (\Throwable $th) {
            //throw $th;
        }
        return view('confirmation.confirmIn.display-in')->with(['fetchDataProductIn'=> $fetchProductIn]);
    }
    

    public function process($id)
    {
        # code...
        $id = decrypt($id);
        /*$this->validate($request,
        [
            'item_id'           => ['required', 'numeric'],
        ]);*/
        
        try {
            //code...$request->get('item_id')
            $updatePrMovement   =   ProductMovement::where('id', '=', $id)
            ->update([
                'isConfirmed'   =>  '1',
                'isConfirmedBy' =>  auth()->user()->id,
                'confirmation_date' => date('d-m-y')
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', '<h5>Oops!</h5> Confirmation failed.');
        }
        return back()->with('success', '<h5>Great!</h5> Item confirmed successfully.');
    }

    public function multiRejection(Request $request)
    {
        # code...
        $this->validate($request,
        [
            'item_id.*'           => ['required', 'numeric'],
            'reason'           => ['required'],
        ]);
        try {
            //code...$request->get('item_id')
            foreach ($request->get('item_id') as $key => $value) {
                # code...
                $updatePrMovement   =   ProductMovement::where('id', '=', $value)
            ->update([
                'isConfirmed'   =>  '2',
                'isConfirmedBy' =>  auth()->user()->id,
                'confirmation_date' => date('d-m-Y')
            ]);
            if ($updatePrMovement) {
                # code...
                $createRejectComment    =   RejectedComment::create([
                    'item_id' => $value,
                    'comment' => $request->get('comment'),
                    'rejected_by' => auth()->user()->id,
                    'rejected_date' => date('d-m-Y')
                ]);
            } else {
                # code...
                return back()->with('error', '<h5>Oops!</h5> Rejection failed.');
            }
            }
            
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', '<h5>Oops!</h5> Rejection failed.');
        }
            return back()->with(['success'=>'<h5>Great!</h5> Rejection successful.']);
    }

    public function singleRejection(Request $request)
    {
        $this->validate($request,
        [
            'item_id' => ['required', 'numeric'],
            'comment' =>  ['required']
        ]);
        # code...
        
        try {
            //code...$request->get('item_id')
            $updatePrMovement   =   ProductMovement::where('id', '=', $request->get('item_id'))
            ->update([
                'isConfirmed'   =>  '2',
                'isConfirmedBy' =>  auth()->user()->id,
                'confirmation_date' => date('d-m-Y')
            ]);
            if ($updatePrMovement) {
                # code...
                $createRejectComment    =   RejectedComment::create([
                    'item_id' => $request->get('item_id'),
                    'comment' => $request->get('comment'),
                    'rejected_by' => auth()->user()->id,
                    'rejected_date' => date('d-m-Y')
                ]);
            } else {
                # code...
                return back()->with('error','<h5>Oops!</h5> Rejection failed.')->withInput();
            }
            
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', '<h5>Oops!</h5> Rejection failed.')->withInput();
        }
            return back()->with('success', '<h5>Great!</h5> Rejection successful.');
    }

    public function multiAproval(Request $request)
    {
        # code...
        $itemID =   $request->get('dataToUpdate');
        $userID =   Auth::user()->id;
        
        try {
            foreach ($itemID as $value) {
            //code...$request->get('item_id')
                # code...
            $updatePrMovement   =   ProductMovement::where('id', '=', $value)
            ->update([
                'isConfirmed'   =>  '1',
                'isConfirmedBy' =>  auth()->user()->id,
                'confirmation_date' => date('d-m-Y')
            ]);
            }
            session()->flash('approvalSuccess', 'Great! Transaction approved successfully.');
            return response()->json(['success'=>'1']);
        } catch (\Throwable $th) {
            //throw $th;
            session()->flash('approvalError', 'Oops! Transaction approval failed.');
            return response()->json(['error'=>'<h5>Oops!</h5> Transaction not approved.']);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listConfirmIn($id)
    {
        # code...
        $orderNo    =   decrypt($id);

        $checkOrder = ProductMovement::where('orderNo','=', $orderNo)->count();
        if($checkOrder == 0)
        {
            return back()->with('err','This order does not exist');
        }

        $data['orders'] = ProductMovement::leftjoin('stores','stores.id','=','product_movements.storeID')
        ->leftjoin('products','products.id','=','product_movements.productID')
        ->leftjoin('measurement_units','measurement_units.id','=','product_movements.measurementID')
        ->leftjoin('measurements','measurements.id','=','measurement_units.measurementID')
        ->where('move_in','>', 0)
        ->where('orderNo','=', $orderNo) 
        ->select('*','product_movements.id as prodMovementID','product_movements.measurementID as munit','measurement_units.quantity as qty','product_movements.productID as prodID','products.productName as prodName','product_movements.quantity as outQty','measurements.id as measurementID','measurements.description as desc', 'product_movements.isConfirmed as isConfirmed')
        ->get();

        $data['product'] = ProductMovement::leftjoin('stores','stores.id','=','product_movements.storeID')
        ->leftjoin('products','products.id','=','product_movements.productID')
        ->leftjoin('projects','projects.id','=','product_movements.projectID')
        ->leftjoin('measurement_units','measurement_units.id','=','product_movements.measurementID')
        ->where('product_movements.orderNo','=', $orderNo)->first();

        $data['isconfirm'] = ProductMovement::where('orderNo','=', $orderNo)->where('isConfirmed','=', 2)->count();

         foreach($data['orders'] as $list) {
           $list->formatqty = $this->FormatQTY($list->prodID,$list->move_in);

            
        }
        
        return view('confirmation.confirmIn.order-list',$data);
    }

    // confoirmation out
    public function indexOut()
    {
        //
        try {
            //code...
            $fetchProductOut = ProductMovement::leftJoin('users', 'users.id', '=', 'product_movements.userID')
            ->leftJoin('stores', 'stores.id', '=', 'product_movements.storeID')
            ->leftJoin('products', 'products.id', '=', 'product_movements.productID')
            ->leftJoin('projects', 'projects.id', '=', 'product_movements.projectID')
            ->select('product_movements.id', 'users.name as users_name', 'projects.projectName', 'store_name', 'productName', 'product_movements.orderNo', 'move_in', 'product_movements.transactionDate', 'product_movements.description')
            ->where('product_movements.move_out', '>', '0')
            ->where('product_movements.move_in', '=', '0')
            ->where('product_movements.is_transferred', '=', '0')
            ->where('product_movements.is_adjusted', '=', '0')
            //->where('product_movements.isConfirmed', '=', '0')
            ->where('product_movements.status', '=', '1')
            ->orderBy('product_movements.transactionDate', 'Asc')
            ->groupBy('orderNo')
            ->get();
            /*leftJoin('users', 'users.id', '=', 'product_movements.userID')
            ->leftJoin('stores', 'stores.id', '=', 'product_movements.storeID')
            ->leftJoin('products', 'products.id', '=', 'product_movements.productID')
            ->leftJoin('projects', 'projects.id', '=', 'product_movements.projectID')
            ->select('product_movements.id', 'users.name as users_name', 'projects.projectName', 'store_name', 'productName', 'product_movements.orderNo', 'move_in', 'product_movements.transactionDate', 'product_movements.description')
            ->where('product_movements.move_in', '>', '0')
            ->where('product_movements.move_out', '=', '0')
            ->where('product_movements.isConfirmed', '=', '0')
            ->where('product_movements.status', '=', '1')
            ->orderBy('product_movements.transactionDate', 'Asc')
            ->get();
            $data['orders'] = DB::table('product_movements')*/
            
            
        } catch (\Throwable $th) {
            //throw $th;
        }
        return view('confirmation.confirmOut.display-out')->with(['fetchDataProductOut'=> $fetchProductOut]);
    }

    
    public function processOut($id)
    {
        # code...
        $id = decrypt($id);
        /*$this->validate($request,
        [
            'item_id'           => ['required', 'numeric'],
        ]);*/
        
        try {
            //code...$request->get('item_id')
            $updatePrMovement   =   ProductMovement::where('id', '=', $id)
            ->update([
                'isConfirmed'   =>  '1',
                'isConfirmedBy' =>  auth()->user()->id,
                'confirmation_date' => date('d-m-y')
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', '<h5>Oops!</h5> Confirmation failed.');
        }
        return back()->with('success', '<h5>Great!</h5> Item confirmed successfully.');
    }


    public function listConfirmOut($id)
    {
        # code...
        $orderNo    =   decrypt($id);

        $checkOrder = ProductMovement::where('orderNo','=', $orderNo)->count();
        if($checkOrder == 0)
        {
            return back()->with('err','This order does not exist');
        }

        $data['orders'] = ProductMovement::leftjoin('stores','stores.id','=','product_movements.storeID')
        ->leftjoin('products','products.id','=','product_movements.productID')
        ->leftjoin('measurement_units','measurement_units.id','=','product_movements.measurementID')
        ->leftjoin('measurements','measurements.id','=','measurement_units.measurementID')
        ->where('move_out','>', 0)
        ->where('orderNo','=', $orderNo) 
        ->select('*','product_movements.id as prodMovementID','product_movements.measurementID as munit','measurement_units.quantity as qty','product_movements.productID as prodID','products.productName as prodName','product_movements.quantity as outQty','measurements.id as measurementID','measurements.description as desc', 'product_movements.isConfirmed as isConfirmed')
        ->get();

        $data['product'] = ProductMovement::leftjoin('stores','stores.id','=','product_movements.storeID')
        ->leftjoin('products','products.id','=','product_movements.productID')
        ->leftjoin('projects','projects.id','=','product_movements.projectID')
        ->leftjoin('measurement_units','measurement_units.id','=','product_movements.measurementID')
        ->where('product_movements.orderNo','=', $orderNo)->first();

        $data['isconfirm'] = ProductMovement::where('orderNo','=', $orderNo)->where('isConfirmed','=', 2)->count();

         foreach($data['orders'] as $list) {
           $list->formatqty = $this->FormatQTY($list->prodID,$list->move_out);

            
        }
        //dd($data['orders']);
        return view('confirmation.confirmOut.order-list',$data);
    }


    public function create()
    {
        //
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
