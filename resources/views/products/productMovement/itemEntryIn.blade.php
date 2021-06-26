@extends('layouts.app')
@section('pageHeaderTitle', 'Product Entry')

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

        <div class="text-right text-dark"><small>Field with asterisk (<b class="text-danger">*</b>) is important.</small></div>

            <div class="white_card card_height_100 mb_30">
                <div class="white_card_header">

                    @includeIf('share.operationCallBackAlert', ['showAlert' => 1])

                    <form action="{{ Route::has('saveProductMovement') ? Route('saveProductMovement') : '#' }}" method="post" enctype="multipart/form-data">
                    @csrf
                            <input type="hidden" name="getRecord" value="{{ (isset($editRecord) && $editRecord) ? $editRecord->recordID : old('getRecord') }}">

                            <div class="row mb-2">
                                <div class="mb-3 col-md-4">
                                    <label for="store" class="form-label text-dark">Store<span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <select required id="getStore" class="form-control searchSelect" name="store">
                                        <option value="">Select</option>
                                        @if(isset($getStore) && $getStore)
                                            @foreach($getStore as $key => $value)
                                                <option value="{{$value->id}}" {{ (isset($editRecord) && $editRecord) && ($editRecord->productStoreID == $value->id) ? 'selected' : (old('store') == $value->id ? 'selected' : '') }}>{{ $value->store_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('store')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="product" class="form-label text-dark">Product<span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <select required class="form-control searchSelect" name="product" id="getProduct" placeholder="Pick a product">
                                        <option value="">Select product</option>
                                        @if(isset($getProduct) && $getProduct)
                                            @foreach($getProduct as $key => $value)
                                                <option value="{{$value->id}}" {{ (isset($editRecord) && $editRecord) && ($editRecord->productID == $value->id) ? 'selected' : (old('product') == $value->id ? 'selected' : '') }}>{{ $value->productName }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('product')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="shelf" class="form-label text-dark">Shelf </label>
                                    <select class="form-control" id="shelfInStore" name="shelf">
                                        <option value="">Select</option>
                                       {{--  @if(isset($editRecord) && $editRecord)
                                            <option value="{{ $editRecord->shelfID }}" selected>{{ $editRecord->shelve_name }} </option>
                                        @endif --}}
                                    </select>
                                    @error('shelf')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="mb-3 col-md-6">
                                    <label for="measure" class="form-label text-dark">Product measure <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <select required class="form-control" id="productMeasurement" name="measure">
                                        <option value="">Select Measurement</option>
                                        {{-- @if(isset($editRecord) && $editRecord)
                                            <option value="{{ $editRecord->measurementID }}" selected>{{ $editRecord->measureName }} </option>
                                        @endif --}}
                                    </select>
                                    @error('measure')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="quantity" class="form-label text-dark">Quantity <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <input type="number" required maxlength="100" class="form-control" name="quantity" value="{{ (isset($editRecord) && $editRecord) ? $editRecord->quantity : '' }}">
                                    @error('quantity')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div align="center" class="mb-3 col-md-12">
                                    @if((isset($editRecord) && $editRecord))
                                        <button type="submit" name="submit" class="btn btn-outline-success">Update record</button>
                                    @else
                                        <button type="submit" name="submit" class="btn btn-outline-success">Add new entry</button>
                                    @endif
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
                                    <th> STORE</th>
                                    <th> SHELF </th>
                                    <th> PRODUCT </th>
                                    <th> MEASURE </th>
                                    <th> QTY </th>
                                    <th colspan="2"> </th>
                                </tr>
                                @if(isset($getRecords) && $getRecords)
                                    @foreach($getRecords as $key => $value)
                                        <tr>
                                            <td> {{ ($key + 1) }} </td>
                                            <td> {{ strtoupper($value->store_name) }} </td>
                                            <td> {{ $value->shelve_name }} </td>
                                            <td> {{ $value->productName }} </td>
                                            <td> {{ $value->measureName }} </td>
                                            <td> {{ $value->productQuantity }} </td>
                                            <td> <a href="{{ Route::has('editProductMovement') ? Route('editProductMovement', ['rid'=>$value->recordID]) : 'javascript:;'  }}" class="btn btn-outline-success btn-sm" title="Edit Record"> <i class="fa fa-edit"></i>  </a></td>
                                            <td> <button type="button" name="submit" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-backdrop="false" data-target="#confirmToDelete{{$key}}" title="Delete Record"> <i class="fa fa-trash"></i> </button></td>
                                        </tr>

                                        <!-- Modal - confirm to delete -->
                                        <div style="z-index: 9999999999;" class="modal fade text-left d-print-none" id="confirmToDelete{{$key}}" tabindex="-1" role="dialog" aria-labelledby="confirmToDelete{{$key}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning">
                                                        <h6 class="modal-title text-dark">Confirm Deletion!</h6>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-success text-center"> <h6>Are you sure you want to remove this record? </h6></div>
                                                        <div class="text-dark text-center">  {{ $value->productName }} </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-default" data-dismiss="modal"> Cancel </button>
                                                        <a href="{{ Route::has('deleteProductMovement') ? Route('deleteProductMovement', ['rid'=>$value->recordID]) : 'javascript:;'  }}" class="btn btn-outline-warning"> Delete </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end Modal-->


                                    @endforeach
                                @endif
                            </table>

                             <div align="center">
                                <button type="button" name="btnBatch" class="btn btn-outline btn-primary btn-sm" data-toggle="modal" data-backdrop="false" data-target="#confirmBatch" title="Batch All Records"> <i class="fa fa-file-o"></i> Batch Item </button>
                            </div><br />

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal - confirm to Batch -->
<form action="{{ Route::has('postBatchItems') ? Route('postBatchItems') : '#' }}" method="post" enctype="multipart/form-data">
    @csrf
    <div style="z-index: 9999999999;" class="modal fade text-left d-print-none" id="confirmBatch" tabindex="-1" role="dialog" aria-labelledby="confirmBatch" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h6 class="modal-title text-dark">Batch Items</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-success text-center"> <h6>Are you sure you want to batch these records? </h6></div>
                    <hr />
                    <div class="mb-3 col-md-12">
                        <label for="transactionDate" class="form-label text-dark">Transaction Date <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                        <input type="text" required name="transactionDate" class="form-control" id="date_from" placeholder="DD-MM-YY">
                        @error('transactionDate')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="description" class="form-label text-dark">Description/Comment <span class="text-danger" title="This most be filled."><b>*</b></span></label>
                        <textarea class="form-control" required name="description">{{ (isset($editRecord) && $editRecord) ? $editRecord->productDescription : old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline btn-primary btn-sm" data-dismiss="modal"> Cancel </button>
                    <button type="submit" class="btn btn-outline btn-success btn-sm"> Batch Items </button>
                </div>
            </div>
        </div>
    </div>
</form>
<!--end Modal-->

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
        $('.searchSelect').selectize({
          sortField: 'text'
        });
    });

    //Get Product Measurements
    $(document).ready(function () {
        var getPID = $('#getProduct').val();
        var getPM = "{{isset($editRecord) && $editRecord ? $editRecord->measurementID : ''}}";
        getProductMeasure(getPID);
        $('#getProduct').change(function() {
            var productID = (getPID != '' ? getPID : $('#getProduct').val());
            getProductMeasure(productID);
        });
        //fetch
        function getProductMeasure(productID)
        {
            $.ajax({
                url: '{{url("/")}}' +  '/get-product-measurement/' + productID,
                type: 'get',
                //data: {'classID': classID, '_token': $('input[name=_token]').val()},
                data: { format: 'json' },
                dataType: 'json',
                success: function(data) {
                    $('#productMeasurement').empty();
                    $('#productMeasurement').append($('<option>').text(" Select Measurement ").attr('value',""));
                    $.each(data, function(model, list) {
                        if(list.measurementID == getPM)
                        {
                            $('#productMeasurement').append($('<option>').text(list.description).attr('value', list.measurementID).attr('selected', true));
                        }else{
                            $('#productMeasurement').append($('<option>').text(list.description).attr('value', list.measurementID));
                        }
                    });
                },
                error: function(error) {
                    alert("Please we are having issue getting product measurement. Check your network or refresh this page !!!");
                }
            });
        }
    });

    //Get Shelf from storeID
    $(document).ready(function () {
        var sID = $('#getStore').val();
        var getRID = "{{isset($editRecord) && $editRecord ? $editRecord->shelfID : ''}}";
        getShelves(sID);
        $('#getStore').change(function() {
            var storeID = $('#getStore').val();
            getShelves(storeID);
        });
        function getShelves(storeID)
        {
            $.ajax({
                url: '{{url("/")}}' +  '/get-shelf-from-store/' + storeID,
                type: 'get',
                data: { format: 'json' },
                dataType: 'json',
                success: function(data) {
                    $('#shelfInStore').empty();
                    $('#shelfInStore').append($('<option>').text(" Select ").attr('value',""));
                    $.each(data, function(model, list) {
                        if(list.id == getRID)
                        {
                            $('#shelfInStore').append($("<option>").text(list.shelve_name).attr('value', list.id).attr('selected', true));
                        }else{
                            $('#shelfInStore').append($("<option>").text(list.shelve_name).attr('value', list.id));
                        }
                    });
                },
                error: function(error) {
                    alert("Please we are having issue getting your shelf. Check your network or refresh this page !!!");
                }
            });
        }
    });

</script>
@endsection
