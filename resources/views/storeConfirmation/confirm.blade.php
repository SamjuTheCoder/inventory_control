@extends('layouts.app')
@section('pageHeaderTitle')
Movement-Confirmation
@endsection
@section('content')
<div class="row justify-content-center">
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
                <div class="card-body">
                    <form method="post" action="{{ route('getconfirmMovement')}}">
                    @csrf
                        <div class="form-row">
                           
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Status:<i style="color:red"></i></label>
                                <select id="clientName" class="form-control" name="status">
                                    <option value="">Choose...</option>
                                    
                                    <option value="0" {{ ($statusx == 0 || old("status") == 0 )? "selected" :"" }}>Pending Confirmation</option>
                                    <option value="2" {{ ($statusx == 2 || old("status") == 2 )? "selected" :"" }}>Rejected</option>
                                    <option value="1" {{ ($statusx == 1 || old("status") == 1 )? "selected" :"" }}>Confirmed</option>
                                    
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" style="height:50px;margin-top:20px;">View</button>
                           
                        </div>
                        
                       
                        
                    </form>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered">
                      <thead>
                          <tr>
                              <th scope="col">#</th>
                              <th scope="col">Batch Number</th>
                              <th scope="col">Description</th>
                              <th scope="col">Originating Store</th>
                              <th scope="col">Date</th>
                              <th scope="col">Status</th>
                              <th scope="col">Action</th>
                          </tr>
                      </thead>
                      @if($details==null)  

                      @else
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($details as $detail)
                         @php  
                            $getStore = DB::table('stores')->where('id',$detail->storeID_destination)->first();
                         @endphp
                            <tr>
                                <th scope="row">{{ $i++ }}</th>
                                <td>{{ $detail->orderNo }}</td>
                                <td>{{ $detail->product_desc }}</td>
                                <td>{{ $getStore->store_name }}</td>
                                <td>{{ date('d-m-Y', strtotime($getStore->created_at)) }}</td>
                                <td>
                                @php
                                                $arr   =   array();
                                                $getAllData = App\Models\ProductMovement::where('product_movements.orderNo', '=', $detail->orderNo)->where('product_movements.move_in', '>', '0')->get();
                                                $countData = App\Models\ProductMovement::where('product_movements.orderNo', '=', $detail->orderNo)->where('product_movements.move_in', '>', '0')
                                                ->where('is_accepted', '=', '0')->count();

                                                $countData1 = App\Models\ProductMovement::where('product_movements.orderNo', '=', $detail->orderNo)->where('product_movements.move_in', '>', '0')
                                                ->where('is_accepted', '=', '1')->count();

                                                $countData2 = App\Models\ProductMovement::where('product_movements.orderNo', '=', $detail->orderNo)->where('product_movements.move_in', '>', '0')
                                                ->where('is_accepted', '=', '2')->count();
                                   
                                                foreach ($getAllData as $key => $value) {
                                                    # code...
                                                    if ($value->is_accepted == 0) {
                                                        # code...
                                                        array_push($arr, '0');
                                                    }
                                                    elseif ($value->is_accepted == 1) {
                                                        # code...
                                                        array_push($arr, '1');
                                                    }
                                                    elseif ($value->is_accepted == 2) {
                                                        # code...
                                                        array_push($arr, '2');
                                                    }
                                                }
                                                
                                                $countArr   =   count($arr);

                                                if ($countArr == $countData) {
                                                    # code...
                                                    print "<span class = 'text-info'> Pending! </span>";
                                                }elseif ($countArr == $countData1) {
                                                    # code...
                                                    print "<span class = 'text-success'> Complete! </span>";
                                                } elseif ($countArr == $countData2){
                                                    # code...
                                                    print "<span class='text-danger'> Rejected! </span>";
                                                
                                                } else {
                                                    # code...
                                                    print "<span class='text-warning'> Partial! </span>";
                                                }
                                                @endphp
                                </td>
                               
                                <td> <a href="view-batch/{{ base64_encode($detail->orderNo) }}" data-toggle="tooltip" data-placement="bootom" title="Confirm"><button class="btn btn-outline-success"><i class="fa fa-eye"></i></button></a> </td>
                            </tr>
                        @endforeach
                        </tbody>
                    @endif
                  </table>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection


@section('style')
 
@endsection


@section('script')

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