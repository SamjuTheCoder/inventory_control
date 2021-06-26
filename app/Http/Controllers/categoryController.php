<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Session;
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
        $data['getCategory'] = category::all();
        $data['get_sub_Category'] = DB::table('sub_categories')->rightjoin('categories', 'categories.id', '=', 'sub_categories.categoryID')->get(); //SubCategory::all(); 
        $data['getSubCategory'] = DB::table('sub_categories')->leftjoin('categories', 'categories.id', '=', 'sub_categories.categoryID')
                                    ->select('*', 'sub_categories.id as catID')
                                    ->get();
                                    //->paginate(50);
        $data['getEditRecord'] =    Session::get('editCategorySession');
        $data['editSubCategory'] =  (Session::get('editSubCategorySession') ? Session::get('editSubCategorySession') : null);
        
        return view('categories.category', $data);
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
            'category'    => ['required', 'string', 'max:190', 'unique:categories,categoryTitle']
         ]);
         //DB transactions
         //$saved = DB::table('categories')->insert([ 'categoryTitle' => $request->input('category')]);
           // dd($saved);
         try{
           // $saved = DB::table('categories')->insert([ 'categoryTitle' => $request->input('category')])
        
             $saved = Category::create(['categoryTitle' => $request['category']]);
             if($saved)
             {
                 return redirect()->route('saveCategory')->with('success', 'New Category was created.');
             }
         }catch(\Throwable $getError){
             $this->storeTryCatchError($getError, 'categoryController@saveCategory', 'Error occurred when adding new record.' );
         }
         return redirect()->route('getCategory')->with('error_message', 'Sorry, we cannot create you record now. Please try again.');
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
        //DB transactions
        try{
            $getRecord      = Category::find($recordID);
            $checkInUse     = Category::where('id', 'recordID')->first();
            if($getRecord && !$checkInUse)
            {
                $getRecord->delete();
                return redirect()->route('getCategory')->with('success', 'Your record was deleted.');
            }
        }catch(\Throwable $getError){
            $this->storeTryCatchError($getError, 'categoryController@deleteCategory', 'Error occurred when deleting record.' );
        }
        if($checkInUse)
        {
            return redirect()->route('getCategory')->with('error_message', 'Sorry, This record is in use. We cannot delete this record now.');
        }
        return redirect()->route('getCategory')->with('error_message', 'Sorry, we cannot delete this record now. Please try again.');
     }
     
    //SUB CATEGORY FUNCTIONS BELOW
     public function SaveSubCategory(Request $request )
    {
        // dd('here');
          //Initialization
          $saved = null;
          //validation
          $this->validate($request,
          [
             'category_id'   => ['required', 'integer'],
             "subCategory"    => ['required', 'string', 'max:190', 'unique:sub_categories,subcategoryName']
          ]);

          $recordID = $request['recordID'];
           //dd($recordID); 
          //DB transactions      
            try{
                $saved = DB::table('sub_categories')->where('categoryID', 'categoryID')->insert(['subcategoryName' => $request['subCategory'], 'categoryID' => $request['category_id']]);
                //$saved = SubCategory::create(['subcategoryName' => $request['subCategory'], 'categoryID' => $request['category_id']]);
                //dd($saved);
                if($saved)
                {
                    return redirect()->route('subCategory')->with('success', 'New Category was created.');
                }
            }catch(\Throwable $getError){
                $this->storeTryCatchError($getError, 'categoryController@SaveSubCategory', 'Error occurred when adding new record.' );
            }
            return redirect()->route('getCategoryInd')->with('error_message', 'Sorry, we cannot create you record now. Please try again.');
    }
        //end fun
      
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
         //dd('here');
         //DB transactions
         try{
             $getRecord      = SubCategory::find($recordID);
             //dd($getRecord);
             $checkInUse     = SubCategory::where('id', 'recordID')->first();
             if($getRecord && !$checkInUse)
             {
                $getRecord->delete();
                return redirect()->route('getCategory')->with('success', 'Your record was deleted.');
             }
         }catch(\Throwable $getError){
             $this->storeTryCatchError($getError, 'categoryController@deleteSubCategory', 'Error occurred when deleting record.' );
         }
         if($checkInUse)
         {
             return redirect()->route('getCategoryInd')->with('error_message', 'Sorry, This record is in use. We cannot delete this record now.');
         }
         return redirect()->route('getCategoryInd')->with('error_message', 'Sorry, we cannot delete this record now. Please try again.');
     }//end func

     public function saveAndUpdate (Request $request) 
     {
         //dd('here');
        $this->validate($request,
        [
           'category_id'   => ['required', 'integer'],
           "subCategory"    => ['required', 'string', 'max:190', 'unique:sub_categories,subcategoryName']
        ]);

        $recordID = $request['recordID'];
        dd($recordID);

        if ($recordID != null){
            $insert = DB::table('sub_categories')->insert([ 'categoryTitle' => $request->input('subCategory'), 'categoryID' => $request->input('category_id')
            ]);
            return redirect()->route('getCategory')->with('success', 'Your record was created successfully.');
        }else{
            $saved = DB::table('sub_categories')->where('categoryID', $recordID)->update([ 'categoryTitle' => $request->input('subCategory'), 'categoryID' => $request->input('category_id')
            ]);
            return redirect()->route('getCategory')->with('success', 'Your record was updated successfully.');
        }
     }
}
