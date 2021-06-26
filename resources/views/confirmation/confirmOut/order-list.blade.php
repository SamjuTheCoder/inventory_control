@extends('layouts.app')

@section('content')
<nav aria-label="breadcrumb" class="">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('display-confirm-out')}}">Transactions-out</a></li>
      <li class="breadcrumb-item active" aria-current="page">Order</li>
    </ol>
  </nav>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div id="alert-msg">
            @if (Session('approvalSuccess'))
                <div class="alert alert-success">
                    {{Session::get('approvalSuccess')}}
                </div>
            @endif
        </div>
    </div>
    
    <div class="col-md-12">
        @includeIf('share.operationCallBackAlert', ['showAlert' => 1])
       </div>
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <!-- Table -->
            <div class="white_card_header ">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Orders </h3>
                    </div>
                    <a href="#">
                        <p></p>
                    </a>
                </div>
            </div>
            
            <div class="white_card_body pt-0">
                <form action="{{route('process-confirm-in-multi-rejection')}}" method="post">
                    @csrf
                            <div class="QA_section">
                                <div class="QA_table mb-0 transaction-table">
                                    <!-- table-responsive -->
                                    @if(!empty($product))
                                    @if($product->move_out != 0)
                                    <h6>Destination: {{$product->location}}</h6>
                                    @endif
                                    @endif
                                    <h6>Description:  {{$product->description ?? ''}} </h6>
                                    <h6>Order Number: {{$product->orderNo ?? ''}}</h6>
                                    <h6>Warehouser:   {{$product->store_name ?? ''}}</h6>
                                    <h6>Date:   {{date('d-m-Y', strtotime($product->transactionDate))?? ''}}</h6>
                                    <div class="table-responsive">
                                        <table class="table lms_table_active2 ">
                                                <thead class="bg-dark text-white">
                                                    <tr>
                                                        <th scope="col">S/N</th>
                                                        <th> Product Name</th>
                                                        <th> Quantity</th>
                                                        <th> <input type="checkbox" class="form-check-input" id="checkAllBox">Status </th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tblCheck">
                                                @foreach($orders as $key => $list)
                                                
                                                    <tr>
                                                        <td scope="row">{{$key+1}}</td>
                                                        <td>{{$list->productName}} </td>
                                                        <td>{{$list->move_out}} [ <span style="color:green">{{ $list->formatqty}}</span>] </td>
                                                        <td>
                                                            <div class="form-group form-check">
                                                                
                                                                @if ($list->isConfirmed == '2')
                                                                <div class="" id="singleCheck">
                                                                    <input type="checkbox" class="form-check-input"  name="item_id[]" value="{{$list->prodMovementID}}">
                                                                </div>
                                                                <a href="#" class="badge badge-danger">Disapproved</a>
                                                                @elseif($list->isConfirmed == '1')
                                                                <div class="" id="singleCheck">
                                                                    <input type="checkbox" class="form-check-input"  name="item_id[]" value="{{$list->prodMovementID}}">
                                                                </div>
                                                                <a href="#" class="badge badge-success">Approved</a>
                                                                @else
                                                                <div class="" id="singleCheck">
                                                                    <input type="checkbox" class="form-check-input"  name="item_id[]" value="{{$list->prodMovementID}}">
                                                                </div>
                                                                
                                                                <a href="#" class="badge badge-warning">pending</a>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                @if ($list->isConfirmed == '2')
                                                                <a href="{{route('post-process-confirm-in', ['id' => encrypt($list->prodMovementID)])}}" class="btn btn-outline-success mr-md-1"><i class="ti-thumb-up"></i></a>
                                                                @elseif($list->isConfirmed == '1')
                                                                <button type="button" class="btn btn-outline-danger" id="mainReject{{$key+1}}"  onclick="getItemSingleId('{{$list->prodMovementID}}', '{{$list->prodName}}')" data-toggle="modal" data-target=".singleComment"><i class="ti-thumb-down"></i></button>
                                                                @else
                                                                <a href="{{route('post-process-confirm-in', ['id' => encrypt($list->prodMovementID)])}}" class="btn btn-outline-success mr-md-1"><i class="ti-thumb-up"></i></a>
                                                                <button type="button" class="btn btn-outline-danger" id="mainReject{{$key+1}}"  onclick="getItemSingleId('{{$list->prodMovementID}}', '{{$list->prodName}}')" data-toggle="modal" data-target=".singleComment"><i class="ti-thumb-down"></i></button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                    </div>
                                <div class="col-md-12 text-right" id="rejectBtn">
                                </div>
                            </div>
                            <div class="col-md-12 text-right" id="rejectBtn">
                        </div>
            <!-- Table -->
        </div>
    </div>
