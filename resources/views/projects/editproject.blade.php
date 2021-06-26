@extends('layouts.app')
@section('pageHeaderTitle')
 Edit Project
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Edit Project <span style="font-size:12px;">Fields marked<i style="color:red">*</i> are important</span></h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="card-body">
                    <form method="post" action="{{ route('updateProject')}}">
                    @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Project name:<i style="color:red">*</i></label>
                                <input type="hidden" name="projectID" class="form-control" id="projectID" value="{{ $projects->id }}" required>
                                <input type="text" name="projectName" class="form-control" id="projectName" placeholder="Project Name" value="{{ $projects->projectName }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Client:<i style="color:red">*</i></label>
                                <select id="clientName" class="form-control" name="clientName" required>
                                    <option value="">Choose...</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ ($projects->clientID==$client->id || old('clientName') == $client->id)? "selected" : "" }}>{{ $client->clientName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Location:</label>
                            <input type="text" name="location" class="form-control" id="location" placeholder="Enter client location" value="{{ $projects->location }}"  >
                        </div>
                       
                        <button type="submit" class="btn btn-primary">Update</button>
                       
                    </form>
                    <br>
                    <a href="{{ route('launchProject') }}"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
            </div>

           
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
  function deleteProject(id) {
      var x = confirm('Do you want to delete');
      if(x==true){
          document.location="delete-project/"+id;
      }
  }
</script>
@endsection