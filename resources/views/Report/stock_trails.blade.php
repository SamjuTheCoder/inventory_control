@extends('layouts.app')
@section('pageHeaderTitle', 'Item Movement Report')

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

    <div class="col-md-12 bg-light pt-3">
        <div class="col-md-12">

            <div class="white_card card_height_100 mb_30">
                <div class="white_card_header">

                    @includeIf('share.operationCallBackAlert', ['showAlert' => 1])

                    <form  method="post" >
                    @csrf
                            
                            <div class="row mb-2">
                                <div class="mb-4 col-md-4">
                                    <label for="store" class="form-label text-dark">Store</label>
                                    <select class="form-control searchSelect" name="store">
                                        <option value="">-Select all-</option>
                                            @foreach($getStore as $key => $value)
                                                <option value="{{$value->id}}" {{  ($store == $value->id) ? 'selected' : (old('store') == $value->id ? 'selected' : '') }}>{{ $value->store_name }}</option>
                                            @endforeach 
                                    </select>
                                    @error('store')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-4">
                                    <label for="product" class="form-label text-dark">Product<span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <select required class="form-control searchSelect" name="product" id="getProduct" placeholder="Pick a product">
                                        <option value="">-Select-</option>                                     
                                            @foreach($getProduct as $key => $value)
                                                <option value="{{$value->id}}" {{ ($product == $value->id) ? 'selected' : (old('product') == $value->id ? 'selected' : '') }}>{{ $value->productName }}</option>
                                            @endforeach
                                    </select>
                                    @error('product')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label for="transactionDate" class="form-label text-dark ">From <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <input type="text" id="getDate" required class="form-control dateFrom" name="fromdate" value="{{$fromdate  }}" placeholder="DD-MM-YY">
                                    @error('transactionDate')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-2">
                                    <label for="transactionDate" class="form-label text-dark">To <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <input type="text" id="getDate" required class="form-control dateTo" name="todate" value="{{ $todate  }}" placeholder="DD-MM-YY">
                                    @error('transactionDate')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div align="center" class="mb-3 col-md-12">                                   
                                        <button type="submit" name="submit" class="btn btn-outline-success">Reload</button>  
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
                                    <th>Transaction Date</th>
                                    <th>Stock In</th>
                                    <th>Stock Out</th>
                                    <th>Stock Balance</th>
                                    <th>Description</th>
                                    <th>Order Number</th>
                                    <th></th>
                                </tr>
                                @if($TrailRecords)
                                <tr>
                                    <td colspan=3>Opening stock</td>
                                    
                                    <td>{{$opening}} </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
                                @endif
                                @foreach($TrailRecords as $list)
                                <tr>
                                    <td>{{ date('d-m-Y', strtotime($list->transactionDate)) }}</td>
                                    <td>{{$list->inQTY!=''?$list->inQTY:'-'}} </td>
                                    <td>{{$list->outQTY!=''?$list->outQTY:'-'}} </td>
                                    <td>{{$list->curQTY!=''?$list->curQTY:'-'}} </td>
                                    <td>{{$list->description}} </td>
                                    <td>{{$list->orderNo}} </td>
                                    <td> <a onclick="ViewDetails('ref')" class="btn btn-success">View</a></td>
                                </tr>
    		                    @endforeach
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
@endsection

@section('script')
<script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>

<script>

$(".dateFrom").datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: '1910:2090', // specifying a hard coded year range
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "dd-mm-yy",
    onSelect: function(dateText, inst){
      var theDate = new Date(Date.parse($(this).datepicker('dateFrom')));
      var dateFormatted = $.datepicker.formatDate('dd-mm-yy', theDate);
       $('.dateFrom').val($.datepicker.formatDate('dd-mm-yy', theDate));
    },
  });

  $(".dateTo").datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: '1910:2090', // specifying a hard coded year range
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "dd-mm-yy",
    onSelect: function(dateText, inst){
      var theDate = new Date(Date.parse($(this).datepicker('dateTo')));
      var dateFormatted = $.datepicker.formatDate('dd-mm-yy', theDate);
       $('.dateTo').val($.datepicker.formatDate('dd-mm-yy', theDate));
    },
  });

</script>

<script>
    //date format
     //$(document).ready(function () {
       /* $("#getDate").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '1910:2090', // specifying a hard coded year range
            showOtherMonths: false,
            selectOtherMonths: false,
            dateFormat: "dd-mm-yyyy",
            onSelect: function(dateText, inst){
                var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                //var theDate = new Date(Date.parse($(this).datepicker('getDate')));
                var theDate = new Date(today.toLocaleDateString("en-NG", options));
                var dateFormatted = $.datepicker.formatDate('dd-mm-yyyy', theDate);
            },
        });*/
    //});
</script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

<script>
    //select field with search
     $(document).ready(function () {
        /* $('select').selectize({
          sortField: 'text'
        }); */
        $('.searchSelect').selectize({
          sortField: 'text'
        });
    });

    //Get Product Measurements
    $(document).ready(function () {
        $('#getProduct').change(function() {
            var productID = $('#getProduct').val();
            $.ajax({
                url: '{{url("/")}}' +  '/get-product-measurement/' + productID,
                type: 'get',
                //data: {'classID': classID, '_token': 