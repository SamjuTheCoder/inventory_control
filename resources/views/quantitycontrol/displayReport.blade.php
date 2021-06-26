@extends('layouts.app')
@section('pageHeaderTitle', 'Quantity Control Report')
@section('content')
<div class="row justify-content-center">
<div class="col-md-12">
    <div class="white_card card_height_100 mb_20">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0"><span class="fa fa-measure"></span> @yield('pageHeaderTitle')</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 bg-light pt-3">
    <div class="col-md-12">

        <div class="text-right text-dark"><small>Field with asterisk (<b class="text-danger">*</b>) is important.</small></div>

            <div class="white_card card_height_100 mb_30">
                <div class="white_card_header">

                    @includeIf('share.operationCallBackAlert', ['showAlert' => 1])
                    <form action="{{ Route::has('quantityControlQuery') ? Route('quantityControlQuery') : '#' }}" method="post" enctype="multipart/form-data">
                            @csrf

                                    <div class="row mb-2">
                                        <div class="mb-3 col-md-4">
                                            <label for="store" class="form-label text-dark">Store<span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                            <select  class="form-control searchSelect" name="store">
                                                <option value="">Select</option>
                                                @if(isset($getStore) && $getStore)
                                                    @foreach($getStore as $key => $value)
                                                    <!-- <option value="{{$value->id}}" {{ (isset($editRecord) && $editRecord) && ($editRecord->productStoreID == $value->id) ? 'selected' : (isset($store) && $store == $value->id ? 'selected' : (old('store') == $value->id ? 'selected' : '')) }}>{{ $value->store_name }}</option> -->
                                                    <option value="{{$value->id}}"
                                                    @if(old('store')==$value->id) 
                                                    selected="selected" 
                                                    @endif>{{ $value->store_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>


                                        <div class="mb-3 col-md-4">
                                            <label for="type" class="form-label text-dark">Movement type </label>
                                            <select class="form-control searchSelect" name="type" id="getType" placeholder="Movement type">
                                                <option value="" >Select Type</option>
                                                <option value="1"
                                                    @if(old('type')=='1') 
                                                    selected="selected" 
                                                    @endif>Move In</option> 
                                                <option value="2"
                                                    @if(old('type')=='2') 
                                                    selected="selected" 
                                                    @endif
                                                >Move Out</option>

                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label for="dateFrom" class="form-label text-dark">From </label>
                                            <input type="text" name="dateFrom" autocomplete="off" class="form-control" id="date_from"  value="{{ old('dateFrom') }}" placeholder="DD-MM-YY">

                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label for="dateTo" class="form-label text-dark">To </label>
                                            <input type="text" name="dateTo" autocomplete="off" class="form-control" id="date_to"  value="{{ old('dateTo') }}" placeholder="DD-MM-YY">

                                        </div>


                                    </div>

                                    <div class="row mb-3">
                                        <div align="center" class="mb-3 col-md-12">

                                                <button type="submit" name="submit" class="btn btn-outline-success">Query Records</button>

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

                            <table class="table table-bordered table-responsive table-hover">
                                <tr class="bg-light text-dark">
                                    <th> SN </th>
                                    <th> Store</th>
                                    <th> Product </th>
                                    <th>Movement Type</th>
                                    <th>Reason</th>
                                    <th> Date </th>
                                    <th> Order Number </th>
                                    <th>Action</th>
                                    
                                </tr>
                                @if(isset($products) && $products)
                                    @foreach($products as $key => $value)
                                        <tr>
                                            <td> {{ ($key + 1) }} </td>
                                            <td> {{ strtoupper($value->storeName) }} </td>
                                            <td> {{ $value->productName }} </td>
                                            
                                            @if($value->move_in_out_type==1)
                                            <td> Transferred In</td>
                                            @else
                                            <td> Transferred Out</td>
                                            @endif
                                            <td>{{ $value->description }}</td>
                                            <td> {{date('d-m-Y', strtotime($value->transactionDate))}}</td>
                                            <td> {{ $value->orderNo }} </td>
                                            <td> <a href="report/quantity-control/{{ base64_encode($value->orderNo) }}" target="_blank" data-toggle="tooltip" data-placement="bootom" title="View All"><button class="btn btn-outline-success"><i class="fa fa-eye"></i></button></a> </td>
           
                                        </tr>

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




@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/datepicker.min.css')}}"/>
@endsection

@section('script')
    <script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>
<script>
   $('input[id$=date_from]').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1910:2090', // specifying a hard coded year range
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: "dd-mm-yy",
        onSelect: function(dateText, inst){
        var theDate = new Date(Date.parse($(this).datepicker('dateFrom')));
        var dateFormatted = $.datepicker.formatDate('dd-mm-yy', theDate);
        $('#dateFrom').val($.datepicker.formatDate('dd-mm-yy', theDate));
        },
    });
</script>
<script>
   $('input[id$=date_to]').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1910:2090', // specifying a hard coded year range
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: "dd-mm-yy",
        onSelect: function(dateText, inst){
        var theDate = new Date(Date.parse($(this).datepicker('dateTo')));
        var dateFormatted = $.datepicker.formatDate('dd-mm-yy', theDate);
        $('#dateFrom').val($.datepicker.formatDate('dd-mm-yy', theDate));
        },
    });
</script>

<script>
    //select field with search
     $(document).ready(function () {
        $('.searchSelect').selectize({
          sortField: 'text'
        });
    });

    //Get Product Measurements
    $(document).ready(function () {
        $('#getProduct').change(function() {
            var productID = $('#getProduct').val();
            $.ajax({
                url: '{{url("/")}}' +  '/transfer-product-get-product-measurement/' + productID,
                type: 'get',
                //data: {'classID': classID, '_token': $('input[name=_token]').val()},
                data: { format: 'json' },
                dataType: 'json',
                success: function(data) {
                    $('#productMeasurement').empty();
                    $('#productMeasurement').append($('<option>').text(" Select Measurement ").attr('value',""));
                    $.each(data, function(model, list) {
                        $('#productMeasurement').append($('<option>').text(list.description +'['+ list.quantity +']').attr('value', list.measurementID));
                    });
                },
                error: function(error) {
                    alert("Please we are having issue getting product measurement. Check your network or refresh this page !!!");
                }
            });
        });
    });

</script>
@endsection
