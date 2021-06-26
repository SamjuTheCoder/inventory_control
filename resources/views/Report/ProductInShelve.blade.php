@extends('layouts.app')
@section('pageHeaderTitle', 'Shelve Report')

@section('content')
<div class="row justify-content-center bg-light pt-3">

    <div class="col-md-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Report</h3><span style="font-size:12px;">Fields marked<i style="color:red">*</i> are important</span>
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

            {{-- form beginning --}}
            <div class="white_card_body">
                <div class="card-body">
                    {{-- <img src="{{asset('assets\img\shelve-avatar.png')}}" class="js-tilt" data-tilt-speed="1000" data-tilt-max="20" data-tilt-scale="1.01" data-tilt-perspective="250" id="output_image"/>
             --}}
                    <form method="post" >
                        @csrf
                        <div class="form-row">                                               
                                                        
                            <div class="form-group col-md-4">
                                <label for="inputPassword4" style="color: black;">Warehouse:<i style="color:red">*</i></label>
                                <select  class="form-control" name="store" >
                                    <option value="">Choose...</option>

                                    @if(isset($stores) && $stores)
                                        @foreach($stores as $item)
                                        <option value="{{ $item->id }}" {{ isset($store) && $store == $item->id ? 'selected' : '' }}>{{$item->store_name}} </option>
                                        @endforeach
                                    @endif 
                                </select>
                            </div>

                            <div class="main form-group col-md-4">
                                <label for="select-product" style="color: black;">Product:<i style="color:red">*</i></label>
                                <select id="select" class="form-control" name="product">
                                    <option value="">type for quick search...</option>
                                    
                                    @if(isset($products) && $products)
                                        @foreach($products as $pd)
                                            <option value="{{ $pd->id }}" {{ (isset($product) && $product == $pd->id ? 'selected' : '') }}>{{$pd->productName}} </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>    
                        </div>
                        <div class="row mt-2 ">
                            <div class="col-9" style="align-content: center !important">
                                <button type="submit" class="btn btn-primary text-center">Generate</button>
                            </div>
                        </div>
                    </form>
                </div>
                    <!-- FIRST TABLE -->
                    <div class="col-md-12">
                        <div class="white_card card_height_100 mb_30">
                            <div class="white_card_header">
                                <div class="box_header m-0">
                                    <div class="main-title">
                                        <h4 class="m-0">Products in Shelve</h4>
                                    </div>
                                </div>
                            </div>
                            {{-- style="background-image: url('{{asset("assets/img/shelve-avatar.jpg")}}')" --}}
                            <div class="white_card_body">
                                <table class="table table-light table-bodered table-hover" style="background-color:#f9f6f6">
                                    <thead class="text-white" style="background-color:#373063">                    
                                        <tr>
                                            <th> SN </th>
                                            <th> Product Name </th>
                                            {{-- <th>Warehouse</th> --}}
                                            <th> Shelf</th>
                                            <!-- <th> Quantity</th> -->
                                        </tr>
                                    </thead>
                                    @if(isset($getReport) && $getReport)
                                        @foreach($getReport as $key => $value)
                                            <tr>
                                                <td> {{ ($key + 1) }} </td>
                                                <td> {{ $value->productName }} </td>
                                                {{-- <td> {{ $value->store_name }} </td> --}}
                                                <td> @if ($value->shelvesID == null) UnShelved @endif  {{ $value->shelve_name }} </td>
                                                <!-- <td> {{ $value->quantity }} </td> -->
                                                {{-- <td> <a href="{{ Route::has('editCategory') ? Route('editCategory', ['rid'=>$value->id]) : 'javascript:;'  }}" class="btn btn-outline-success btn-sm"><i class="fa fa-edit"></i></a>
                                                <button type="button" name="submit" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-backdrop="false" data-target="#confirmToDelete{{$key}}"><i class="fa fa-trash"></i></button></td>                                         --}}
                                            </tr>
                                        {{ $getReport->onEachSide(5)->links() }}
                                        @endforeach
                                    @endif
                                </table>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* html,body{
  height:100%;
}
body{
  padding:0;
  margin:0;
  color: #2c3e51;
  background: #f5f5f5;
  font-family: 'Ubuntu', sans-serif;
}
.container{
  height:100%;
  display:flex;
  align-items:center;
  justify-content:center;
}
.main{
  margin:1rem;
  max-width:350px;
  width:50%;  
  height:250px;
}
@media(max-width:34em){
  .main{
    min-width:150px;
    width:auto;
  }
} */
#select {
    display: none !important;
}

