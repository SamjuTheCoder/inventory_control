@extends('layouts.app')
@section('pageHeaderTitle', 'Inventory Balance')

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

            <div class="text-right text-dark">
                <a href="{{ Route::has('refreshProductReport') ? route('refreshProductReport') : '#' }}">Refresh page</a>
            </div>

            <div class="white_card card_height_100 mb_30">
                <div class="white_card_header">

                    @includeIf('share.operationCallBackAlert', ['showAlert' => 1])

                    <form action="{{ Route::has('postProductMovementInOut') ? Route('postProductMovementInOut') : '#' }}" method="post" enctype="multipart/form-data">
                    @csrf
                            <div class="row mb-2">
                                <div class="mb-3 col-md-6">
                                    <label for="store" class="form-label text-dark">Store </label>
                                    <select class="form-control searchSelect" name="store">
                                        <option value="">Select</option>
                                        <option value="All">Empty store</option>
                                        @if(isset($getStore) && $getStore)
                                            @foreach($getStore as $key => $value)
                                                <option value="{{$value->id}}" {{ isset($store) && $store == $value->id ? 'selected' : '' }}>{{ $value->store_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('store')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="category" class="form-label text-dark">Category </label>
                                    <select class="form-control" id="getCategory" name="category" placeholder="Pick a category"> {{-- searchSelect --}}
                                        <option value="">Select</option>
                                        <option value="All">Empty Category</option>
                                        @if(isset($getCategory) && $getCategory)
                                            @foreach($getCategory as $key => $value)
                                                <option value="{{$value->id}}" {{ isset($category) && $category == $value->id ? 'selected' : '' }}>{{ $value->categoryTitle }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('category')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="mb-3 col-md-6">
                                    <label for="product" class="form-label text-dark">Product </label>
                                    <select class="form-control" name="product" id="getAllProduct" placeholder="Pick a product">
                                        <option value="">Select Product</option>
                                        {{-- @if(isset($getProduct) && $getProduct)
                                            @foreach($getProduct as $key => $value)
                                                <option value="{{$value->id}}" {{ isset($product) && $product == $value->id ? 'selected' : '' }}>{{ $value->productName }}</option>
                                            @endforeach
                                        @endif --}}
                                    </select>
                                    @error('product')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="transactionDate" class="form-label text-dark">Transaction date </label>
                                    <input type="text" id="date_from" class="form-control" name="transactionDate" value="{{ isset($transactionDate) ? $transactionDate : '' }}" placeholder="DD-MM-YY">
                                    @error('transactionDate')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div align="center" class="mb-3 col-md-12">
                                    <button type="submit" name="submit" class="btn btn-outline-success">Search</button>
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
                                    <th> Product </th>
                                    <th> Available QTY </th>
                                    <th>QTY in format </th>
                                </tr>
                                @if(isset($getRecords) && $getRecords)
                                    @foreach($getRecords as $key => $value)
                                        <tr>
                                            <td> {{ ($key + 1) }} </td>
                                            <td> {{ $value->productName }} </td>
                                            <td> {{ ($value->totalIn - $value->totalOut) }} </td>
                                            <td> {{ $value->formatqty }} </td>
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
    //select field with search
     $(document).ready(function () {
        /* $('select').selectize({
          sortField: 'text'
        }); */
        $('.searchSelect').selectize({
          sortField: 'text'
        });
    });

    //Get Product from category
    $(document).ready(function () {
        var categoryID = 'All';
        getProducts(categoryID);
        //function
        function getProducts(categoryID)
        {
              $.ajax({
                url: '{{url("/")}}' +  '/get-product-from-category/' + categoryID,
                type: 'get',
                //data: {'classID': classID, '_token': $('input[name=_token]').val()},
                data: { format: 'json' },
                dataType: 'json',
                success: function(data) {
                    $('#getAllProduct').empty();
                    $('#getAllProduct').append($('<option>').text(" Select Product ").attr('value',""));
                    $.each(data, function(model, list) {
                        $('#getAllProduct').append($('<option>').text(list.productName).attr('value', list.id));
                    });
                },
                error: function(error) {
                    alert("Please we are having issue getting product measurement. Check your network or refresh this page !!!");
                }
            });
        }
        $('#getCategory').change(function() {
            var categoryID = $('#getCategory').val();
            getProducts(categoryID);
        });
    });

</script>
@endsection
