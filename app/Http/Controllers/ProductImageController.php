<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductImage;


class ProductImageController extends Controller
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
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $request->validate([
            'product_id'  =>  ['required', 'numeric'],
            //'category'  =>  ['required', 'numeric'],
            'product_image'  =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);

        $prID   =   $request->get('product_id');
        $prImage=   $request->file('product_image');

        $directory = 'assets/img/inventory/product/';

        $modelPrImg =   new ProductImage;

        function fileUploadFunction($imagepath, $title, $folder)
        {
            # code...
            $fileExtension  =   $imagepath->getClientOriginalExtension();
            $imgName        =   $title.'_'.time().'.'.$fileExtension;
            $imagepath->move($folder, $imgName);
            return $imgName;
            //print $imgName;
        }

        if ($request->hasFile('product_image')) {
            # code...
            $fileName   =   fileUploadFunction($prImage, $prID, $directory);
            try {
                //code...
                $modelPrImg::create([
                    'productID' => $prID,
                    'pr_filename'   =>  $fileName,
                    ]);
            } catch (\Throwable $th) {
                //throw $th;
                return back()->with('error', 'Oops! Something went wrong. Image could not save.');
            }
        } else {
            # code...
            return back()->with('error', 'Oops! This is not a file.');
        }
        return back()->with('success', 'Great! Image saved successfully.');
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
    public function update(Request $request)
    {
        //
        $request->validate([
            'record_id'  =>  ['required', 'numeric'],
            //'category'  =>  ['required', 'numeric'],
            'product_image'  =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);

        $ID         =   $request->get('record_id');
        $prImage    =   $request->file('product_image');
        $directory  = 'assets/img/inventory/product/';
        $modelPrImg =   new ProductImage;

        function fileUploadFunction($imagepath, $title, $folder)
        {
            # code...
            $fileExtension  =   $imagepath->getClientOriginalExtension();
            $imgName        =   $title.'_'.time().'.'.$fileExtension;
            $imagepath->move($folder, $imgName);
            return $imgName;
            //print $imgName;
        }

        if ($request->hasFile('product_image')) {
            # code...
            $fileName   =   fileUploadFunction($prImage, $ID, $directory);
            try {
                //code...
                $modelPrImg::where('id', '=', $ID)->update(['pr_filename'   =>  $fileName]);
            } catch (\Throwable $th) {
                //throw $th;
                return back()->with('error', 'Oops! Something went wrong. Image could not save.');
            }
        } else {
            # code...
            return back()->with('error', 'Oops! This is not a file.');
        }
        return back()->with('success', 'Great! Image updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        //
        $request->validate([
            'record_id'  =>  ['required', 'numeric']
        ]);

        $ID         =   $request->get('record_id');
        $modelPrImg =   new ProductImage;
        try {
            //code...
            $modelPrImg::where('id', '=', $ID)->delete();
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', 'Oops! Something went wrong. Image could not delete.');
        }
        return back()->with('success', 'Great! Image deleted successfully.');
    }
    public function destroy($id)
    {
        //
    }
}