.dropdown-select {
    background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0) 100%);
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#40FFFFFF', endColorstr='#00FFFFFF', GradientType=0);
    background-color: #fff;
    border-radius: 6px;
    border: solid 1px #eee;
    box-shadow: 0px 2px 5px 0px rgba(155, 155, 155, 0.5);
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    float: left;
    font-size: 14px;
    font-weight: normal;
    height: 42px;
    line-height: 40px;
    outline: none;
    padding-left: 18px;
    padding-right: 30px;
    position: relative;
    text-align: left !important;
    transition: all 0.2s ease-in-out;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    white-space: nowrap;
    width: auto;

}

.dropdown-select:focus {
    background-color: #fff;
}

.dropdown-select:hover {
    background-color: #fff;
}

.dropdown-select:active,
.dropdown-select.open {
    background-color: #fff !important;
    border-color: #bbb;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05) inset;
}

.dropdown-select:after {
    height: 0;
    width: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 4px solid #777;
    -webkit-transform: origin(50% 20%);
    transform: origin(50% 20%);
    transition: all 0.125s ease-in-out;
    content: '';
    display: block;
    margin-top: -2px;
    pointer-events: none;
    position: absolute;
    right: 10px;
    top: 50%;
}

.dropdown-select.open:after {
    -webkit-transform: rotate(-180deg);
    transform: rotate(-180deg);
}

.dropdown-select.open .list {
    -webkit-transform: scale(1);
    transform: scale(1);
    opacity: 1;
    pointer-events: auto;
}

.dropdown-select.open .option {
    cursor: pointer;
}

.dropdown-select.wide {
    width: 100%;
}

.dropdown-select.wide .list {
    left: 0 !important;
    right: 0 !important;
}

.dropdown-select .list {
    box-sizing: border-box;
    transition: all 0.15s cubic-bezier(0.25, 0, 0.25, 1.75), opacity 0.1s linear;
    -webkit-transform: scale(0.75);
    transform: scale(0.75);
    -webkit-transform-origin: 50% 0;
    transform-origin: 50% 0;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.09);
    background-color: #fff;
    border-radius: 6px;
    margin-top: 4px;
    padding: 3px 0;
    opacity: 0;
    overflow: hidden;
    pointer-events: none;
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 999;
    max-height: 250px;
    overflow: auto;
    border: 1px solid #ddd;
}

.dropdown-select .list:hover .option:not(:hover) {
    background-color: transparent !important;
}
.dropdown-select .dd-search{
  overflow:hidden;
  display:flex;
  align-items:center;
  justify-content:center;
  margin:0.5rem;
}

.dropdown-select .dd-searchbox{
  width:90%;
  padding:0.5rem;
  border:1px solid #999;
  border-color:#999;
  border-radius:4px;
  outline:none;
}
.dropdown-select .dd-searchbox:focus{
  border-color:#12CBC4;
}

.dropdown-select .list ul {
    padding: 0;
}

.dropdown-select .option {
    cursor: default;
    font-weight: 400;
    line-height: 40px;
    outline: none;
    padding-left: 18px;
    padding-right: 29px;
    text-align: left;
    transition: all 0.2s;
    list-style: none;
}

.dropdown-select .option:hover,
.dropdown-select .option:focus {
    background-color: #f6f6f6 !important;
}

.dropdown-select .option.selected {
    font-weight: 600;
    color: #12cbc4;
}

.dropdown-select .option.selected:focus {
    background: #f6f6f6;
}

.dropdown-select a {
    color: #aaa;
    text-decoration: none;
    transition: all 0.2s ease-in-out;
}

