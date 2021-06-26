<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeasurementModel;
use App\Models\ProductMovement;
use App\Models\MeasurementUnitModel;
use App\Models\Product;
use App\Models\Project;
use App\Models\Store;
use App\Models\StoreUser;
use App\Models\Shelf;
use Session;
use Auth;
use DB;


class TransferProductOutController extends Controller
{
    //class contructor
    public function __construct()
    {
        $this->middleware('auth');
    }


    //create measurement
    public function createProductTransfer()
    {
        $data = $this->indexData();
        Session::forget('editRecord');
        return view('productTransfer.transferProductOut', $data);
    }//end fun

    public function indexData()
    {
        $data['getRecords']     = [];
        $data['getStore']       = [];
        $data['getShelf']       = [];
        $data['getMeasurement'] = [];
        $data['getProduct']     = [];

        try{
            $data['getRecords']     = $this->getProductEntered();
            $data['getStore']       = $this->getUserStore();
            $data['getDestinationStore'] = Store::all(); //you can reuse this function by passing one parameter: userID
            //$data['getShelf']       = Shelf::all();
            $data['getProject']     = Project::all();
            $data['getProduct']     = Product::all();
            (Session::get('editRecord') ? $data['editRecord'] = Session::get('editRecord') : null);
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'ProductMovementOutController@createProductMovement', 'Error occurred when fetching records.' );
        }

