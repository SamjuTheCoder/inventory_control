@extends('layouts.app')
@section('pageHeaderTitle')
 Edit Client
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Edit Client <span style="font-size:12px;">Fields marked<i style="color:red">*</i> are important</span></h3>
                    </div>
                </div>
                @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <strong></strong> {{ session('success') }}</div>
                @endif
                @if(session('error_message'))
                <div class="alert alert-error alert-dismissible" role="alert">
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
                    <form method="post" action="{{ route('updateClient')}}">
                    @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Client name: <i style="color:red">*</i></label>
                                <input type="hidden" name="clientID" class="form-control" id="clientID" value="{{ $clients->id }}" required>
                                <input type="text" name="clientName" class="form-control" id="projectName" value="{{ $clients->clientName }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputAddress">Location:<i style="color:red">*</i></label>
                                <input type="text" name="location" class="form-control" id="location"  value="{{ $clients->location }}" required >
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputAddress">Address: </label>
                                <input type="text" name="address" class="form-control" id="address"  value="{{ $clients->address }}" >
                            </div>

                        </div>
                        
                       
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                    <br>
                    <a href="{{ route('launchClient') }}"><i class="fa fa-arrow-left"></i> Back</a>
                </div>

            </div>
        </div>
    </div>
</div>
  
@endsection

@section('script')
<script>
  function deleteClient(id) {
      var x = confirm('Do you want to delete');
      if(x==true){
          document.location="delete-client/"+id;
      }
  }
</script>
@endsection
