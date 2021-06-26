@extends('layouts.app')
@section('pageHeaderTitle', 'Product Movement In-Out Report')

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
                <a href="{{ Route::has('refreshProductReport') ? route('refreshProductReport') : '#' }}">Refresh Page</a>
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
                                        <option value="All">Empty Store</option>
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
                                    <label for="product" class="form-label text-dark">Product </label>
                                    <select class="form-control searchSelect" name="product" id="getProduct" placeholder="Pick a product">
                                        <option value="">Select Product</option>
                                        <option value="All">Empty Product</option>
                                        @if(isset($getProduct) && $getProduct)
                                            @foreach($getProduct as $key => $value)
                                                <option value="{{$value->id}}" {{ isset($product) && $product == $value->id ? 'selected' : '' }}>{{ $value->productName }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('product')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="mb-3 col-md-6">
                                    <label for="category" class="form-label text-dark">Category </label>
                                    <select class="form-control searchSelect" name="category" placeholder="Pick a category">
                                        <option value="">Select Product</option>
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
                                <div class="mb-3 col-md-6">
                                    <label for="transactionDate" class="form-label text-dark">Transaction Date </label>
                                    <input type="date" id="getTransDate-NOT" class="form-control" name="transactionDate" value="{{ isset($transactionDate) ? $transactionDate : '' }}" placeholder="DD-MM-YY">
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
                                    <th> PRODUCT </th>
                                    <th> AVAILABLE QTY </th>
                                </tr>
                                @if(isset($getRecords) && $getRecords)
                                    @foreach($getRecords as $key => $value)
                                        <tr>
                                            <td> {{ ($key + 1) }} </td>
                                            <td> {{ $value->productName }} </td>
                                            <td> {{ ($value->totalIn - $value->totalOut) }} </td>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
@endsection


@section('script')
<script>
    //date format
     //$(document).ready(function () {
        $("#getTransDate").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '1910:2090', // specifying a hard coded year range
            showOtherMonths: false,
            selectOtherMonths: false,
            dateFormat: "dd-mm-yyyy",
            onSelect: function(dateText, inst){
                var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                var theDate = new Date(today.toLocaleDateString("en-NG", options));
                var dateFormatted = $.datepicker.formatDate('dd-mm-yyyy', theDate);
            },
        });
    //});
</script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

<script>
    //select field with search
     $(document).ready(function () {
        $('.searchSelect').selectize({
          sortField: 'text'
        });
    });
</script>
@endsection
