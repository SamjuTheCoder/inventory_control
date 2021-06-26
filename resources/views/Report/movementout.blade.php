@extends('layouts.app')
@section('pageHeaderTitle')
Movement-Out Report
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Movement-Out Report <span style="font-size:12px;">Fields marked<i style="color:red">*</i> are important</span></h3>
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
                    <form method="post" action="{{ route('getReport')}}">
                    @csrf
                        <div class="form-row">
                           
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Store:<i style="color:red">*</i></label>
                                <select id="clientName" class="form-control" name="store">
                                    <option value="">Choose...</option>
                                    @foreach($store as $st)
                                    <option value="{{ $st->id }}" {{ ($storex == $st->id || old("store") == $st->id )? "selected" :"" }}>{{$st->store_name}} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Project:<i style="color:red">*</i></label>
                                <select id="" class="form-control" name="project">
                                    <option value="">Choose...</option>
                                    @foreach($project as $pt)
                                    <option value="{{ $pt->id }}" {{ ($projectx == $pt->id || old("project") == $pt->id )? "selected" :"" }}>{{$pt->projectName}} </option>

                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Product:<i style="color:red">*</i></label>
                                <select id="select-state" class="form-control" name="product" >
                                    <option value="">Choose...</option>
                                    @foreach($product as $pd)
                                    <option value="{{ $pd->id }}" {{ ($productx == $pd->id || old("product") == $pd->id )? "selected" :"" }}>{{$pd->productName}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Date From:<i style="color:red">*</i></label>
                                <input type="date" name="date_from" class="form-control" id="date_from" value="{{ $datefrom }}">
                            </div>   
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Date To:<i style="color:red">*</i></label>
                                <input type="date" name="date_to" class="form-control" id="date_to" value="{{ $dateto }}">
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
                                <td>{{ $sum }}</td>
                                <td> <a href="report/viewall/{{ base64_encode($detail->pid) }}" target="_blank" data-toggle="tooltip" data-placement="bootom" title="View All"><button class="btn btn-outline-success"><i class="fa fa-eye"></i></button></a> </td>
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
@section('script')
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