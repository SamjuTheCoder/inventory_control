@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="white_card card_height_100 mb_30">
          <div class="white_card_header">
              <div class="box_header m-0">
                  <div class="main-title">
                      <h3 class="m-0">Basic Table</h3>
                  </div>
              </div>
          </div>
          <div class="white_card_body">

            <h6 class="card-subtitle mb_20">Just add the base class  <code>.table</code> and
              <code>&lt;table&gt;</code>.</h6>
              <div class="table-responsive">
                  <table class="table">
                      <thead>
                          <tr>
                              <th scope="col">#</th>
                              <th scope="col">First</th>
                              <th scope="col">Last</th>
                              <th scope="col">Handle</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <th scope="row">1</th>
                              <td>Mark</td>
                              <td>Otto</td>
                              <td>@mdo</td>
                          </tr>
                          <tr>
                              <th scope="row">2</th>
                              <td>Jacob</td>
                              <td>Thornton</td>
                              <td>@fat</td>
                          </tr>
                          <tr>
                              <th scope="row">3</th>
                              <td>Larry</td>
                              <td>the Bird</td>
                              <td>@twitter</td>
                          </tr>
                      </tbody>
                  </table>
              </div>
      
          </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="white_card card_height_100 mb_30">
          <div class="white_card_header">
              <div class="box_header m-0">
                  <div class="main-title">
                      <h3 class="m-0">Dark Layout Table</h3>
                  </div>
              </div>
          </div>
          <div class="white_card_body">

            <h6 class="card-subtitle mb_20">You can also invert the colors—with light text on dark backgrounds—with <code class="highlighter-rouge">.table-dark</code>.</h6>
            <div class="table-responsive">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Larry</td>
                            <td>the Bird</td>
                            <td>@twitter</td>
                        </tr>
                    </tbody>
                </table>                                            
            </div>
        
          </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="white_card card_height_100 mb_30">
          <div class="white_card_header">
              <div class="box_header m-0">
                  <div class="main-title">
                      <h3 class="m-0">Table head options</h3>
                  </div>
              </div>
          </div>
          <div class="white_card_body">

            
            <h6 class="card-subtitle mb_20">Similar to tables and dark tables, use the modifier classes <code class="highlighter-rouge">.thead-light</code> or <code class="highlighter-rouge">.thead-dark</code> to make <code class="highlighter-rouge">&lt;thead&gt;</code>s appear light or dark gray.</h6>
            <div class="table-responsive m-b-30">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Larry</td>
                            <td>the Bird</td>
                            <td>@twitter</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Larry</td>
                            <td>the Bird</td>
                            <td>@twitter</td>
                        </tr>
                    </tbody>
                </table>          
            </div>
        
        
          </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="white_card card_height_100 mb_30">
          <div class="white_card_header">
              <div class="box_header m-0">
                  <div class="main-title">
                      <h3 class="m-0">Striped rows</h3>
                  </div>
              </div>
          </div>
          <div class="white_card_body">

            <h6 class="card-subtitle mb_20">Use <code class="highlighter-rouge">.table-striped</code> to add zebra-striping to any table row within the <code class="highlighter-rouge">&lt;tbody&gt;</code>.</h6>
            <div class="table-responsive m-b-30">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Larry</td>
                            <td>the Bird</td>
                            <td>@twitter</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-dark">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Larry</td>
                            <td>the Bird</td>
                            <td>@twitter</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        
          </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="white_card card_height_100 mb_30">
          <div class="white_card_header">
              <div class="box_header m-0">
                  <div class="main-title">
                      <h3 class="m-0">Bordered table</h3>
                  </div>
              </div>
          </div>
          <div class="white_card_body">

            <h6 class="card-subtitle mb_20">Add <code class="highlighter-rouge">.table-bordered</code> for borders on all sides of the table and cells.</h6>
            <div class="table-responsive m-b-30">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td colspan="2">Larry the Bird</td>
                            <td>@twitter</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-dark">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td colspan="2">Larry the Bird</td>
                            <td>@twitter</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        
        
          </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="white_card card_height_100 mb_30">
          <div class="white_card_header">
              <div class="box_header m-0">
                  <div class="main-title">
                      <h3 class="m-0">Bordered table</h3>
                  </div>
              </div>
          </div>
          <div class="white_card_body">

            <h6 class="card-subtitle mb_20">Add <code class="highlighter-rouge">.table-borderless</code> for a table without borders. <code class="highlighter-rouge">.table-borderless</code> can also be used on dark tables.</h6>
            <div class="table-responsive m-b-30">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td colspan="2">Larry the Bird</td>
                            <td>@twitter</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless table-dark">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td colspan="2">Larry the Bird</td>
                            <td>@twitter</td>
                        </tr>
                    </tbody>
                </table>
            </div>
          </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="white_card card_height_100 mb_30">
          <div class="white_card_header">
              <div class="box_header m-0">
                  <div class="main-title">
                      <h3 class="m-0">Contextual classes</h3>
                  </div>
              </div>
          </div>
          <div class="white_card_body">

            <h6 class="card-subtitle mb_20">Regular table background variants are not available with the dark table, however, you may use <a href="https://demo.dashboardpack.com/docs/4.1/utilities/colors/">text or background utilities</a> to achieve similar styles.</h6>
            <div class="table-responsive">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Heading</th>
                            <th scope="col">Heading</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-primary">
                            <th scope="row">1</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr class="bg-success">
                            <th scope="row">3</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr>
                            <th scope="row">4</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr class="bg-info">
                            <th scope="row">5</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr>
                            <th scope="row">6</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr class="bg-warning">
                            <th scope="row">7</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr>
                            <th scope="row">8</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr class="bg-danger">
                            <th scope="row">9</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                    </tbody>
                </table>                                            
            </div>
        
          </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="white_card card_height_100 mb_30">
          <div class="white_card_header">
              <div class="box_header m-0">
                  <div class="main-title">
                      <h3 class="m-0">Contextual classes</h3>
                  </div>
              </div>
          </div>
          <div class="white_card_body">

            <h6 class="card-subtitle mb_20">Use contextual classes to color table rows or individual cells.</h6>
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">Class</th>
                            <th scope="col">Heading</th>
                            <th scope="col">Heading</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-active">
                            <th scope="row">Active</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr>
                            <th scope="row">Default</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>                                        
                        <tr class="table-primary">
                            <th scope="row">Primary</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr class="table-secondary">
                            <th scope="row">Secondary</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr class="table-success">
                            <th scope="row">Success</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr class="table-danger">
                            <th scope="row">Danger</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr class="table-warning">
                            <th scope="row">Warning</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr class="table-info">
                            <th scope="row">Info</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr class="table-light">
                            <th scope="row">Light</th>
                            <td>Cell</td>
                            <td>Cell</td>
                        </tr>
                        <tr class="table-dark">
                            <th scope="row" class="border-top-0">Dark</th>
                            <td class="border-top-0">Cell</td>
                            <td class="border-top-0">Cell</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        
          </div>
      </div>
    </div>
</div>
@endsection
