@inject('userinfo', 'App\UserInfo')
@inject('lang', 'App\Lang')
@extends('bsb.app')

@section('content')
    <div class="header">
        <div class="row clearfix">
            <div class="col-md-12">
                <h3 class="">{{$lang->get(27)}}</h3>        {{--Settings--}}
            </div>
        </div>
    </div>
    <div class="body">

        <div class="card">
            <div class="header">
                <h4>
                </h4>
            </div>
            <body>
            <div class="table-responsive" style="margin-left: 5%; margin-top: 5%; margin-right: 5%" >
                <table style="margin-bottom: 5%;" width="90%">
                    <tbody id="tbody">

{{--Tax--}}
                    <tr>
                        <td width="50%">
                            <b>{{$lang->get(318)}}:</b>
                            <div class="input-group">
                                <div class="form-line">
                                    <input type="number" id="tax" class="form-control" value="" min="0" max="100" step="1">
                                </div>
                                <p>{{$lang->get(319)}}</p>
                            </div>
                        </td>
                        <td width="50%"><div></div></td>
                    </tr>

{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>


{{--km or miles--}}
                    <tr>
                        <td width="50%">
                            <b>{{$lang->get(320)}}:</b>
                                <div class="form-group form-group-lg form-float">
                                    <select name="distanceUnit" id="distanceUnit" class="form-control show-tick ">
                                        @if ($distanceUnit == 'km')
                                            <option value="km" style="font-size: 16px  !important;" selected>Km</option>
                                            <option value="mi" style="font-size: 16px  !important;">Miles</option>
                                        @else
                                            <option value="mi" style="font-size: 16px  !important;" selected>Miles</option>
                                            <option value="km" style="font-size: 16px  !important;">Km</option>
                                        @endif
                                    </select>
                                    <label class="font-12 font-bold"><p>{{$lang->get(321)}}</p></label>
                            </div>
                        </td>
                        <td width="50%"><div></div></td>
                    </tr>
{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>

{{--timezone--}}
                    <tr>
                        <td width="50%">
                            <b>{{$lang->get(468)}}:</b>   {{--"Set Time Zone",--}}
                            <div class="form-group form-group-lg form-float">
                                <select name="timezone" id="timezone" class="form-control show-tick" data-size="5">
                                    @foreach($timezonesArray as $key => $data)
                                        @if ($data == $timezone)
                                            <option value="{{$data}}" style="font-size: 16px  !important;" selected>{{$data}}</option>
                                        @else
                                            <option value="{{$data}}" style="font-size: 16px  !important;">{{$data}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <label class="font-12 font-bold"><p>{{$lang->get(469)}}</p></label>    {{--"Select default Time Zone for Admin Panel",--}}
                            </div>
                        </td>
                        <td width="50%"><div></div></td>
                    </tr>
{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>


{{--Firebase Cloud Messaging Key--}}

                    <th colspan="3">
                        <b>{{$lang->get(322)}}:</b>
                        <div class="input-group">
                            <div class="form-line">
                                <input type="text" id="firebase" class="form-control">
                            </div>
                            <p style="font-weight: 400;">{{$lang->get(323)}}</p>
                        </div>
                    </th>

{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>

{{--Google Maps Api Key--}}

                    <tr>
                        <td width="50%">
                        <b>Google Maps Api Key:</b>
                        <div class="input-group">
                            <div class="form-line">
                                <input type="text" id="mapapikey" class="form-control">
                            </div>
                            <p style="font-weight: 400;">{{$lang->get(324)}}</p>
                        </div>
                        </td>
                        <td width="5%">
                        </td>
                        <td width="45%">
                            {{$lang->get(325)}}
                            <a href="https://developers.google.com/maps/gmp-get-started">https://developers.google.com/maps/gmp-get-started.</a>
                            <br>
                            {{$lang->get(326)}}
                            <a href="https://www.valeraletun.ru/codecanyon/delivery/documentation/index.html#/?id=create-your-own-google-maps-api-key">documentation</a>
                        </td>
                    </tr>
{{--save--}}
                    <tr>
                        <td width="70%">
                            <div align="right">
                                <div class="q-btn-all q-color-second-bkg waves-effect" onClick="onSave()"><h4>{{$lang->get(142)}}</h4></div>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>

{{--line--}}        <hr>

            <div align="center">
            <form id="form" method="post" action="{{url('settingsSetLang')}}"  >
                @csrf
                <div class="row clearfix">
                    <div class="col-md-4 form-control-label">
{{--Select Language for Admin Panel--}}
                        <label for="name"><h4>{{$lang->get(436)}}</h4></label>
                    </div>
                    <div class="col-md-4">
                        <select name="newLang" id="newLang" class="form-control show-tick" style="font-size: 26px  !important; ">
                            @foreach($langs as $key => $data)
                                @if ($defLang == $data["file"])
                                    <option value="{{$data['file']}}" selected style="font-size: 18px  !important;" >{{$data["name"]}} - {{$data["name2"]}}</option>
                                @else
                                    <option value="{{$data['file']}}" style="font-size: 18px  !important;">{{$data["name"]}} - {{$data["name2"]}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

{{--Set language--}}
                    <div class="col-md-4">
                        <button type="submit" class="q-btn-all q-color-second-bkg waves-effect"><h5>{{$lang->get(437)}}</h5></button>   {{--Set language--}}
                    </div>
                </div>

            </form>
            </div>

            </body>
        </div>
    <script>

        var tax = document.getElementById('tax');
        tax.addEventListener('input',  function(e){inputHandler(e, tax, 0, 100);});

        // init parameters

        document.getElementById("tax").value = "{{$tax}}" ;
        document.getElementById("firebase").value = "{{$firebase}}" ;
        document.getElementById("mapapikey").value = "{{$mapapikey}}" ;

        function onSave(){
            var firebase = document.getElementById("firebase").value;
            var mapapikey = document.getElementById("mapapikey").value;
            var distanceUnit = document.getElementById("distanceUnit").value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url("settingschange") }}',
                data: {
                    tax: document.getElementById("tax").value,
                    distanceUnit: distanceUnit,
                    firebase: firebase,
                    mapapikey : mapapikey,
                    timezone: document.getElementById("timezone").value,
                },
                success: function (data){
                    console.log(data);
                    showNotification("bg-teal", "Settings Saved", "bottom", "center", "", "");
                },
                error: function(e) {
                    console.log(e);
                }}
            );
        }

    </script>


@endsection

@section('content2')

@endsection
