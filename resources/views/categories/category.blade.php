@extends('layouts.app')
@section('pageHeaderTitle', 'Category')

@section('content')
<div class="row justify-content-center bg-light pt-3">

    <div class="col-md-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Category</h3><span style="font-size:12px;">Fields marked<i style="color:red">*</i> are important</span>
                    </div>                   
                </div>
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                    <strong></strong> {{ session('success') }}</div>
                    @endif
                    @if(session('error_message'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                    <strong>Error!</strong> {{ session('error_message') }}</div>
                @endif
                
                @if (count($errors) > 0)
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Error!</strong> 
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- first card for the form -->
    <div class="col-md-6">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="">              

                    <form action="{{ Route::has('saveCategory') ? Route('saveCategory') : '#' }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3 col-md-8 offset-md-2">
                            <label for="category" class="form-label text-dark">Create new category<span class="text-danger" title="This must be filled."><b>*</b></span> </label>
                            <input type="text" required maxlength="190" autofocus class="form-control" name="category" value="{{ (isset($getEditRecord) && $getEditRecord) ? $getEditRecord->categoryTitle : old('category') }}"> <!-- aria-describedby="description" -->
                            @error('category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        

                        <div align="center" class="mb-3 col-md-12">
                            @if(isset($getEditRecord) && $getEditRecord)
                            <input type="hidden" name="recordID" value="{{ (isset($getEditRecord) && $getEditRecord) ? $getEditRecord->id : old('recordID') }}">
                                <button type="submit" name="submit" class="btn btn-secondary">Update</button>
                                <button type="button" onclick="clearSession()" id="cancel" class="btn btn-warning" data-dismiss="modal"> Cancel </button>
                            @else
                                <button type="submit" name="submit" class="btn btn-primary">Save</button>
                            @endif
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- second card for the form || SUB CATEGORY FORM -->
    <div class="col-md-6">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                  

                    <form action="{{ Route::has('SaveSubCategory') ? Route('SaveSubCategory') : '#' }}" method="post" enctype="multipart/form-data">
                     @csrf
                        <div class="row">
                            
                           
                                <div class="form-group mx-sm-6 mb-3 mr-4">
                                    
                                    <label for="category_id" class="form-label text-dark">Category<span class="text-danger" title="This must be filled."><b>*</b></span> </label>                                    
                                    <select id="category" name="category_id" class="form-control">
                                        <option selected="">Select a category</option>
                                        @if(isset($getCategory) && $getCategory)
                                            @foreach ($getCategory as $item)
                                                <option value="{{ $item->id }}" {{ ((isset($editSubCategory) && $editSubCategory) && ($editSubCategory->categoryID == $item->id)) ? 'selected' : (old('category_id') == $item->id ? 'selected' : '') }}> {{$item->categoryTitle}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('category_id')
                                    <div class="invalid-feedback" role='alert' style="display: block;"><strong>{{$message}}</strong></div>
                                    @enderror
                                </div>
                            
                            <div class="form-group mx-sm-6 mb-3">
                                <label for="subCategory" class="form-label text-dark"> Subcategory<span class="text-danger" title="This must be filled."><b>*</b></span> </label>
                                <input type="text"   class="form-control" name="subCategory" value="{{ (isset($editSubCategory) && $editSubCategory) ? $editSubCategory->subcategoryName : old('subCategory') }}"> 
                                @error('subCategory')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
                            </div>
                                                      
                        </div>
                        
                        <div align="center" class="mb-3 col-md-12">
                            
                            @if(isset($editSubCategory) && $editSubCategory)
                            <input type="hidden" name="recordID" value="{{ (isset($editSubCategory) && $editSubCategory) ? $editSubCategory->subCategoryID : old('recordID') }}"> 
                                <button type="submit" name="submit" class="btn btn-secondary">Update</button>
                                <button type="button" onclick="clearSession2()" id="cancel" class="btn btn-warning" data-dismiss="modal"> Cancel </button>
                            @else
                                <button type="submit" name="submit" class="btn btn-primary">Save</button>
                            @endif
                        </div>
                    </form>
                    
                <!-- </div>- -->
            </div>
        </div>
    </div>
    <!-- end of 2nd form -->

    <!-- FIRST TABLE -->
    <div class="col-md-6">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Category</h3>
                    </div>
                </div>
            </div>

            <div class="white_card_body">
                <table class="table table-light table-bodered table-hover">
                    <thead class="text-white" style="background-color:#373063">                    
                        <tr>
                            <th> SN </th>
                            <th> Category </th>
                            <th> Action</th>
                        </tr>
                    </thead>
                    @if(isset($getCategory) && $getCategory)
                        @foreach($getCategory as $key => $value)
                            <tr>
                                <td> {{ ($value->firstItem() + $key ) }}</td>
                                <td> {{ $value->categoryTitle }} </td>                               
                                <td> <a href="{{ Route::has('editCategory') ? Route('editCategory', ['rid'=>$value->id]) : 'javascript:;'  }}" class="btn btn-outline-success btn-sm"><i class="fa fa-edit"></i></a>
                                 <button type="button" name="submit" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-backdrop="false" data-target="#confirmToDelete{{$key}}"><i class="fa fa-trash"></i></button>
                                </td>                                        
                            </tr>
                        
                        <!-- Modal - confirm to delete -->
                        <div style="z-index: 9999999999;" class="modal fade text-left d-print-none" id="confirmToDelete{{$key}}" tabindex="-1" role="dialog" aria-labelledby="confirmToDelete{{$key}}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h6 class="modal-title text-dark">Confirm Deletion!</h6>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-success text-center"> <h6>Are you sure you want to remove this record? </h6></div>
                                        <div class="text-dark text-center">  {{ $value->categoryTitle }} </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-default" data-dismiss="modal"> Cancel </button>
                                        <a href="{{ Route::has('deleteCategory') ? Route('deleteCategory', ['rid'=>$value->id]) : 'javascript:;'  }}" type="submit" class="btn btn-outline-danger"> Delete </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end Modal-->
                        @endforeach
                    @endif
                </table>
             </div>
        </div>
    </div>
    <!--Second table -->
    <div class="col-lg-6">
      <div class="white_card card_height_100 mb_30">
          <div class="white_card_header">
              <div class="box_header m-0">
                  <div class="main-title">
                      <h3 class="m-0">Subcategory</h3>
                  </div>
              </div>
          </div>
          <div class="white_card_body">

            <!-- <h6 class="card-subtitle mb_20">You can also invert the colors—with light text on dark backgrounds—with <code class="highlighter-rouge">.table-dark</code>.</h6> -->
            <div class="white_card_body">
                <table class="table table-light table-bodered table-hover">
                    <thead class='text-white' style="background-color:#373063;">     
                        <tr>
                            <th> SN </th>
                            <th> Category </th>
                            <th> Subcategory </th>
                            {{-- <th>Created</th> --}}
                            <th> Action</th>
                        </tr>
                    </thead>
                    @if(isset($getSubCategory) && $getSubCategory)
                        @foreach($getSubCategory as $key => $value)
                            <tr>
                                <td> {{ ($getSubCategory->firstItem() + $key ) }} </td>
                                <td> {{ $value->categoryTitle }} </td>
                                <td>{{ $value->subcategoryName }}</td>
                                {{-- <td> {{ date('d-m-Y', strtotime($value->created_at)) }} </td> --}}
                                <td> 
                                    <div class="btn-group" role="group">
                                        <a href="{{ url('category/edit/sub/'.$value->subcatID) }}" class="btn btn-outline-success btn-sm mr-1"> <i class="fa fa-edit"></i></a>
                                        <button type="button ml 2" name="submit" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-backdrop="false" data-target="#confirmToDelete1{{$key}}"><i class="fa fa-trash"></i></button>
                                    </div>
                                </td>                                        
                                    
                            </tr>
                        
                            <!-- Modal - confirm to delete -->
                            <div style="z-index: 9999999999;" class="modal fade text-left d-print-none" id="confirmToDelete1{{$key}}" tabindex="-1" role="dialog" aria-labelledby="confirmToDelete1{{$key}}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h6 class="modal-title text-dark">Confirm Deletion!</h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-success text-center"> <h6>Are you sure you want to remove this record? </h6></div>
                                            <div class="text-dark text-center">  {{ $value->subcategoryName }} </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-default" data-dismiss="modal"> Cancel </button>
                                            <a href="{{ Route::has('deleteSubCategory') ? Route('deleteSubCategory', ['id'=>$value->subcatID]) : 'javascript:;'  }}" type="submit" class="btn btn-outline-danger"> Delete </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end Modal-->
                        @endforeach
                    @endif
                </table>
            </div>
            {{-- {!! $getSubCategory->links() !!}  --}}
            {!! $getSubCategory->render("pagination::bootstrap-4") !!}        
          </div>
      </div>
    </div>

</div>
@endsection

@section('styles')
<style>
.th{
    color:white !important;
}
</style>
@endsection

@section('script')
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>

<script>
    function clearSession() {
    location.href = "{{ route('forgetCategory')}}";
    }
</script>

<script>
    function clearSession2() {
    location.href = "{{ route('forgetSubCategory')}}";
    }
</script>

@endsection
