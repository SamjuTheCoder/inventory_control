<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Pagination\Paginator;
//use Session;
use DB;
use Auth;

class categoryController extends Controller
{

     //class contructor
     public function __construct()
     {
         $this->middleware('auth');
     }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd('h');
        $data['getCategory'] = Category::all();
        $data['SubCategory'] = SubCategory::all();
        $data['get_sub_Category'] = DB::table('sub_categories')->rightjoin('categories', 'categories.id', '=', 'sub_categories.categoryID')->get(); //SubCategory::all(); 
        $data['getSubCategory'] = DB::table('sub_categories')->leftjoin('categories', 'categories.id', '=', 'sub_categories.categoryID')
                                    ->select('*', 'sub_categories.id as subcatID', 'categories.id as catID', 'categories.categoryTitle as categoryTitle')
                                    ->orderby('categoryTitle')->orderby('subcategoryName')
                                    //->simplePaginate(10);
                                    //dd($data['getSubCategory']);
                                    ->paginate(10);
        $data['getEditRecord'] =    Session::get('editCategorySession');
        $data['editSubCategory'] =  (Session::get('editSubCategorySession') ? Session::get('editSubCategorySession') : null);
        
        return view('categories.category', $data);
    }

    public function forgetCategory (Request $request){
        Session::forget('editCategorySession');
        return redirect()->route('getCategory');
    }

    public function forgetSubCategory (Request $request){
        Session::forget('editSubCategorySession');
        return redirect()->route('getCategory');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveCategory(Request $request)
    {
        //dd('here');
         //Initialization
         $saved = null;
         //validation
         $this->validate($request,
         [
            'category'    => ['required', 'string', 'max:190', 'unique:categories,categoryTitle'],
            
         ]);
         $recordID = $request->get('recordID');
         //DB transactions
         //$saved = DB::table('categories')->insert([ 'categoryTitle' => $request->input('category')]);
           // dd($recordID);
        if($recordID == null){  
            //try{
                $saved = DB::table('categories')->insert([ 'categoryTitle' => $request->input('category')]);
                // $saved = new Category;
                // $saved = $Category->categoryTitle = $request->input('category');
                //$saved = Category::create(['categoryTitle' => $request['category']]);
                // if($saved == true)
                // {
                    return back()->with('success', 'New Category was created.');
                //}
            }//catch(\Throwable $getError){
            //    $this->storeTryCatchError($getError, 'categoryController@saveCategory', 'Error occurred when adding new record.' );
           // }
        //}
        else{
            $saved = DB::table('categories')->where('id', $recordID)->update([ 'categoryTitle' => $request->input('category')]);
            Session::forget('editCategorySession');
            //dd($saved);
            return redirect()->route('getCategory')->with('success', 'Category updated successfully.');
        }
         return back()->with('error_message', 'Sorry, we cannot create you record now. Please try again.');           
    }//end fun
    

     public function editCategory( $recordID)
     {   
         //DB transactions
         Session::forget('editCategorySession');
         try{
             $getRecord      = Category::find($recordID);
            //dd($getRecord);
             if($getRecord)
             {
                 Session::put('editCategorySession', $getRecord);
                 return redirect()->route('getCategory');
             }
         }catch(\Throwable $getError){
             $this->storeTryCatchError($getError, 'categoryController@editCategory', 'Error occurred when editing record.' );
         }
         return redirect()->route('getCategory')->with('error_message', 'Sorry, we cannot edit this record now. Please try again.');
     }//end func
 
 
     //Delete Record
     public function deleteCategory($recordID)
     {   
        //$getRecord      = Category::where('id', 'recordID')->first();
        $checkInUse =   DB::table('sub_categories')->where('categoryID', $recordID)->first();
        //dd($checkInUse);
        if ($checkInUse == true  ){
            //dd('computers');
            return back()->with('error_message', 'record cannot be deleted because it is currently assigned to a Subcategory!');
        }else{
            //dd('Construction');
            DB::table('Categories')->where('id', $recordID)->delete();
            return back()->with('success', 'record has been deleted!');
        }
     }
     
    
    public function editSubCategory($recordID)
    {            
          //dd('here');
          //DB transactions
          Session::forget('editSubCategorySession');
          try{
              $getRecords = SubCategory::where('sub_categories.id', $recordID)
                                 ->join('categories', 'categories.id', '=', 'sub_categories.categoryID')
                                 ->select('*', 'categories.id as categoryNameID', 'sub_categories.id as subCategoryID')
                                 ->first();
              if($getRecords)
              {
                  Session::put('editSubCategorySession', $getRecords);
                  return redirect()->route('getCategory');
              }
          }catch(\Throwable $getError){
              $this->storeTryCatchError($getError, 'categoryController@editSubCategory', 'Error occurred when editing record.' );
          }
          return redirect()->route('getCategory')->with('error_message', 'Sorry, we cannot edit this record now. Please try again.');
    }//end func
 
    public function deleteSubCategory($recordID)
    {           
         //DB transactions
         $getRecord      = SubCategory::where('id', $recordID)->first();
         $checkInUse     = Product::where('subcategoryID', $recordID)->first();

         if($checkInUse == false){
             //dd('delete');
             DB::table('sub_categories')->where('id', $recordID)->delete();
             return redirect()->route('getCategory')->with('success', 'record deleted successfully.');
         }else{
             //dd('dont delete');
             return redirect()->route('getCategory')->with('error_message', "Sorry, This record it's in use. We cannot delete this record now.");
         }//end func
    }

    public function SaveSubCategory (Request $request) 
    {
         //dd('here');
        $this->validate($request,
        [
           'category_id'   => ['required', 'integer'],
           "subCategory"    => ['required', 'string', 'max:190', 'unique:sub_categories,subcategoryName']
        ]);

        $recordID = $request['recordID'];
        //dd($recordID);

        if ($recordID == null){
            $insert = DB::table('sub_categories')->insert([ 'subcategoryName' => $request->input('subCategory'), 'categoryID' => $request->input('category_id')
            ]);
            return redirect()->route('getCategoryInd')->with('success', 'Your record was created successfully.');
        }else{
            $saved = DB::table('sub_categories')->where('id', $recordID)->update([ 'subcategoryName' => $request->input('subCategory'), 'categoryID' => $request->input('category_id')
            ]);
            Session::forget('editSubCategorySession');
            return redirect()->route('getCategoryInd')->with('success', 'Your record was updated successfully.');
        }
    }
}


