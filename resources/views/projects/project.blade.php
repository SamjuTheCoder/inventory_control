@extends('layouts.app')
@section('pageHeaderTitle')
 Add Project
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Add Project <span style="font-size:12px;">Fields marked<i style="color:red">*</i> are important</span></h3>
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
                    <form method="post" action="{{ route('saveProject')}}">
                    @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Project name:<i style="color:red">*</i></label>
                                <input type="text" name="projectName" class="form-control" id="projectName" placeholder="Project Name" value="{{ old('projectName') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Client:<i style="color:red">*</i></label>
                                <select id="clientName" class="form-control" name="clientName" required>
                                    <option value="">Choose...</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->clientName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Location:</label>
                            <input type="text" name="location" class="form-control" id="location" placeholder="Enter client location" value="{{ old('address') }}"  >
                        </div>
                       
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered">
                      <thead>
                      
                          <tr>
                              <th scope="col">#</th>
                              <th scope="col">Project</th>
                              <th scope="col">Client</th>
                              <th scope="col">Location</th>
                              <th scope="col">Action</th>
                          </tr>
                      </thead>
                      <tbody>
                      @php $i=1; @endphp
                      @foreach($projects as $project)
                     
                          <tr>
                              <th scope="row">{{ $i++ }}</th>
                              <td>{{ $project->projectName }}</td>
                              <td>{{ $project->clientName }}</td>
                              <td>{{ $project->plocation  }}</td>
                              <td> <a href="edit-project/{{ base64_encode($project->pid) }}" data-toggle="tooltip" data-placement="bootom" title="Edit record"><button class="btn btn-outline-success"><i class="fa fa-edit"></i></button></a> | <a onclick="deleteProject('{{$project->pid}}')" style="cursor:pointer;color:red" data-toggle="tooltip" data-placement="bootom" title="Delete record"><button class="btn btn-outline-danger"><i class="fa fa-trash"></i></button</a></td>
                          </tr>
                     @endforeach
                      </tbody>
                     
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
@endsection