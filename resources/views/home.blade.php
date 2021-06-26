@extends('layouts.app')

@section('content')
    
            <!-- page title  -->
            <div class="row">
                <div class="col-12">
                    <div class="page_title_box d-flex flex-wrap align-items-center justify-content-between">
                        <div class="page_title_left">
                            <h3 class="mb-0" >Dashboard</h3>
                            <p>Dashboard/MBR Inventory</p>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="row ">
                <div class="col-xl-12">
                    <div class="white_card  mb_30">
                        <div class="white_card_header ">
                            <div class="box_header m-0">
                                <ul class="nav  theme_menu_dropdown">
                                    <li class="nav-item">
                                      <a class="CHATBOX_open nav-link" href="#">Warehouses</a>
                                    </li>
                                    <li class="nav-item">
                                      <a class="bell_notification_clicker nav-link" href="#">Products</a>
                                    </li>
                                  </ul>
                                  
                                  <div class="button_wizerd">
                                      <a href="{{url('/product-entry')}}" class="white_btn mr_5">Add item</a>
                                  </div>
                            </div>
                        </div>

                        <!-- Menu_NOtification_Wrap  -->
                        <div class="Menu_NOtification_Wrap">
                            <div class="notification_Header">
                                <h4>MBR Products</h4>
                            </div>
                            <div class="Notification_body">
                                <!-- single_notify  -->
                                @foreach ($allProduct as $item)
                                <div class="single_notify d-flex align-items-center">
                                    <div class="notify_thumb">
                                        <a href="#"><img src="{{($item->pr_filename == NULL)? asset('assets/img/inventory/product/default.jpg'):asset('assets/img/inventory/product/'.$item->pr_filename)}}" alt="" style="object-fit: contain; background: #ccc;"></a>
                                    </div>
                                    <div class="notify_content">
                                        <a href="#"><h5>{{$item->productName}} </h5></a>
                                        <p>{{$item->categoryTitle}}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="nofity_footer">
                                <div class="submit_button text-center pt_20">
                                    <a href="{{url('/product/search')}}" class="btn_1 green">See More</a>
                                </div>
                                <div class="text-center mt-2">
                                    <small>MBR computers</small>
                                </div>
                            </div>
                        </div>
                        <div class="white_card_body anlite_table p-0">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="single_analite_content">
                                        <h4>Total Product</h4>
                                        <h3> <span class="counter">{{$productCount}}</span> </h3>
                                        <div class="d-flex">
                                            <span>Shipped in this month</span>
                                            <div ><i class="fa fa-caret-up"></i><span class="counter">{{$dataShipIn}}</span> </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="single_analite_content">
                                        <h4>Number of Stores</h4>
                                        <h3><span class="counter">{{$storeCount}}</span> </h3>
                                        <div class="mt-md-2 d-flex">
                                            <span>Shipped out this month</span>
                                            <div><i class="fa fa-caret-up"></i>{{$dataShipOut}} </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="single_analite_content">
                                        <h4>Active Projects</h4>
                                        <h3><span class="counter">{{$projectCount}}</span> </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                
                <div class="col-lg-12">
                    <div class="white_card mb_30 card_height_100">
                        <div class="white_card_header ">
                            <div class="box_header m-0">
                                <div class="main-title">
                                    <h3 class="m-0">Recent Activities</h3>
                                </div>
                                
                            </div>
                        </div>
                        <div class="white_card_body pt-0">
                            <div class="QA_section">
                                <div class="QA_table mb-0 transaction-table">
                                    <!-- table-responsive -->
                                    <div class="table-responsive">
                                        <table class="table  ">
                                            <tbody>
                                                @foreach ($allRecentProduct as $key => $item)
                                                    <tr>
                                                        @if ($item->move_in > '0')
                                                        <td scope="row"> 
                                                            <span class="buy-thumb"><i class="ti-arrow-down"></i></span>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-success">Moved in</span>
                                                        </td>
                                                        @else
                                                        <td scope="row"> 
                                                            <span class="sold-thumb"><i class="ti-arrow-up"></i></span>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-danger">Moved out</span>
                                                        </td>
                                                        @endif
                                                        
                                                        <td> <img class="small_img" src="img/currency/1.svg" alt=""> Order No. - {{$item->orderNo}} </td>
                                                        <td>To - {{$item->store_name}} </td>
                                                        <td>{{$item->description}}</td>
                                                        <td>{{date('d-m-Y', strtotime($item->transactionDate))?? ''}}</td>
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
@section('modal')
        <!-- ### CHAT_MESSAGE_BOX   ### -->

        <div class="CHAT_MESSAGE_POPUPBOX">
            <div class="CHAT_POPUP_HEADER">
            <div class="MSEESAGE_CHATBOX_CLOSE">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.09939 5.98831L11.772 10.661C12.076 10.965 12.076 11.4564 11.772 11.7603C11.468 12.0643 10.9766 12.0643 10.6726 11.7603L5.99994 7.08762L1.32737 11.7603C1.02329 12.0643 0.532002 12.0643 0.228062 11.7603C-0.0760207 11.4564 -0.0760207 10.965 0.228062 10.661L4.90063 5.98831L0.228062 1.3156C-0.0760207 1.01166 -0.0760207 0.520226 0.228062 0.216286C0.379534 0.0646715 0.578697 -0.0114918 0.777717 -0.0114918C0.976738 -0.0114918 1.17576 0.0646715 1.32737 0.216286L5.99994 4.889L10.6726 0.216286C10.8243 0.0646715 11.0233 -0.0114918 11.2223 -0.0114918C11.4213 -0.0114918 11.6203 0.0646715 11.772 0.216286C12.076 0.520226 12.076 1.01166 11.772 1.3156L7.09939 5.98831Z" fill="white"/>
            </svg>
    
            </div>
                <h3>MBR stores</h3>
            </div>
            <div class="CHAT_POPUP_BODY">
                <div class="QA_section">
                    <div class="QA_table mb-0 transaction-table">
                        <!-- table-responsive -->
                        <div class="table-responsive">
                            <table class="table  ">
                                <thead class="">
                                    <tr>
                                        <td></td>
                                        <th>Name</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    
                                @foreach ($allStore as $k => $item)
                                <tr>
                                    
                                    <td>
                                        <span class="badge badge-success">{{$k+1}}</span>
                                    </td>
                                    <td> {{$item->store_name}} </td>
                                    <td> {{$item->location}} </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!--/### CHAT_MESSAGE_BOX  ### -->
@endsection
