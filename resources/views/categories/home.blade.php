@extends('layouts.app')
@section('pageHeaderTitle', 'Create Product Measurement')

@section('content')
<div class="row justify-content-center">

    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0 text-uppercase"><span class="fa fa-measure"></span> @yield('pageHeaderTitle')</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-about about_area bg--white section-padding--lg" style="margin-top:60px;">
        <div class="container">         
            @if (count($errors) > 0)
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Error!</strong> 
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif                        
        @if(session('msg'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Success!</strong> <br />
                {{ session('msg') }}
            </div>                        
        @endif
        
        @if(session('err'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Input Error!</strong> <br />
                {{ session('err') }}
            </div>                        
        @endif
        </div>
    </div>                
    <br>
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    
                    <div class="white_card_body">
                        <form>
                            <div class="row">
                                <div class="col-md-16">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <input type="text" class="form-control" name="description" aria-describedby="description" placeholder="Enter Description">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection
