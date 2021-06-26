@extends('layouts.app')
@section('pageHeaderTitle', 'Quantity Adjustment')

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

                    <form action="{{ Route::has('quantityControl') ? Route('quantityControl') : '#' }}" method="post" enctype="multipart/form-data">
                    @csrf
                            <input type="hidden" name="getRecord" value="{{ (isset($editSelected) && $editSelected) ? $editSelected->id : old('getRecord') }}">

                            <div class="row mb-2">
                                <div class="mb-3 col-md-4">
                                    <label for="store" class="form-label text-dark">Store<span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <select required class="form-control searchSelect" name="store">
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
                                    @error('store')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="mb-3 col-md-4">
                                    <label for="type" class="form-label text-dark">Movement type<span class="text-danger" title="This must be filled."><b>*</b></span> </label>
                                    <select required class="form-control searchSelect" name="type" id="getType" placeholder="Movement type">
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
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="product" class="form-label text-dark">Product<span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <select required class="form-control searchSelect" name="product" id="getProduct" placeholder="Pick a product">
                                        <option value="">Select Product</option>
                                        @if(isset($getProduct) && $getProduct)
                                            @foreach($getProduct as $key => $value)
                                               <!-- <option value="{{$value->id}}" {{ (isset($editRecord) && $editRecord) && ($editRecord->productID == $value->id) ? 'selected' : '' }}>{{ $value->productName }}</option> -->
                                               <option value="{{$value->id}}"
                                               @if(old('product')==$value->id) 
                                               selected="selected" 
                                               @endif>{{ $value->productName }}</option>
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
                            <div class="mb-3 col-md-4">
                                    <label for="product" class="form-label text-dark">Reasons<span class="text-danger" title="This must be filled."><b>*</b></span> </label>
                                    <select required class="form-control" name="reason" id="reasons" placeholder="Pick a Reason">
                                    <option value="">Select reason</option>
                                    </select>
                                    @error('reason')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="measure" class="form-label text-dark">Product measure <span class="text-danger" title="This must be filled."><b>*</b></span> </label>
                                    <select required class="form-control" id="productMeasurement" name="measure">
                                        <option value="">Select measurement</option>
                                        @if(isset($editRecord) && $editRecord)
                                            <option value="{{ $editRecord->measurementID }}" selected>{{ $editRecord->measureName }} </option>
                                        @endif
                                    </select>
                                    @error('measure')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                 <div class="mb-3 col-md-4">
                                    <label for="quantity" class="form-label text-dark">Quantity <span class="text-danger" title="This must be filled."><b>*</b></span> </label>
                                    <!--<input type="number" id="quantity" required maxlength="100" class="form-control" name="quantity" value="{{ (isset($editRecord) && $editRecord) ? $editRecord->quantity : '' }}"> -->
                                    <input type="number" id="quantity" required maxlength="100" min="1" class="form-control" name="quantity" value="{{old('quantity')}}"
                                    @error('quantity')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div align="center" class="mb-3 col-md-12">

                                        <button type="submit" name="submit" class="btn btn-outline-success">Update Records</button>

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
                                    <th> PRODUCT </th>
                                    <th>REASON</th>
                                    <th> MEASURE </th>
                                    <th> QTY </th>
                                    <th colspan="2"> </th>
                                </tr>
                                @if(isset($getRecords) && $getRecords)
                                    @foreach($getRecords as $key => $value)
                                        <tr>
                                            <td> {{ ($key + 1) }} </td>
                                            <td> {{ strtoupper($value->store_name) }} </td>
                                            <td> {{ $value->productName }} </td>
                                            <td> {{ $value->reason }} </td>
                                            <td> {{ $value->measureName }} </td>
                                            <td> {{ $value->productQuantity }} </td>
                                            <td> <a href="#"  data-toggle="modal"  data-target="#editC" class="btn btn-outline-success btn-sm handle" title="Edit Record"
                                            data-quantity="{{ $value->productQuantity }}"
                                            data-id="{{$value->recordID }}"> <i class="fa fa-edit"></i>  </a></td>
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
                                                        <a href="{{ Route::has('deleteQC') ? Route('deleteQC', ['rid'=>$value->recordID]) : 'javascript:;'  }}" class="btn btn-outline-warning"> Delete </a>
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
                <!-- Modal - confirm to edit -->
                <div class="modal text-left " id="editC" tabindex="-1" role="dialog"  >
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning">
                                                        <h6 class="modal-title text-dark">Edit Quantity</h6>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ Route::has('editQuantity') ? Route('editQuantity') : '#' }}" method="post" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" id="id">
                                                                    <div class="mb-3 col-md-10">
                                                                        <label for="quantity" class="form-label text-dark">Quantity <span class="text-danger" title="This must be filled."><b>*</b></span> </label>
                                                                        <input type="number" id="quantityNew" required maxlength="100" class="form-control" name="quantity">
                                                                    </div>


                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div align="center" class="mb-3 col-md-12">

                                                                            <button type="submit" name="submit" class="btn btn-outline-success">Update Records</button>

                                                                    </div>
                                                                </div>

                                                        </form>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-default" data-dismiss="modal"> Cancel </button>
                                                        <a href="{{ Route::has('deleteQC') ? Route('deleteQC', ['rid'=>$value->recordID]) : 'javascript:;'  }}" class="btn btn-outline-warning"> Delete </a>
                                                    </div>
                                                </div>
                                            </div>
                </div>
                <!--end Modal-->
