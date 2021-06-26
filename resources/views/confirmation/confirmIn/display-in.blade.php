@extends('layouts.app')

@section('style')
@endsection
@section('content')
    @include('share.operationCallBackAlert', ['showAlert' => 1])
<div class="row justify-content-center" >
    <div class="col-md-12"> @include('share.operationCallBackAlert', ['showAlert' => 1])</div>
</div>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Transactions movement-In</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="QA_section">
                    <div class="QA_table mb-0 transaction-table">
                        <!-- table-responsive -->
                        <div class="table-responsive">
                            <table class="table lms_table_active2  ">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th scope="col">S/N</th>
                                            <th> Order Number </th>
                                            <th> Description </th>
                                            <th scope="col"> Store/Warehouse </th>
                                            <th scope="col"> Action Status</th>
                                            <th scope="col"> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    @foreach($fetchDataProductIn as $key => $list)
                                        <tr>
                                            <td scope="row">{{$key+1}}</td>
                                            <td>{{$list->orderNo}} </td>
                                            <td>{{$list->description}}</td>
                                            <td>{{$list->store_name}} </td>
                                            <td> 
                                                @php
                                                $arr   =   array();
                                                $getAllData = App\Models\ProductMovement::where('product_movements.orderNo', '=', $list->orderNo)->where('product_movements.move_in', '>', '0')->get();
                                                $countData = App\Models\ProductMovement::where('product_movements.orderNo', '=', $list->orderNo)->where('product_movements.move_in', '>', '0')
                                                ->where('product_movements.move_out', '=', '0')->count();
                                                //dd($getAllData);
                                                foreach ($getAllData as $key => $value) {
                                                    # code...
                                                    if ($value->isConfirmed != 0) {
                                                        # code...
                                                        array_push($arr, '1');
                                                    }
                                                }
                                                
                                                $countArr   =   count($arr);
                                                if ($countArr == 0) {
                                                    # code...
                                                    print "<span class = 'text-danger'> Pending! </span>";
                                                }elseif ($countArr == $countData) {
                                                    # code...
                                                    print "<span class = 'text-success'> Complete! </span>";
                                                } else {
                                                    # code...
                                                    print "<span class='text-warning'> Partial! </span>";
                                                }
                                                @endphp
                                            </td>
                                            
                                            <td>
                                                <a href="{{route('process-confirm-in', ['id' => encrypt($list->orderNo)])}}" class="btn btn-outline-success btn-sm" >
                                                <i class="ti-hand-point-right"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                        
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-right" id="rejectBtn">
                    
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
@section('modal')
              <!-- Edit multi measurement quantity -->
              <div class="modal fade" id="addMultipleComment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Comment</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                          <div class="mb-3 col-md-12"> 
                              <label for="quantity" class="form-label text-dark">Reason: <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                              <textarea class="form-control" id="reason" rows="3"></textarea>
                              @error('measurement_quantity')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
  
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary" id='btn-submit'>Save changes</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Edit single measurement quantity -->
              <div class="modal fade singleComment" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg ">
                  <div class="modal-content p-4">
                    <div class="mb-3">
                        <h5 class="modal-title" id="exampleModalLongTitle"><u>Create Comment</u></h5>
                    </div>
                    <form action="{{route('process-confirm-in-single-rejection')}}" method="post">
                        @csrf
                        <input type="hidden" name="item_id" id="singleItemId">
                        <div class="col-sm-8 mb-3">
                            <input type="text" class="form-control" name="product_name" id="singleItemName" readonly>
                        </div>
                        <div class="mb-3 col-md-8"> 
                            <label for="quantity" class="form-label text-dark">Reason: <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                            <textarea class="form-control mb-3" name="comment" rows="3"></textarea>
                            @error('comment')
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                             </span>
                            @enderror
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        
                        
                    </form>
                  </div>
                </div>
              </div>
@endsection
@section('script')
    <script>
        function getItemSingleId(data1, data2) {
            $('#singleItemId').val(data1);
            $('#singleItemName').val(data2);
        }
        $(':checkbox').change(function() {
        var checked = $("#tblCheck input[type=checkbox]:checked").length;
        if (checked > 1) {
            //alert(checked + " CheckBoxe(s) are checked.");
            $('#rejectBtn').html('<button class="btn btn-success mt-3" type="button" onclick="productDetail()" data-toggle="modal" data-target="#addMultipleComment">Reject</button>');
            for (i = 1; i <= {{count($fetchDataProductIn)}}; i++) {
            $('#mainReject'+i).prop("disabled", true);
            }
            return true;
        }else{
            $('#rejectBtn').empty();
            for (i = 1; i <= {{count($fetchDataProductIn)}}; i++) {
            $('#mainReject'+i).prop("disabled", false);
            }
        }
    });
    </script>
    <script>
        $('#btn-submit').on('click',
            function () {
                $.each($("input[name = 'item_id']:checked"), function () {
                    //alert();
                    var item    =   $(this).val();
                    var comment =   $('#reason').val();
                    //alert(item+comment);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{route("process-confirm-in-multi-rejection")}}',
                        method: 'post',
                        data: {
                            item_id : item, comment: comment
                        },
                        success: function(data) {
                            window.location.reload(1);
                            $('#alert-msg').append(data.success);
                            console.log(data);
                        }
                    });

                });
            }
        )
        
    </script>
@endsection