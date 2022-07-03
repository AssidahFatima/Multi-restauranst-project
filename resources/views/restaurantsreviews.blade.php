@inject('userinfo', 'App\UserInfo')
@inject('lang', 'App\Lang')
@extends('bsb.app')

@section('content')
    <div class="header">
        <div class="row clearfix">
            <div class="col-md-12">
                <h3 class="">{{$lang->get(182)}}</h3>
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
                                    {{$lang->get(183)}}
                                </h3>
                            </div>
                            <div class="body">
                                <div class="table-responsive">
                                    <table id="data_table" class="table table-bordered table-striped table-hover dataTable js-exportable">
                                        <thead>
                                        <tr>
                                            <th>{{$lang->get(68)}}</th>
                                            <th>{{$lang->get(140)}}</th>
                                            <th>{{$lang->get(136)}}</th>
                                            <th>{{$lang->get(137)}}</th>
                                            <th>{{$lang->get(89)}}</th>
                                            <th>{{$lang->get(72)}}</th>
                                            <th>{{$lang->get(74)}}</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>{{$lang->get(68)}}</th>
                                            <th>{{$lang->get(140)}}</th>
                                            <th>{{$lang->get(136)}}</th>
                                            <th>{{$lang->get(137)}}</th>
                                            <th>{{$lang->get(89)}}</th>
                                            <th>{{$lang->get(72)}}</th>
                                            <th>{{$lang->get(74)}}</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>

                                        @foreach($idata as $key => $data)
                                            <tr id="tr{{$data->id}}">
                                                <td>{{$data->id}}</td>
                                                <td>{{$data->desc}}</td>

                                                <td>{{$data->rate}}</td>

                                                <td>
                                                @foreach($iusers as $key => $idata)
                                                    @if ($idata->id == $data->user)
                                                        {{$idata->name}}
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

                                                <td>{{$data->updated_at}}</td>

                                                <td>
                                                    @if ($userinfo->getUserPermission("RestaurantReview::Edit"))
                                                    <button type="button" class="btn btn-default waves-effect"
                                                            onclick="editItem('{{$data->id}}',
                                                                '{{$data->user}}', '{{$data->restaurant}}',
                                                                '{{$data->rate}}', '{{$data->desc}}',
                                                                )">
                                                        <img src="img/iconedit.png" width="25px">
                                                    </button>
                                                    @endif
                                                    @if ($userinfo->getUserPermission("RestaurantReview::Delete"))
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

        <form id="formcreate" method="post" action="{{url('restorantsreviewadd')}}"  >

            @csrf

            <input type="hidden" id="image" name="image"/>

            <div class="row clearfix">
                <div class="col-md-2 form-control-label">
                    <label for="name"><h4>{{$lang->get(137)}} <span class="col-red">*</span></h4></label>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-group-lg form-float">
                        <div class="form-line">
                            <select name="user" id="user" class="form-control bs-searchbox " style="font-size: 26px  !important; ">
                                @foreach($iusers as $key => $datausers)
                                    <option value="{{$datausers->id}}" style="font-size: 18px  !important;">{{$datausers->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 form-control-label">
                    <label for="name"><h4>{{$lang->get(89)}} <span class="col-red">*</span></h4></label>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-group-lg form-float">
                        <div class="form-line">
                            <select name="restaurant" id="restaurant" class="form-control bs-searchbox" style="font-size: 26px  !important; ">
                                <option value="-1" style="font-size: 18px  !important;">No</option>
                                @foreach($irestaurants as $key => $data)
                                    <option value="{{$data->id}}" style="font-size: 18px  !important;">{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-md-2 form-control-label">
                    <label for="name"><h4>{{$lang->get(136)}} <span class="col-red">*</span></h4></label>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-group-lg form-float">
                        <div class="form-line">
                            <input type="number" name="rate" id="rate" class="form-control" placeholder="" min="1" max="5">
                            <label class="form-label">{{$lang->get(139)}}</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-md-2 form-control-label">
                    <label><h4>{{$lang->get(140)}} <span class="col-red">*</span></h4></label>
                </div>
                <div class="col-md-10">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="review" id="review" class="form-control" placeholder="" maxlength="300">
                        </div>
                        <label class="font-12">{{$lang->get(141)}}</label>
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

    <script>

        var form = document.getElementById("formcreate");
        form.addEventListener("submit", checkForm, true);

        function checkForm(event) {
            var alertText = "";

            var sel = document.getElementById("restaurant");
            if (sel.options[sel.selectedIndex].value == -1){
                alertText = "<h4>{{$lang->get(184)}}</h4>";
            }
            if (!document.getElementById("rate").value) {
                alertText = alertText+"<h4>{{$lang->get(144)}}</h4>";
            }
            if (!document.getElementById("review").value) {
                alertText = alertText+"<h4>{{$lang->get(145)}}</h4>";
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


    </script>


    <!-- Tab Edit -->

        <div role="tabpanel" class="tab-pane fade" id="edit">

            <div id="redalertEdit" class="alert bg-red" style='display:none;' >

            </div>

            <form id="formedit" method="post" action="{{url('restaurantsreviewedit')}}"  >
                @csrf

                <input type="hidden" id="editid" name="id"/>

                <div class="row clearfix">
                    <div class="col-md-2 form-control-label">
                        <label for="name"><h4>{{$lang->get(137)}} <span class="col-red">*</span></h4></label>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form-group-lg">
                            <div class="form-line">
                                <select name="user" id="userEdit" class="form-control bs-searchbox show-tick" style="font-size: 26px  !important; ">
                                    @foreach($iusers as $key => $datausers)
                                        <option value="{{$datausers->id}}" style="font-size: 18px  !important;">{{$datausers->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 form-control-label">
                        <label for="name"><h4>{{$lang->get(89)}} <span class="col-red">*</span></h4></label>
                    </div>
                    <div class="col-md-4">
                        <div class="form-line">
                            <select name="restaurant" id="restaurantEdit" class="form-control bs-searchbox show-tick" style="font-size: 26px  !important; ">
                                <option value="-1" style="font-size: 18px  !important;">{{$lang->get(114)}}</option>
                                @foreach($irestaurants as $key => $data)
                                    <option value="{{$data->id}}" style="font-size: 18px  !important;">{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-md-2 form-control-label">
                        <label for="name"><h4>{{$lang->get(136)}} <span class="col-red">*</span></h4></label>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form-group-lg">
                            <div class="form-line">
                                <input type="number" name="rate" id="rateEdit" class="form-control" placeholder="" min="1" max="5">
                            </div>
                            <label>{{$lang->get(139)}}</label>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-md-2 form-control-label">
                        <label for="ckeditorEdit"><h4>{{$lang->get(140)}} <span class="col-red">*</span></h4></label>
                    </div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="review" id="reviewEdit" class="form-control" placeholder="" maxlength="300">
                            </div>
                            <label class="font-12">{{$lang->get(141)}}</label>
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

        var form = document.getElementById("formedit");
        form.addEventListener("submit", checkFormEdit, true);

        function checkFormEdit(event) {
            var alertText = "";
            var sel = document.getElementById("restaurantEdit");
            if (sel.options[sel.selectedIndex].value == -1){
                alertText = "<h4>{{$lang->get(184)}}</h4>";
            }
            if (!document.getElementById("rateEdit").value) {
                alertText = alertText+"<h4>{{$lang->get(144)}}</h4>";
            }
            if (!document.getElementById("reviewEdit").value) {
                alertText = alertText+"<h4>{{$lang->get(145)}}</h4>";
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

        $('.js-exportable').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
        ]
        });

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
                        url: '{{ url("restaurantsreviewdelete") }}',
                        data: {id: id},
                        success: function (data){
                            if (!data.ret)
                                return showNotification("bg-red", data.text, "bottom", "center", "", "");
                            //
                            // remove from ui
                            //
                            var table = $('#data_table').DataTable();
                            var indexes = table
                                .rows()
                                .indexes()
                                .filter( function ( value, index ) {
                                    return id === table.row(value).data()[0];
                                } );
                            var page = moveToPageWithSelectedItem(id);
                            table.rows(indexes).remove().draw();
                            table.page(page).draw(false);
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
            console.log(target);
        });

        async function editItem(id, user, restaurant, rate, ckeditor) {
            document.getElementById("tabEdit").style.display = "block";
            document.getElementById("editid").value = id;
            $('.nav-tabs a[href="#edit"]').tab('show');
            document.getElementById("rateEdit").value = rate;
            $('select[name=user]').val(user);
            $('select[name=restaurant]').val(restaurant);
            $('.show-tick').selectpicker('refresh')
            document.getElementById("reviewEdit").value = ckeditor;
        }
    </script>


@endsection

@section('content2')

@endsection
