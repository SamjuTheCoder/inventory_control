@extends('layouts.app')
@section('pageHeaderTitle')
Status Report
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Status Report </h3>
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
                    <form method="post" action="{{ route('getconfirmReport')}}">
                    @csrf
                        <div class="form-row">
                           
                            <div class="form-group col-md-12">
                                <label for="inputPassword4">Select:<i style="color:red"></i></label>
                                <select id="clientName" class="form-control" name="status">
                                    <option value="">Choose...</option>
                                    
                                    <option value="0" {{ ($statusx == 0 || old("status") == 0 )? "selected" :"" }}>Pending Confirmation</option>
                                    <option value="2" {{ ($statusx == 2 || old("status") == 2 )? "selected" :"" }}>Rejected</option>
                                    <option value="1" {{ ($statusx == 1 || old("status") == 1 )? "selected" :"" }}>Confirmed</option>
                                    
                                </select>
                            </div>

                           
                        </div>
                        
                       
                        <button type="submit" class="btn btn-primary">View</button>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered">
                      <thead>
                          <tr>
                              <th scope="col">#</th>
                              <th scope="col">Product</th>
                              <th scope="col">Originating Store</th>
                              <th scope="col">Quantity</th>
                              <!-- <th scope="col">Action</th> -->
                          </tr>
                      </thead>
                      @if($details==null)  

                      @else
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($details as $detail)
                            <tr>
                                <th scope="row">{{ $i++ }}</th>
                                <td>{{ $detail->productName }}</td>
                                <td>{{ $detail->store_name }}</td>
                                <td>{{ $detail->move_in }} [ <span style="color:green">{{ $detail->formatqty }} </span>]</td>
                                <!-- <td> <a href="report/viewall/{{ base64_encode($detail->pid) }}" target="_blank" data-toggle="tooltip" data-placement="bootom" title="View All"><button class="btn btn-outline-success"><i class="fa fa-eye"></i></button></a> </td>
                             -->
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
  <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" rel="stylesheet" />
@endsection


@section('script')
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>
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