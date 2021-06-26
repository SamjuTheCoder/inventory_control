<?php
namespace App\Repository;

use App\Repository\ConfirmInterface;
use App\Models\ProductMovement;
use App\Models\StoreUser;
use App\Models\RejectedComment;
use DB;
use Auth;


class ConfirmRepository implements ConfirmInterface
{
    protected $model;
    protected $model_comment;

    public function __construct()
    {
        $this->model = new ProductMovement;
        $this->model_comment = new RejectedComment;
    }

    public function all($id)
    {    
        return $this->model
        ->where('storeID',$id)
        ->where('is_transferred','=',1)
        ->where('product_movements.status','=',1)
        ->where('is_accepted','=',0)
        ->where('is_adjusted','=',0)
        ->leftjoin('stores', 'product_movements.storeID_destination', '=', 'stores.id')
        ->leftjoin('projects', 'product_movements.projectID', '=', 'projects.id')
        ->leftjoin('products', 'product_movements.productID', '=', 'products.id')
        ->leftjoin('measurements', 'product_movements.measurementID', '=', 'measurements.id')
        ->select('*', DB::raw("SUM(move_in) as totalIn"),'product_movements.description as product_desc', 'product_movements.orderNo', 'product_movements.productID as pid','products.productName')
        ->groupBy('product_movements.orderNo')
        ->get();
    }

    public function view($id,$isAccepted)
    {
        return $this->model
        ->where('orderNo',$id)
        ->where('move_in','>',0)
        //->where('is_accepted','=',$isAccepted)
        ->where('is_adjusted','=',0)
        ->leftjoin('stores', 'product_movements.storeID_destination', '=', 'stores.id')
        ->leftjoin('products', 'product_movements.productID', '=', 'products.id')
        ->select('*','product_movements.id as pid')
        ->get();
    }

    public function confirm($id,$value)
    {
        return $this->model
        ->where('orderNo',$id)
        ->where('id',$value)
        ->update([
         'is_accepted'=>1,
         'user_acceptanceID'=>Auth::user()->id
         ]);
    }

    public function updateReceivedProductIsaccepted($id)
    {
        return $this->model
        ->where('id',$id)
        ->update([
         'is_accepted'=>1,
         ]);
    }

    public function reject($id, $value)
    {
        return $this->model
        ->where('orderNo',$id)
        ->where('id',$value)
        ->update([
         'is_accepted'=>2,
         'status'=>1,
         'user_acceptanceID'=>Auth::user()->id
         ]);
    }

    public function search($store,$id)
    {
        return $this->model
        ->where('storeID',$store)
        ->where('is_accepted',$id)
        ->where('is_transferred','=',1)
        ->where('product_movements.status','=',1)
        ->where('is_adjusted','=',0)
        ->leftjoin('stores', 'product_movements.storeID', '=', 'stores.id')
        ->leftjoin('projects', 'product_movements.projectID', '=', 'projects.id')
        ->leftjoin('products', 'product_movements.productID', '=', 'products.id')
        ->leftjoin('measurements', 'product_movements.measurementID', '=', 'measurements.id')
        ->select('*', DB::raw("SUM(move_in) as totalIn"),'product_movements.description as product_desc', 'product_movements.orderNo', 'product_movements.productID as pid','products.productName')
        ->groupBy('product_movements.orderNo')
        ->get();
    }

    public function saveToCommentTable(array $data)
    {
        return $this->model_comment->create($data);
    }

    public function singleProductConfirmation($id)
    {
        $getID = $this->model->where('id',$id)->first();
        DB::table('product_movements')->where('id',$getID->transfer_refID)->update(['is_accepted'=>1]);

        return $this->model
        ->where('id',$id)
        ->update(['is_accepted'=>1, 'user_acceptanceID'=>Auth::user()->id]);
    }

    public function singleReject($id, $value)
    {
        return $this->model
        ->where('id',$id)
        ->where('productID',$value)
        ->update([
         'is_accepted'=>2,
         'status'=>1,
         'user_acceptanceID'=>Auth::user()->id
         ]);
    }

    public function getProductRefID($id)
    {
        return $this->model->where('id',$id)->first();
    }

    public function updateRejectProduct($id)
    {
        return $this->model
        ->where('id',$id)
        ->update([
         'is_accepted'=>2,
         'status'=>1,
         ]);
    }
}