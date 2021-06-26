@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            
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
               
                </div>
            </div>
            <!-- Table -->

            <div class="white_card_header ">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Orders Brought In</h3>
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
                                        <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">SN</th>
                                                        <th> Order Number </th>
                                                        <th> Description </th>
                                                       
                                                        <th scope="col">Store/Warehouse</th>
                                                        
                                                        <th scope="col">Action</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                $n = 1;
                                                @endphp
                                                @foreach($orders as $list)
                                                @php
                                                $checkOrder = DB::table('product_movements')->where('orderNo','=', $list->orderNo)->count();
                                                $checkrejected = DB::table('product_movements')->where('orderNo','=', $list->orderNo)->where('isconfirmed','=', 2)->count();
                                                $check = DB::table('product_movements')->where('orderNo','=', $list->orderNo)->where('isconfirmed','=', 1)->count();
                                                @endphp
                                                    <tr>
                                                        <td scope="row">{{$n++}}</td>
                                                        <td>{{$list->orderNo}} </td>
                                                        <td>{{$list->description}}  
                                                        @if($checkrejected > 0)
                                                        (<span class="blink">One or more items in this order rejected</span>)
                                                        @elseif($checkOrder == $checkrejected)
                                                        (<span class="blink">This order was rejected</span>)
                                                        @endif
                                                        @if($check > 0 && $check != $checkOrder)
                                                        <span  class="blink" style="text-warning"> Partial Action </span>
                                                        @endif
                                                        </td>
                                                        
                                                        <td>{{$list->store_name}} </td>
                                                        
                                                        <td><a href="{{url('/view/orders-in/'.$list->orderNo)}}" class="btn btn-outline-success btn-sm" ><i class="fa fa-eye"></i></a>
                                                                                                                 </td>
                                                    </tr>
                                                @endforeach
                                                    
                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                            </div>
                        </div>


            <!-- Table -->


        </div>
    </div>
</div>

<!-- Edit Shelve Modal -->
@section('modal')
<form method="post" action="{{url('/update/shelve')}}">
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

                <!-- /// Edit Shelve Modal -->



@endsection

@section('style')

<style type="text/css">
.blink
{

color:red;
font-size: 16px;
}


</style>


@endsection

@section('script')
<script>

$(document).ready(function()
{
    $('.blink').each(function() {
    var elem = $(this);
    setInterval(function() {
        if (elem.css('visibility') == 'hidden') {
            elem.css('visibility', 'visible');
        } else {
            elem.css('visibility', 'hidden');
        }    
    }, 2000);
});

});

    $(document).ready(function(){
  
    $("table tr td .edit").click(function(){
       $('#desc').val($(this).attr('description'));
       $('#shelve').val($(this).attr('name'));
       $('#storeName').val($(this).attr('storeName'));
     
       var storeID = $(this).attr('storeID');
       var storeName = $(this).attr('storeName');
       $('#shelveID').val($(this).attr('shelveID'));
       $('#storeID').append("<option value='"+storeID+"' selected>"+ storeName+"<option>");
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