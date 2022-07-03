@extends('bsb.app')
@inject('lang', 'App\Lang')

@section('content')
    <div class="header">
        <div class="row clearfix">
            <div class="col-md-12">
                <h3 class="">{{$lang->get(204)}}</h3>
            </div>
        </div>
    </div>
    <div class="body">

    <!-- Tabs -->

        <!-- Tab List -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="home">
                @if ($texton == "green")
                    <div class="alert bg-green" >
                        {{$text}}
                    </div>
                @endif

                <div class="row clearfix js-sweetalert">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h3>
                                    {{$lang->get(205)}}
                                </h3>
                            </div>
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                        <thead>
                                        <tr>
                                            <th>{{$lang->get(127)}}</th>
                                            <th>{{$lang->get(206)}}</th>
                                            <th>{{$lang->get(207)}}</th>
                                            <th>{{$lang->get(208)}}</th>
                                            <th>{{$lang->get(209)}}</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>{{$lang->get(127)}}</th>
                                            <th>{{$lang->get(206)}}</th>
                                            <th>{{$lang->get(207)}}</th>
                                            <th>{{$lang->get(208)}}</th>
                                            <th>{{$lang->get(209)}}</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>

                                        @foreach($idata as $key => $data)
                                            <tr id="tr{{$data->id}}">
                                                <td>{{$data->value}}</td>

                                                <td>
                                                    @if ($data->role1 == "1")
                                                        @include('elements.form.check', array('id' => "id1_" . $data->id, 'text' => "", 'initvalue' => "true"))
                                                    @else
                                                        @include('elements.form.check', array('id' => "id1_" . $data->id, 'text' => "", 'initvalue' => "false"))
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($data->role2 == "1")
                                                        @include('elements.form.check', array('id' => "id2_" . $data->id, 'text' => "", 'initvalue' => "true"))
                                                    @else
                                                        @include('elements.form.check', array('id' => "id2_" . $data->id, 'text' => "", 'initvalue' => "false"))
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($data->role3 == "1")
                                                        @include('elements.form.check', array('id' => "id3_" . $data->id, 'text' => "", 'initvalue' => "true"))
                                                    @else
                                                        @include('elements.form.check', array('id' => "id3_" . $data->id, 'text' => "", 'initvalue' => "false"))
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($data->role4 == "1")
                                                        @include('elements.form.check', array('id' => "id4_" . $data->id, 'text' => "", 'initvalue' => "true"))
                                                    @else
                                                        @include('elements.form.check', array('id' => "id4_" . $data->id, 'text' => "", 'initvalue' => "false"))
                                                    @endif
                                                </td>

                                            </tr>

                                        @endforeach

                                        </tbody>
                                    </table>

                                </div>
                                @include('elements.form.button', array('label' => $lang->get(142), 'onclick' => "onSave();"))  {{-- Save --}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        <!-- End Tab List -->

<script>

    function onSave(){
        var data = {
        };
        @foreach($idata as $key => $value)
            data.id1_{{$value->id}} = (id1_{{$value->id}}) ? "1" : "0";
            data.id2_{{$value->id}} = (id2_{{$value->id}}) ? "1" : "0";
            data.id3_{{$value->id}} = (id3_{{$value->id}}) ? "1" : "0";
            data.id4_{{$value->id}} = (id4_{{$value->id}}) ? "1" : "0";
        @endforeach
            console.log(data);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'POST',
            url: '{{ url("permissionSet") }}',
            data: data,
            success: function (data){
                console.log(data);
                if (data.error == "3")
                    return showNotification("bg-red", data.text, "bottom", "center", "", "");  // This is demo app. You can't change this section
                if (data.error == "2")
                    return showNotification("bg-red", "{{$lang->get(486)}}", "bottom", "center", "", "");  // Change Permissions can only Adminnistrator
                if (data.error != "0")
                    return showNotification("bg-red", "{{$lang->get(479)}}", "bottom", "center", "", "");  // Something went wrong
                else
                    return showNotification("bg-teal", "{{$lang->get(485)}}", "bottom", "center", "", "");  // 'Data saved',
            },
            error: function(e) {
                showNotification("bg-red", "{{$lang->get(479)}}", "bottom", "center", "", "");  // Something went wrong
                console.log(e);
            }}
        );
    }

</script>

@endsection

