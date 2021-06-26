@extends('layouts.app')
@section('pageHeaderTitle')
Movement-In Report
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Movement-In Report </h3>
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
                    <form method="post" action="{{ route('getInReport')}}">
                    @csrf
                        <div class="form-row">
                           
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Store:<i style="color:red"></i></label>
                                <select id="clientName" class="form-control" name="store">
                                    <option value="">Choose...</option>
                                    @foreach($store as $st)
                                    <option value="{{ $st->id }}" {{ ($storex == $st->id || old("store") == $st->id )? "selected" :"" }}>{{$st->store_name}} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Product:<i style="color:red"></i></label>
                                <select id="select-state" class="form-control" name="product" >
                                    <option value="">Choose...</option>
                                    @foreach($product as $pd)
                                    <option value="{{ $pd->id }}" {{ ($productx == $pd->id || old("product") == $pd->id )? "selected" :"" }}>{{$pd->productName}} </option>
                                    @endforeach
                                </select>
                            </div> 
                        
                        
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Date from:<i style="color:red"></i></label>
                                <input type="text" name="date_from" class="form-control" id="date_from" value="{{ $datefrom }}" placeholder="Select date to search from">
                            </div>   
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Date to:<i style="color:red"></i></label>
                                <input type="text" name="date_to" class="form-control" id="date_to" value="{{ $dateto }}" placeholder="Select date to search to"> 
                            </div>
                        </div>
                        
                       
                        <button type="submit" class="btn btn-primary">Generate</button>
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
                              <th scope="col">Quantity</th>
                              <th scope="col">Action</th>
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
                                <td>{{ $detail->totalIn }} [ <span style="color:green">{{ $detail->formatqty }} </span>]</td>
                                <td> <a href="in-report/viewall/{{ base64_encode($detail->pid) }}" target="_blank" data-toggle="tooltip" data-placement="bootom" title="View All"><button class="btn btn-outline-success"><i class="fa fa-eye"></i></button></a> </td>
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