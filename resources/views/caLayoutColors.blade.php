@inject('userinfo', 'App\UserInfo')
@inject('lang', 'App\Lang')
@extends('bsb.app')

@section('content')
    <!-- Colorpicker Css -->
    <link href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" />
    <!-- Bootstrap Colorpicker Js -->
    <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>

    <style>
        .colorpicker{
            z-index: 1!important;
        }
    </style>

    <div class="header">
        <div class="row clearfix">
            <div class="col-md-12">
                <h3 class="">{{$lang->get(401)}}</h3>
            </div>
        </div>
    </div>
    <div class="body">

        <div id="redalert" class="alert bg-red" style='display:none;' >
        </div>

        <div class="card">
            <div class="header">
            </div>
                <body>
                <div class="table-responsive" style="margin-left: 5%; margin-top: 5%; margin-right: 5%;">
                <table style="margin-bottom: 5%;">
                    <tbody id="tbody">

{{--Title Bar Color--}}
                    <tr>
                        <td>
                            <b>{{$lang->get(402)}}</b>
                            <div class="input-group colorpicker">
                                <div class="form-line">
                                    <input type="text" id="titleBarColor" class="form-control" value="">
                                </div>
                                <span class="input-group-addon">
                                    <i style="border: solid 2px; border-color: #b1b1b1;"></i>
                                </span>
                            </div>
                        </td>
                        <td width="20%"></td>
                        <td>
                            <img src="img/colortb.jpg" width="400px">
                        </td>
                    </tr>

