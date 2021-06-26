@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add Store/Warehouse <span style="font-size:12px;">Fields marked<i style="color:red">*</i> are important</span></h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="card-body">
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
          @if(session('msg'))
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Success!</strong> 
            {{ session('msg') }}
          </div>                        
          @endif
          @if(session('err'))
          <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Error!</strong> 
            {{ session('err') }}
          </div>                        
          @endif
                    <form method="post" action="{{ url('/create/warehouse')}}">
                    @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Store name:<i style="color:red">*</i></label>
                                <input type="text" name="store_name" class="form-control" id="storeName" placeholder="Store Name" value="{{ old('storeName') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Location<i style="color:red">*</i></label>
                                <input type="text" name="location" class="form-control" id="location" placeholder="Location" value="{{ old('location') }}" required>
                            
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Address:</label>
                           <textarea name="address" class="form-control" id="address"></textarea>
                        </div>
                       
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>

                    
                </div>
            </div>
            <!-- Table -->
                <div class="white_card_header ">
                            <div class="box_header m-0">
                                <div class="main-title">
                                    <h3 class="m-0">List of Warehouses</h3>
                                </div>
                                <a href="#">
                                    <p></p>
                                </a>
                            </div>
                </div>
            <div class="white_card_body pt-0">
                            <div class="QA_section">
                                <div class="QA_table mb-0 transaction-table">
                                    <!-- table-responsive -->
                                    <div class="table-responsive">
                                        <table class="table lms_table_active2  ">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">SN</th>
                                                        <th scope="col">Store Name</th>
                                                        <th scope="col">Location</th>
                                                        <th scope="col">Address</th>
                                                        <th scope="col">Action</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                $n = 1;
                                                @endphp
                                                @foreach($allStores as $list)
                                                    <tr>
                                                        <td scope="row">{{$n++}}</td>
                                                        <td>{{$list->store_name}} </td>
                                                        <td>{{$list->location}} </td>
                                                        <td>{{$list->address}} </td>
                                                        
                                                        <td><a href="javascript:void()" class="btn btn-outline-success edit btn-sm" title="Edit Store" storeId="{{$list->id}}" name="{{$list->store_name}}" location="{{$list->location}}" address="{{$list->address}}"><i class="fa fa-edit"></i></a>
                                                         | <a href="{{url('/delete/store/'.$list->id)}}" class="btn btn-outline-danger btn-sm" onclick="return confirmDelete()" title="Delete Store" storeId="{{$list->id}}"><i class="ti-trash text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                    
                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                            </div>
                        </div>

            <!-- table -->


        </div>
    </div>
</div>

<!-- Edit Store Modal -->
@section('modal')
<form method="post" action="{{url('/update/store')}}">
{{ csrf_field() }}
<div id="editModal" class="modal fade" style="z-index:5000">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
                <p id="message"></p>
            </div>
            <div class="modal-body">
            <!-- form -->
            <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Store Name:<i style="color:red">*</i></label>
                                <input type="text" name="store_name" class="form-control" id="storeNameModal" placeholder="Store Name" value="{{ old('storeName') }}" required>
                                <input type="hidden" name="storeID" id="storeId" />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Location<i style="color:red">*</i></label>
                                <input type="text" name="location" class="form-control" id="locationModal" placeholder="Location" value="{{ old('location') }}" required>
                            
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Address:</label>
                           <textarea name="address" class="form-control" id="addressModal"></textarea>
                        </div>
            <!-- // form -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary adv" id="adv">Save changes</button>
            </div>
        </div>
    </div>
</div>
</form>
@endsection


@endsection

@section('script')
<script>
    $(document).ready(function(){
  
    $("table tr td .edit").click(function(){
       $('#addressModal').val($(this).attr('address'));
       $('#locationModal').val($(this).attr('location'));
       $('#storeNameModal').val($(this).attr('name'));
     
       var storeID = $(this).attr('storeID')
       $('#storeId').val($(this).attr('storeID'));
      // $('#country').append("<option value='"+countryID+"' selected>"+ country+"<option>");
        $("#editModal").modal('show');
    });
    
    });

</script>

<script>
    $(document).ready(function(){
  
    $("table tr td .del").click(function(){
       
       $('#user').val($(this).attr('userID'));
    
        $("#delModal").modal('show');
    });
    
    });

    function confirmDelete()
    {
        var x = confirm('Are you sure you want to delete this record ?');
        if (x)
        {
            return true;
        } 
        else
        {
            return false;
        }
    }

</script>
@endsection