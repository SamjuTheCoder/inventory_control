@extends('layouts.app')
@section('pageHeaderTitle', 'Product Transferred Report Details')

@section('content')
<div class="row justify-content-center">

    <div class="col-md-12 d-print-none">
        <div class="white_card card_height_100 mb_20">
            <div class="white_card_header">
                <div class="row box_header m-0">
                    <div class="col-md-8 main-title">
                        <h3 class="m-0">
                        <a href="{{ Route::has('viewTransaferReport') ? Route('viewTransaferReport') : '' }}" class="text-dark"><span class="fa fa-angle-left fa-2x"></span> Go back </a> | @yield('pageHeaderTitle')</h3>
                    </div>
                    <div class="col-md-12">
                        <hr />
                       <div class="p-1 h6"> Category: {{ isset($ProductDetails) ? $ProductDetails->categoryTitle  : '' }} </div>
                        <div class="p-1 h6">Transaction date: {{ ($ProductDetails ? date('d-m-Y', strtotime($ProductDetails->transactionDate)) : '') }} </div>
                        <div class="p-1 h6">Order number: {{ isset($ProductDetails) ? $ProductDetails->orderNo : '' }} </div>
                        <div class="p-1 h6">Source store: {{ isset($ProductDetails) ? $ProductDetails->store_name : '' }} </div>
                        <div class="p-1 h6">Destination store: {{ isset($destinationStoreName) ? $destinationStoreName : '' }} </div>
                        <div class="p-1 h6">Description: {{ isset($ProductDetails) ? $ProductDetails->productDescription : '' }} </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 bg-light pt-3">
        @includeIf('share.operationCallBackAlert', ['showAlert' => 1])
        <div class="col-md-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_header">
                    <div class="box_header m-0 p-0">
                        <div class="main-title">
                           
                            <table class="table table-bordered table-responsive table-condensed table-hover">
                                <tr class="bg-white text-success">
                                    <th colspan="2"> Category: {{ isset($ProductDetails) ? $ProductDetails->categoryTitle  : '' }} </th>
                                    <th colspan="2"> Transaction date: {{ ($ProductDetails ? date('d-m-Y', strtotime($ProductDetails->transactionDate)) : '') }} </th>
                                    <th colspan="2"> Order number: {{ isset($ProductDetails) ? $ProductDetails->orderNo : '' }} </th>
                                </tr>
                                <tr class="bg-light text-dark">
                                    <th> SN </th>
                                    <th> Product </th>
                                    <th> QTY </th>
                                    <th> Bacode </th>
                                    <th> Status </th>
                                    <th> Name </th>
                                </tr>
                                @if(isset($transferredProduct) && $transferredProduct)
                                    @foreach($transferredProduct as $key => $value)
                                        <tr>
                                            <td> {{ ($transferredProduct->currentpage()-1) * $transferredProduct->perpage() + (1+$key ++) }} </td>
                                            <td> {{ $value->productName }} </td>
                                            <td> {{ $value->move_out }} </td>
                                            <td> {{ ($value->barcode) }} </td>
                                            <td> {!! $value->itemStatus !!} </td>
                                            <td> {{ $value->name }} </td>
                                            
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                            @if(isset($transferredProduct) && $transferredProduct)
                                <div class="row">
                                    <div align="right" class="col-xs-12 col-sm-12">
                                        Showing {{($transferredProduct->currentpage()-1)*$transferredProduct->perpage()+1}}
                                        to {{$transferredProduct->currentpage()*$transferredProduct->perpage()}}
                                        of  {{$transferredProduct->total()}} entries
                                        <br />
                                        <div class="hidden-print">{{ $transferredProduct->links() }}</div>
                                    </div>
                                </div>
                            @endif

                            
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
    //select field with search
     $(document).ready(function () {
        /* $('select').selectize({
          sortField: 'text'
        }); */
        $('.searchSelect').selectize({
          sortField: 'text'
        });
    });

    //Submit status code
    $(document).ready(function () {
        $('#statusCode').change(function() {
            $('#formQueryRecord').submit();
        });
    });

</script>
@endsection
