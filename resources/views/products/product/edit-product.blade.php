@extends('layouts.app')

@section('style')
<link rel="stylesheet" href="{{asset('assets/css/src.c961d18f.css')}}">
<style>
    .scrollbar-thin {
        scrollbar-width: thin;
        height: 500px;
        overflow-y: scroll;
    }
    .load-prev{margin-bottom:8px}
        .load-next,.load-prev{display:block;padding:5px 12px;background:hsla(0,0%,96.1%,.96);font-size:12px;text-align:center}
        .load-next{margin-top:8px}
        
    #wrapper
    {
     text-align:center;
     margin:0 auto;
     padding:0px;
     /*width:995px;*/
    }
    #output_image
    {
     max-width:12rem;
    }
    @media only screen and (max-width: 950px) {
    .my-img
    {
        background-color: ;
        max-width:12rem;
        object-fit: contain;
    }
    }
    @media only screen and (min-width: 600px) {
    .my-img
    {
        background-color:;
        max-width:8rem;
        object-fit: contain;
    }
    }
</style>
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">@includeIf('share.operationCallBackAlert', ['showAlert' => 1])</div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="col-lg-12">
            @foreach ($dataProduct as $item)
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_header">
                    <div class="box_header m-0">
                        <div class="main-title">
                            <h3 class="m-0">Edit Product</h3>
                        </div>
                    </div>
                </div>

                <div class="white_card_body">
                    <form action="{{route('update-product')}}" method="post">
                        @csrf
                        <input type="hidden" value="{{$item->id}}" name="product_id">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">Product Title:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="product_name" id="inputText" placeholder="Product name" value="{{$item->productName}}">
                                @error('product_name')
                                    <div class="invalid-feedback" role='alert' style="display: block;"><strong>{{$message}}</strong></div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-4 col-form-label">Category:</label>
                            <div class="col-sm-8">
                                <select id="category" name="category" class="form-control">
                                    <option value="" selected>Choose...</option>
                                    @foreach ($dataCategory as $category)
                                        <option value="{{$category->id}}" {{$category->id == $item->categoryID?'selected':''}}>{{$category->categoryTitle}}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                <div class="invalid-feedback" role='alert' style="display: block;"><strong>{{$message}}</strong></div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-4 col-form-label">Sub category:</label>
                            <div class="col-sm-8">
                                <select id="subcategory" name="subcategory" class="form-control">
                                    <option value="" selected>Choose...</option>
                                </select>
                                @error('subcategory')
                                <div class="invalid-feedback" role='alert' style="display: block;"><strong>{{$message}}</strong></div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-4 col-form-label">Barcode:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="barcode_number" value="{{$item->barcode}}"  placeholder="Bar code number">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary ">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        
        </div>
        <div class="col-md-12">
                <div class="white_card card_height_100 mb_30">
                    <div class="white_card_header">
                        <div class="box_header m-0">
                            <div class="main-title">
                                <h3 class="m-0">Edit Minimum Measurement</h3>
                            </div>
                        </div>
                    </div>
        
                    <div class="white_card_body">
                        <form action="{{route('edit-pr-measurement')}}" method="post">
                            @csrf
                            <input type="hidden" value="{{$item->id}}" name="product_id">
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-4 col-form-label">Measurement:</label>
                                <div class="col-sm-8">
                                    <select id="measurement" name="measurement" class="form-control">
                                        <option value="" selected>Choose...</option>
                                        @foreach ($dataMeasurement as $measurement)
                                            <option value="{{$measurement->id}}" {{$measurement->id == $item->min_measurementID?'selected':''}}>{{$measurement->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary ">Save Changes</button>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    
    <div class="col-md-6 position-relative mb_20">
            <div class="white_card mb_20">
                <div class="white_card_header">
                    <div class="box_header m-0">
                        <div class="main-title">
                            <h3 class="m-0">Product Images</h3>
                        </div>
                    </div>
                </div>
            </div>
                <!---->
            <div class="row scrollbar-thin pt-md-3">
                @foreach ($dataImg as $key => $prImg)
                <div class="col-md-6">
                    <div class="white_card position-relative mb_20 ">
                        <div class="card-body">
                            <div class="ribbon1 rib1-primary"><span class="text-white text-center rib1-primary">Image {{$key + 1}}</span></div>
                            <!--end ribbon--><img src="{{asset('assets/img/inventory/product/'.$prImg->pr_filename)}}" alt="" class="d-block mx-auto my-4 my-img" height="150">
                            <div class="btn-group btn-block" role="group" aria-label="Basic example">
                                <a class="btn btn-outline-primary text-center mr-sm-1" data-toggle="modal" data-target="#prUploadImg" onclick="getImgDetail('{{$prImg->id}}', '{{$prImg->pr_filename}}')">Edit</a>
                                <a href="#" class="btn btn-outline-danger" data-toggle="modal" data-target="#prDeleteImg" onclick="deleteImgDetail('{{$prImg->id}}')">Delete</a>
                            </div>
                        </div>
                        <!--end card-body-->
                    </div>
                </div>
                @endforeach
            </div>
        </div>
</div>


<div class="col-md-12">

    <div class="text-right text-dark"><small>Field with asterisk (<b class="text-danger">*</b>) is important.</small></div>

        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">

                <form action="{{ Route::has('saveMeasurementUnit') ? Route('saveMeasurementUnit') : '#' }}" method="post" enctype="multipart/form-data">
                @csrf
                        <input type="hidden" name="getRecord" value="{{ (isset($editRecord) && $editRecord) ? $editRecord->recordID : old('getRecord') }}"> 
                        <div class="row">
                            <input type="hidden" name="product" value="{{$item->id}}">
                            @error('product')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="mb-3 col-md-6">  
                                <label for="measurementName" class="form-label text-dark">Measure <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                <select required class="form-control" name="measurementName">
                                    <option value="">Select Measurement</option>
                                    @if(isset($dataMeasurement) && $dataMeasurement)
                                        @foreach($dataMeasurement as $key => $value)
                                            <option value="{{$value->id}}" {{ (isset($editRecord) && $editRecord) && ($editRecord->measurementID == $value->id) ? 'selected' : (old('measurementName') == $value->id ? 'selected' : '') }}>{{ $value->description }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('measurementName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6"> 
                                <label for="quantity" class="form-label text-dark">Quantity <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                <input type="number" required maxlength="10" class="form-control" name="quantity" value="{{ (isset($editRecord) && $editRecord) ? $editRecord->quantity : old('quantity') }}">
                                @error('quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div align="center" class="mb-3 col-md-12">
                                @if((isset($editRecord) && $editRecord))
                                    <button type="submit" name="submit" class="btn btn-outline-success">Update</button>
                                @else
                                    <button type="submit" name="submit" class="btn btn-outline-success">Save</button>
                                @endif
                            </div>
                        </div>
                    </form>
                    @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Product table</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="QA_section">
                    

                    <div class="table-responsive m-b-30">
                        <!-- table-responsive -->
                        <table class="table lms_table_active table-bordered">
                            <thead class="bg-dark">
                                <tr>
                                    <th class="text-white" scope="col">S/N</th>
                                    <th class="text-white" scope="col">Product</th>
                                    <th class="text-white" scope="col">Quantity</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataMeasurementUnit as $key => $MeasurementUnit)
                                    <tr>
                                        <th> {{$key + 1}} </th>
                                        <td>{{$MeasurementUnit->desc}}</td>
                                        <td>{{$MeasurementUnit->quantity}}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a onclick="productDetail('{{$item->id}}', '{{$item->productName}}')" class="btn btn-outline-success mr-sm-1" data-toggle="modal" data-target="#prUploadImg"><i class="ti-image"></i></a>
                                                <a href="{{route('edit-product', ['id'=> encrypt($item->id)])}}" class="btn btn btn-outline-warning  mr-sm-1"><i class="ti-pencil"></i></a>
                                                <a href="#" class="btn btn btn-outline-danger"><i class="ti-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"> List empty</td>
                                    </tr>
                                @endforelse
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
        <!-- Add image-->
  <div class="modal fade" id="prUploadImg" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="{{route('edit-image-product')}}" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Upload Image</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
              @csrf
              <input type="hidden" name="record_id" id="pr_id">
            <div class="form-group mx-sm-3 mb-2">
                <div class="form-group mb-3">
                    <input type="file" class="form-control-file" name="product_image" id="exampleFormControlFile1" onchange="preview_image(event)">
                    @error('product_image')
                    <div class="invalid-feedback" role='alert' style="display: block;"><strong>{{$message}}</strong></div>
                    @enderror
                </div>
                <img class="js-tilt" data-tilt-speed="1000" data-tilt-max="20" data-tilt-scale="1.01" data-tilt-perspective="250" id="output_image"/>
            </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
    </form>
      </div>
    </div>
  </div>

          <!-- Add imag-->
          <div class="modal fade" id="prDeleteImg" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <form action="{{route('delete-image-product')}}" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Alert</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                      @csrf
                      <input type="hidden" name="record_id" id="delete_pr_id">
                      <i class="ti-na"></i> Are you sure you want to remove this record?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
              </div>
            </div>
          </div>
@endsection

@section('script')
<script src="{{asset('assets/js/src.bfb40cc8.js')}}"></script>
<script>
    $(function(){$(".select").jselect_search({fillable:!0,searchable:!0}),$("#state").attr("data-pagination",1),$("#state").jselect_search({on_clear_reflect:["#el1","#el2"],fillable:!0,searchable:function(t){console.log(t)},on_change:function(){alert()},on_top_edge:function(t){parseInt($("#state").attr("data-pagination"))>1&&($("#state").attr("data-pagination",parseInt($("#state").attr("data-pagination"))-1),t.find(".load-next").hide(),t.find(".load-prev").show(),alert($("#state").attr("data-pagination")))},on_bottom_edge:function(t){parseInt($("#state").attr("data-pagination"))>=1&&(t.find(".load-prev").hide(),t.find(".load-next").show(),$("#state").attr("data-pagination",parseInt($("#state").attr("data-pagination"))+1))},on_created:function(t){t.find(".load-prev").text("Load previous...").hide(),t.find(".load-next").text("Load more...").hide()}})});
</script> 
<script>
    function getImgDetail(data1, data2) {
            $('#pr_id').val(data1);
            $('#output_image').attr('src', '{{asset("assets/img/inventory/product")}}'+"/"+data2);
        }
    function deleteImgDetail(data1) {
        //alert(data1);
            $('#delete_pr_id').val(data1);
        }
    function preview_image(event) 
        {
         var reader = new FileReader();
         reader.onload = function()
         {
            var output = document.getElementById('output_image');
            output.src = reader.result;
        
         }
         reader.readAsDataURL(event.target.files[0]);
        }

        $(document).ready(function() {
            $('.js-tilt').tilt({   glare: true,maxGlare: .5 })
        })
</script>
    <script>
        
        var categoryid = $('#category').val();
        //alert({{$item->subcategoryID}});
        $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
              $.ajax({
                  url: '{{route("pr-subcategory")}}',
                  method: 'post',
                  data: {
                      id : categoryid
                  },
                  success: function(data) {
                      console.log(data);
                      $('#subcategory').empty();
                      $('#subcategory').append("<option value = '' disabled selected> Choose... </option>");
                      $.each(data, function(index, value) {
                          console.log(index);
                          

                          
                          $('#subcategory').append(
                              '<option value="'+ value.id +'" id="'+value.id+'">' + value.subcategoryName+'</option>'
                              );
                         //$('#test'+value.id).attr('selected');

                         if (value.id == {{$item->subcategoryID}}) {
                             $('#'+value.id).attr('selected', true);
                         }
                              
                      })
                  }
              });

              /*

              @foreach ($dataSubCategory as $subCategory)
                                        <option value="{{$subCategory->id}}" {{$subCategory->id == $item->subcategoryID?'selected':''}}>{{$subCategory->subcategoryName}}</option>
                                    @endforeach
              {{$subCategory->id == $item->subcategoryID?'selected':''}}*/
              //alert(categoryid);
        $('#category').on('change', function() {
            var catID = $(this).val();
            
            //alert(catID);
            $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
              $.ajax({
                  url: '{{route("pr-subcategory")}}',
                  method: 'post',
                  data: {
                      id : catID
                  },
                  success: function(data) {
                      console.log(data);
                      $('#subcategory').removeAttr('disabled');
                      $('#subcategory').empty();
                      $('#subcategory').append("<option value = '' disabled selected> Choose... </option>");
                      $.each(data, function(index, value) {
                          console.log(index);
                          $('#subcategory').append(
                                new Option(value.subcategoryName, value.id)
                              );
                      })
                  }
              });
        });
    </script>
@endsection