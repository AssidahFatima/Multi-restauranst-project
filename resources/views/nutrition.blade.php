@inject('userinfo', 'App\UserInfo')
@inject('lang', 'App\Lang')

@extends('bsb.app')

@section('content')
    <div class="header">
        <div class="row clearfix">
            <div class="col-md-12">
                <h3 class="">{{$lang->get(123)}}</h3>
            </div>
        </div>
    </div>
    <div class="body">

        <!-- Tabs -->

        <ul class="nav nav-tabs tab-nav-right" role="tablist">
            <li role="presentation" class="active"><a href="#home" data-toggle="tab"><h4>{{$lang->get(64)}}</h4></a></li>
            @if ($userinfo->getUserPermission("Food::NutritionGroup::Create"))
            <li role="presentation"><a href="#create" data-toggle="tab" ><h4>{{$lang->get(124)}}</h4></a></li>
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
                    <div id="redzone" class="alert bg-red" hidden>
                    </div>

                    <div class="row clearfix js-sweetalert">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h3>
                                    {{$lang->get(125)}}
                                </h3>
                            </div>

                            <div class="body">
                                <div class="panel-group " id="accordion_17" role="tablist" aria-multiselectable="true">
                                @foreach($inutritiongroup as $key => $data)

                                    <div id="nutritiongroup{{$data->id}}" class="panel panel-col-orange">
                                        <div class="panel-heading" role="tab" id="headingOne_19">
                                            <h4 class="panel-title">

                                                <a role="button" data-toggle="collapse" href="#collapse{{$data->id}}" aria-expanded="true" aria-controls="collapse{{$data->id}}">

                                                    :: {{$data->name}}

                                                    @if ($userinfo->getUserPermission("Food::NutritionGroup::Edit"))
                                                    <img src="img/delete.png" width="70px" onclick="showDeleteMessage('{{$data->id}}')" align="right">
                                                    @endif

                                                    @if ($userinfo->getUserPermission("Food::NutritionGroup::Delete"))
                                                    <img src="img/edit.png" width="70px" onclick="editItem('{{$data->id}}', '{{$data->name}}')" align="right">
                                                    @endif
                                                </a>

                                            </h4>

                                        </div>
                                        <div id="collapse{{$data->id}}" class="panel-collapse collapse
                                        @if ($data->id == $open)
                                            in
                                        @endif
                                            " role="tabpanel" aria-labelledby="heading{{$data->id}}">


                                            <div id="parent{{$data->id}}" class="panel-body">

                                                <div  class="row clearfix js-sweetalert">
                                                    <div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                        <div id="table{{$data->id}}">
                                                            <div class="header">
                                                                <h4>
                                                                    {{$lang->get(126)}}
                                                                </h4>
                                                            </div>
                                                            <div class="body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>{{$lang->get(69)}}</th>
                                                                            <th>{{$lang->get(127)}}</th>
                                                                            <th>{{$lang->get(74)}}</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tfoot>
                                                                        <tr>
                                                                            <th>{{$lang->get(69)}}</th>
                                                                            <th>{{$lang->get(127)}}</th>
                                                                            <th>{{$lang->get(74)}}</th>
                                                                        </tr>
                                                                        </tfoot>
                                                                        <tbody id="tbody{{$data->id}}">

                                                                        @foreach($inutrition as $key => $idata)
                                                                            @if ($idata->nutritiongroup == $data->id)
                                                                            <tr id="tr{{$idata->id}}">
                                                                                <td>{{$idata->name}}</td>
                                                                                <td>{{$idata->desc}}</td>
                                                                                <td>

                                                                                @if ($userinfo->getUserPermission("Food::Nutrition::Edit"))
                                                                                <button type="button" class="btn btn-default waves-effect"
                                                                                    onclick="onEditExtras('{{$data->id}}', '{{$idata->id}}', '{{$idata->name}}',
                                                                                        '{{$idata->desc}}')">
                                                                                    <img src="img/iconedit.png" width="25px">
                                                                                </button>
                                                                                @endif
                                                                                @if ($userinfo->getUserPermission("Food::Nutrition::Delete"))
                                                                                <button type="button" class="btn btn-default waves-effect" onclick="showDeleteMessage2('{{$idata->id}}')">
                                                                                    <img src="img/icondelete.png" width="25px">
                                                                                </button>
                                                                                @endif

                                                                                </td>
                                                                            </tr>
                                                                            @endif
                                                                        @endforeach

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div align="center">
                                                                    @if ($userinfo->getUserPermission("Food::Nutrition::Create"))
                                                                    <button class="q-btn-all q-color-second-bkg waves-effect"
                                                                            onclick="onAddExtras('{{$data->id}}')"><h5>{{$lang->get(120)}}</h5>  {{--Add New--}}
                                                                    </button>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
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

                <form id="formcreate" method="post" action="{{url('nutritiongroupadd')}}"  >
                    @csrf

                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <label for="name"><h4>{{$lang->get(69)}}</h4></label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-group-lg">
                                <div class="form-line">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="" maxlength="100">
                                </div>
                                <label>{{$lang->get(91)}}</label>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-12 form-control-label">
                            <div align="center">
                                <button type="submit"  class="q-btn-all q-color-second-bkg waves-effect"><h5>{{$lang->get(128)}}</h5></button>
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
                    if (!document.getElementById("name").value) {
                        alertText = "<h4>{{$lang->get(85)}}</h4>";
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

                <div id="redalertedit" class="alert bg-red" style='display:none;' >

                </div>

                <form id="formedit" method="post" action="{{url('nutritiongroupedit')}}"  >
                    @csrf

                    <input type="hidden" id="editid" name="id"/>

                    <div class="row clearfix">
                        <div class="col-md-2 form-control-label">
                            <label for="name"><h4>{{$lang->get(69)}}</h4></label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-group-lg">
                                <div class="form-line">
                                    <input type="text" name="name" id="editname" class="form-control" placeholder="" maxlength="100">
                                </div>
                                <label>{{$lang->get(91)}}</label>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-12 form-control-label">
                            <div align="center">
                                <button type="submit" class="q-btn-all q-color-second-bkg waves-effect"><h5>{{$lang->get(80)}}</h5></button>
                            </div>
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </div>

{{--    form create extars (hidden by default)      --}}

<div id="formparent" hidden>
    <form id="formcreateextras" hidden>

        @csrf

        <input type="hidden" id="onedit" name="onedit"/>
        <input type="hidden" id="eid" name="eid"/>

        <div class="row clearfix">
            <div class="col-md-2 form-control-label">
                <label for="name"><h4>{{$lang->get(69)}}</h4></label>
            </div>
            <div class="col-md-4">
                <div class="form-group form-group-lg form-float">
                    <div class="form-line">
                        <input type="text" name="name" id="ename" class="form-control" placeholder="" maxlength="100">
                    </div>
                    <label class="font-12">{{$lang->get(91)}}</label>
                </div>
            </div>
            <div class="col-md-2 form-control-label">
                <label for="desc"><h4>{{$lang->get(127)}}</h4></label>
            </div>
            <div class="col-md-4">
                <div class="form-group form-group-lg form-float">
                    <div class="form-line">
                        <input type="text" name="desc" id="desc" class="form-control" placeholder="" maxlength="300">
                    </div>
                    <label class="font-12">{{$lang->get(129)}}</label>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-md-12 form-control-label">
                <div align="center">
                    <button type="submit" class="q-btn-all q-color-second-bkg waves-effect"><h5>{{$lang->get(130)}}</h5></button>
                    <button onclick="onCancelEditExtras()" class="q-btn-all q-color-second-bkg waves-effect"><h5>{{$lang->get(131)}}</h5></button>
                </div>
            </div>
        </div>

    </form>
</div>

    <script type="text/javascript">

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href")
            console.log(target);
            if (target != "#edit") {
                console.log("close");
                document.getElementById("tabEdit").style.display = "none";
            }

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
                        url: '{{ url("nutritiongroupdelete") }}',
                        data: {id: id},
                        success: function (data){
                            if (!data.ret){
                                document.getElementById("redzone").innerHTML = data.text;
                                document.getElementById("redzone").style.display = "block";
                                return;
                            }
                            //
                            // remove from ui
                            //
                            var source = document.getElementById('formcreateextras');
                            var parentElement = document.getElementById("formparent");
                            parentElement.appendChild(source);
                            source.hidden = false;
                            currentGroupId = "";
                            //
                            var div = document.getElementById('nutritiongroup'+id);
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

        function showDeleteMessage2(id){
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
                        url: '{{ url("nutritiondelete") }}',
                        data: {id: id},
                        success: function (data){
                            if (!data.ret){
                                document.getElementById("redzone").innerHTML = data.text;
                                document.getElementById("redzone").style.display = "block";
                                return;
                            }
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

        async function editItem(id, name, restaurant) {
            document.getElementById("tabEdit").style.display = "block";
            $('.nav-tabs a[href="#edit"]').tab('show');
            document.getElementById("editid").value = id;
            document.getElementById("editname").value = name;
            //restaurant
            $('select[name=restaurant]').val(restaurant);
            $('.show-tick').selectpicker('refresh')
        }

        var currentGroupId = "";

        //
        function onEditExtras(groupid, id, name, desc){
            if (currentGroupId != ""){
                document.getElementById('formcreateextras').hidden = true;              // hide form
                document.getElementById('div2').hidden = true;
                document.getElementById("table"+currentGroupId).style.display='block';    // show table
            }
            currentGroupId = groupid;
            document.getElementById("table"+groupid).style.display='none';;
            var source = document.getElementById('formcreateextras');
            var parentElement = document.getElementById("parent"+groupid);
            parentElement.appendChild(source);
            source.hidden = false;
            //
            document.getElementById('ename').value = name;
            document.getElementById('desc').value = desc;
            document.getElementById('onedit').value = "true";
            document.getElementById('eid').value = id;
        }
        function onAddExtras(groupid){
            clearForm();
            if (currentGroupId != ""){
                document.getElementById('formcreateextras').hidden = true;              // hide form
                document.getElementById("table"+currentGroupId).style.display='block';    // show table
            }
            currentGroupId = groupid;
            document.getElementById("table"+groupid).style.display='none';;
            var source = document.getElementById('formcreateextras');
            var parentElement = document.getElementById("parent"+groupid);
            parentElement.appendChild(source);
            source.hidden = false;
        }

        var form = document.getElementById("formcreateextras");
        form.addEventListener("submit", saveExtras, true);

        function saveExtras(){
            var eid = document.getElementById('eid').value;
            var name = document.getElementById('ename').value;
            var desc = document.getElementById('desc').value;
            if (name === ""){
                swal("{{$lang->get(85)}}");
                event.preventDefault();
                return false;
            }
            if (desc === ""){
                swal("{{$lang->get(132)}}");
                event.preventDefault();
                return false;
            }
            var onedit = document.getElementById('onedit').value;
            console.log("onedit " + onedit);
            if (onedit == "true"){
                $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{ url("nutritionedit") }}',
                        data: {
                            '_token': $('meta[name="_token"]').attr('content'),
                            id: eid,
                            name: name,
                            desc: desc,
                            nutritiongroup: currentGroupId,
                        },
                        success: function (data) {
                            console.log("successfully " + data.filename);
                            var old = document.getElementById('tr'+eid);
                            document.getElementById('formcreateextras').hidden = true;              // hide form
                            document.getElementById("table" + currentGroupId).style.display = 'block';    // show table
                            var parentElement = document.getElementById('tbody' + currentGroupId);        // add new element
                            var div = document.createElement("tr");
                            var buttons = addButtonsToExtra(currentGroupId, eid, name, desc);
                            div.innerHTML = '<tr><td>' + name + '</td> <td>' + desc + '</td><td>' + buttons + '</td></tr>';
                            div.setAttribute("id", "tr"+eid);
                            parentElement.replaceChild(div, old);
                            currentGroupId = "";
                            clearForm();
                        },
                        error: function (e) {
                            console.log(e);
                        }
                    }
                );
            }else {
                $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{ url("nutritionadd") }}',
                        data: {
                            '_token': $('meta[name="_token"]').attr('content'),
                            name: name,
                            desc: desc,
                            nutritiongroup: currentGroupId,
                        },
                        success: function (data) {
                            console.log("successfully " + data.filename);
                            document.getElementById('formcreateextras').hidden = true;              // hide form
                            document.getElementById("table" + currentGroupId).style.display = 'block';    // show table
                            var parentElement = document.getElementById('tbody' + currentGroupId);        // add new element
                            var div = document.createElement("tr");
                            var buttons = addButtonsToExtra(currentGroupId, data.id, name, desc);
                            div.innerHTML = '<tr><td>' + name + '</td><td>' + desc + '</td><td>' + buttons + '</td></tr>';
                            div.setAttribute("id", "tr"+data.id);
                            parentElement.appendChild(div);
                            currentGroupId = "";
                            clearForm();
                        },
                        error: function (e) {
                            console.log(e);
                        }
                    }
                );
            }

            event.preventDefault();
            return false;
        }

        function clearForm(){
            document.getElementById('ename').value = "";
            document.getElementById('desc').value = "";
            document.getElementById('onedit').value = "";
            document.getElementById('eid').value = "";
        }

        function addButtonsToExtra(groupid, id, name, desc){
            var text = ' <button type="button" class="btn btn-default waves-effect"\
                    onclick="onEditExtras(\''+ groupid +'\', \''+id+'\', \''+name+'\',\''+desc+'\')"> \
                            <img src="img/iconedit.png" width="25px"> </button>\
                        <button type="button" class="btn btn-default waves-effect" onclick="showDeleteMessage2(\''+id+'\')">\
                        <img src="img/icondelete.png" width="25px"> </button> ';
            return text;
        }

        function onCancelEditExtras(){
            console.log("cancel");
            document.getElementById('formcreateextras').hidden = true;              // hide form
            document.getElementById("table"+currentGroupId).style.display='block';    // show table
            currentGroupId = "";
            clearForm();
            event.preventDefault();
            return false;
        }

</script>

@endsection

@section('content2')

@endsection






