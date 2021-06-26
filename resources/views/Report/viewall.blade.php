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
                        <h3 class="m-0">Movement-Out Report </h3>
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
                   
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered">
                      <thead>
                          <tr>
                              <th scope="col">#</th>
                              <th scope="col">Product</th>
                              <th scope="col">Description</th>
                              <th scope="col">Order No</th>
                              <th scope="col">Quantity</th>
                              <th scope="col">Date</th>
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
                                <td>{{ $detail->description }}</td>
                                <td>{{ $detail->orderNo }}</td>
                                <td>{{ $detail->move_out }}</td>
                                <td>{{ date('d-m-Y', strtotime($detail->transactionDate))  }}</td>
                                <!-- <td> <a href="edit-project" data-toggle="tooltip" data-placement="bootom" title="Edit record"><button class="btn btn-outline-success"><i class="fa fa-edit"></i></button></a> </td> -->
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