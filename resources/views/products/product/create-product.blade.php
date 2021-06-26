@extends('layouts.app')

@section('style')
    <style>
    #wrapper
    {
     text-align:center;
     margin:0 auto;
     padding:0px;
     width:995px;
    }
    #output_image
    {
     max-width:17rem;
    }
    </style>
@endsection
@section('content')
<div class="row justify-content-center">
       <div class="col-md-12">
        @includeIf('share.operationCallBackAlert', ['showAlert' => 1])
       </div>
<div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Create Product</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <form action="{{route('store-product')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group mb-2 col-md-6">
                            <label for="staticEmail2" class="sr-onl">Product name<span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                            <input type="text" class="form-control" name="product_name" id="inputText" placeholder="Product name" value="{{old('product_name')}}">
                            @error('product_name')
                                <div class="invalid-feedback" role='alert' style="display: block;"><strong>{{$message}}</strong></div>
                            @enderror
                        </div>
                        <div class="form-group mb-2 col-md-3">
                            <label for="staticEmail2" class="sr-onl mr-2">Bar code number</label>
                            <input type="text" class="form-control" name="barcode_number" id="inputText" placeholder="Bar code number" value="{{old('barcode_number')}}">
                        </div>
                        
                        <div class="form-group mb-2 col-md-3">
                            <label for="staticEmail2" class="sr-onl">Minimum measurement<span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                            <select id="min-measurement" name="minimum_measurement" class="form-control" >
                                <option selected="">Choose...</option>
                                @foreach ($dataMeasurement as $item)
                                    @if (old('minimum_measurement') == $item->id)
                                      <option value="{{$item->id}}" selected>{{$item->description}}</option>
                                    @else
                                      <option value="{{$item->id}}">{{$item->description}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    
                </div>
                    <div class="row">
                        <div class="form-group col-md-3 mb-2">
                            <label for="inputPassword2" class="sr-onl mr-2">Category<span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                            <select id="category" name="category" class="form-control">
                                <option selected="">Choose...</option>
                                @foreach ($dataCategory as $item)
                                    @if (old('category') == $item->id)
                                      <option value="{{$item->id}}" selected>{{$item->categoryTitle}}</option>
                                    @else
                                      <option value="{{$item->id}}">{{$item->categoryTitle}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('category')
                            <div class="invalid-feedback" role='alert' style="display: block;"><strong>{{$message}}</strong></div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label for="inputPassword2" class="sr-onl mr-2">Subcategory<span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                            
                            <select id="subcategory" name="subcategory" class="form-control">
                                <option selected="">Choose...</option>
                            </select>
                            @error('subcategory')
                            <div class="invalid-feedback" role='alert' style="display: block;"><strong>{{$message}}</strong></div>
                            @enderror
                        </div>
                    </div>
                    <div class="row justify-content-center"><button type="submit" class="btn btn-primary mb-2">Save</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Product List</h3>
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
                                    <th class="text-white" scope="col">Category</th>
                                    <th class="text-white" scope="col">Product</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataProducts as $key => $item)
                                    <tr>
                                        <th> {{$key + 1}} </th>
                                        <td>{{$item->categoryTitle}}</td>
                                        <td>{{$item->productName}}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <!--<a onclick="productDetail('{{$item->id}}', '{{$item->productName}}')" class="btn btn-outline-success mr-sm-1" data-toggle="modal" data-target="#prUploadImg"><i class="ti-image"></i></a>-->
                                                <a href="{{route('edit-product', ['id'=> encrypt($item->id)])}}" class="btn btn btn-outline-warning  mr-sm-1"><i class="ti-pencil"></i></a>
                                                <a href="#" class="btn btn btn-outline-danger"  data-toggle="modal" data-target="#productDelete" onclick="getProductDetailDelete('{{$item->id}}')"><i class="ti-trash"></i></a>
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
    </div>

@endsection

@section('modal')
      <!-- Add image-->
  <div class="modal fade" id="prUploadImg" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="{{route('image-product')}}" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Upload Image</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
              @csrf
            <div class="form-group mx-sm-3 mb-2">
                <label for="inputPassword2" class="sr-onl mr-4">Product<span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                <input type="hidden" id="prID" value="" name="product_id">
                <input type="text" id="prName" class="form-control" value="" name="product_name" id="inputText" placeholder="Product name" readonly>
                @error('category')
                <div class="invalid-feedback" role='alert' style="display: block;"><strong>{{$message}}</strong></div>
                @enderror
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <div class="form-group mb-3">
                    <input type="file" class="form-control-file" name="product_image" id="exampleFormControlFile1" onchange="preview_image(event)">
                    @error('product_image')
                    <div class="invalid-feedback" role='alert' style="display: block;"><strong>{{$message}}</strong></div>
                    @enderror
                </div>

                <img src="{{asset('assets\img\prd_avatar.png')}}" class="js-tilt" data-tilt-speed="1000" data-tilt-max="20" data-tilt-scale="1.01" data-tilt-perspective="250" id="output_image"/>
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

          <!-- Delete product -->
          <div class="modal fade" id="productDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <form action="{{route('delete-product')}}" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle"><i class="ti-na text-danger" style="font-weight: bolder;"></i> Delete </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                      @csrf
                      <input type="hidden" name="product_id" id="delete_pr_id">
                       Are you sure you want to delete this record?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-danger" >Delete</button>
                </div>
            </form>
              </div>
            </div>
          </div>

@endsection

@section('script')
<script>
    function getProductDetailDelete(data) {
            $('#delete_pr_id').val(data);
        }
</script>
    <script type='text/javascript'>
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

        function productDetail(id, name) {
            //alert(id);
            $(document).ready(function(){
                var prName  =   $('#prName');
                var prID  =   $('#prID');
                prName.val(name);
                prID.val(id);
            })
        }
        $('#subcategory').attr('disabled', 'true');
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