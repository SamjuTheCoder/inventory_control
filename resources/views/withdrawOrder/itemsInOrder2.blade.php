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
                        <h3 class="m-0">Orders Moved Out</h3>
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
                                    @if(!empty($product))
                                    @if($product->move_in == 0)
                                    <h6>Destination: {{$product->location ?? ''}}</h6>
                                    @endif
                                    @endif
                                    <h6>Description: {{$product->description ?? ''}} </h6>
                        <h6>Order Number: {{$product->orderNo ?? ''}}</h6>
                        <h6>Warehouser: {{$product->store_name ?? ''}}</h6>
                                    <div class="table-responsive">
                                    <form method="post" action="{{url('/cancel/order')}}">
                                    @csrf
                                        <table class="table lms_table_active2  ">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">SN</th>
                                                        <th> Product Name</th>
                                                        <th> Quantity</th>
                                                        <th> Movement Date </th>
                                                        <td>Reason</th>
                                                        <th scope="col"> <span> <input type="checkbox" name="all" id="checkall"> Checkall</span> <span  class="float-right">Cancel</span></th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                $n = 1;
                                                @endphp
                                                @foreach($orders as $list)
                                                @php
                $checkconfirmed = DB::table('product_movements')->where('orderNo','=', $list->orderNo)->where('id','=', $list->prodMovementID)->first();
                $rejectedComment = DB::table('rejected_comment')->where('item_id','=', $list->prodMovementID)->first();

         $q1=db::table('measurement_units')->select('measurement_units.*','measurements.description')
        ->leftJoin('measurements','measurements.id','measurement_units.measurementID')
        ->orderby('quantity', 'desc')->where('productID',$list->productID)->get();
        //dd($q1);
        $qty = $list->move_out;
        $qty1=$qty;
	    $data='';
        foreach ($q1 as $b){
	        $formatqty= $b->quantity;
	        if($formatqty==0)$formatqty=1;
    	    $q = intval($qty / $formatqty);
            $qty = $qty % $formatqty;
        	if($q<>0){
        	   $data.= ' '.Abs($q).$b->description;
        	 }    
	    }
             $data = (int)$qty1 < 0 ? '('.$data.')':$data;
         
                                                @endphp
                                                    <tr>
                                                        <td scope="row">{{$n++}}</td>
                                                        <td>{{$list->productName}} </td>
                                                        <td>{{$data}} </td>
                                                        <td>{{date('d-m-Y', strtotime($list->transactionDate))}}</td>
                                                        <td>@if(!empty($rejectedComment))
                                                        <a href="javascript:void()" class="btn btn-outline-success btn-sm comment" comm="{{$rejectedComment->comment}}">Reason for Rejection</a>
                                                        @endif</td>
                                                        <td>
                                                        @if(!empty($checkconfirmed) )
                                                        @if($checkconfirmed->isConfirmed == 2 )
                                                        <input type="checkbox" name="item[]" value="{{$list->prodMovementID}}">                                                                                                             </td>
                                                        @endif
                                                        @endif
                                                        
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                    
                                                </tbody>
                                            </table>
                                            <button type="submit" onclick="return confirmDelete();" class="btn btn-outline-success float-right">Cancel</button>
                                            </form>
                                    </div>
                                </div>
                            </div>
                        </div>


            <!-- Table -->


        </div>
    </div>
</div>

<!--  Modal -->
<form method="post" action="{{url('/update/shelve')}}">
{{ csrf_field() }}
<div id="commentModal" class="modal fade" >
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
<!-- /// Modal -->



@endsection

@section('script')
<script>
    $(document).ready(function(){
  
    $("table tr td .comment").click(function(){
       
       //$('#description').html($(this).attr('comm'));
       //alert($(this).attr('comm'));
       $("#commentModal").modal('show');
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