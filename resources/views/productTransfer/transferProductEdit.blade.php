@extends('layouts.app')
@section('pageHeaderTitle', 'Rejected Product')

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

                    <form action="{{ Route::has('postEditTransaferReport') ? Route('postEditTransaferReport') : '#' }}" method="post" enctype="multipart/form-data">
                    @csrf
                            <input type="hidden" name="getRecord" value="{{ (isset($editRecord) && $editRecord) ? $editRecord->recordID : old('getRecord') }}">

                            <div class="row mb-2">
                                <div class="mb-3 col-md-6">
                                    <label for="store" class="form-label text-dark">Store<span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <select required class="form-control searchSelect" name="store">
                                        <option value="">Select</option>
                                        @if(isset($getStore) && $getStore)
                                            @foreach($getStore as $key => $value)
                                                <option value="{{$value->id}}" {{ (isset($editRecord) && $editRecord) && ($editRecord->productStoreID == $value->id) ? 'selected' : (isset($store) && $store == $value->id ? 'selected' : (old('store') == $value->id ? 'selected' : '')) }}>{{ $value->store_name }}</option>
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
                                    <label for="product" class="form-label text-dark">Product<span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <select required class="form-control searchSelect" name="product" id="getProduct" placeholder="Pick a product">
                                        <option value="">Select Product</option>
                                        @if(isset($getProduct) && $getProduct)
                                            @foreach($getProduct as $key => $value)
                                                <option value="{{$value->id}}" {{ (isset($editRecord) && $editRecord) && ($editRecord->productID == $value->id) ? 'selected' : '' }}>{{ $value->productName }}</option>
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
                                    <label for="measure" class="form-label text-dark">Product Measure <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <select required class="form-control" id="productMeasurement" name="measure">
                                        <option value="">Select Measurement</option>
                                        @if(isset($editRecord) && $editRecord)
                                            <option value="{{ $editRecord->measurementID }}" selected>{{ $editRecord->measureName . '['.(isset($measureQuantiry) ? $measureQuantiry : 0).']' }} </option>
                                        @endif
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
                                        <button type="submit" name="submit" class="btn btn-outline-success">Update Record</button>
                                    @else
                                        {{-- <button type="submit" name="submit" class="btn btn-outline-success">Add Product (Transfer Out)</button> --}}
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
                            <form action="{{ Route::has('postTransaferReport') ? Route('postTransaferReport') : '#' }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <table class="table table-bordered table-responsive table-hover">
                                <tr class="bg-light text-dark">
                                    <th> SN </th>
                                    <th> STORE</th>
                                    <th> PRODUCT </th>
                                    <th> MEASURE </th>
                                    <th> QTY </th>
                                    <th> Order no. </th>
                                    <th colspan="3"> </th>
                                    <th class="d-print-none">
                                        <button type="button" title="Send Selected record" class="btn btn-sm btn-outline-success" data-toggle="modal" data-backdrop="false" data-target="#confirmAction"><i class="fa fa-save"></i> </button>
                                    </th>
                                </tr>
                                @if(isset($getRecords) && $getRecords)
                                    @foreach($getRecords as $key => $value)
                                        <tr>
                                            <td> {{ ($key + 1) }} </td>
                                            <td> {{ strtoupper($value->store_name) }} </td>
                                            <td> {{ $value->productName . ' ['. $value->recordID . ']' }} </td>
                                            <td> {{ $value->measureName }} </td>
                                            <td> {{ $value->productQuantity }} </td>
                                            <td> {{ $value->orderNo }} </td>
                                            <td> <a href="javascript:;" class="btn btn-outline-success btn-sm" title="View Comment" data-toggle="modal" data-backdrop="true" data-target="#viewComment{{$key}}"> <i class="fa fa-comment"></i>  </a></td>
                                            <td> <a href="{{ Route::has('editRejectedProductTransfer') ? Route('editRejectedProductTransfer', ['rid'=>$value->recordID]) : 'javascript:;'  }}" class="btn btn-outline-success btn-sm" title="Edit Record"> <i class="fa fa-edit"></i>  </a></td>
                                            <td> <button type="button" name="submit" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-backdrop="false" data-target="#confirmToDelete{{$key}}" title="Delete Record"> <i class="fa fa-trash"></i> </button></td>
                                            <td class="d-print-none">
                                                <div align="center">
                                                    <input type="checkbox" name="rejected[]" value="{{ $value->recordID }}" class="form-check-input mt-2" >
                                                </div>
                                            </td>
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
                                                        <a href="{{ Route::has('deleteProductTransfer') ? Route('deleteProductTransfer', ['rid'=>$value->recordID]) : 'javascript:;'  }}" class="btn btn-outline-warning"> Delete </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end Modal-->

                                         <!-- Modal - comment -->
                                         <div style="z-index: 9999999999;" class="modal fade text-left d-print-none" id="viewComment{{$key}}" tabindex="-1" role="dialog" aria-labelledby="viewComment{{$key}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-light">
                                                        <h6 class="modal-title text-dark"><i class="fa fa-comment"></i> Comment</h6>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-dark text-center">Comment for:  <b>{{ $value->productName }}</b> </div>
                                                        <hr />
                                                        <div class="text-dark" align="center" style="max-height: 250px; overflow-y: auto;"> 
                                                            @if(isset($allComments) && $allComments[$value->recordID])
                                                                @forelse($allComments[$value->recordID] as $sn=>$item)
                                                                    <div class="row">
                                                                        <div class="col-md-1 text-left">{{($sn + 1)}}</div>
                                                                        <div class="col-md-7 text-left">{{($item->comment ? $item->comment : '-')}}</div>
                                                                        <div class="col-md-4 text-left">{{date('d-m-Y', strtotime($item->rejected_date))}}</div>
                                                                    </div>
                                                                    <hr />
                                                                @empty
                                                                    <div class="row">
                                                                        <div class="col-md-12 text-warning text-center"> <i class="fa fa-comment"></i> No Comment found!</div>
                                                                    </div>
                                                                @endforelse
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-default" data-dismiss="modal"> Cancel </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end Modal-->

                                    @endforeach

                                @endif
                            </table>


                            <!-- Modal - confirm to take action -->
                            <div style="z-index: 9999999999;" class="modal fade text-left d-print-none" id="confirmAction" tabindex="-1" role="dialog" aria-labelledby="confirmAction" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-light">
                                                <h6 class="modal-title text-dark">Resend Product</h6>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-success text-center"> <h6 class="warning">Are you sure you want to resend these records? </h6></div>
                                                <br />
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline btn-primary btn-sm" data-dismiss="modal"> Cancel </button>
                                                <button type="submit" class="btn btn-outline btn-success btn-sm"> Resend Product </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!--end Modal-->



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

    //Get Product Measurements
    $(document).ready(function () {
        var productID = $('#getProduct').val(); 
        var productMeasurement = $('#productMeasurement').val();
        if(productID != '')
        {
            getProductMeasurement(productID, productMeasurement);
        }

        $('#getProduct').change(function() {
            var productID = $('#getProduct').val();
            getProductMeasurement(productID, null);
        });
        function getProductMeasurement(productID = null, productMeasurement = null)
        {
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
                        if(list.measurementID == productMeasurement)
                        {
                            $('#productMeasurement').append($('<option>').text(list.description +'['+ list.quantity +']').attr('value', list.measurementID).attr('selected', true));
                        }else{
                            $('#productMeasurement').append($('<option>').text(list.description +'['+ list.quantity +']').attr('value', list.measurementID));
                        }
                    });
                },
                error: function(error) {
                    alert("Please we are having issue getting product measurement. Check your network or refresh this page !!!");
                }
            });
        }
    });

</script>
@endsection
