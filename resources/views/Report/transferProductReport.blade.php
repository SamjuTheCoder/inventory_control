@extends('layouts.app')
@section('pageHeaderTitle', 'Product Transferred Report')

@section('content')
<div class="row justify-content-center">

    <div class="col-md-12 d-print-none">
        <div class="white_card card_height_100 mb_20">
            <div class="white_card_header">
                <div class="row box_header m-0">
                    <div class="col-md-8 main-title">
                        <h3 class="m-0"><span class="fa fa-measure"></span> @yield('pageHeaderTitle')</h3>
                    </div>
                   <!--  <div class="col-md-4">
                        <div class="mt-1">
                            <form id="formQueryRecord" action="{{ Route::has('changeQueryReport') ? Route('changeQueryReport') : '#' }}" method="post" enctype="multipart/form-data">
                            @csrf
                                <select id="statusCode" class="form-control" name="statusCode"> {{-- calss="searchSelect" --}}
                                    <option value="" selected>Filter</option>
                                    <option value="">All records</option>
                                    <option value="1" {{ (isset($status) && $status == 1 ? 'selected' : '') }}>Accepted</option>
                                    <option value="2" {{ (isset($status) && $status == 2 ? 'selected' : '') }}>Rejected</option>
                                    <option value="pending" {{ (isset($status) && $status == 3 ? 'selected' : '') }}>Pending</option>
                                </select>
                                @error('statusCode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </form>
                        </div>
                    </div> -->
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
                            <form action="{{ Route::has('postTransaferReport') ? Route('postTransaferReport') : '#' }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <table class="table table-bordered table-responsive table-condensed table-hover">
                                <tr class="bg-light text-dark">
                                    <th> SN </th>
                                    <th> Category </th>
                                    <th> Source store </th>
                                    <th> Destination <br /> store </th>
                                    <th> Order No. </th>
                                    <th> Date </th>
                                    <th> Description </th>
                                    <!-- <th> Status </th> -->
                                    <th class="d-print-none"></th>
                                </tr>
                                @if(isset($transferredProduct) && $transferredProduct)
                                    @foreach($transferredProduct as $key => $value)
                                        <tr>
                                            <td> {{ ($transferredProduct->currentpage()-1) * $transferredProduct->perpage() + (1+$key ++) }} </td>
                                            <td> {{ $value->categoryTitle }} </td>
                                            <td> {{ $value->store_name }} </td>
                                            <td> {{ $value->destinationStoreName }} </td>
                                            <td> {{ $value->orderNo }} </td>
                                            <td> {{ ($value->transactionDate ? date('d-m-Y', strtotime($value->transactionDate)) : '') }} </td>
                                            <td> {{ $value->productDescription }} </td>
                                            <!-- <td> {!! $value->itemStatus !!} </td> -->
                                            <th class="d-print-none">
                                                <a href="{{Route::has('viewTransaferReportDetails') ? Route('viewTransaferReportDetails', ['orderNo'=>$value->orderNo]) : 'javascript:;' }}" title="View Details" class="btn btn-sm btn-outline-grey"><i class="fa fa-file"></i> Details </a>
                                            </th>
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
                                                {{-- <div class="mb-3 col-md-12">
                                                    <label for="destinationStore" class="form-label text-dark">Destination Store <i class="text-danger" title="This most be filled."><b>*</b></i> </label>
                                                    <select required class="form-control searchSelect" name="destinationStore">
                                                        <option value="">Select</option>
                                                        @if(isset($getDestinationStore) && $getDestinationStore)
                                                            @foreach($getDestinationStore as $key => $value)
                                                                <option value="{{$value->id}}" {{ old('destinationStore') == $value->id ? 'selected' : '' }}>{{ $value->store_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @error('destinationStore')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-12">
                                                    <label for="transactionDate" class="form-label text-dark">Transaction Date <i class="text-danger" title="This most be filled."><b>*</b></i> </label>
                                                    <input type="text" required name="transactionDate" class="form-control" id="date_from"  value="{{ old('transactionDate') }}" placeholder="DD-MM-YY">
                                                    @error('transactionDate')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-12">
                                                    <label for="description" class="form-label text-dark">Description/Comment <i class="text-danger" title="This most be filled."><b>*</b></i></label>
                                                    <textarea class="form-control" required name="description">{{ old('description') }}</textarea>
                                                    @error('description')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div> --}}
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

    //Submit status code
    $(document).ready(function () {
        $('#statusCode').change(function() {
            $('#formQueryRecord').submit();
        });
    });

</script>
@endsection
