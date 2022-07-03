@extends('bsb.app')
@inject('lang', 'App\Lang')

@section('content')
    <div class="header">
        <div class="row clearfix">
            <div class="col-md-12">
                <h3 class="">{{$lang->get(201)}}</h3>
            </div>
        </div>
    </div>
    <div class="body">

    <!-- Tabs -->

        <!-- Tab List -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="home">

                <div class="row clearfix js-sweetalert">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h3>
                                    {{$lang->get(202)}}
                                </h3>
                            </div>
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                        <thead>
                                        <tr>
                                            <th>{{$lang->get(69)}}</th>
                                            <th>{{$lang->get(203)}}</th>
                                            <th>{{$lang->get(49)}}</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>{{$lang->get(69)}}</th>
                                            <th>{{$lang->get(203)}}</th>
                                            <th>{{$lang->get(49)}}</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>


                                        @foreach($idata as $key => $data)
                                            <tr id="tr{{$data->id}}">
                                                <td>{{$data->role}}</td>

                                                <td>
                                                    @if ($data->default == "true")
                                                        <img src="img/iconyes.png" width="40px">
                                                    @else
                                                        <img src="img/iconno.png" width="40px">
                                                    @endif
                                                </td>

                                                <td>{{$data->updated_at}}</td>
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

        <!-- End Tab List -->



@endsection

@section('content2')

@endsection
