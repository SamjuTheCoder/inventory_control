@extends('layouts.app')

@section('style')
@endsection
@section('content')
    @include('share.operationCallBackAlert', ['showAlert' => 1])
<div class="row justify-content-center" >
    <div class="col-md-12"> @include('share.operationCallBackAlert', ['showAlert' => 1])</div>
</div>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Transactions movement-Out</h3>
                    </div>
                </div>
            </div>
            <div class="white_card_body">
                <div class="QA_section">
                    <div class="QA_table mb-0 transaction-table">
                        <!-- table-responsive -->
                        <div class="table-responsive">
                            <table class="table lms_table_active2  ">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th scope="col">S/N</th>
                                            <th> Order Number </th>
                                            <th> Description </th>
                                            <th scope="col"> Store/Warehouse </th>
                                            <th scope="col"> Action Status </th>
                                            <th scope="col"> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    @foreach($fetchDataProductOut as $key => $list)
                                        <tr>
                                            <td scope="row">{{$key+1}}</td>
                                            <td>{{$list->orderNo}} </td>
                                            <td>{{$list->description}}</td>
                                            <td>{{$list->store_name}} </td>
                                            <td> 
                                                @php
                                                $arr   =   array();
                                                $getAllData = App\Models\ProductMovement::where('product_movements.orderNo', '=', $list->orderNo)->where('product_movements.move_out', '>', '0')->get();
                                                $countData = App\Models\ProductMovement::where('product_movements.orderNo', '=', $list->orderNo)->where('product_movements.move_out', '>', '0')
                                                ->where('product_movements.move_in', '=', '0')->count();
                                                //dd($getAllData);
                                                foreach ($getAllData as $key => $value) {
                                                    # code...
                                                    if ($value->isConfirmed != 0) {
                                                        # code...
                                                        array_push($arr, '1');
                                                    }
                                                }
                                                $countArr   =   count($arr);
                                                
                                                //$countRecord=   count($list);
                                                //print($countData);
                                                if ($countArr == 0) {
                                                    # code...
                                                    print "<span class = 'text-danger'> Pending! </span>";
                                                }elseif ($countArr == $countData) {
                                                    # code...
                                                    print "<span class = 'text-success'> Complete! </span>";
                                                } else {
                                                    # code...
                                                    print "<span class='text-warning'> Partial! </span>";
                                                }
                                                @endphp
                                            </td>
                                            
                                            <td>
                                                <a href="{{route('process-confirm-out', ['id' => encrypt($list->orderNo)])}}" class="btn btn-outline-success btn-sm" >
                                                <i class="ti-hand-point-right"></i></a>
                                            </td>
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
</div>
@endsection