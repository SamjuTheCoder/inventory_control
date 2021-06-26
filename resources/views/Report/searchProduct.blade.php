@extends('layouts.app')
@section('pageHeaderTitle', 'Search Product')

@section('content')
<div class="row justify-content-center bg-light pt-3">

    <div class="col-md-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Products Report</h3><span style="font-size:12px;">Fields marked<i style="color:red">*</i> are important</span>
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
                    <form action="{{ Route::has('productSearch') ? Route('productSearch') : '#' }}" method="post" >
                    @csrf
                        <div class="form-row">                                               
                                                        
                            {{-- <div class="form-group col-md-3">
                                <label for="inputPassword4" style="color: black;">Warehouse:<i style="color:red" >*</i></label>
                                <select id="select-state" class="form-control" name="store" required>
                                    <option value="">Choose...</option>

                                    @if(isset($stores) && $stores)
                                        @foreach($stores as $item)
                                        <option value="{{ $item->id }}" {{ isset($store) && $store == $item->id ? 'selected' : '' }}>{{$item->store_name}} </option>
                                        @endforeach
                                    @endif 
                                </select>
                            </div>  --}}


                            <div class="form-group col-md-4">
                                <label for="inputPassword4" style="color: black;">Category:<i style="color:red" >*</i></label>
                                <select id="select-category" class="form-control" name="Category">
                                    <option value="">Choose...</option>
                                    
                                    @if(isset($categories) && $categories)
                                        @foreach($categories as $item)
                                            <option value="{{ $item->id }}" {{ (isset($Category) && $Category == $item->id ? 'selected' : '') }}>{{ $item->categoryTitle }} </option>
                                       @endforeach 
                                    @endif
                                </select>
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label for="inputPassword4" style="color: black;">Subcategory:<i style="color:red">*</i></label>
                                <select id="subCategory" class="form-control" name="subCategory" >
                                    <option value="">Choose...</option>
                                </select>
                            </div>
                        </div>
                        <br />
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                    <!-- FIRST TABLE -->
                    <div class="col-md-12">
                        <div class="white_card card_height_100 mb_30">
                            <div class="white_card_header">
                                <div class="box_header m-0">
                                    <div class="main-title">
                                        <h4 class="m-0">Product List</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="white_card_body">
                                <table class="table table-light table-bodered table-hover">
                                    <thead class="text-white" style="background-color:#373063">                    
                                        <tr>
                                            <th> SN </th>
                                            {{-- <th> Product Name </th> --}}
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Subcategory</th>
                                            {{-- <th></th> --}}
                                            <th>Measurement Unit</th>
                                            
                                            {{-- <th> Subcategory</th> --}}
                                        </tr>
                                    </thead>
                                    @if(isset($getReport) && $getReport)
                                        @foreach($getReport as $key => $value)
                                            <tr>
                                                <td> {{ ($getReport->firstItem() + $key ) }} </td>
                                                <td> {{ $value->productName }} </td>
                                                <td> {{ $value->categoryTitle }} </td>
                                                <td> {{ $value->subcategoryName }}</td>  
                                                <td>@foreach($value->mformat as $val2){{$val2->description  }}[ <span style="color:green">{{ $val2->quantity }} </span>] @endforeach</td>    
                                                {{-- <td> @if ($value->shelvesID == null) UnShelved @endif  {{ $value->shelve_name }} </td> --}}
                                                {{--<!-- <td> {{ $value->quantity }} </td> -->
                                                 <td> <a href="{{ Route::has('editCategory') ? Route('editCategory', ['rid'=>$value->id]) : 'javascript:;'  }}" class="btn btn-outline-success btn-sm"><i class="fa fa-edit"></i></a>
                                                <button type="button" name="submit" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-backdrop="false" data-target="#confirmToDelete{{$key}}"><i class="fa fa-trash"></i></button></td>                                         --}}
                                            </tr>                                           
                                        @endforeach                                        
                                    @endif
                                </table>                       
                            </div>{{ $getReport->render("pagination::bootstrap-4") }}
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
{{-- <script>
  function deleteProject(id) {
      var x = confirm('Do you want to delete?');
      if(x==true){
          document.location="delete-project/"+id;
      }
  }
</script> --}}

<script>
    var subCategoryID = "{{ (isset($subCategory) ? $subCategory : '') }}";
    var categoryID = $('#select-category').val();
    if(categoryID != null)
    {
            getCategory(categoryID, subCategoryID);
    }
    //get subcategory
    $('#select-category').on('change', function() {
            var catID = $(this).val();
            getCategory(catID, null);
    });
    //
    function getCategory(categoryID, subCategoryID = null)
    {
        //$('#select-category').on('change', function() {
            //var catID = $(this).val();
            $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
              $.ajax({
                  url: '{{route("AjaxproductSearchForSubcategory")}}',
                  method: 'post',
                  data: {
                    Category : categoryID
                  },
                  success: function(data) {
                      console.log(data);
                      $('#subCategory').removeAttr('disabled');
                      $('#subCategory').empty();
                      $('#subCategory').append("<option value = '' selected> Choose... </option>");
                      $.each(data, function(model, list) {
                        if(list.id == subCategoryID)
                        {
                            $('#subCategory').append($('<option>').text(list.subcategoryName).attr('value', list.id).attr('selected', true));
                        }else{
                            $('#subCategory').append($('<option>').text(list.subcategoryName).attr('value', list.id));
                        }
                    });
                      /* $.each(data, function(index, value) {
                          console.log(index);
                           $('#subCategory').append(
                                new Option(value.subcategoryName, value.id)
                            );
                      }) */
                  }
              });
        //});
    }
    
    
</script>

{{-- <script>

    $(document).ready(function () {
          $('select').selectize({
              sortField: 'text'
          });
      });
</script> --}}
@endsection