</div>
                <!-- /// Edit Shelve Modal -->
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
                        <div id="attachItemId"></div>
                          <div class="mb-3 col-md-12"> 
                              <label for="quantity" class="form-label text-dark">Reason: <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                              <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
                              @error('comment')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
  
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Reject</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
              <!-- Approve product -->
              <div class="modal fade" id="approveProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Approve Items</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <div id="attachItemId"></div>
                          You Do you want to approve this items?
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                      <button type="button" class="btn btn-primary" id='btn-approve'>Yes</button>
                    </div>
                  </div>
                </div>
              </div>
            

              <!-- Edit single measurement quantity -->
              <div class="modal fade singleComment" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md ">
                  <div class="modal-content p-4">
                    <div class="mb-3">
                        <h5 class="modal-title" id="exampleModalLongTitle"><u>Create Comment</u></h5>
                    </div>
                    <form action="{{route('process-confirm-in-single-rejection')}}" method="post">
                        @csrf
                        <input type="hidden" name="item_id" id="singleItemId">
                        <div class="col-sm-12 mb-3">
                            <input type="text" class="form-control" name="product_name" id="singleItemName" readonly>
                        </div>
                        <div class="mb-3 col-md-12"> 
                            <label for="quantity" class="form-label text-dark">Reason: <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                            <textarea class="form-control mb-3" name="comment" rows="3"></textarea>
                            @error('comment')
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                             </span>
                            @enderror
                            <button type="submit" class="btn btn-primary">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        $('#checkAllBox').click(function () {
            $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
        });
        $(':checkbox').change(function() {
        var checked = $("#tblCheck input[type=checkbox]:checked").length;
        if (checked > 1) {
            //alert(checked + " CheckBoxe(s) are checked.");
            $('#rejectBtn').html('<div class="btn-group" role="group"><button class="btn btn-success mt-3 mr-1" type="button" data-toggle="modal" data-target="#approveProduct">Approve Transaction</button><button class="btn btn-danger mt-3" type="button" data-toggle="modal" data-target="#addMultipleComment">Disapprove Transaction</button></div>');
            for (i = 1; i <= {{count($orders)}}; i++) {
            $('#mainReject'+i).prop("disabled", true);
            }
            return true;
        }else{
            $('#rejectBtn').empty();
            for (i = 1; i <= {{count($orders)}}; i++) {
            $('#mainReject'+i).prop("disabled", false);
            }
        }
    });
    </script>
    <script>
        $('#btn-approve').on('click',
            function () {
                const docsArray = [];
                $.each($("input[name = 'item_id[]']:checked"), function () {
                    //alert();
                    var item    =   $(this).val();
                    docsArray.push(item);
                });
                
                //alert(item);
                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{route("process-confirm-in-multi-approval")}}',
                        method: 'post',
                        data: {dataToUpdate:docsArray},
                        success: function(data) {
                            
                            //$('#alert-msg').append(data.success);
                            console.log(data);

                            if (data.success) {
                                
                                location.reload();
                            }
                            //document.getElementById('message').style.display = 'block';
                        }
                    });
            }
        )
        
    </script>
@endsection