<!-- Modal - confirm to Batch -->
<form action="{{ Route::has('batchQC') ? Route('batchQC') : '#' }}" method="post" enctype="multipart/form-data">
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
                                    <label for="reason" class="form-label text-dark">Reason For Adjustment<span class="text-danger" title="This must be filled."><b>*</b></span> </label>
                                    <input type="text" class="form-control" name="reason">
                    </div>
                    <div class="mb-3 col-md-12">
                                            <label for="dateFrom" class="form-label text-dark">transactionDate</label>
                                            <input type="text" name="transactionDate" class="form-control" autocomplete="off" id="date_from"  value="{{ old('transactionDate') }}" placeholder="DD-MM-YY">

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
     <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" rel="stylesheet" />
     <link rel="stylesheet" href="{{asset('assets/css/datepicker.min.css')}}"/>
@endsection

@section('script')
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>
<script>

   $(document).ready(function () {
        $('input[id$=date_from]').datepicker({
            dateFormat: 'dd-mm-yy',
            maxDate: 0			
        });
  });
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
                //data: {'classID': classID, '_token': $('input[name=_token]').val()},
                data: { format: 'json' },
                dataType: 'json',
                success: function(data) {
                    $('#productMeasurement').empty();
                    $('#productMeasurement').append($('<option>').text(" Select Measurement ").attr('value',""));
                    $.each(data, function(model, list) {
                        $('#productMeasurement').append($('<option>').text(list.description+'['+list.quantity+']').attr('value', list.measurementID));
                    });
                },
                error: function(error) {
                    alert("Please we are having issue getting product measurement. Check your network or refresh this page !!!");
                }
            });
        });
    });

</script>
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
var type= $('#getType').val()
    var reason = "{{old('reason')}}"
    var productID = "{{old('product')}}"
    var measureID ="{{old('measure')}}"
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
                        if(list.measurementID== measureID){
                            $('#productMeasurement').append($('<option selected>').text(list.description).attr('value', list.measurementID));
                        }
                        else{
                        $('#productMeasurement').append($('<option>').text(list.description+'['+list.quantity+']').attr('value', list.measurementID));
                    }
                    });
                },
                error: function(error) {
                    alert("Please we are having issue getting product measurement. Check your network or refresh this page !!!");
                }
            });
    $.ajax({
        url: "\get-reasons",
        type:"POST",
        data:{
          'type': type,
          "_token": "{{ csrf_token() }}"
        },
        dataType: 'json',
        success:function(response){
            $("#reasons").empty();
            $("#reasons").append("<option value=''>Select Reason</option>")
           $.each(response,function(key, variable){
               if(variable.reasonID== reason){
                $("#reasons").append("<option value='"+variable.reasonID+"' selected>"+variable.description+"</option>")
               }
               else{
            $("#reasons").append("<option value='"+variable.reasonID+"'>"+variable.description+"</option>") }
           })

        },
        error : function(jqXHR, textStatus, errorThrown){
            alert(errorThrown)
        }
       });
$('.handle').on('click',function(){
            var id = $(this).attr('data-id');
            
            var quantity = $(this).attr('data-quantity');
            $('#quantityNew').val(quantity);
            $('#id').val(id);
})
$('#getType').on('change',function(){
    var type= $(this).val()
    $.ajax({
        url: "\get-reasons",
        type:"POST",
        data:{
          'type': type,
          "_token": "{{ csrf_token() }}"
        },
        dataType: 'json',
        success:function(response){
            $("#reasons").empty();
            $("#reasons").append("<option value=''>Select Reason</option>")
           $.each(response,function(key, variable){
            $("#reasons").append("<option value='"+variable.reasonID+"'>"+variable.description+"</option>")
           })

        },
        error : function(jqXHR, textStatus, errorThrown){
            alert(errorThrown)
        }
       });
  });
</script>
@endsection





