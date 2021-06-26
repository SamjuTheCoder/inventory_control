@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Create Warehouse Users <span style="font-size:12px;">Fields marked<i style="color:red">*</i> are important</span></h3>
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
                    <form method="post" action="{{ url('/create/store-users')}}">
                    @csrf
                        <div class="form-row">
                            
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Store/Warehouse<i style="color:red">*</i></label>
                                <select name="store" class="form-control" id="store" required>
                                <option value="">Select</option>
                                @foreach($stores as $list)
                                <option value="{{$list->id}}">{{$list->store_name}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">User<i style="color:red">*</i></label>
                                <select name="user" class="form-control" id="user" required>
                                <option value="">Select</option>
                                @foreach($allUsers as $list)
                                <option value="{{$list->id}}">{{$list->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        
                       
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="white_card_header ">
                            <div class="box_header m-0">
                                <div class="main-title">
                                    <h3 class="m-0">List of Store Users</h3>
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
                                                        <th scope="col">User</th>
                                                        <th scope="col">Store/Warehouse</th>
                                                        
                                                        <th scope="col">Action</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                $n = 1;
                                                @endphp
                                                @foreach($storeUsers as $list)
                                                    <tr>
                                                        <td scope="row">{{$n++}}</td>
                                                        <td>{{$list->name}} </td>
                                                        <td>{{$list->store_name}} </td>
                                                        
                                                        <td><a href="javascript:void()" class="btn btn-outline-success btn-sm edit" title="Edit Store" storeName="{{$list->store_name}}" userID="{{$list->userID}}" user="{{$list->name}}" storeID="{{$list->storeID}}" storeUserID="{{$list->storeUserID}}"><i class="fa fa-edit"></i></a>
                                                         | <a href="{{url('/delete/store-user/'.$list->storeUserID)}}" class="btn btn-outline-danger btn-sm" title="Delete Store" onclick="return confirmDelete();" class="" storeUserID="{{$list->storeUserID}}"><i class="ti-trash text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                    
                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                            </div>
                        </div>

            <!-- // Table -->
        </div>
    </div>
</div>

<!-- Edit Shelve Modal -->
@section('modal')
<form method="post" action="{{url('/update/store-users')}}">
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
                                <label for="inputPassword4">Store/Warehouse<i style="color:red">*</i></label>
                                <select name="store" class="form-control" id="storeOption">
                                <option value="">Select</option>
                                @foreach($stores as $list)
                                <option value="{{$list->id}}">{{$list->store_name}}</option>
                                @endforeach
                                </select>
                                <input type="hidden" name="storeUserID" id="storeUserID" />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">User<i style="color:red">*</i></label>
                                <select name="user" class="form-control" id="userOption" required>
                                <option value="">Select</option>
                                @foreach($allUsers as $list)
                                <option value="{{$list->id}}">{{$list->name}}</option>
                                @endforeach
                                </select>
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

<form method="post" action="{{url('/delete/store-user')}}">
{{ csrf_field() }}
<div id="deleteModal" class="modal fade" style="z-index:5000">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                
                <p id="message"></p>
            </div>
            <div class="modal-body">
            <h4 class="modal-title">Do you actually want to delete this record?</h4>
            <input type="hidden" name="storeUserID" id="storeUserId" />
                          
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-primary adv" id="adv">Yes</button>
            </div>
        </div>
    </div>
</div>
</form>

@endsection

                <!-- /// Edit Shelve Modal -->



@endsection

@section('script')
<script>
    $(document).ready(function(){
  
    $("table tr td .edit").click(function(){
       $('#storeUserID').val($(this).attr('storeUserID'));
       $('#user').val($(this).attr('user'));
       $('#storeName').val($(this).attr('storeName'));
     
       var storeID = $(this).attr('storeID');
       var storeName = $(this).attr('storeName');
       var userID   = $(this).attr('userID');
       var user   = $(this).attr('user');
       $('#storeOption').append("<option value='"+storeID+"' selected>"+ storeName+"<option>");
       $('#userOption').append("<option value='"+userID+"' selected>"+ user +"<option>");
        $("#editModal").modal('show');
    });
    
    });

</script>

<script>
    $(document).ready(function(){
  
    $("table tr td .delete").click(function(){
     $('#storeUserId').val($(this).attr('storeUserID'));
     $("#deleteModal").modal('show');
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