@extends('layouts.app')
@section('pageHeaderTitle', 'Shelve Report')

@section('content')
<div class="row justify-content-center bg-light pt-3">

    <div class="col-md-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Report</h3><span style="font-size:12px;">Fields marked<i style="color:red">*</i> are important</span>
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

            {{-- form beginning --}}
            <div class="white_card_body">
                <div class="card-body">
                    <form method="post" action="{{ route('productInShelve')}}">
                    @csrf
                        <div class="form-row">                                               
                            <div class="form-group col-md-6">
                                <label for="inputPassword4" style="color: black;">Product:<i style="color:red">*</i></label>
                                <select id="select-state" class="form-control" name="product" >
                                    <option value="">Choose...</option>
                                    @if(isset($product) && $product)
                                        @foreach($product as $pd)
                                            <option value="{{ $pd->id }}" {{ ($productx == $pd->id || old("product") == $pd->id )? "selected" :"" }}>{{$pd->productName}} </option>
                                        @endforeach
                                    @endif
                                </select>

                                {{-- <label for="inputPassword4" style="color: black;">Warehouse:<i style="color:red">*</i></label>
                                <select id="select-state" class="form-control" name="store" >
                                    <option value="">Choose...</option>
                                    @if(isset($product) && $product)
                                        @foreach($product as $item)
                                            <option value="{{ $item->id }}" {{ ((isset($editSubCategory) && $editSubCategory) && ($editSubCategory->categoryID == $item->id)) ? 'selected' : (old('category_id') == $item->id ? 'selected' : '') }}>{{$item->store_name}} </option>
                                        @endforeach
                                    @endif 
                                </select>     --}}
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label for="inputPassword4" style="color: black;">Warehouse:<i style="color:red">*</i></label>
                                <select id="select-state" class="form-control" name="store" >
                                    <option value="">Choose...</option>
                                    @if(isset($stores) && $stores)
                                        @foreach($stores as $item)
                                            <option value="{{ $item->id }}" {{ ((isset($editSubCategory) && $editSubCategory) && ($editSubCategory->categoryID == $item->id)) ? 'selected' : (old('category_id') == $item->id ? 'selected' : '') }}>{{$item->store_name}} </option>
                                        @endforeach
                                    @endif 
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </form>

                    <!-- FIRST TABLE -->
                    <div class="col-md-12">
                        <div class="white_card card_height_100 mb_30">
                            <div class="white_card_header">
                                <div class="box_header m-0">
                                    <div class="main-title">
                                        <h4 class="m-0">Products in Shelve</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="white_card_body">
                                <table class="table table-light table-bodered table-hover">
                                    <thead class="text-white" style="background-color:#373063">                    
                                        <tr>
                                            <th> SN </th>
                                            <th> Product Name </th>
                                            <th>Warehouse</th>
                                            <th> Shelf</th>
                                        </tr>
                                    </thead>
                                    @if(isset($getCategory) && $getCategory)
                                        @foreach($getCategory as $key => $value)
                                            <tr>
                                                <td> {{ ($key + 1) }} </td>
                                                <td> {{ $value->categoryTitle }} </td>                               
                                                <td> <a href="{{ Route::has('editCategory') ? Route('editCategory', ['rid'=>$value->id]) : 'javascript:;'  }}" class="btn btn-outline-success btn-sm"><i class="fa fa-edit"></i></a>
                                                <button type="button" name="submit" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-backdrop="false" data-target="#confirmToDelete{{$key}}"><i class="fa fa-trash"></i></button></td>                                        
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function(){
    $([data-toggle="tooltip"]).tooltip();
});
</script>
<script>
  function deleteProject(id) {
      var x = confirm('Do you want to delete?');
      if(x==true){
          document.location="delete-project/"+id;
      }
  }
</script>

<script>

    $(document).ready(function () {
          $('select').selectize({
              sortField: 'text'
          });
      });

</script>
@endsection