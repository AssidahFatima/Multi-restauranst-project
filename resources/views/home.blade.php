@inject('userinfo', 'App\UserInfo')
@extends('bsb.app')
@inject('lang', 'App\Lang')

@section('content')

    <!-- ChartJs -->
    <script src="plugins/chartjs/Chart.bundle.js"></script>

    <div class="body">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div id="orders" class="info-box hover-zoom-effect"style="background-color: #4badfb;color:#fff">
                    <div class="icon">
                        <i class="material-icons">payment</i>
                    </div>
                    <div class="content">
                        <div class="font-30 font-bold">{{$currency}}{{$earning}}</div>
                        <div class="font-15">{{$lang->get(38)}}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div id="orders4" class="info-box hover-zoom-effect" style="background-color: #956edb;color:#fff">
                    <div class="icon">
                        <i class="material-icons">assessment</i>
                    </div>
                    <div class="content">
                        <div class="font-30 font-bold">{{$orderscount}}</div>
                        <div class="font-15">{{$lang->get(39)}}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div id="users" class="info-box  hover-zoom-effect"style="background-color: #4badfb;color:#fff">
                <div class="icon">
                    <i class="material-icons">person_outline</i>
                </div>
                <div class="content">
                    <div class="font-30 font-bold">{{$userscount}}</div>
                    <div class="font-15">{{$lang->get(40)}}</div>
                </div>
            </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div id="restaurants" class="info-box  hover-zoom-effect" style="background-color: #956edb;color:#fff">
                    <div class="icon">
                        <i class="material-icons">restaurant</i>
                    </div>
                    <div class="content">
                        <div class="font-30 font-bold">{{$restaurantsCount}}</div>
                        <div class="font-15">{{$lang->get(41)}}</div>
                    </div>
                </div>
            </div>

        <div id="orders2" class="col-md-12">
            <div class="card">
                <div class="body">
                    <canvas id="line_chart" height="50"></canvas>
                </div>
            </div>
        </div>

        <div id="orders3" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h3>
                                {{$lang->get(42)}}
                            </h3>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                    <tr>
                                        <th>{{$lang->get(43)}}</th>
                                        <th>{{$lang->get(44)}}</th>
                                        <th>{{$lang->get(45)}}</th>
                                        <th>{{$lang->get(46)}}</th>
                                        <th>{{$lang->get(47)}}</th>
                                        <th>{{$lang->get(48)}}</th>
                                        <th>{{$lang->get(49)}}</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>{{$lang->get(43)}}</th>
                                        <th>{{$lang->get(44)}}</th>
                                        <th>{{$lang->get(45)}}</th>
                                        <th>{{$lang->get(46)}}</th>
                                        <th>{{$lang->get(47)}}</th>
                                        <th>{{$lang->get(48)}}</th>
                                        <th>{{$lang->get(49)}}</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>

                                    @foreach($iorders as $key => $data)
                                        @if ($data->send == 1)
                                            <tr id="tr{{$data->id}}">
                                                <td>{{$data->id}}</td>
                                                <td id="total{{$data->id}}">{{$currency}}{{$data->total}}</td>
                                                <td>
                                                    @foreach($iusers as $key => $idata)
                                                        @if ($idata->id == $data->user)
                                                            {{$idata->name}}
                                                        @endif
                                                    @endforeach
                                                </td>

                                                <td>
                                                    @foreach($iorderstatus as $key => $idata)
                                                        @if ($idata->id == $data->status)
                                                            {{$idata->status}}
                                                        @endif
                                                    @endforeach
                                                </td>

                                                <td>
                                                    @foreach($irestaurants as $key => $idata)
                                                        @if ($idata->id == $data->restaurant)
                                                            {{$idata->name}}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if ($data->curbsidePickup == "true")
                                                        <span class="badge bg-red">Curbside Pickup</span>
                                                    @endif
                                                    @if ($data->arrived == "true")
                                                        <span class="badge bg-red">Customer arrived</span><br>
                                                    @else
                                                        <br>
                                                    @endif
                                                    <span class="badge bg-teal">{{$data->method}}</span>
                                                </td>
                                                <td>{{$data->updated_at}}</td>
                                            </tr>
                                        @endif

                                    @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


    </div>


    <script>
        new Chart(document.getElementById("line_chart").getContext("2d"), getChartJs('line'));

        function getChartJs(type) {
            var config = null;

            if (type === 'line') {
                config = {
                    type: 'line',
                    data: {
                        labels: ["{{$lang->get(50)}}", "{{$lang->get(51)}}", "{{$lang->get(52)}}", "{{$lang->get(53)}}",
                            "{{$lang->get(54)}}", "{{$lang->get(55)}}", "{{$lang->get(56)}}", "{{$lang->get(57)}}",
                            "{{$lang->get(58)}}", "{{$lang->get(59)}}", "{{$lang->get(60)}}", "{{$lang->get(61)}}"],
                        datasets: [{
                            label: "{{$lang->get(62)}}",
                            data: [{{$e1}}, {{$e2}}, {{$e3}}, {{$e4}}, {{$e5}}, {{$e6}}, {{$e7}}, {{$e8}}, {{$e9}}, {{$e10}}, {{$e11}}, {{$e12}}],
                            borderColor: '#673ab7',
                            backgroundColor: '#30136499',
                            pointBorderColor: '#673ab7',
                            pointBackgroundColor: '#673ab7',
                            pointBorderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        legend: false
                    }
                }
            }
            return config;
        }

        var orders = document.getElementById('orders');
        orders.style.cursor = 'pointer';
        orders.onclick = function() {
            window.location='orders';
        };

        var orders2 = document.getElementById('orders2');
        orders2.style.cursor = 'pointer';
        orders2.onclick = function() {
            window.location='orders';
        };

        var orders3 = document.getElementById('orders3');
        orders3.style.cursor = 'pointer';
        orders3.onclick = function() {
            window.location='orders';
        };

        var orders4 = document.getElementById('orders4');
        orders4.style.cursor = 'pointer';
        orders4.onclick = function() {
            window.location='orders';
        };

        var users = document.getElementById('users');
        users.style.cursor = 'pointer';
        users.onclick = function() {
            window.location='users';
        };

        var restaurants = document.getElementById('restaurants');
        restaurants.style.cursor = 'pointer';
        restaurants.onclick = function() {
            window.location='restaurants';
        };

    </script>
@endsection
