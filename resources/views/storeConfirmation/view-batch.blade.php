@extends('layouts.app')
@section('pageHeaderTitle')
Movement-Confirmation
@endsection
@section('content')
<div class="row justify-content-center">

<!-- Edit single measurement quantity -->
<div class="modal fade singleComment" id="singleCommentx" tabindex="" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered ">
                  <div class="modal-content p-4">
                    <div class="mb-3">
                        <h5 class="modal-title" id="exampleModalLongTitle"><u>Create Comment</u></h5>
                    </div>
                    
                        @csrf
                        <input type="hidden" name="item_id" id="singleItemId">
                        <input type="hidden" name="item_id2" id="singleItemId2">
                        <input type="hidden" name="item_id3" id="singleItemId3">
                        <div class="mb-3 col-md-12"> 
                            <label for="quantity" class="form-label text-dark">Reason: <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                            <textarea class="form-control mb-3" id="comment" name="comment" rows="3" required></textarea>
                            @error('comment')
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                             </span>
                            @enderror
                            <button type="submit" class="btn btn-primary" id="btnClick">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    
                  </div>
                </div>
        </div>
    
    <!-- Edit single measurement quantity -->
<div class="modal fade" id="multipleComment" tabindex="" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered ">
                  <div class="modal-content p-4">
                    <div class="mb-3">
                        <h5 class="modal-title" id="exampleModalLongTitle"><u>Create Comment</u></h5>
                    </div>
                    
                        @csrf
                        <input type="hidden" name="item_id" id="singleItemIdx">
                        <div class="mb-3 col-md-12"> 
                            <label for="quantity" class="form-label text-dark">Reason: <span class="text-danger" title="This most be filled."><b>*</b></span> </label>
                            <textarea class="form-control mb-3" id="multiple_comment" name="comment" rows="3" required></textarea>
                            @error('comment')
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                             </span>
                            @enderror
                            <button type="submit" class="btn btn-primary" id="btnSaveMultiple">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    
                  </div>
                </div>
        </div>
        

    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Movement-Confirmation </h3>
                    </div>
                </div>
                @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <strong></strong> {{ session('success') }}</div>
                @endif
                @if(session('error_message'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <strong>Error!</strong> {{ session('error_message') }}</div>
                @endif
                
                @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Error!</strong> 
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                @endif
            </div>
            <div class="white_card_body">
              
            </div>
            <form id="formSubmit"> 
            @csrf
            <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered" id="tblCheck">
                      <thead>
                          <tr>
                              <th scope="col">#</th>
                              <th scope="col">Product</th>
                              <th scope="col">Quantity</th>
                              <th scope="col" style="padding-left:20px;"><input type="checkbox" class="form-check-input" id="checkAllBox" onClick="check_uncheck(this.checked);">Status</th>
                              <th scope="col">Action</th>
                          </tr>
                      </thead>
                      @if($details==null)  

                      @else
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($details as $key => $detail)
                            <tr>
                                <th scope="row">{{ $i++ }}</th>
                                <td>{{ $detail->productName }}</td>
                                <td>{{ $detail->move_in }} [ <span style="color:green">{{ $detail->formatqty }} </span>] </td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class="form-check-input" id="getProductChecked" name="item_id[]" value="{{ $detail->pid }}" data-toggle="tooltip" data-placement="bootom" title="Confirm">   
                                @if($detail->is_accepted==0)
                                <a href="#" class="badge badge-warning">Pending</a>
                                @elseif($detail->is_accepted==1)
                                <a href="#" class="badge badge-success">Confirmed</a>
                                @elseif($detail->is_accepted==2)
                               
                                <a href="#" class="badge badge-danger">Rejected</a>
                                <span class="badge badge-info" style="cursor:pointer" data-toggle="modal" data-target="#comments_page{{$detail->pid}}">view reason</span>
                                @endif
                                
                                </td>
                                <td> <div id="single_confirm_reject">
                                    @if($detail->is_accepted==0)
                                    <a href="{{ route('post-confirm-in', ['id' => encrypt($detail->pid)]) }}" class="btn btn-outline-success mr-md-1"><i class="ti-thumb-up"></i></a>
                                    <button type="button" class="btn btn-outline-danger" id="mainReject{{$key+1}}"  onclick="getItemSingleId('{{$detail->pid}}','{{$detail->productID}}','{{$detail->orderNo}}')" data-toggle="modal" data-target=".singleComment"><i class="ti-thumb-down"></i></button>
                                    @elseif($detail->is_accepted==1)
                                    <button type="button" class="btn btn-outline-danger" id="mainReject{{$key+1}}"  onclick="getItemSingleId('{{$detail->pid}}','{{$detail->productID}}','{{$detail->orderNo}}')" data-toggle="modal" data-target=".singleComment"><i class="ti-thumb-down"></i></button>
                                    @elseif($detail->is_accepted==2)
                                    <a href="{{ route('post-confirm-in', ['id' => encrypt($detail->pid)]) }}" class="btn btn-outline-success mr-md-1"><i class="ti-thumb-up"></i></a>
                                    
                                    @endif
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="comments_page{{$detail->pid}}" tabindex="" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered ">
                                    <div class="modal-content p-4">
                                        <div class="mb-3">
                                            <h5 class="modal-title" id="exampleModalLongTitle"><u>Comments</u></h5>
                                        </div>
                                        
                                           @php   
                                            $getComments = DB::table('rejected_comment')
                                            //->where('orderNo',$detail->orderNo)
                                            ->where('item_id',$detail->pid)
                                            ->leftjoin('products','rejected_comment.item_id','=','products.id')
                                            ->leftjoin('users','rejected_comment.rejected_by','=','users.id')
                                            ->get();  
                                           @endphp
                                           
                                        <div class="mb-3 col-md-12" >
                                            <div style="height: 250px; overflow-y: auto;">
                                            @foreach($getComments as $key => $comment)
                                            
                                                <span>Rejected By: {{ $comment->name }} <i style="color:green">|</i> Date: {{ $comment->rejected_date }}</span><br>
                                                <i class="fa fa-check"></i><span>{{ $comment->productName }}</span><br>
                                                <b>Reason:</b>
                                                <span>{{ $comment->comment}}</span>
                                            
                                            <hr>
                                            @endforeach   
                                            </div>     
                                        </div>

                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        
                                    </div>
                                    </div>
                            </div>
                        @endforeach
                        </tbody>
                    @endif
                  </table>
                </div>
                <input type="hidden" name="productID" class="form-control" id="productID" value="{{ $id }}">
                       
                <div> 
                                
                <div  id="actionStatus" style="display:none">
                    <button type="button" class="btn btn-success text-white" id="submitThisForm"> <i class="fa fa-check-circle"></i> Confirm</button> 
                        &nbsp;
                    <button type="button" class="btn btn-danger text-white" id="rejectMultiple"> <i class="fa fa-ban"></i> Reject</button> 
                </div>
                               
                </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
        

@endsection


@section('script')

<script>

    $(':checkbox').change(function() {
        var checked = $("#tblCheck input[type=checkbox]:checked").length;
        if (checked > 1) {
            $("#checkAllBox").css("display","block");
            $("#actionStatus").css("display","block");
            $("#single_confirm_reject").css("display:none");
        }else{
            $("#actionStatus").css("display","none");
            $("#checkAllBox").css("display","none");
        }
    });

    function check_uncheck(isChecked)
    {
        if(isChecked)
        {
            $("input[type=checkbox]").each(function() {
                this.checked = true;
                
            });
            $("#actionStatus").css("display","block");
        }
        else
        {
            $("input[type=checkbox]").each(function() {
                this.checked = false;
            });

            $("#actionStatus").css("display","none");
        }
    }
    
    $(document).ready(function () {
        $('#submitThisForm').click(function() {
             
            var productID = $("#productID").val();          
            const docsArray = [];

            $.each($("input[name = 'item_id[]']:checked"), function () {
                   // alert();
                var item    =   $(this).val();
                docsArray.push(item);
            });
            
            if (docsArray != false) {
            
            var x = confirm('Are you sure?');

            if(x==true) {
            
            $.ajax({
                url: '{{url("/")}}' +  '/post-confirm-batch',
                type: 'post',
                data: {productID:productID, dataToUpdate:docsArray, '_token': $('input[name=_token]').val()},
                    //data: { format: 'json' },
                    dataType: 'json',
                    success: function(result) {
                    
                    alert("Confirm successfull");
                    window.location.reload();
                   
                },
                error: function(error) {
                    alert("Confirm successfull");
                    window.location.reload();
                }
            });
          }  
        }else{
            alert("Please select record to confirm");
          }
        });
        
        $('#rejectMultiple').click(function() {
            
            var productID = $("#productID").val();          
            const docsArray = [];

            $.each($("input[name = 'item_id[]']:checked"), function () {
                   // alert();
                var item   =  $(this).val();
                docsArray.push(item);
            });
           

            $("#multipleComment").modal('show');
            
        });

        $('#btnSaveMultiple').click(function() {
            
            var comment = $("#multiple_comment").val();
            //alert("Please enter comment");

            if(comment=="")
            {
                alert("Please enter comment");
                exit();   
            }

            $("#multipleComment").modal('hide');

             var productID = $("#productID").val();          
             const docsArray = [];
 
             $.each($("input[name = 'item_id[]']:checked"), function () {
                    // alert();
                 var item    =   $(this).val();
                 docsArray.push(item);
             });
                           
             $.ajax({
                 url: '{{url("/")}}' +  '/post-reject-batch',
                 type: 'post',
                 data: {productID:productID, dataToUpdate:docsArray,comment:comment, '_token': $('input[name=_token]').val()},
                     //data: { format: 'json' },
                     dataType: 'json',
                     success: function(result) {
                     
                     alert("Reject successfull");
                     window.location.reload();
                    
                 },
                 error: function(error) {
                     alert("Reject successfull");
                     window.location.reload();
                 }
             });
         });
    });

</script>

<!--Single product approval and reject-->
<script>
function getItemSingleId(data1,data2,data3) {
    $('#singleItemId').val(data1);
    $('#singleItemId2').val(data2);
    $('#singleItemId3').val(data3);
}

    $(document).ready(function () {
        $('#btnClick').click(function() {

            var comment = $("#comment").val();

            if(comment=="")
            {
                alert("Please enter comment");
                exit();   
            }
            $(".singleComment").modal('hide');

            var pID = $("#singleItemId").val();
            var productID = $("#singleItemId2").val();    
            var orderNo = $("#singleItemId3").val();        
                      
            
            $.ajax({
                url: '{{url("/")}}' +  '/post-single-reject-batch',
                type: 'post',
                data: {prodID:pID,productID:productID, comment:comment, orderNo:orderNo, '_token': $('input[name=_token]').val()},
                    //data: { format: 'json' },
                    dataType: 'json',
                    success: function(result) {
                    
                    alert("Reject successfull");
                    window.location.reload();
                   
                },
                error: function(error) {
                    alert("Reject successfull");
                    window.location.reload();
                }
            });
            
        });
    });

</script>

<script>

   $(document).ready(function () {
        $('input[id$=date_from]').datepicker({
            dateFormat: 'dd-mm-yy'			// Date Format "dd-mm-yy"
        });
  });
  
  $(document).ready(function () {
        $('input[id$=date_to]').datepicker({
            dateFormat: 'dd-mm-yy'			// Date Format "dd-mm-yy"
        });
  });
  
</script>

<script>
$(document).ready(function(){
    $([data-toggle="tooltip"]).tooltip();
});
</script>
<script>
  function deleteProject(id) {
      var x = confirm('Do you want to delete?');
      if(x==true){
          document.location="delete-project/"+id;
      }
  }
</script>

<script>

    $(document).ready(function () {
          $('select').selectize({
              sortField: 'text'
          });
      });

</script>
@endsection