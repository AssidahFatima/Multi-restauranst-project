@inject('userinfo', 'App\UserInfo')
@extends('bsb.app')
@inject('lang', 'App\Lang')

@section('content')

    <div class="header">
        <div class="row clearfix">
            <div class="col-md-12">
                <h3 class="">{{$lang->get(280)}}</h3>
            </div>
        </div>
    </div>

    <div class="body">

        <!-- Tabs -->

        <ul class="nav nav-tabs tab-nav-right" role="tablist">
            <li role="presentation" class="active"><a href="#home" data-toggle="tab"><h4>{{$lang->get(64)}}</h4></a></li>
            @if ($userinfo->getUserPermission("RestaurantReview::Create"))
            <li role="presentation"><a href="#create" data-toggle="tab" ><h4>{{$lang->get(65)}}</h4></a></li>
            @endif
        </ul>

        <!-- Tab List -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="home">
                @php

                @endphp
                @if ($texton == "green")
                    <div class="alert bg-green" >
                        {{$text}}
                    </div>
                @endif
                <div id="redzone" class="alert bg-red" hidden>
                </div>

                <div class="row clearfix js-sweetalert">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h3>
                                    {{$lang->get(281)}}
                                </h3>
                            </div>
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                        <thead>
                                        <tr>
                                            <th>{{$lang->get(282)}}</th>
                                            <th>{{$lang->get(283)}}</th>
                                            <th>{{$lang->get(284)}}</th>
                                            <th>{{$lang->get(137)}}</th>
                                            <th>{{$lang->get(70)}}</th>
                                            <th>{{$lang->get(285)}}</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>{{$lang->get(282)}}</th>
                                            <th>{{$lang->get(283)}}</th>
                                            <th>{{$lang->get(284)}}</th>
                                            <th>{{$lang->get(137)}}</th>
                                            <th>{{$lang->get(70)}}</th>
                                            <th>{{$lang->get(285)}}</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>

                                        @foreach($idata as $key => $data)
                                            <tr id="tr{{$data->id}}">
                                                <td>{{$data->updated_at}}</td>

                                                <td>{{$data->title}}</td>

                                                <td>{{$data->text}}</td>

                                                <td>
                                                    @if ($data->show == 2)
                                                        Send to All Users
                                                    @endif
                                                    @if ($data->show != 2)
                                                        @foreach($iusers as $key => $value)
                                                            @if ($value->id == $data->user)
                                                                {{$value->name}}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    @foreach($petani as $key => $dataimage)
                                                        @if ($dataimage->id == $data->image)
                                                            <img src="images/{{$dataimage->filename}}" height="50" style='min-height: 50px; ' alt="">
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>Read {{$data->countRead}} from {{$data->countAll}} users</td>
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

                <form id="formcreate" method="post" action="{{url('sendmsg')}}"  >

                    @csrf

                    <input type="hidden" id="imageid" name="imageid"/>

                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <label for="name"><h4>User <span class="col-red">*</span></h4></label>
                        </div>
                        <div class="col-md-4">

                            <select name="user" id="user" class="q-form-s show-tick q-radius" data-live-search="true">
                                <option value="-1" style="font-size: 18px !important;">{{$lang->get(286)}}</option>     {{--Send to All users--}}
                                @foreach($iusers as $key => $datausers)
                                    <option value="{{$datausers->id}}" style="font-size: 18px !important;">{{$datausers->name}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-2 form-control-label">
                            <label for="name"><h4>{{$lang->get(283)}} <span class="col-red">*</span></h4></label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-group-lg form-float">
                                <div class="form-line">
                                    <input type="text" name="title" id="title" class="form-control" placeholder="" maxlength="200">
                                    <label class="form-label">{{$lang->get(287)}}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <label><h4>{{$lang->get(288)}} <span class="col-red">*</span></h4></label>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" name="text" id="text" class="form-control" placeholder="" maxlength="1000">
                                </div>
                                <label class="font-12">{{$lang->get(289)}}</label>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <label><h4>{{$lang->get(290)}}:</h4></label>
                            <br>
                            <div align="center">
                                <button type="button" onclick="fromLibrary()" class="q-btn-all q-color-second-bkg waves-effect"><h5>{{$lang->get(77)}}</h5></button>
                            </div>
                        </div>
                        <div class="col-md-10">
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
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-12 form-control-label">
                            <div align="center">
                                <button type="submit" class="q-btn-all q-color-second-bkg waves-effect"><h5>{{$lang->get(291)}}</h5></button>
                            </div>
                        </div>
                    </div>

                </form>

            </div>

            @include('bsb.image', array('petani' => $petani))

            <script>

                addDefault();

                function addDefault() {
                    document.getElementById("imageid").value = "{{$defaultImageId}}";
                    mockFile = {
                        name: "images/{{$defaultImage}}",
                        size: {{$filesize}},
                        dataURL: "images/{{$defaultImage}}"
                    };
                    myDropzone.createThumbnailFromUrl(mockFile, myDropzone.options.thumbnailWidth, myDropzone.options.thumbnailHeight, myDropzone.options.thumbnailMethod, true, function (dataUrl) {
                        myDropzone.emit("thumbnail", mockFile, dataUrl);
                    });
                    myDropzone.emit("addedfile", mockFile);
                    myDropzone.emit("complete", mockFile);
                    myDropzone.files.push(mockFile);
                    editFileNameNotify = "{{$defaultImage}}";
                }

            </script>



@endsection

@section('content2')

@endsection

