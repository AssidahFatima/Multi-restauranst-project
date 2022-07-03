@extends('bsb.app')
@inject('lang', 'App\Lang')

@section('content')
    <div class="header">
        <div class="row clearfix">
            <div class="col-md-12">
                <h3 class="">{{$lang->get(430)}}</h3>
            </div>
        </div>
    </div>
    <div class="body">

        <!-- Tabs -->
        <ul class="nav nav-tabs tab-nav-right" role="tablist">
            <li role="presentation" class="active"><a href="#home" data-toggle="tab"><h4>{{$lang->get(431)}}</h4></a></li>
            <li id="tabEdit" role="presentation"><a href="#api" data-toggle="tab"><h4>{{$lang->get(432)}}</h4></a></li>
        </ul>

        <!-- Tab List -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="home">

                <div class="row clearfix js-sweetalert">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h3>
                                    {{$lang->get(433)}}
                                </h3>
                            </div>
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                        <thead>
                                        <tr>
                                            <th>{{$lang->get(68)}}</th>
                                            <th>{{$lang->get(69)}}</th>
                                            <th>{{$lang->get(434)}}</th>
                                            <th>{{$lang->get(284)}}</th>
                                            <th>{{$lang->get(49)}}</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>{{$lang->get(68)}}</th>
                                            <th>{{$lang->get(69)}}</th>
                                            <th>{{$lang->get(434)}}</th>
                                            <th>{{$lang->get(284)}}</th>
                                            <th>{{$lang->get(49)}}</th>
                                        </tr>
                                        </tfoot>
                                        <tbody id="tbody">

                                        @foreach($logging as $key => $data)


                                            <tr>
                                                <td>{{$data->id}}</td>
                                                <td>{{$data->user}}</td>
                                                <td>{{$data->param5}}</td>
                                                <td>{{$data->param1}} {{$data->param2}} {{$data->param3}}</td>
                                                <td>{{$data->updated_at}}</td>
                                            </tr>

                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                                <div align="center">
                                <nav>
                                    <ul class="pagination">
                                        <li id="lid0" class="active"><a href="javascript:setPage(0);" class="waves-effect">1</a></li>
                                        @for ($i = 1; $i < $loggingCount; $i++)
                                            <li id="lid{{$i}}"><a href="javascript:setPage({{$i}});" class="waves-effect">{{$i+1}}</a></li>
                                        @endfor

                                    </ul>
                                </nav>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

            <!-- End Tab List -->
            <div role="tabpanel" class="tab-pane fade " id="api">

                <div class="row clearfix js-sweetalert">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h3>
                                    {{$lang->get(435)}}
                                </h3>
                            </div>
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                        <thead>
                                        <tr>
                                            <th>{{$lang->get(68)}}</th>
                                            <th>{{$lang->get(69)}}</th>
                                            <th>{{$lang->get(434)}}</th>
                                            <th>{{$lang->get(284)}}</th>
                                            <th>{{$lang->get(49)}}</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>{{$lang->get(68)}}</th>
                                            <th>{{$lang->get(69)}}</th>
                                            <th>{{$lang->get(434)}}</th>
                                            <th>{{$lang->get(284)}}</th>
                                            <th>{{$lang->get(49)}}</th>
                                        </tr>
                                        </tfoot>
                                        <tbody id="tbodyA">

                                        @foreach($loggingApi as $key => $data)
                                                <tr>
                                                    <td>{{$data->id}}</td>
                                                    <td>{{$data->user}}</td>

                                                    <td>{{$data->param5}}</td>
                                                    <td>{{$data->param1}} {{$data->param2}} {{$data->param3}}</td>
                                                    <td>{{$data->updated_at}}</td>
                                                </tr>

                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                                <div align="center">
                                    <nav>
                                        <ul class="pagination">
                                            <li id="alid0" class="active"><a href="javascript:setPageA(0);" class="waves-effect">1</a></li>
                                            @for ($i = 1; $i < $loggingCountApi; $i++)
                                                <li id="alid{{$i}}"><a href="javascript:setPageA({{$i}});" class="waves-effect">{{$i+1}}</a></li>
                                            @endfor

                                        </ul>
                                    </nav>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        lastPage = 0;
        currentPage = 0;

        function setPage(page){
            currentPage = page;
            if (lastPage == currentPage)
                return;
            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ url("loggingPage") }}',
                    data: {
                        '_token': $('meta[name="_token"]').attr('content'),
                        page: page,
                    },
                    success: function (jsonData) {
                        document.getElementById("tbody").innerHTML = '';
                        for (var i = 0; i < jsonData.logging.length; i++) {
                            var div = document.createElement("tr");
                            var logging = jsonData.logging[i];
                            div.innerHTML = '<tr> <td>' + logging.id + '</td><td>' + logging.user + '</td><td>' + logging.param5 + '</td>' +
                                '<td>' + logging.param1 + logging.param2 + logging.param3 + '</td><td>' + logging.updated_at + '</td></tr>';
                            document.getElementById("tbody").appendChild(div);
                        }

                        document.getElementById("lid"+lastPage).className = "";
                        document.getElementById("lid"+currentPage).className = "active";
                        lastPage = currentPage;
                    },
                    error: function (e) {
                        console.log(e);
                    }
                }
            );
        }


        lastPageA = 0;
        currentPageA = 0;

        function setPageA(page){
            currentPageA = page;
            if (lastPageA == currentPageA)
                return;
            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ url("loggingPage") }}',
                    data: {
                        '_token': $('meta[name="_token"]').attr('content'),
                        page: page,
                        type: "api",
                    },
                    success: function (jsonData) {
                        document.getElementById("tbodyA").innerHTML = '';
                        for (var i = 0; i < jsonData.logging.length; i++) {
                            var div = document.createElement("tr");
                            var logging = jsonData.logging[i];
                            div.innerHTML = '<tr> <td>' + logging.id + '</td><td>' + logging.user + '</td><td>' + logging.param5 + '</td>' +
                                '<td>' + logging.param1 + logging.param2 + logging.param3 + '</td><td>' + logging.updated_at + '</td></tr>';
                            document.getElementById("tbodyA").appendChild(div);
                        }

                        document.getElementById("alid"+lastPageA).className = "";
                        document.getElementById("alid"+currentPageA).className = "active";
                        lastPageA = currentPageA;
                    },
                    error: function (e) {
                        console.log(e);
                    }
                }
            );
        }

    </script>
@endsection

@section('content2')

@endsection