.dropdown-select a:hover {
    color: #666;
}

    </style>
@endsection
    

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

        {{-- <script>
            $("#js-example-basic-hide-search").select2({
            minimumResultsForSearch: Infinity
        });
        </script> --}}

        <script>
                    function create_custom_dropdowns() {
                $('#select').each(function (i, select) {
                    if (!$(this).next().hasClass('dropdown-select')) {
                        $(this).after('<div class="dropdown-select wide ' + ($(this).attr('class') || '') + '" tabindex="0"><span class="current"></span><div class="list"><ul></ul></div></div>');
                        var dropdown = $(this).next();
                        var options = $(select).find('option');
                        var selected = $(this).find('option:selected');
                        dropdown.find('.current').html(selected.data('display-text') || selected.text());
                        options.each(function (j, o) {
                            var display = $(o).data('display-text') || '';
                            dropdown.find('ul').append('<li class="option ' + ($(o).is(':selected') ? 'selected' : '') + '" data-value="' + $(o).val() + '" data-display-text="' + display + '">' + $(o).text() + '</li>');
                        });
                    }
                });

                $('.dropdown-select ul').before('<div class="dd-search"><input id="txtSearchValue" autocomplete="off" onkeyup="filter()" class="dd-searchbox" type="text"></div>');
            }

            // Event listeners

            // Open/close
            $(document).on('click', '.dropdown-select', function (event) {
                if($(event.target).hasClass('dd-searchbox')){
                    return;
                }
                $('.dropdown-select').not($(this)).removeClass('open');
                $(this).toggleClass('open');
                if ($(this).hasClass('open')) {
                    $(this).find('.option').attr('tabindex', 0);
                    $(this).find('.selected').focus();
                } else {
                    $(this).find('.option').removeAttr('tabindex');
                    $(this).focus();
                }
            });

            // Close when clicking outside
            $(document).on('click', function (event) {
                if ($(event.target).closest('.dropdown-select').length === 0) {
                    $('.dropdown-select').removeClass('open');
                    $('.dropdown-select .option').removeAttr('tabindex');
                }
                event.stopPropagation();
            });

            function filter(){
                var valThis = $('#txtSearchValue').val();
                $('.dropdown-select ul > li').each(function(){
                var text = $(this).text();
                    (text.toLowerCase().indexOf(valThis.toLowerCase()) > -1) ? $(this).show() : $(this).hide();         
            });
            };
            // Search

            // Option click
            $(document).on('click', '.dropdown-select .option', function (event) {
                $(this).closest('.list').find('.selected').removeClass('selected');
                $(this).addClass('selected');
                var text = $(this).data('display-text') || $(this).text();
                $(this).closest('.dropdown-select').find('.current').text(text);
                $(this).closest('.dropdown-select').prev('select').val($(this).data('value')).trigger('change');
            });

            // Keyboard events
            $(document).on('keydown', '.dropdown-select', function (event) {
                var focused_option = $($(this).find('.list .option:focus')[0] || $(this).find('.list .option.selected')[0]);
                // Space or Enter
                //if (event.keyCode == 32 || event.keyCode == 13) {
                if (event.keyCode == 13) {
                    if ($(this).hasClass('open')) {
                        focused_option.trigger('click');
                    } else {
                        $(this).trigger('click');
                    }
                    return false;
                    // Down
                } else if (event.keyCode == 40) {
                    if (!$(this).hasClass('open')) {
                        $(this).trigger('click');
                    } else {
                        focused_option.next().focus();
                    }
                    return false;
                    // Up
                } else if (event.keyCode == 38) {
                    if (!$(this).hasClass('open')) {
                        $(this).trigger('click');
                    } else {
                        var focused_option = $($(this).find('.list .option:focus')[0] || $(this).find('.list .option.selected')[0]);
                        focused_option.prev().focus();
                    }
                    return false;
                    // Esc
                } else if (event.keyCode == 27) {
                    if ($(this).hasClass('open')) {
                        $(this).trigger('click');
                    }
                    return false;
                }
            });

            $(document).ready(function () {
                create_custom_dropdowns();
            });

        </script>
@endsection