{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>

{{--icon colors--}}

                    <tr>
                        <td>
                            <b>{{$lang->get(403)}}</b>
                            <div class="input-group colorpicker">
                                <div class="form-line">
                                    <input type="text" id="iconColorWhiteMode" class="form-control" >
                                </div>
                                <span class="input-group-addon">
                                    <i style="border: solid 2px; border-color: #b1b1b1;"></i>
                                </span>
                            </div>
                        </td>
                        <td width="20%"></td>
                        <td>
                            <img src="img/iconscolor.jpg" width="400px">
                        </td>
                    </tr>

{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>

{{--Restaurants Title Color--}}

                    <tr>
                        <td>
                            <b>{{$lang->get(404)}}</b>
                            <div class="input-group colorpicker">
                                <div class="form-line">
                                    <input type="text" id="restaurantTitleColor" class="form-control" >
                                </div>
                                <span class="input-group-addon">
                                    <i style="border: solid 2px; border-color: #b1b1b1;"></i>
                                </span>
                            </div>
                        </td>
                        <td width="20%"></td>
                        <td>
                            <img src="img/resttitle.jpg" width="400px">
                        </td>
                    </tr>

{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>

{{--Restaurants Background Color--}}

                    <tr>
                        <td>
                            <b>{{$lang->get(405)}}</b>
                            <div class="input-group colorpicker">
                                <div class="form-line">
                                    <input type="text" id="restaurantBackgroundColor" class="form-control" >
                                </div>
                                <span class="input-group-addon">
                                    <i style="border: solid 2px; border-color: #b1b1b1;"></i>
                                </span>
                            </div>
                        </td>
                        <td width="20%"></td>
                        <td>
                            <img src="img/restback.jpg" width="400px">
                        </td>
                    </tr>

{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>

{{--Most Popular Title Color--}}

                    <tr>
                        <td>
                            <b>{{$lang->get(406)}}</b>
                            <div class="input-group colorpicker">
                                <div class="form-line">
                                    <input type="text" id="dishesTitleColor" class="form-control" >
                                </div>
                                <span class="input-group-addon">
                                    <i style="border: solid 2px; border-color: #b1b1b1;"></i>
                                </span>
                            </div>
                        </td>
                        <td width="20%"></td>
                        <td>
                            <img src="img/mptitle.jpg" width="400px">
                        </td>
                    </tr>

{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>

{{--Most Popular Background Color--}}

                    <tr>
                        <td>
                            <b>{{$lang->get(407)}}</b>
                            <div class="input-group colorpicker">
                                <div class="form-line">
                                    <input type="text" id="dishesBackgroundColor" class="form-control" >
                                </div>
                                <span class="input-group-addon">
                                    <i style="border: solid 2px; border-color: #b1b1b1;"></i>
                                </span>
                            </div>
                        </td>
                        <td width="20%"></td>
                        <td>
                            <img src="img/mpback.jpg" width="400px">
                        </td>
                    </tr>

{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>

{{--Categories Title Color--}}

                    <tr>
                        <td>
                            <b>{{$lang->get(408)}}</b>
                            <div class="input-group colorpicker">
                                <div class="form-line">
                                    <input type="text" id="categoriesTitleColor" class="form-control" >
                                </div>
                                <span class="input-group-addon">
                                    <i style="border: solid 2px; border-color: #b1b1b1;"></i>
                                </span>
                            </div>
                        </td>
                        <td width="20%"></td>
                        <td>
                            <img src="img/cattitle.jpg" width="400px">
                        </td>
                    </tr>

{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>

{{--Categories Background Title Color--}}

                    <tr>
                        <td>
                            <b>{{$lang->get(409)}}</b>
                            <div class="input-group colorpicker">
                                <div class="form-line">
                                    <input type="text" id="categoriesBackgroundColor" class="form-control" value="">
                                </div>
                                <span class="input-group-addon">
                                    <i style="border: solid 2px; border-color: #b1b1b1;"></i>
                                </span>
                            </div>
                        </td>
                        <td width="20%"></td>
                        <td>
                            <img src="img/catback.jpg" width="400px">
                        </td>
                    </tr>

{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>

{{--Reviews Title Color--}}

                    <tr>
                        <td>
                            <b>{{$lang->get(410)}}</b>
                            <div class="input-group colorpicker">
                                <div class="form-line">
                                    <input type="text" id="reviewTitleColor" class="form-control" value="">
                                </div>
                                <span class="input-group-addon">
                                    <i style="border: solid 2px; border-color: #b1b1b1;"></i>
                                </span>
                            </div>
                        </td>
                        <td width="20%"></td>
                        <td>
                            <img src="img/revcolor.jpg" width="400px">
                        </td>
                    </tr>

{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>

{{--Reviews Background Title Color--}}

                    <tr>
                        <td>
                            <b>{{$lang->get(411)}}</b>
                            <div class="input-group colorpicker">
                                <div class="form-line">
                                    <input type="text" id="reviewBackgroundColor" class="form-control" value="">
                                </div>
                                <span class="input-group-addon">
                                    <i style="border: solid 2px; border-color: #b1b1b1;"></i>
                                </span>
                            </div>
                        </td>
                        <td width="20%"></td>
                        <td>
                            <img src="img/revbk.jpg" width="400px">
                        </td>
                    </tr>

{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>

{{--Search Title Color--}}

                    <tr>
                        <td>
                            <b>{{$lang->get(412)}}</b>
                            <div class="input-group colorpicker">
                                <div class="form-line">
                                    <input type="text" id="searchBackgroundColor" class="form-control" value="">
                                </div>
                                <span class="input-group-addon">
                                    <i style="border: solid 2px; border-color: #b1b1b1;"></i>
                                </span>
                            </div>
                        </td>
                        <td width="20%"></td>
                        <td>
                            <img src="img/search.jpg" width="400px">
                        </td>
                    </tr>


{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>

{{--Bottom Bar Color--}}
                    <tr>
                        <td>
                            <b>{{$lang->get(413)}}</b>
                            <div class="input-group colorpicker">
                                <div class="form-line">
                                    <input type="text" id="bottomBarColor" class="form-control" value="">
                                </div>
                                <span class="input-group-addon">
                                    <i style="border: solid 2px; border-color: #b1b1b1;"></i>
                                </span>
                            </div>
                        </td>
                        <td width="20%"></td>
                        <td>
                            <img src="img/colorbb.jpg" width="400px">
                        </td>
                    </tr>

{{--line--}}        <tr><td><hr></td><td><hr></td><td><hr></td></tr>

{{--save button--}}

                    <tr>
                        <td>
                            <div align="right">
                                <div class="q-btn-all q-color-second-bkg waves-effect" onClick="onSave()"><h4>{{$lang->get(142)}}</h4></div> {{--Save--}}
                            </div>
                        </td>
                        <td></td>
                        <td>
                            @include('elements.form.info', array('title' => $lang->get(555), 'body1' => $lang->get(556), 'body2' => $lang->get(557)))  {{--Attention! - --}}
                        </td>
                    </tr>

                    </tbody>
                </table>

            </div>
</body>

        </div>

        <script>

        $(function () {
            $('.colorpicker').colorpicker(
                {format: 'hex'}
            );
        });

        document.getElementById("iconColorWhiteMode").value = "{{$iconColorWhiteMode}}" ;
        document.getElementById("restaurantTitleColor").value = "{{$restaurantTitleColor}}" ;
        document.getElementById("restaurantBackgroundColor").value = "{{$restaurantBackgroundColor}}" ;
        document.getElementById("dishesBackgroundColor").value = "{{$dishesBackgroundColor}}" ;
        document.getElementById("dishesTitleColor").value = "{{$dishesTitleColor}}" ;
        document.getElementById("categoriesTitleColor").value = "{{$categoriesTitleColor}}" ;
        document.getElementById("categoriesBackgroundColor").value = "{{$categoriesBackgroundColor}}" ;
        document.getElementById("searchBackgroundColor").value = "{{$searchBackgroundColor}}" ;
        document.getElementById("reviewTitleColor").value = "{{$reviewTitleColor}}" ;
        document.getElementById("reviewBackgroundColor").value = "{{$reviewBackgroundColor}}" ;
        document.getElementById("bottomBarColor").value = "{{$bottomBarColor}}" ;
        document.getElementById("titleBarColor").value = "{{$titleBarColor}}" ;

        function onSave(){
            var iconColorWhiteMode = document.getElementById("iconColorWhiteMode").value;
            iconColorWhiteMode = iconColorWhiteMode.substring(1);
            var restaurantTitleColor = document.getElementById("restaurantTitleColor").value;
            restaurantTitleColor = restaurantTitleColor.substring(1);
            var restaurantBackgroundColor = document.getElementById("restaurantBackgroundColor").value;
            restaurantBackgroundColor = restaurantBackgroundColor.substring(1);
            var dishesBackgroundColor = document.getElementById("dishesBackgroundColor").value;
            dishesBackgroundColor = dishesBackgroundColor.substring(1);
            var dishesTitleColor = document.getElementById("dishesTitleColor").value;
            dishesTitleColor = dishesTitleColor.substring(1);
            var categoriesTitleColor = document.getElementById("categoriesTitleColor").value;
            categoriesTitleColor = categoriesTitleColor.substring(1);
            var categoriesBackgroundColor = document.getElementById("categoriesBackgroundColor").value;
            categoriesBackgroundColor = categoriesBackgroundColor.substring(1);
            var searchBackgroundColor = document.getElementById("searchBackgroundColor").value;
            searchBackgroundColor = searchBackgroundColor.substring(1);
            var reviewTitleColor = document.getElementById("reviewTitleColor").value;
            reviewTitleColor = reviewTitleColor.substring(1);
            var reviewBackgroundColor = document.getElementById("reviewBackgroundColor").value;
            reviewBackgroundColor = reviewBackgroundColor.substring(1);
            var bottomBarColor = document.getElementById("bottomBarColor").value;
            bottomBarColor = bottomBarColor.substring(1);
            var titleBarColor = document.getElementById("titleBarColor").value;
            titleBarColor = titleBarColor.substring(1);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url("caLayout_changeColors") }}',
                data: {
                    iconColorWhiteMode: iconColorWhiteMode,
                    restaurantTitleColor: restaurantTitleColor,
                    restaurantBackgroundColor : restaurantBackgroundColor,
                    dishesBackgroundColor: dishesBackgroundColor,
                    dishesTitleColor: dishesTitleColor,
                    categoriesTitleColor : categoriesTitleColor,
                    categoriesBackgroundColor : categoriesBackgroundColor,
                    searchBackgroundColor : searchBackgroundColor,
                    reviewTitleColor : reviewTitleColor,
                    reviewBackgroundColor : reviewBackgroundColor,
                    bottomBarColor : bottomBarColor,
                    titleBarColor : titleBarColor,
                },
                success: function (data){
                    console.log(data);
                    showNotification("bg-teal", "{{$lang->get(414)}}", "bottom", "center", "", "");
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