        return $data;
    }

    public function indexReportTransferDetails($orderNumber = null)
    {       
        try{
            $data['ProductDetails'] = ProductMovement::where('orderNo', $orderNumber)
            ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
            ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
            ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
            ->select('*', 'product_movements.id as recordID', 'product_movements.description as productDescription')
            ->first();
            $data['destinationStoreName'] = $this->destinationStore('storeID_destination', $data['ProductDetails']->recordID);

            if( !$data['ProductDetails'])
            {
                return redirect()->back()->with('error', 'Sorry, this record cannot be found!'); 
            }
            $data['transferredProduct'] =  ProductMovement::where('product_movements.is_transferred', 1)
                //->where('product_movements.is_accepted', ($getStatus ? '=' : '<>'), $getStatus)
                ->where('product_movements.move_in', 0)
                ->where('product_movements.orderNo', $orderNumber)
                //->where('product_movements.userID', (Auth::check() ? Auth::user()->id : null))
                ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->leftjoin('measurements', 'measurements.id', '=', 'product_movements.measurementID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('users', 'users.id', '=', 'product_movements.userID')
                ->select('*', 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'measurements.id as measurementID', 'measurements.description as measureName', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.orderNo', 'Asc')
                ->orderBy('product_movements.quantity', 'Asc')
                ->orderBy('product_movements.id', 'Desc')
                ->paginate(50);
            foreach($data['transferredProduct'] as $item)
            {
                $item->itemStatus = $this->getProductStatus($item->recordID);
                $item->destinationStoreName = $this->destinationStore('storeID_destination', $item->recordID);
            }
        }catch(\Throwable $e){}
        return view('Report.transferProductReportDetails', $data);
    }



    //Get Measurement when a product is selected
    public function getProductMeasurement($productID = null)
    {
        $getMeasurement = [];
        if(Product::find($productID))
        {
            $getMeasurement = MeasurementUnitModel::where('measurement_units.productID', $productID)
                            ->join('measurements', 'measurements.id', '=', 'measurement_units.measurementID')
                            ->select('measurement_units.id as id', 'description', 'measurementID', 'quantity')
                            ->orderBy('quantity', 'Asc')
                            ->get();
        }
        return $getMeasurement;
    }


    //Get User Store
    public function getUserStore($userID = null)
    {
        $myStore    = [];
        $userID     = ($userID == null ? (Auth::check() ? Auth::user()->id : null) : $userID);
        if(StoreUser::where('userID', $userID))
        {
            $myStore = StoreUser::where('store_users.userID', $userID)
                            ->join('stores', 'stores.id', '=', 'store_users.storeID')
                            ->get();
        }
        return $myStore;
    }


    //Store
    public function saveProductTransfer(Request $request)
    {
        //Initialization
        $saved = null;
        $request['save'] = null;
       
        //validation
        $this->validate($request,
        [
            'store'             => ['required', 'numeric'],
            'product'           => ['required', 'numeric'],
            'measure'           => ['required', 'numeric'],
            'quantity'          => ['required', 'numeric', 'min: 1'],
        ]);
        $recordID = $request['getRecord'];
        try{
            //check if user selected difference stores
            $getStore = ProductMovement::where('status', 0)->where('is_transferred', 1)->where('is_accepted', 1)->where('userID', (Auth::check() ? Auth::user()->id : null))->value('storeID');
            if($getStore && ($request['store'] <> $getStore) )
            {
                return redirect()->back()->withInput($request->all())->with('error', 'Sorry, you cannot select another store apart from the one you have started with. Complete this transaction to change your store.');
            }

            //Check product availability
            $getProductBal = $this->getProductQtyBalance($request['product'], $request['quantity'], $request['store']);
            if($getProductBal['validationPass'] == false)
            {
                return redirect()->back()->withInput($request->all())->with('error', 'Sorry, the quantity you want to move out is more than the available quantity. Available quantity is: '. $getProductBal['productAvailable'])->withInputs($request->all());
            }

            $getProductLeast = MeasurementUnitModel::where('measurementID', $request['measure'])->where('productID', $request['product'])->value('quantity');
            if(ProductMovement::find($recordID))
            {
                $saved = ProductMovement::where('id', $recordID)->update(
                    [
                        'userID'            => (Auth::check() ? Auth::user()->id : null),
                        'storeID'           => $request['store'],
                        'productID'         => $request['product'],
                        'measurementID'     => $request['measure'],
                        'move_out'          => (is_numeric($request['quantity']) ? ($getProductLeast * $request['quantity']) : 1),
                        'quantity'          => $request['quantity'],
                    ]);
            }else{
                $saved = ProductMovement::create(
                    [
                        'userID'            => (Auth::check() ? Auth::user()->id : null),
                        'storeID'           => $request['store'],
                        'productID'         => $request['product'],
                        'measurementID'     => $request['measure'],
                        'move_out'          => (is_numeric($request['quantity']) ? ($getProductLeast * $request['quantity']) : 1),
                        'quantity'          => $request['quantity'],
                        'is_transferred'    => 1,
                        'status'            => 0
                    ]);
            }
            if($saved)
            {   
                $request['save'] = $saved;
                Session::forget('editRecord');
                return redirect()->back()->withInput($request->all)->with('message', 'New product was created/updated successfully.', $data);
            }
        }catch(\Throwable $getError){
            //$this->storeTryCatchError($getError, 'TransferProductOutController@saveProductTransfer', 'Error occurred when adding/updating product.' );
        }
        return redirect()->back()->withInput($request->all)->with('error', 'Sorry, we cannot create/update your record now. Please try again.');
    }//end fun




    //Create Eidt Index for Rejected Product
    public function indexRejectedProductTransferOut()
    {
        if (url()->previous() != url()->current()) { 
            Session::forget('editRecordRejected');
        }
        $comment  = [];
        try{
            $data['getStore']       = $this->getUserStore();
            $data['getDestinationStore'] = Store::all();
            $data['getProduct']     = Product::all();

            $data['getRecords'] =  ProductMovement::where('product_movements.is_transferred', 1)
                ->where('product_movements.is_accepted', 2)
                ->where('product_movements.move_out', '<>', 0)
                //->where('product_movements.userID', (Auth::check() ? Auth::user()->id : null))
                ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->leftjoin('measurements', 'measurements.id', '=', 'product_movements.measurementID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('users', 'users.id', '=', 'product_movements.userID')
                ->select('*', 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'measurements.id as measurementID', 'measurements.description as measureName', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.orderNo', 'Asc')
                ->orderBy('product_movements.quantity', 'Asc')
                ->orderBy('product_movements.id', 'Desc')
                ->get(20);

            $data['editRecord'] = Session::get('editRecordRejected');
            //get comment
            foreach($data['getRecords'] as $key=>$item)
            {
                $getSendRecordID = ProductMovement::where('transfer_refID', $item->recordID)->value('id');
                $comment[$item->recordID] = DB::table('rejected_comment')->where('item_id', $getSendRecordID)->get(); //->where('order_no', $item->orderNo)
            }
            $data['allComments'] = $comment; 
            
            //get quantity
            if($data['editRecord'])
            {
                $data['measureQuantiry'] = MeasurementUnitModel::where('measurement_units.productID', $data['editRecord']->productID)
                ->join('measurements', 'measurements.id', '=', 'measurement_units.measurementID')
                ->value('quantity');
            }

        }catch(\Throwable $getError){}

        return view('productTransfer.transferProductEdit', $data);
    }


    //SAVE EDIT For Rejected Products
    public function saveEditProductTransferOut(Request $request)
    {
        //Initialization
        $saved = null;
        //validation
        $this->validate($request,
        [
            'store'             => ['required', 'numeric'],
            'product'           => ['required', 'numeric'],
            'measure'           => ['required', 'numeric'],
            'quantity'          => ['required', 'numeric', 'min: 1'],
        ]);
        $recordID = $request['getRecord'];

        try{
            //check if user selected difference stores
            $getStore = ProductMovement::where('status', 0)->where('is_transferred', 1)->where('is_accepted', 1)->where('userID', (Auth::check() ? Auth::user()->id : null))->value('storeID');
            if($getStore && ($request['store'] <> $getStore) )
            {
                return redirect()->back()->with('error', 'Sorry, you cannot select another store apart from the one you have started with. Complete this transaction to change your store.');
            }
            //Check product availability
            $getProductBal = $this->getProductQtyBalance($request['product'], $request['quantity']);
            if($getProductBal['validationPass'] == false)
            {
                return redirect()->route('createEditTransaferReport')->with('error', 'Sorry, the quantity you want to move out is more than the available quantity. Available quantity is: '. $getProductBal['productAvailable'])->withInputs($request->all());
            }
            $getProductLeast = MeasurementUnitModel::where('measurementID', $request['measure'])->where('productID', $request['product'])->value('quantity');
            if(ProductMovement::find($recordID))
            {
                //mine
                $saved = ProductMovement::where('id', $recordID)->update(
                    [
                        'userID'            => (Auth::check() ? Auth::user()->id : null),
                        'storeID'           => $request['store'],
                        'productID'         => $request['product'],
                        'measurementID'     => $request['measure'],
                        'move_out'          => (is_numeric($request['quantity']) ? ($getProductLeast * $request['quantity']) : 1),
                        'quantity'          => $request['quantity'],
                    ]);
                //sender
                $getSendRecordID = ProductMovement::where('transfer_refID', $recordID)->value('id');
                $saved = ProductMovement::where('id', $getSendRecordID)->update(
                    [
                        'userID'            => (Auth::check() ? Auth::user()->id : null),
                        'storeID'           => $request['store'],
                        'productID'         => $request['product'],
                        'measurementID'     => $request['measure'],
                        'move_in'           => (is_numeric($request['quantity']) ? ($getProductLeast * $request['quantity']) : 1),
                        'quantity'          => $request['quantity'],
                    ]);
            }
            if($saved)
            {
                Session::forget('editRecordRejected');
                return redirect()->back()->with('message', 'Your record was updated successfully.');
            }
        }catch(\Throwable $getError){}
        return redirect()->back()->with('error', 'Sorry, we cannot update your record now. Please try again.');
    }//end fun



    //Edit Product Movement Record
    public function editProductMovement($recordID)
    {
        //DB transactions
        Session::forget('editRecord');
        try{
            $getRecord      = ProductMovement::find($recordID);
            if($getRecord)
            {
                Session::put('editRecord', $this->getProductEntered($recordID));

                return redirect()->route('transferProduct');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'TransferProductOutController@editProductMovement', 'Error occurred when editting record.' );
        }

        return redirect()->route('transferProduct')->with('error', 'Sorry, we cannot edit this record now. Please try again.');
    }//end fun


    //Delete Record
    public function deleteProductMovement($recordID)
    {
        //DB transactions
        try{
            $getRecord       = ProductMovement::find($recordID);
            if($getRecord  && ($getRecord->isConfirmed <= 0))
            {
                $getSendRecordID = ProductMovement::where('transfer_refID', $recordID)->value('id');
                ProductMovement::find($getSendRecordID)->delete();
                $getRecord->delete();
                return redirect()->back()->with('message', 'Your record was deleted successfully.');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'TransferProductOutController@deleteProductMovement', 'Error occurred when deleting record.' );
        }
        return redirect()->back()->with('error', 'Sorry, we cannot delete this record now. Please try again.');
    }//end fun


    public function getProductEntered($recordID = null)
    {
        try{
            if($recordID)
            {
                return ProductMovement::where('product_movements.id', $recordID)
                    ->where('product_movements.status', 0)
                    ->where('product_movements.is_transferred', 1)
                    ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
                    ->leftjoin('measurements', 'measurements.id', '=', 'product_movements.measurementID')
                    ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                    ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                    ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                    ->select('*', 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'measurements.id as measurementID', 'measurements.description as measureName', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                    ->orderBy('product_movements.id', 'Desc')
                    ->first();
            }else{
                return ProductMovement::where('product_movements.status', 0)
                    ->where('product_movements.is_transferred', 1)
                    ->where('product_movements.is_adjusted', 0)
                    ->where('product_movements.userID', (Auth::check() ? Auth::user()->id : null))
                    ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
                    ->leftjoin('measurements', 'measurements.id', '=', 'product_movements.measurementID')
                    ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                    ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                    ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                    ->select('*', 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'measurements.id as measurementID', 'measurements.description as measureName', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                    ->orderBy('product_movements.id', 'Desc')
                    ->get();
            }

        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'TransferProductOutController@getProductEntered', 'Error occurred when fetching records.' );
        }
        return;
    }


    //Batch Item
    public function batchAllProductsToBeTransferred(Request $request)
    {
        $isSuccess = 0;
        $this->validate($request,
        [
            'transactionDate'   => ['required', 'date'],
            'description'       => ['required', 'string'],
            'destinationStore'  => ['required', 'numeric'],
        ]);
        if($request['destinationStore'] == ProductMovement::where('status', 0)->where('is_transferred', 1)->where('is_accepted', 1)->where('userID', (Auth::check() ? Auth::user()->id : null))->value('storeID'))
        {
            return redirect()->back()->with('error', 'Sorry, transferring from the same store/warehouse to the same store is not allowed.');
        }

        try{
            $productMovement = new ProductMovement;
            $randomDigit = rand(98979489, 8357373853);
            $isSuccess = $productMovement::where('status', 0)->where('is_transferred', 1)->where('is_accepted', 1)->where('userID', (Auth::check() ? Auth::user()->id : null))->update([
                'status'            => 1,
                'orderNo'           => $randomDigit,
                'transactionDate'   => date('Y-m-d', strtotime($request['transactionDate'])),
                'description'       => $request['description'],
                'is_transferred'    => 1,
                'storeID_destination' => $request['destinationStore'],
            ]);

            if($isSuccess)
            {
                $addDataToIn = ProductMovement::where('orderNo', $randomDigit)
                    ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
                    ->leftjoin('measurements', 'measurements.id', '=', 'product_movements.measurementID')
                    ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                    ->select('*', 'product_movements.id as productMoveID', 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'measurements.id as measurementID', 'measurements.description as measureName', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                    ->get();

                foreach ($addDataToIn as $key => $value)
                {
                    ProductMovement::create(
                    [
                        'userID'            => $value->userID,
                        'storeID'           => $request['destinationStore'],
                        'productID'         => $value->productID,
                        'measurementID'     => $value->measurementID,
                        'transfer_refID'    => $value->productMoveID,
                        'move_in'           => $value->move_out,
                        'quantity'          => $value->quantity,
                        'is_transferred'    => $value->is_transferred,
                        'is_accepted'       => 0,
                        'status'            => 1,
                        'orderNo'           => $value->orderNo,
                        'transactionDate'   => $value->transactionDate,
                        'description'       => $request['description'],
                        'storeID_destination' => $value->storeID
                    ]);
                }
            }

        }catch(\Throwable $err){}
        if($isSuccess)
        {
            return redirect()->back()->with('success', 'All unbatched items were now batched successfully as a single entry with the same order number: '. $randomDigit);
        }else{
            return redirect()->back()->with('warning', 'Sorry, we cannot batch your items now or no items to be batched. Please try again.');
        }
    }


    //Get All Transfer Report
    public function indexReportTransfer()
    {
        $data = [];
        $data['status'] = (Session::get('getStatus') ?  Session::get('getStatus') : null);
        $data['getDestinationStore'] = Store::all();
        $data['transferredProduct'] = $this->getProductTransferredQuery($data['status']);

        return view('Report.transferProductReport', $data);
    }

    //Get All Transfer data/Record
    public function getProductTransferredQuery($getStatus = null, $status = 1)
    {
        $itemStatus = null;
        $arrayID    = [];
        //$getStatus = ($getStatus == 3 ? 0 : $getStatus);
        try{
            if($getStatus == 'pending')
            {
                $getQueryID =  ProductMovement::where('product_movements.is_transferred', 1)
                ->where('product_movements.is_accepted', '=', 0)
                ->where('product_movements.move_out', '=', 0)
                ->select('product_movements.transfer_refID as rID')
                ->groupBy('product_movements.orderNo')
                ->get();
            }else{
                $getQueryID =  ProductMovement::where('product_movements.is_transferred', 1)
                ->where('product_movements.is_accepted', ($getStatus ? '=' : '<>'), $getStatus)
                ->where('product_movements.status', ($status ? '=' : '<>'), $status)
                ->where('product_movements.move_out', '=', 0)
                ->select('product_movements.transfer_refID as rID')
                ->groupBy('product_movements.orderNo')
                ->get();
            }
            foreach($getQueryID as $item)
            {
                $arrayID[] = $item->rID;
            }
            $queryResult =  ProductMovement::where('product_movements.is_transferred', 1)
                //->where('product_movements.is_accepted', ($getStatus ? '=' : '<>'), $getStatus)
                //->where('product_movements.move_out', '<>', 0)
                ->whereIn('product_movements.id', $arrayID)
                //->where('product_movements.userID', (Auth::check() ? Auth::user()->id : null))
                ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
                ->leftjoin('measurements', 'measurements.id', '=', 'product_movements.measurementID')
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                ->leftjoin('users', 'users.id', '=', 'product_movements.userID')
                ->select('*', 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'measurements.id as measurementID', 'measurements.description as measureName', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                ->orderBy('product_movements.orderNo', 'Asc')
                ->orderBy('product_movements.quantity', 'Asc')
                ->orderBy('product_movements.id', 'Desc')
                ->paginate(20);
                foreach($queryResult as $item)
                {
                    $item->itemStatus = $this->getProductStatus($item->recordID);
                    $item->destinationStoreName = $this->destinationStore('storeID_destination', $item->recordID);
                }
            return $queryResult;
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'TransferProductOutController@getProductTransferredQuery', 'Error occurred when fetching records.' );
        }
        return;
    }

    //Get destination store name
    public function destinationStore($fieldName = null, $productMovemtID = null)
    {
        return ProductMovement::where('product_movements.is_transferred', 1)->where('product_movements.move_out', '<>', 0)
                ->leftjoin('stores', 'stores.id', '=', 'product_movements.'.$fieldName)
                ->where('product_movements.id', $productMovemtID)
                ->value('store_name');
                //->value();
    }

    //Get product status
    public function getProductStatus($productMovemtID = null)
    {
        $getValue = ProductMovement::where('product_movements.transfer_refID', $productMovemtID)->value('is_accepted');
        if($getValue == 0) $status = '<span class="text-info">Pending</span>';
        if($getValue == 1) $status = '<span class="text-success">Accepted</span>';
        if($getValue == 2) $status = '<span class="text-danger">Rejected</span>';

        return $status;
    }

    //change report query
    public function queryTransferredReport(Request $request)
    {   $arrayStatus = array(1,2,3);
        if( in_array($request['statusCode'], $arrayStatus) || $request['statusCode'] <> 'All')
        {
            Session::put('getStatus', $request['statusCode']);
        }else{
            Session::forget('getStatus');
        }
        return redirect()->route('viewTransaferReport');
    }



    //Prepare Edit Record for rejetc product
    public function editRejectedProductMovement($recordID)
    {
        //DB transactions
        Session::forget('editRecordRejected');
       try{
            $getRecord      = ProductMovement::find($recordID);
            if($getRecord)
            {
                $getEditRecord =  ProductMovement::where('product_movements.id', $recordID)
                    ->where('product_movements.is_transferred', 1)
                    ->leftjoin('products', 'products.id', '=', 'product_movements.productID')
                    ->leftjoin('measurements', 'measurements.id', '=', 'product_movements.measurementID')
                    ->leftjoin('stores', 'stores.id', '=', 'product_movements.storeID')
                    ->leftjoin('categories', 'categories.id', '=', 'products.categoryID')
                    ->leftjoin('projects', 'projects.id', '=', 'product_movements.projectID')
                    ->select('*', 'product_movements.storeID as productStoreID', 'product_movements.description as productDescription', 'product_movements.quantity as productQuantity', 'measurements.id as measurementID', 'measurements.description as measureName', 'product_movements.id as recordID', 'product_movements.updated_at as dateUpdated')
                    ->orderBy('product_movements.id', 'Desc')
                    ->first();
                Session::put('editRecordRejected', $getEditRecord);
                return redirect()->route('createEditTransaferReport');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'TransferProductOutController@editRejectedProductMovement', 'Error occurred when editting record.' );
        }
        return redirect()->route('createEditTransaferReport')->with('error', 'Sorry, we cannot edit this record now. Please try again.');
    }//end fun


    //Resend Transaferred product
    public function resendProductTransferred(Request $request)
    {
        $isSuccess = 0;
        $this->validate($request,
        [
            'rejected'      => ['required'],
        ]);
        try{
            foreach($request['rejected'] as $item)
            {
                //mine
                $isSuccess = ProductMovement::where('id', $item)->update([
                    'status'            => 1,
                    'is_accepted'       => 1
                ]);
                //Sender
                $getSendRecordID = ProductMovement::where('transfer_refID', $item)->value('id');
                $isSuccess = ProductMovement::where('id', $getSendRecordID)->update([
                    'status'            => 1,
                    'is_accepted'       => 0
                ]);
            }
        }catch(\Throwable $e){}
        if($isSuccess)
        {
            return redirect()->back()->with('message', 'Your record was resend successfully.');
        }
        return redirect()->back()->with('error', 'Sorry, an error occurred when resending your record.');
    }


}//end class
