<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Models\Store;
use App\Models\Product;
use App\Models\Project;
use App\Models\ProductMovement;
use Carbon\Carbon;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /*
        $myData = new DashboardController;
        $data['totalProducts'] = $myData->Statistics();
        
        $data['warehouse'] = DB::table('stores')->get();
        */
        $currentMonth = date('m');
        $fetchStoreCount    =   Store::count();
        $fetchProductCount  =   Product::count();
        $fetchProjectCount  =   Project::where('status', '=', 1)->count();
        $fetchProductMovementInSum   =   ProductMovement::sum('move_in');
        $fetchProductMovementOutSum  =   ProductMovement::sum('move_out');
        $fetchProductMovementShipIn  =   ProductMovement::groupBy('orderNo')->where('move_in', '>', '0')->where('status', '>', '0')->where('product_movements.is_transferred', '=', '0')
        ->where('product_movements.is_adjusted', '=', '0')->whereMonth('created_at', Carbon::now()->month)->count();
        $fetchProductMovementShipOut =   ProductMovement::groupBy('orderNo')->where('move_out', '>', '0')->where('status', '>', '0')->where('product_movements.is_transferred', '=', '0')
        ->where('product_movements.is_adjusted', '=', '0')->whereMonth('created_at', Carbon::now()->month)->count();

        //get data
        $fetchProduct  =   Product::leftJoin('categories', 'categories.id', '=', 'products.categoryID')->leftJoin('product_images', 'product_images.productID', '=', 'products.id')->limit(20)->get();
        $fetchStore    =   Store::get();
        $fetchRecentProduct = ProductMovement::leftJoin('users', 'users.id', '=', 'product_movements.userID')
            ->leftJoin('stores', 'stores.id', '=', 'product_movements.storeID')
            ->leftJoin('products', 'products.id', '=', 'product_movements.productID')
            ->leftJoin('projects', 'projects.id', '=', 'product_movements.projectID')
            ->select('product_movements.id', 'users.name as users_name', 'projects.projectName', 'store_name', 'productName', 'product_movements.orderNo', 'move_in', 'product_movements.transactionDate', 'product_movements.description')
            ->where('product_movements.is_transferred', '=', '0')
            ->where('product_movements.is_adjusted', '=', '0')
            ->where('product_movements.status', '=', '1')
            ->orderBy('product_movements.transactionDate', 'asc')
            ->groupBy('orderNo')
            ->limit(10)
            ->get();
        //dd($fetchProductMovementShipIn);
        //dd($fetchProjectMovementInSum);
        return view('home')->with([
            'storeCount' => $fetchStoreCount,
            'productCount' => $fetchProductCount,
            'projectCount' => $fetchProjectCount,
            'productMovementInSum' => $fetchProductMovementInSum,
            'productMovementOutSum' => $fetchProductMovementOutSum,
            'dataShipIn' => $fetchProductMovementShipIn,
            'dataShipOut' => $fetchProductMovementShipOut,
            'allProduct' => $fetchProduct,
            'allStore'  =>  $fetchStore,
            'allRecentProduct' => $fetchRecentProduct]);
    }
    public function form()
    {
        return view('components.pages.basic-form');
    }
    public function table()
    {
        return view('components.pages.table');
    }
    public function modal()
    {
        return view('components.pages.modal');
    }
    public function icon()
    {
        return view('components.pages.themefy-icon');
    }
}
