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
                                    @if(!empty($product))
                                    @if($product->move_in == 0)
                                    <h6>Destination: {{$product->location ?? ''}}</h6>
                                    @endif
                                    @endif
                                    <h6>Description: {{$product->description ?? ''}} </h6>
                        <h6>Order Number: {{$product->orderNo ?? ''}}</h6>
                        <h6>Warehouser: {{$product->store_name ?? ''}}</h6>
                        <h6>Movement Date: {{date('d-m-Y', strtotime($product->transactionDate))}}</h6>
                                    <div class="table-responsive">
                                    <form method="post" action="{{url('/cancel/order')}}">
                                    @csrf
                                        <table class="table lms_table_active2  ">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">SN</th>
                                                        <th> Product Name</th>
                                                        <th> Quantity</th>
                                                        
                                                        <td>Status</th>
                                                        <th scope="col"> <span> @if($isconfirm > 0) <input type="checkbox" name="all" id="checkall"> Checkall</span> <span  class="float-right">Cancel</span>@endif</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                $n = 1;
                                                @endphp
                                                @foreach($orders as $list)
                                                @php
                $checkconfirmed = DB::table('product_movements')->where('orderNo','=', $list->orderNo)->where('id','=', $list->prodMovementID)->first();
                $rejectedComment = DB::table('rejected_comment')->where('item_id','=', $list->prodMovementID)->orderBy('id','desc')->first();
                $isrejected = DB::table('product_movements')->where('orderNo','=', $list->orderNo)->where('id','=', $list->prodMovementID)->where('isConfirmed','=', 2)->count();

         $q1=db::table('measurement_units')->select('measurement_units.*','measurements.description')
        ->leftJoin('measurements','measurements.id','measurement_units.measurementID')
        ->orderby('quantity', 'desc')->where('productID',$list->prodID)->get();
        //dd($q1);
        $qty = $list->move_in;
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
                                                        <td>{{$list->move_in}} [ <span style="color:green;"> {{$data}} </span>]</td>
                                                        
                                                        <td>@if($isrejected > 0)
                                                        <a href="javascript:void()" class="btn btn-outline-success btn-sm comment"  comm="{{$rejectedComment->comment ?? ''}}">Reason for Rejection</a>

                                                        <a prodMovementID="{{$list->prodMovementID}}" product="{{$list->prodID}}" quantity="{{$list->outQty}}" href="javascript:void()" orderNo="{{$list->orderNo}}" class="btn btn-outline-success btn-sm resend">Re-send</a>
                                                        <a measureID="{{$list->munit}}" measurement="{{$list->desc}}" prodMovementID="{{$list->prodMovementID}}" product="{{$list->prodID}}" quantity="{{$list->outQty}}" href="javascript:void()" class="btn btn-outline-success btn-sm adjust">Adjust Qty</a>
                                                        @else
                                                        @if(!empty($checkconfirmed))
                                                        @if($checkconfirmed->isConfirmed ==1)
                                                        <span>Confirmed</span>
                                                        @elseif($checkconfirmed->isConfirmed ==0)
                                                        <span>Awaiting Confirmation</span>
                                                        <a measureID="{{$list->munit}}" measurement="{{$list->desc}}" prodMovementID="{{$list->prodMovementID}}" product="{{$list->prodID}}" quantity="{{$list->outQty}}" href="javascript:void()" class="btn btn-outline-success btn-sm adjust">Adjust Qty</a>
                                                        @endif
                                                        @endif
                                                        @endif

                                                        

                                                       </td>
                                                        <td>
                                                        @if(!empty($checkconfirmed) )
                                                        @if($checkconfirmed->isConfirmed == 2 )
                                                        <input type="checkbox" name="item[]" value="{{$list->prodMovementID}}">          
                                                        @endif
                                                        @endif
                                                        
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                    
                                                </tbody>
                                            </table>
                                            @if($isconfirm > 0)
                                            <button type="submit" onclick="return confirmDelete();" class="btn btn-outline-success float-right">Cancel</button>
                                            @endif
                                            </form>
                                    </div>
                                </div>
                            </div>
                        </div>


            <!-- Table -->


        </div>
    </div>
</div>
@endsection

@section('modal')


<!--  Modal -->
<div id="descmodal"  class="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="description"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>
<!-- /// Modal -->




@endsection

@section('modal2')


<!--  Modal -->
<div id="mymodal" class="modal " tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <form method="post" action="{{url('/adjust/quantity-in')}}">
            {{ csrf_field() }}
      <div class="modal-body">

            <div class="row mb-2">
                                <div class="mb-3 col-md-6">
                                    <label for="measure" class="form-label text-dark">Product Measure <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <select required class="form-control m" id="productMeasurement" name="measure">
                                        <option value="">Select Measurement</option>
                                        @if(!empty($mesurements))
                                        @foreach($mesurements as $list)
                                            <option value="{{$list->measureID}}">{{$list->description}} [{{$list->quantity}}]</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <input type="hidden" name="productMovementID" id="prodMovementID">
                                    <input type="hidden" name="productID" id="prodID">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="quantity" class="form-label text-dark">Quantity <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <input type="number" required maxlength="100" class="form-control" name="quantity" id="quantity" value="">
                                                                    </div>
                            </div>

       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary adv" id="adv">Save changes</button>
        
      </div>
       </form>
    </div>
  </div>
</div>
<!-- /// Modal -->


<!--  Modal -->
<div id="resendmodal"  class="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="{{url('/resend/product')}}">
        {{ csrf_field() }}
      <div class="modal-body">
        <input type="hidden" name="productMovementID" id="prodMovementId">
        <input type="hidden" name="orderNo" id="orderNo">
        <p> Do you actually want to resend this product ?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <button type="submit" class="btn btn-primary adv" id="adv">Yes</button>
      </div>
  </form>
    </div>
  </div>
</div>
<!-- /// Modal -->

@endsection

@section('style')
<style type="text/css">
    svg, #SvgjsSvg1001
    {
        display: none;
    }
</style>

@endsection

@section('script')

<script>

    $(document).ready(function(){

    $("table tr td .comment").click(function(){

       $('#description').html($(this).attr('comm'));
       //alert(2);
       $('#descmodal').modal('show');
    });

    });
</script>


<script>

    $(document).ready(function(){

    $("table tr td .adjust").click(function(){
        
        var id = $(this).attr('measureID');
        var measure = $(this).attr('measurement');
        
      $('#quantity').val($(this).attr('quantity'));
      $('#prodID').val($(this).attr('product'));
      $('#prodMovementID').val($(this).attr('prodMovementID'));
      $('.m').find('option:selected').remove();
       $('.m').append('<option value="'+ id +'" selected>'+ measure +'</option>');
       //alert(2);
       $('#mymodal').modal('show');
    });

    });
</script>

<script>

    $(document).ready(function(){

    $("table tr td .resend").click(function(){

      $('#prodMovementId').val($(this).attr('prodMovementID'));
       $('#orderNo').val($(this).attr('orderNo'));
       $('#resendmodal').modal('show');
    });

    });
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
