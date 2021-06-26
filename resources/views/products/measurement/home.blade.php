@extends('layouts.app')
@section('pageHeaderTitle', 'Create Product Measurement')

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
                    <div class="">

                        @includeIf('share.operationCallBackAlert', ['showAlert' => 1])

                        <form action="{{ Route::has('saveMeasurement') ? Route('saveMeasurement') : '#' }}" method="post" enctype="multipart/form-data">
                        @csrf
                            <div class="mb-3 col-md-6 offset-md-3"> 
                                <label for="measurementName" class="form-label text-dark">Enter measurement Name <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                                <input type="text" required maxlength="190" autofocus class="form-control" name="measurementName" value="{{ (isset($editRecord) && $editRecord) ? $editRecord->description : old('measurementName') }}"> <!-- aria-describedby="description" -->
                                <input type="hidden" name="getRecord" value="{{ (isset($editRecord) && $editRecord) ? $editRecord->id : old('getRecord') }}"> 
                                @error('measurementName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div align="center" class="mb-3 col-md-12">
                                @if(isset($editRecord) && $editRecord)
                                    <button type="submit" name="submit" class="btn btn-outline-primary">Update</button>
                                @else
                                    <button type="submit" name="submit" class="btn btn-outline-success">Save</button>
                                @endif
                                
                            </div>
                        </form>
                        
                    </div>
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
                                    <th> DESCRIPTION </th>
                                    <th> CREATED ON </th>
                                    <th colspan="2"> </th>
                                </tr>
                                @if(isset($getRecords) && $getRecords)
                                    @foreach($getRecords as $key => $value)
                                        <tr>
                                            <td> {{ ($key + 1) }} </td>
                                            <td> {{ $value->description }} </td>
                                            <td> {{ date('d-m-Y', strtotime($value->created_at)) }} </td>
                                            <td> <a href="{{ Route::has('editMeasurement') ? Route('editMeasurement', ['rid'=>$value->id]) : 'javascript:;'  }}" class="btn btn-outline-success btn-sm" title="Edit Record"> <i class="fa fa-edit"></i>  </a></td>
                                            <td> <button type="button" name="submit" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-backdrop="false" data-target="#confirmToDelete{{$key}}" title="Delete Record"> <i class="fa fa-trash"></i>  </button></td>
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
                                                        <a href="{{ Route::has('deleteMeasurement') ? Route('deleteMeasurement', ['rid'=>$value->id]) : 'javascript:;'  }}" type="submit" class="btn btn-outline-warning"> Delete </a>
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
