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
                        <h3 class="m-0">Product</h3>
                        
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

                        <h6>Description: {{$products[0]->description ?? ''}} </h6>
                        <h6>Order Number: {{$products[0]->orderNo ?? ''}}</h6>
                        <h6>Warehouser: {{$products[0]->storeName ?? ''}}</h6>
                        <h6>Transfer Type:
                        @if($products[0]->move_in_out_type==1)
                        In
                        @else
                        Out
                        @endif
                        </h6>
                        <h6>Movement Date: {{date('d-m-Y', strtotime($products[0]->transactionDate))}}</h6>
                                    <div class="table-responsive">
                                    <form method="post" action="{{url('/cancel/order')}}">
                                    @csrf
                                        <table class="table lms_table_active2">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">SN</th>
                                                        <th> Product Name</th>
                                                        <th> Quantity</th>  

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($products as $product)
                                                    <tr>
                                                        <td scope="row">{{1}}</td>
                                                        <td>{{$product->productName}} </td>
                                                        <td>{{$product->moved}}</td>
                                                        
                                                        
                                                    </tr>
                                                    @endforeach
                                               
                                                    
                                                </tbody>
                                            </table>
                                               </form>
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

@section('script')
<script>
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
<script>
$(document).ready(function(){

$('#checkall').change(function () {
    var state = this.checked; //checked ? - true else false

state ? $(':checkbox').prop('checked',true) : $(':checkbox').prop('checked',false);

});


});
</script>
@endsection