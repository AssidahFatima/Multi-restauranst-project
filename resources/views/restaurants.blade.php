@inject('userinfo', 'App\UserInfo')
@inject('currency', 'App\Currency')
@inject('lang', 'App\Lang')
@extends('bsb.app')

@section('content')

    <!-- Multi Select Css -->
    <link href="plugins/multi-select/css/multi-select.css" rel="stylesheet">
    <!-- Multi Select Plugin Js -->
    <script src="plugins/multi-select/js/jquery.multi-select.js"></script>

    <!-- Input Mask Plugin Js -->
    <script src="plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

    <div class="header">
        <div class="row clearfix">
            <div class="col-md-12">
                <h3 class="">{{$lang->get(148)}}</h3>
            </div>
        </div>
    </div>
    <div class="body">

    <!-- Tabs -->

        <ul class="nav nav-tabs tab-nav-right" role="tablist">
            <li role="presentation" class="active"><a href="#home" data-toggle="tab"><h4>{{$lang->get(64)}}</h4></a></li>
            @if ($userinfo->getUserPermission("Restaurants::Create"))
            <li role="presentation"><a href="#create" data-toggle="tab" ><h4>{{$lang->get(65)}}</h4></a></li>
            @endif
            <li id="tabEdit" style='display:none;' role="presentation"><a href="#edit" data-toggle="tab"><h4>{{$lang->get(66)}}</h4></a></li>
        </ul>


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
                                    {{$lang->get(149)}}
                                </h3>
                            </div>
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                        <thead>
                                        <tr>
                                            <th>{{$lang->get(69)}}</th>
                                            <th>{{$lang->get(70)}}</th>
                                            <th>{{$lang->get(71)}}</th>
                                            <th>{{$lang->get(150)}}</th> {{--Address--}}
                                            <th>{{$lang->get(151)}}</th> {{--Phone--}}
                                            <th>{{$lang->get(152)}}</th> {{--Mobile Phone--}}
                                            <th>{{$lang->get(612)}}</th> {{--Foods count--}}
                                            <th>{{$lang->get(73)}}</th>
                                            <th>{{$lang->get(72)}}</th>
                                            <th>{{$lang->get(74)}}</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>{{$lang->get(69)}}</th>
                                            <th>{{$lang->get(70)}}</th>
                                            <th>{{$lang->get(71)}}</th>
                                            <th>{{$lang->get(150)}}</th>
                                            <th>{{$lang->get(151)}}</th>
                                            <th>{{$lang->get(152)}}</th>
                                            <th>{{$lang->get(612)}}</th> {{--Foods count--}}
                                            <th>{{$lang->get(73)}}</th>
                                            <th>{{$lang->get(72)}}</th>
                                            <th>{{$lang->get(74)}}</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>


                                        @foreach($restaurants as $key => $data)
                                            <tr id="tr{{$data->id}}">
                                                <td>{{$data->name}}</td>
                                                <td>
                                                    <img src="images/{{$data->filename}}" height="50" style='min-height: 50px; ' alt="">
                                                </td>

                                                <td>{{$data->desc}}</td>

                                                <td>{{$data->address}}</td>

                                                <td>{{$data->phone}}</td>

                                                <td>{{$data->mobilephone}}</td>
                                                <td>{{$data->productsCount}}</td>

                                                <td>
                                                    @if ($data->published == "1")
                                                        <img src="img/iconyes.png" width="40px">
                                                    @else
                                                        <img src="img/iconno.png" width="40px">
                                                    @endif
                                                </td>

                                                <td>{{$data->updated_at}}</td>

                                                <td>
                                                    @if ($userinfo->getUserPermission("Restaurants::Edit"))
                                                    <button type="button" class="btn btn-default waves-effect"
                                                            onclick="editItem('{{$data->id}}','{{$data->name}}', '{{$data->published}}', '{{$data->imageid}}',
                                                                    '{{$data->filename}}', '{{$data->desc}}',
                                                                '{{$data->delivered}}', '{{$data->address}}', '{{$data->phone}}', '{{$data->mobilephone}}',
                                                                '{{$data->lat}}', '{{$data->lng}}', '{{$data->fee}}', '{{$data->percent}}', '{{$data->minAmount}}',
                                                                '{{$data->perkm}}', '{{$data->city}}')">
                                                        <img src="img/iconedit.png" width="25px">
                                                    </button>
                                                    @endif
                                                    @if ($userinfo->getUserPermission("Restaurants::Delete"))
                                                    <button type="button" class="btn btn-default waves-effect" onclick="showDeleteMessage('{{$data->id}}')">
                                                        <img src="img/icondelete.png" width="25px">
                                                    </button>
                                                    @endif
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

        <!-- End Tab List -->

        <!-- Tab Create -->


        <div role="tabpanel" class="tab-pane fade" id="create">

            <div id="redalert" class="alert bg-red" style='display:none;' >

            </div>

            <form id="formcreate" method="post" action="{{url('restorantsadd')}}"  >

            @csrf

            <input type="hidden" id="imageid" name="image"/>
            <input type="hidden" id="cityData" name="cityData"/>

            <div class="row clearfix">

                <div class="col-md-6 foodm">

                    <div class="col-md-3 foodm">
                        <label for="name"><h4>{{$lang->get(69)}} <span class="col-red">*</span></h4></label>
                    </div>
                    <div class="col-md-9 foodm">
                        <div class="form-group form-group-lg form-float">
                            <div class="form-line">
                                <input type="text" name="name" id="name" class="form-control" placeholder="" maxlength="100">
                                <label class="form-label">{{$lang->get(91)}}</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 foodm">
                    </div>
                    <div class="col-md-9 foodm">
                        <div class="form-group">
                            <input type="checkbox" id="visible" name="visible" class="filled-in checkmark" checked>
                            <label for="visible" class="foodlabel"><h4>{{$lang->get(75)}}</h4></label>
                        </div>
                    </div>

                    <div class="col-md-3 foodm" style="margin-top: 10px;">
                        <label for="name"><h4>{{$lang->get(150)}}</h4></label>
                    </div>
                    <div class="col-md-9 foodm " style="margin-top: 20px !important;">
                        <div class="form-group form-group-lg form-float">
                            <div class="form-line">
                                <input type="text" name="address" id="address" class="form-control" placeholder="" maxlength="100">
                                <label class="form-label">{{$lang->get(153)}}</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 foodm">
                        <label><h4>{{$lang->get(151)}}</h4></label>
                    </div>
                    <div class="col-md-9 foodm">
                        <div class="form-group form-group-lg form-float">
                            <div class="form-line">
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="" maxlength="20">
                                <label class="form-label">{{$lang->get(154)}}</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 foodm">
                        <label><h4>{{$lang->get(155)}}</h4></label>
                    </div>
                    <div class="col-md-9 foodm">
                        <div class="form-group form-group-lg form-float">
                            <div class="form-line">
                                <input type="text" name="mobilephone" id="mobilephone" class="form-control" placeholder="" maxlength="20">
                                <label class="form-label">{{$lang->get(156)}}</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 foodm">
                        <label><h4>{{$lang->get(71)}}</h4></label>
                    </div>
                    <div class="col-md-9 foodm">
                        <div class="form-group form-group-lg form-float">
                            <div class="form-line">
                                <input type="text" name="desc" id="desc" class="form-control" placeholder="" maxlength="300">
                                <label class="form-label">{{$lang->get(76)}}</label>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 foodm">
                        <label><h4>{{$lang->get(157)}} <span class="col-red">*</span></h4></label>  {{--Delivery fee--}}
                    </div>
                    <div class="col-md-8 foodm">
                        <div class="form-group form-group-lg form-float">
                            <div class="col-md-12">
                                <input type="number" name="fee" id="fee" class="q-form" placeholder="" min="0" step="0.01">
                            </div>
                            <label class="font-12">{{$lang->get(158)}}</label>          {{--Insert Delivery Fee--}}
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="checkbox" id="percent" name="percent" class="filled-in checkmark">
                                <label for="percent" class="form-label"><h5>{{$lang->get(159)}}</h5></label>     {{--Percents--}}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="checkbox" id="perkm" name="perkm" class="filled-in checkmark">
                                <label for="perkm" class="form-label"><h5>{{$lang->get(611)}}</h5></label>     {{--per kilometer or mile--}}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 info q-mb20" style="margin-top: 5px; margin-left: 20px;">
                        <h4>{{$lang->get(160)}}</h4>            {{--Delivery fee may be in percentages from order or a given amount.--}}
                        <p>{{$lang->get(161)}}</p>              {{--If `percent` CheckBox is clear, the delivery fee in application set a given amount.--}}
                        <p id="current">{{$lang->get(162)}}: {{$currency->currency()}}0</p> {{--Current--}}
                    </div>


                    <div class="col-md-3 foodm">
                        <label><h4>{{$lang->get(163)}}</h4></label>
                    </div>
                    <div class="col-md-9 foodm">
                        <div class="form-group form-group-lg form-float">
                            <div class="form-line">
                                <input type="number" name="area" id="area" class="form-control" placeholder="" value="30">
                                <label class="form-label">{{$lang->get(164)}}</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 foodm">
                        <label><h4>{{$lang->get(550)}}</h4></label>  {{--Minimum purchase amount --}}
                    </div>
                    <div class="col-md-9 foodm">
                        <div class="form-group form-group-lg form-float">
                            <div class="form-line">
                                <input type="number" name="minAmount" id="minAmount" class="form-control" placeholder="" value="0" step="0.01">
                                <label class="form-label">{{$lang->get(551)}}</label> {{-- For ex: 100. If 0 - no Minimum purchase amount --}}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 foodm q-mt10" >
                        <h4>{{$lang->get(642)}}</h4>  {{--Select cities for market--}}
                        <select id="city" class="ms" multiple="multiple">
                        </select>
                    </div>


                </div>

                <div class="col-md-6 foodm">

                    <div class="col-md-3 foodm">
                        <label for="lat"><h4>{{$lang->get(165)}} <span class="col-red">*</span></h4></label>
                    </div>
                    <div class="col-md-9 foodm">
                        <div class="form-group form-group-lg form-float">
                            <div class="form-line">
                                <input type="number" name="lat" id="lat" class="form-control" placeholder="" step="0.00000000000000001">
                                <label class="form-label">{{$lang->get(166)}}</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 foodm">
                        <label for="lng"><h4>{{$lang->get(167)}} <span class="col-red">*</span></h4></label>
                    </div>
                    <div class="col-md-9 foodm">
                        <div class="form-group form-group-lg form-float">
                            <div class="form-line">
                                <input type="number" name="lng" id="lng" class="form-control" placeholder="" step="0.00000000000000001">
                                <label class="form-label">{{$lang->get(168)}}</label>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-3 foodm">
                        <label><h4>{{$lang->get(70)}}:</h4></label>
                        <br>
                        <div align="center">
                            <button type="button" onclick="fromLibrary()" class="q-btn-all q-color-second-bkg waves-effect"><h5>{{$lang->get(77)}}</h5></button>
                        </div>
                    </div>
                    <div class="col-md-9 foodm">
                        <div id="dropzone2" class="fallback dropzone">
                            <div class="dz-message">
                                <div class="drag-icon-cph">
                                    <i class="material-icons">touch_app</i>
                                </div>
                                <h3>{{$lang->get(78)}}</h3>
                            </div>
                            <div class="fallback">
                                <input name="file" type="file" multiple />
                            </div>
                        </div>
                    </div>


                    <div class="col-md-3 foodm">
                        <label><h4>{{$lang->get(169)}}:</h4></label>
                    </div>
                    <div class="col-md-9 foodm" style="margin-top: 20px;">
                        <table border="0">
                            <tr>
                                <td></td>
                                <td></td>
                                <td>
                                    <h5>{{$lang->get(170)}}</h5>
                                </td>
                                <td></td>
                                <td>
                                    <h5>{{$lang->get(171)}}</h5>
                                </td>

                            </tr>
                            <tr>
                                <td><h5>{{$lang->get(172)}}:<h5></td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="openTimeMonday" id="openTimeMonday" class="form-control time24" placeholder="Ex: 10:00">
                                        </div>
                                    </td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="closeTimeMonday" id="closeTimeMonday" class="form-control time24" placeholder="Ex: 23:00">
                                        </div>
                                    </td>
                            </tr>
                            <tr>
                                <td><h5>{{$lang->get(173)}}:<h5></td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="openTimeTuesday" id="openTimeTuesday" class="form-control time24" placeholder="Ex: 10:00">
                                        </div>
                                    </td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="closeTimeTuesday" id="closeTimeTuesday" class="form-control time24" placeholder="Ex: 23:00">
                                        </div>
                                    </td>
                            </tr>
                            <tr style="margin-top: 5px;">
                                <td><h5>{{$lang->get(174)}}:<h5></td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="openTimeWednesday" id="openTimeWednesday" class="form-control time24" placeholder="Ex: 10:00">
                                        </div>
                                    </td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="closeTimeWednesday" id="closeTimeWednesday" class="form-control time24" placeholder="Ex: 23:00">
                                        </div>
                                    </td>
                            </tr>
                            <tr style="margin-top: 5px;">
                                <td><h5>{{$lang->get(175)}}:<h5></td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="openTimeThursday" id="openTimeThursday" class="form-control time24" placeholder="Ex: 10:00">
                                        </div>
                                    </td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="closeTimeThursday" id="closeTimeThursday" class="form-control time24" placeholder="Ex: 23:00">
                                        </div>
                                    </td>
                            </tr>
                            <tr>
                                <td><h5>{{$lang->get(176)}}:<h5></td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="openTimeFriday" id="openTimeFriday" class="form-control time24" placeholder="Ex: 10:00">
                                        </div>
                                    </td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg " style="margin-top: 5px;">
                                            <input type="text" name="closeTimeFriday" id="closeTimeFriday" class="form-control time24" placeholder="Ex: 23:00">
                                        </div>
                                    </td>
                            </tr>
                            <tr>
                                <td><h5>{{$lang->get(177)}}:<h5></td>
                                <td width="5%"></td>
                                <td>
                                    <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                        <input type="text" name="openTimeSaturday" id="openTimeSaturday" class="form-control time24" placeholder="Ex: 10:00">
                                    </div>
                                </td>
                                <td width="5%"></td>
                                <td>
                                    <div class="demo-masked-input input-group-lg " style="margin-top: 5px;">
                                        <input type="text" name="closeTimeSaturday" id="closeTimeSaturday" class="form-control time24" placeholder="Ex: 23:00">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><h5>{{$lang->get(178)}}:<h5></td>
                                <td width="5%"></td>
                                <td>
                                    <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                        <input type="text" name="openTimeSunday" id="openTimeSunday" class="form-control time24" placeholder="Ex: 10:00">
                                    </div>
                                </td>
                                <td width="5%"></td>
                                <td>
                                    <div class="demo-masked-input input-group-lg " style="margin-top: 5px;">
                                        <input type="text" name="closeTimeSunday" id="closeTimeSunday" class="form-control time24" placeholder="Ex: 23:00">
                                    </div>
                                </td>
                            </tr>

                        </table>

                    </div>



                </div>

            </div>

            <div class="row clearfix">
                <div class="col-md-12 form-control-label">
                    <div align="center">
                    <button type="submit"  class="q-btn-all q-color-second-bkg waves-effect"><h5>{{$lang->get(142)}}</h5></button>
                    </div>
                </div>
            </div>


        </form>


    </div>

    <!-- Tab Edit -->

        <div role="tabpanel" class="tab-pane fade" id="edit">

            <div id="redalertEdit" class="alert bg-red" style='display:none;' >

            </div>

            <form id="formedit" method="post" action="{{url('restaurantsedit')}}"  >
                @csrf

                <input type="hidden" id="imageidEdit" name="image"/>
                <input type="hidden" id="editid" name="id"/>
                <input type="hidden" id="cityDataEdit" name="cityData"/>

                <div class="row clearfix">

                    <div class="col-md-6 foodm">

                        <div class="col-md-3 foodm">
                            <label for="name"><h4>{{$lang->get(69)}} <span class="col-red">*</span></h4></label>
                        </div>
                        <div class="col-md-9 foodm">
                            <div class="form-group form-group-lg form-float">
                                <div class="form-line">
                                    <input type="text" name="name" id="nameEdit" class="form-control" placeholder="" maxlength="100">
                                </div>
                                <label class="foodm">{{$lang->get(91)}}</label>
                            </div>
                        </div>


                        <div class="col-md-3 foodm">
                            <label for="name"><h4>{{$lang->get(150)}}</h4></label>
                        </div>
                        <div class="col-md-9 foodm">
                            <div class="form-group form-group-lg form-float">
                                <div class="form-line">
                                    <input type="text" name="address" id="addressEdit" class="form-control" placeholder="" maxlength="100">
                                </div>
                                <label class="foodm">{{$lang->get(153)}}</label>
                            </div>
                        </div>

                        <div class="col-md-3 foodm">
                            <label><h4>{{$lang->get(151)}}</h4></label>
                        </div>
                        <div class="col-md-9 foodm">
                            <div class="form-group form-group-lg form-float">
                                <div class="form-line">
                                    <input type="text" name="phone" id="phoneEdit" class="form-control" placeholder="" maxlength="20">
                                </div>
                                <label class="foodm">{{$lang->get(154)}}</label>
                            </div>
                        </div>

                        <div class="col-md-3 foodm">
                            <label><h4>{{$lang->get(155)}}</h4></label>
                        </div>

                        <div class="col-md-9 foodm">
                            <div class="form-group form-group-lg form-float">
                                <div class="form-line">
                                    <input type="text" name="mobilephone" id="mobilephoneEdit" class="form-control" placeholder="" maxlength="20">
                                </div>
                                <label class="foodm">{{$lang->get(156)}}</label>
                            </div>
                        </div>

                        <div class="col-md-3 foodm">
                            <label><h4>{{$lang->get(71)}}</h4></label>
                        </div>
                        <div class="col-md-9 foodm">
                            <div class="form-group form-group-lg form-float">
                                <div class="form-line">
                                    <input type="text" name="desc" id="descEdit" class="form-control" placeholder="" maxlength="300">
                                </div>
                                <label class="foodm">{{$lang->get(76)}}</label>
                            </div>
                        </div>

                        <div class="col-md-4 foodm">
                            <label><h4>{{$lang->get(157)}} <span class="col-red">*</span></h4></label>  {{--Delivery fee--}}
                        </div>
                        <div class="col-md-8 foodm">
                            <div class="form-group form-group-lg form-float">
                                <div class="col-md-12">
                                    <input type="number" name="fee" id="feeEdit" class="q-form" placeholder="" min="0" step="0.01">
                                </div>
                                <label class="font-12">{{$lang->get(158)}}</label>          {{--Insert Delivery Fee--}}
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="checkbox" id="percentEdit" name="percent" class="filled-in checkmark">
                                    <label for="percent" class="form-label"><h5>{{$lang->get(159)}}</h5></label>     {{--Percents--}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="checkbox" id="perkmEdit" name="perkm" class="filled-in checkmark">
                                    <label for="perkm" class="form-label"><h5>{{$lang->get(611)}}</h5></label>     {{--per kilometer or mile--}}
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12 info" style="margin-top: 5px; margin-left: 20px;">
                            <h4>{{$lang->get(160)}}</h4>
                            <p>{{$lang->get(161)}}</p>
                            <p id="currentEdit">{{$lang->get(162)}}: {{$currency->currency()}}0</p>
                        </div>

                        <div class="col-md-3 foodm">
                            <label><h4>{{$lang->get(163)}}</h4></label>
                        </div>
                        <div class="col-md-9 foodm">
                            <div class="form-group form-group-lg form-float">
                                <div class="form-line">
                                    <input type="number" name="area" id="areaEdit" class="form-control" placeholder="" value="30">
                                    <label class="form-label">{{$lang->get(164)}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 foodm">
                            <label><h4>{{$lang->get(550)}}</h4></label>  {{--Minimum purchase amount --}}
                        </div>
                        <div class="col-md-9 foodm">
                            <div class="form-group form-group-lg form-float">
                                <div class="form-line">
                                    <input type="number" name="minAmount" id="minAmountEdit" class="form-control" placeholder="" value="0" step="0.01">
                                    <label class="form-label">{{$lang->get(551)}}</label> {{-- For ex: 100. If 0 - no Minimum purchase amount --}}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 foodm q-mt10" >
                            <h4>{{$lang->get(642)}}</h4>  {{--Select cities for market--}}
                            <select id="cityEdit" class="ms" multiple="multiple">
                            </select>
                        </div>


                    </div>

                    <div class="col-md-6 foodm">

                        <div class="col-md-3 foodm">
                            <label for="lat"><h4>{{$lang->get(165)}} <span class="col-red">*</span></h4></label>
                        </div>
                        <div class="col-md-9 foodm">
                            <div class="form-group form-group-lg form-float">
                                <div class="form-line">
                                    <input type="number" name="lat" id="latEdit" class="form-control" placeholder="" step="0.00000000000000001">
                                </div>
                                <label class="foodm">{{$lang->get(166)}}</label>
                            </div>
                        </div>

                        <div class="col-md-3 foodm">
                            <label for="lng"><h4>{{$lang->get(167)}} <span class="col-red">*</span></h4></label>
                        </div>
                        <div class="col-md-9 foodm">
                            <div class="form-group form-group-lg form-float">
                                <div class="form-line">
                                    <input type="number" name="lng" id="lngEdit" class="form-control" placeholder="" step="0.00000000000000001">
                                </div>
                                <label class="foodm">{{$lang->get(168)}}</label>
                            </div>
                        </div>


                        <div class="row clearfix">
                            <div class="col-md-2 form-control-label">
                                <label><h4>{{$lang->get(70)}}:</h4></label>
                                <br>
                                <div align="center">
                                    <button type="button" onclick="fromLibraryEdit()" class="q-btn-all q-color-second-bkg waves-effect"><h5>{{$lang->get(77)}}</h5></button>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div id="dropzoneEdit" class="fallback dropzone">
                                    <div class="dz-message">
                                        <div class="drag-icon-cph">
                                            <i class="material-icons">touch_app</i>
                                        </div>
                                        <h3>{{$lang->get(78)}}</h3>
                                    </div>
                                    <div class="fallback">
                                        <input name="file" type="file" multiple />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 foodm">
                        </div>
                        <div class="col-md-9 foodm">
                            <div class="form-group">
                                <input type="checkbox" id="visibleEdit" name="visible" class="filled-in checkmark" checked>
                                <label for="visible"><h4>{{$lang->get(75)}}</h4></label>
                            </div>
                        </div>

                        <div class="col-md-3 foodm">
                            <label><h4>{{$lang->get(169)}}:</h4></label>
                        </div>
                        <div class="col-md-9 foodm" style="margin-top: 20px;">
                            <table border="0">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <h5>{{$lang->get(170)}}</h5>
                                    </td>
                                    <td></td>
                                    <td>
                                        <h5>{{$lang->get(171)}}</h5>
                                    </td>

                                </tr>
                                <tr>
                                    <td><h5>{{$lang->get(172)}}:<h5></td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="openTimeMonday" id="openTimeMondayEdit" class="form-control time24" placeholder="Ex: 10:00">
                                        </div>
                                    </td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="closeTimeMonday" id="closeTimeMondayEdit" class="form-control time24" placeholder="Ex: 23:00">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><h5>{{$lang->get(173)}}:<h5></td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="openTimeTuesday" id="openTimeTuesdayEdit" class="form-control time24" placeholder="Ex: 10:00">
                                        </div>
                                    </td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="closeTimeTuesday" id="closeTimeTuesdayEdit" class="form-control time24" placeholder="Ex: 23:00">
                                        </div>
                                    </td>
                                </tr>
                                <tr style="margin-top: 5px;">
                                    <td><h5>{{$lang->get(174)}}:<h5></td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="openTimeWednesday" id="openTimeWednesdayEdit" class="form-control time24" placeholder="Ex: 10:00">
                                        </div>
                                    </td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="closeTimeWednesday" id="closeTimeWednesdayEdit" class="form-control time24" placeholder="Ex: 23:00">
                                        </div>
                                    </td>
                                </tr>
                                <tr style="margin-top: 5px;">
                                    <td><h5>{{$lang->get(175)}}:<h5></td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="openTimeThursday" id="openTimeThursdayEdit" class="form-control time24" placeholder="Ex: 10:00">
                                        </div>
                                    </td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="closeTimeThursday" id="closeTimeThursdayEdit" class="form-control time24" placeholder="Ex: 23:00">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><h5>{{$lang->get(176)}}:<h5></td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="openTimeFriday" id="openTimeFridayEdit" class="form-control time24" placeholder="Ex: 10:00">
                                        </div>
                                    </td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg " style="margin-top: 5px;">
                                            <input type="text" name="closeTimeFriday" id="closeTimeFridayEdit" class="form-control time24" placeholder="Ex: 23:00">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><h5>{{$lang->get(177)}}:<h5></td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="openTimeSaturday" id="openTimeSaturdayEdit" class="form-control time24" placeholder="Ex: 10:00">
                                        </div>
                                    </td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg " style="margin-top: 5px;">
                                            <input type="text" name="closeTimeSaturday" id="closeTimeSaturdayEdit" class="form-control time24" placeholder="Ex: 23:00">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><h5>{{$lang->get(178)}}:<h5></td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg" style="margin-top: 5px;">
                                            <input type="text" name="openTimeSunday" id="openTimeSundayEdit" class="form-control time24" placeholder="Ex: 10:00">
                                        </div>
                                    </td>
                                    <td width="5%"></td>
                                    <td>
                                        <div class="demo-masked-input input-group-lg " style="margin-top: 5px;">
                                            <input type="text" name="closeTimeSunday" id="closeTimeSundayEdit" class="form-control time24" placeholder="Ex: 23:00">
                                        </div>
                                    </td>
                                </tr>

                            </table>

                        </div>


                    </div>

                </div>

                <div class="row clearfix">
                    <div class="col-md-12 form-control-label">
                        <div align="center">
                            <button type="submit" class="q-btn-all q-color-second-bkg waves-effect"><h5>{{$lang->get(142)}}</h5></button>
                        </div>
                    </div>
                </div>


            </form>

        </div>

    </div>
    </div>

    <script type="text/javascript">


        //
        // city
        //
        let arrCity = "{{$city}}".split(',');
        let arrCityInit = [];
        let arrCitySelected = [];

        arrCity.forEach(function (item) {
            $('#city').append($('<option>').text(item).attr('value', item));
            $('#cityEdit').append($('<option>').text(item).attr('value', item));
        });

        $('#city').multiSelect({
            afterSelect: function(values){
                console.log("Select value: "+values);
                console.log("Categories All value: "+arrCitySelected);
                arrCitySelected.push(values);
                console.log("Categories All value: "+arrCitySelected);
                arrCitySelected.removeAll("");
                document.getElementById("cityData").value = arrCitySelected.toString();
            },
            afterDeselect: function(values){
                console.log("Deselect value: "+values);
                arrCitySelected = arrCitySelected.filter(p => p[0] != values[0]);
                console.log("Categories All value: "+arrCitySelected);
                arrCitySelected.removeAll("");
                document.getElementById("cityData").value = arrCitySelected.toString();
            }
        });

        $('#cityEdit').multiSelect({
            afterSelect: function(values){
                console.log("Select value: "+values);
                console.log("Categories All value: "+arrCitySelected);
                arrCitySelected.push(values);
                console.log("Categories All value: "+arrCitySelected);
                arrCitySelected.removeAll("");
                document.getElementById("cityDataEdit").value = arrCitySelected.toString();
            },
            afterDeselect: function(values){
                if (values == null)
                    return;
                console.log("Deselect value: "+values);
                arrCitySelected = arrCitySelected.filter(p => p[0] != values[0]);
                console.log("Categories All value: "+arrCitySelected);
                arrCitySelected.removeAll("");
                document.getElementById("cityDataEdit").value = arrCitySelected.toString();
            }
        });

        Array.prototype.empty = function() {
            for (var i = 0, s = this.length; i < s; i++) { this.pop(); }
            return this;
        };

        Array.prototype.removeAll = function(item) {
            var result = [];

            for (var i = 0, j = 0, s = this.length; i < s; i++) {
                if (this[i] != item) { result[j++] = this[i]; }
            }

            this.empty();
            for (var i = 0, s = result.length; i < s;) { this.push(result[i++]); }
        };

        //
        // end city
        //

        var form = document.getElementById("formcreate");
        form.addEventListener("submit", checkForm, true);

        function checkForm(event) {
            var alertText = "";
            if (!document.getElementById("name").value) {
                alertText = "<h4>{{$lang->get(85)}}</h4>";
            }
            if (!document.getElementById("lat").value) {
                alertText = alertText+"<h4>{{$lang->get(179)}}</h4>";
            }
            if (!document.getElementById("lng").value) {
                alertText = alertText+"<h4>{{$lang->get(180)}}</h4>";
            }
            if (alertText != "") {
                var div = document.getElementById("redalert");
                div.innerHTML = '';
                div.style.display = "block";
                var div2 = document.createElement("div");
                div2.innerHTML = alertText;
                div.appendChild(div2);
                window.scrollTo(0, 0);
                event.preventDefault();
                return false;
            }
        }

        function showDeleteMessage(id) {
            swal({
                title: "{{$lang->get(81)}}",
                text: "{{$lang->get(82)}}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{$lang->get(83)}}",
                cancelButtonText: "{{$lang->get(84)}}",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    console.log(id);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{ url("restaurantsdelete") }}',
                        data: {id: id},
                        success: function (data){
                            if (!data.ret)
                                return showNotification("bg-red", data.text, "bottom", "center", "", "");
                            //
                            // remove from ui
                            //
                            var div = document.getElementById('tr'+id);
                            div.remove();
                        },
                        error: function(e) {
                            console.log(e);
                        }}
                    );
                } else {

                }
            });
        }

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href")
            if (target != "#edit")
                document.getElementById("tabEdit").style.display = "none";
            // if (target == "#create")
            //     $('#city').data('multiselect').deselect_all();
            console.log(target);
        });

        async function editItem(id, name, visible, imageid, ifile, desc,
                                delivered, address, phone, mobilephone, lat, lng,
                                fee, percent, minAmount, perkm, city) {
            document.getElementById("tabEdit").style.display = "block";
            $('.nav-tabs a[href="#edit"]').tab('show');

            document.getElementById("nameEdit").value = name;
            document.getElementById("editid").value = id;
            document.getElementById("visibleEdit").checked = visible === '1';
            document.getElementById("addressEdit").value = address;
            document.getElementById("phoneEdit").value = phone;
            document.getElementById("mobilephoneEdit").value = mobilephone;
            document.getElementById("latEdit").value = lat;
            document.getElementById("lngEdit").value = lng;
            document.getElementById("descEdit").value = desc;
            document.getElementById("perkmEdit").checked = perkm === '1';
            //
            document.getElementById('feeEdit').value = fee;
            document.getElementById("percentEdit").checked = percent === '1';
            if (percent == '1')
                currentEdit.innerHTML = "Current: "+fee+"%";
            else
                currentEdit.innerHTML = "Current: {{$currency->currency()}}"+fee;
            //
            @foreach($restaurants as $key => $data)
            if ({{$data->id}} == id){
                document.getElementById("openTimeMondayEdit").value = '{{$data->openTimeMonday}}';
                document.getElementById("closeTimeMondayEdit").value = '{{$data->closeTimeMonday}}';
                document.getElementById("openTimeTuesdayEdit").value = '{{$data->openTimeTuesday}}';
                document.getElementById("closeTimeTuesdayEdit").value = '{{$data->closeTimeTuesday}}';
                document.getElementById("openTimeWednesdayEdit").value = '{{$data->openTimeWednesday}}';
                document.getElementById("closeTimeWednesdayEdit").value = '{{$data->closeTimeWednesday}}';
                document.getElementById("openTimeThursdayEdit").value = '{{$data->openTimeThursday}}';
                document.getElementById("closeTimeThursdayEdit").value = '{{$data->closeTimeThursday}}';
                document.getElementById("openTimeFridayEdit").value = '{{$data->openTimeFriday}}';
                document.getElementById("closeTimeFridayEdit").value = '{{$data->closeTimeFriday}}';
                document.getElementById("openTimeSaturdayEdit").value = '{{$data->openTimeSaturday}}';
                document.getElementById("closeTimeSaturdayEdit").value = '{{$data->closeTimeSaturday}}';
                document.getElementById("openTimeSundayEdit").value = '{{$data->openTimeSunday}}';
                document.getElementById("closeTimeSundayEdit").value = '{{$data->closeTimeSunday}}';
                //
                var area = '{{$data->area}}';
                if (area == "")
                    area = 30;
                document.getElementById("areaEdit").value = area;
                document.getElementById("minAmountEdit").value = minAmount;

            }
            @endforeach

            // city
            arrCitySelected = city.split(',');
            $('#cityEdit').data('multiselect').deselect_all();
            arrCitySelected.forEach(function (item) {
                console.log("arrCitySelected: " +item);
                $('#cityEdit').multiSelect('select', item);
            });
            //
            addEditImage(imageid, ifile);
        }

        var form = document.getElementById("formedit");
        form.addEventListener("submit", checkFormEdit, true);

        function checkFormEdit(event) {
            var alertText = "";
            if (!document.getElementById("nameEdit").value) {
                alertText = "<h4>{{$lang->get(85)}}</h4>";
            }
            if (!document.getElementById("latEdit").value) {
                alertText = alertText+"<h4>{{$lang->get(179)}}</h4>";
            }
            if (!document.getElementById("lngEdit").value) {
                alertText = alertText+"<h4>{{$lang->get(180)}}</h4>";
            }
            if (alertText != "") {
                var div = document.getElementById("redalertEdit");
                div.innerHTML = '';
                div.style.display = "block";
                var div2 = document.createElement("div");
                div2.innerHTML = alertText;
                div.appendChild(div2);
                window.scrollTo(0, 0);
                event.preventDefault();
                return false;
            }
        }


        //
        // create
        //
        percent = document.getElementById('percent');
        current = document.getElementById('current');
        fee = document.getElementById('fee');
        percent.addEventListener('change', (event) => {
            var vl = fee.value;
            if (vl == null) vl = 0;
            if (event.target.checked) {
                if (fee.value > 100){
                    vl = 100;
                    fee.value = 100;
                }
                current.innerHTML = "Current: "+vl+"%";
            } else {
                current.innerHTML = "Current: {{$currency->currency()}}"+vl;
            }
        })
        fee.addEventListener('input', (event) => {
            var vl = fee.value;
            if (vl == null) vl = 0;
            if (percent.checked) {
                if (fee.value > 100){
                    vl = 100;
                    fee.value = 100;
                }
                current.innerHTML = "Current: "+vl+"%";
            } else {
                current.innerHTML = "Current: {{$currency->currency()}}"+vl;
            }
        })

        //
        // edit
        //
        percentEdit = document.getElementById('percentEdit');
        currentEdit = document.getElementById('currentEdit');
        feeEdit = document.getElementById('feeEdit');
        percentEdit.addEventListener('change', (event) => {
            var vl = feeEdit.value;
            if (vl == null) vl = 0;
            if (event.target.checked) {
                if (feeEdit.value > 100){
                    vl = 100;
                    feeEdit.value = 100;
                }
                currentEdit.innerHTML = "Current: "+vl+"%";
            } else {
                currentEdit.innerHTML = "Current: {{$currency->currency()}}"+vl;
            }
        })
        feeEdit.addEventListener('input', (event) => {
            var vl = feeEdit.value;
            if (vl == null) vl = 0;
            if (percentEdit.checked) {
                if (feeEdit.value > 100){
                    vl = 100;
                    feeEdit.value = 100;
                }
                currentEdit.innerHTML = "Current: "+vl+"%";
            } else {
                currentEdit.innerHTML = "Current: {{$currency->currency()}}"+vl;
            }
        })

    //Time
    var $demoMaskedInput = $('.demo-masked-input');

    $demoMaskedInput.find('.time12').inputmask('hh:mm t', { placeholder: '__:__ _m', alias: 'time12', hourFormat: '12' });
    $demoMaskedInput.find('.time24').inputmask('hh:mm', { placeholder: '__:__ _m', alias: 'time24', hourFormat: '24' });

    //
    var percentEdit = document.getElementById('percentEdit');
    var currentEdit = document.getElementById('currentEdit');
    var feeEdit = document.getElementById('feeEdit');
    var perkmEdit = document.getElementById('perkmEdit');
    percentEdit.addEventListener('change', (event) => {
        var vl = feeEdit.value;
        if (vl == null) vl = 0;
        if (event.target.checked) {
            if (feeEdit.value > 100){
                vl = 100;
                feeEdit.value = 100;
            }
            currentEdit.innerHTML = "Current: "+vl+"%";
            perkmEdit.checked = false;
        } else {
            if (perkmEdit.checked)
                currentEdit.innerHTML = "Current: {{$currency->currency()}}" + vl + " per km or ml"
            else
                currentEdit.innerHTML = "Current: {{$currency->currency()}}"+vl;
        }
    });
    perkmEdit.addEventListener('change', (event) => {
        if (perkmEdit.checked) {
            let vl = feeEdit.value;
            percentEdit.checked = false;
            currentEdit.innerHTML = "Current: {{$currency->currency()}}" + vl + " per km or ml"
        }
    });

    </script>

@include('bsb.image', array('petani' => $petani))

@endsection
