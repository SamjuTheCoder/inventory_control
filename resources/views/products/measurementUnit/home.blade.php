@extends('layouts.app')
@section('pageHeaderTitle', 'Product Measurement Unit Set-Up')

@section('content')
<div class="row justify-content-center">

    <div class="col-md-12">
        <div class="white_card card_height_100 mb_20">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0 text-uppercase"><span class="fa fa-measure"></span> @yield('pageHeaderTitle')</h3>
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

                    <form action="{{ Route::has('saveMeasurementUnit') ? Route('saveMeasurementUnit') : '#' }}" method="post" enctype="multipart/form-data">
                    @csrf
                            <input type="hidden" name="getRecord" value="{{ (isset($editRecord) && $editRecord) ? $editRecord->recordID : old('getRecord') }}"> 
                            <div class="row">

                                <div class="mb-3 col-md-5"> 
                                    <label for="product" class="form-label text-dark">Product<span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <select required class="form-control" name="product">
                                        <option value="">Select Product</option>
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
                                    <label for="measurementName" class="form-label text-dark">Measure <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <select required class="form-control" name="measurementName">
                                        <option value="">Select Measurement</option>
                                        @if(isset($getMeasurement) && $getMeasurement)
                                            @foreach($getMeasurement as $key => $value)
                                                <option value="{{$value->id}}" {{ (isset($editRecord) && $editRecord) && ($editRecord->measurementID == $value->id) ? 'selected' : (old('measurementName') == $value->id ? 'selected' : '') }}>{{ $value->description }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('measurementName')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-3"> 
                                    <label for="quantity" class="form-label text-dark">Quantity <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                    <input type="number" required maxlength="10" class="form-control" name="quantity" value="{{ (isset($editRecord) && $editRecord) ? $editRecord->quantity : old('quantity') }}">
                                    @error('quantity')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div align="center" class="mb-3 col-md-12">
                                    @if((isset($editRecord) && $editRecord))
                                        <button type="submit" name="submit" class="btn btn-outline-success">Update</button>
                                    @else
                                        <button type="submit" name="submit" class="btn btn-outline-success">Save</button>
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
                            
                            <table class="table table-bordered table-responsive table-hover ">
                                <tr class="bg-light text-dark">
                                    <th> SN </th>
                                    <th> PRODUCT NAME </th>
                                    <th> BARCODE </th>
                                    <th> DESCRIPTION </th>
                                    <th> QUANTITY </th>
                                    <th> CREATED ON </th>
                                    <th colspan="2"> </th>
                                </tr>
                                @if(isset($getRecords) && $getRecords)
                                    @foreach($getRecords as $key => $value)
                                        <tr>
                                            <td> {{ ($key + 1) }} </td>
                                            <td> {{ strtoupper($value->productName) }} </td>
                                            <td> {{ $value->barcode }} </td>
                                            <td> {{ $value->description }} </td>
                                            <td> {{ $value->quantity }} </td>
                                            <td> {{ date('d-m-Y', strtotime($value->dateCreated)) }} </td>
                                            <td> <a href="{{ Route::has('editMeasurementUnit') ? Route('editMeasurementUnit', ['rid'=>$value->recordID]) : 'javascript:;'  }}" class="btn btn-outline-success btn-sm" title="Edit Record"> <i class="fa fa-edit"></i> </a></td>
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
                                                        <div class="text-dark text-center">  {{ $value->description }} </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-default" data-dismiss="modal"> Cancel </button>
                                                        <a href="{{ Route::has('deleteMeasurementUnit') ? Route('deleteMeasurementUnit', ['rid'=>$value->recordID]) : 'javascript:;'  }}" type="submit" class="btn btn-outline-warning"> Delete </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end Modal-->


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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<script>
    //select field with search
    $(document).ready(function () {
      $('select').selectize({
          sortField: 'text'
      });
    });
</script>
@endsection