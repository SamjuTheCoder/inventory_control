@extends('layouts.app')
@section('pageHeaderTitle')
 Add Client
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add Client <span style="font-size:12px;">Fields marked<i style="color:red">*</i> are important</span></h3>
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
                    <form method="post" action="{{ route('saveClient')}}">
                    @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Client name: <i style="color:red">*</i></label>
                                <input type="text" name="clientName" class="form-control" id="projectName" placeholder="Enter client name" value="{{ old('clientName') }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputAddress">Location:<i style="color:red">*</i></label>
                                <input type="text" name="location" class="form-control" id="location" placeholder="Enter client location" value="{{ old('location') }}" required >
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputAddress">Address: </label>
                                <input type="text" name="address" class="form-control" id="address" placeholder="Enter client address" value="{{ old('address') }}" >
                            </div>

                        </div>
                        
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>

                <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered">
                      <thead>
                          <tr>
                              <th scope="col">#</th>
                              <th scope="col">Client</th>
                              <th scope="col">Location</th>
                              <th scope="col">Address</th>
                              <th scope="col">Action</th>
                          </tr>
                      </thead>
                      <tbody>
                      @php $i=1; @endphp
                      @foreach($clients as $client)
                     
                          <tr>
                              <th scope="row">{{ $i++ }}</th>
                              <td>{{ $client->clientName }}</td>
                              <td>{{ $client->location }}</td>
                              <td>{{ $client->address  }}</td>
                              <td><a href="edit-client/{{ base64_encode($client->id) }}" data-toggle="tooltip" data-placement="bootom" title="Edit record"><button class="btn btn-outline-success"><i class="fa fa-edit"></i></button></a> | <a onclick="deleteClient('{{$client->id}}')" style="cursor:pointer;color:red" data-toggle="tooltip" data-placement="bootom" title="Delete record"><button class="btn btn-outline-danger"><i class="fa fa-trash"></i></button></a></td>
                          </tr>
                     @endforeach
                      </tbody>
                  </table>
                </div>
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
  function deleteClient(id) {
      var x = confirm('Do you want to delete?');
      if(x==true){
          document.location="delete-client/"+id;
      }
  }
</script>
@endsection
