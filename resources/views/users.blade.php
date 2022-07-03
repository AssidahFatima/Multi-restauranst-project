@inject('userinfo', 'App\UserInfo')
@inject('lang', 'App\Lang')
@extends('bsb.app')

@section('content')
    <div class="header">
        <div class="row clearfix">
            <div class="col-md-12">
                <h3 class="">{{$lang->get(189)}}</h3>
            </div>
        </div>
    </div>
    <div class="body">

        <!-- Tabs -->

        <ul class="nav nav-tabs tab-nav-right" role="tablist">
            <li role="presentation" class="active"><a href="#home" data-toggle="tab"><h4>{{$lang->get(64)}}</h4></a></li>
            @if ($userinfo->getUserPermission("Users::Create"))
                <li role="presentation"><a href="#create" data-toggle="tab" ><h4>{{$lang->get(65)}}</h4></a></li>
            @endif
            <li id="tabEdit" style='display:none;' role="presentation"><a href="#edit" data-toggle="tab"><h4>{{$lang->get(66)}}</h4></a></li>
        </ul>

        <!-- Tab List -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="home">

                <div class="row clearfix js-sweetalert">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h3>
                                    {{$lang->get(190)}}  {{--USERS LIST--}}
                                </h3>
                            </div>
                            <div class="body">
                                @include('elements.usersTable', array())
                            </div>
                        </div>
                    </div>
                </div>

                <!-- End Tab List -->

                <!-- Tab Create -->

                <div role="tabpanel" class="tab-pane fade" id="create">

                    <div id="form">

                        <div class="row clearfix">
                            <div class="col-md-6 ">
                                @include('elements.form.text', array('label' => $lang->get(69), 'text' => $lang->get(91), 'id' => "name", 'request' => "true", 'maxlength' => "40"))  {{-- Name - Insert Name --}}
                                @include('elements.form.selectRoles', array('label' => $lang->get(192), 'onchange' => "checkRole(event)", 'text' => $lang->get(114), 'id' => "role", 'request' => "true"))  {{-- Role - Insert Name --}}
                                @include('elements.form.text', array('label' => $lang->get(191), 'text' => $lang->get(193), 'id' => "email", 'request' => "true", 'maxlength' => "40"))  {{-- Email - Insert Email --}}
                                @include('elements.form.text', array('label' => $lang->get(194), 'text' => $lang->get(195), 'id' => "password", 'request' => "true", 'maxlength' => "40"))  {{-- Password - Insert Password --}}
                                <div class="col-md-4 "></div>
                                <div class="col-md-8 ">
                                    <div id="social_image"></div>
                                </div>
                                @include('elements.form.text', array('label' => $lang->get(150), 'text' => $lang->get(153), 'id' => "address", 'request' => "false", 'maxlength' => "100"))  {{-- Address - Insert Address --}}
                                @include('elements.form.text', array('label' => $lang->get(151), 'text' => $lang->get(154), 'id' => "phone", 'request' => "false", 'maxlength' => "40"))  {{-- Phone - Insert Phone number --}}
                            </div>
                            <div class="col-md-6 ">
                                @include('elements.form.image', array())
                            </div>
                        </div>

                        <div id="restList" class="row clearfix" hidden>
                            <div class="col-md-1 " style="margin-top: 20px;">
                            </div>
                            <div class="col-md-5 " style="margin-top: 20px;">
                                @foreach($restaurants as $key => $value)
                                    <div>
                                        @include('elements.form.check', array('id' => "rest" . $value->id, 'text' => $value->name, 'initvalue' => "false"))
                                    </div>

                                @endforeach

                            </div>
                            <div class="col-md-6 " style="margin-top: 50px;">
                                @include('elements.form.info', array('title' => $lang->get(196), 'body1' => $lang->get(197), 'body2' => $lang->get(198)))  {{-- Important - Select Markets for this Manager - Manager can edit Market Info...--}}
                            </div>
                        </div>

                        @include('elements.form.button', array('label' => $lang->get(142), 'onclick' => "onSaveUser();"))  {{-- Save --}}

                    </div>
                </div>


                <!-- Tab Edit -->

                <div role="tabpanel" class="tab-pane fade" id="edit">
                </div>

            </div>
        </div>

        @include('elements.imageselect', array())

        <script type="text/javascript">

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr("href")
                if (target != "#edit")
                    document.getElementById("tabEdit").style.display = "none";
                if (target == "#create") {
                    clearForm();
                    document.getElementById('create').appendChild(document.getElementById("form"));
                    $('#role').val(4).change();
                    $('.show-tick').selectpicker('refresh');
                }
                if (target == "#home") {
                    clearForm();
                }
            });

            @if ($showuser != "")
                editItem('{{$showuser}}');
            @endif

            var editId = 0;

            function editItem(id) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ url("userGetInfo") }}',
                    data: {
                        id: id,
                    },
                    success: function (data){
                        console.log(data);
                        if (data.error != "0" || data.user == null)
                            return showNotification("bg-red", "{{$lang->get(479)}}", "bottom", "center", "", "");  // Something went wrong
                        document.getElementById("tabEdit").style.display = "block";
                        $('.nav-tabs a[href="#edit"]').tab('show');
                        //
                        var target = document.getElementById("form");
                        document.getElementById('edit').appendChild(target);
                        //
                        document.getElementById("name").value = data.user.name;
                        editId = data.user.id;
                        // document.getElementById("imageid").value = data.user.imageid;
                        document.getElementById("address").value = data.user.address;
                        document.getElementById("phone").value = data.user.phone;
                        document.getElementById("email").value = data.user.email;
                        //
                        addEditImage(data.user.imageid, data.user.filename);
                        //
                        $('#role').val(data.user.role).change();
                        $('.show-tick').selectpicker('refresh');
                        //
                        //
                        // Manager -> Restaurants
                        //
                        if (data.user.role == "2") {
                            document.getElementById("restList").hidden = false;
                            data.managerest.forEach(function(item, i, arr) {
                                if (data.user.id == item.user) {
                                    this["onSetCheck_rest"+item.restaurant](true);
                                }
                            });
                        }else
                            document.getElementById("restList").hidden = true;
                        //
                        @if ($userinfo->getUserPermission("Users::ChangePassword"))
                            document.getElementById("element_password").hidden = false;
                        @else
                            document.getElementById("element_password").hidden = true;
                        @endif
                        //
                        if (data.user.typeReg == "facebook" || data.user.typeReg == "google") {
                            document.getElementById("social_image").innerHTML = `<img src="img/${data.user.typeReg}.png" height="50px">`;
                            document.getElementById("element_password").hidden = true;
                            document.getElementById("element_email").hidden = true;
                        }
                    },
                    error: function(e) {
                        showNotification("bg-red", "{{$lang->get(479)}}", "bottom", "center", "", "");  // Something went wrong
                        console.log(e);
                    }}
                );
            }

            function checkRole(event){
                if (event.target.value == "2")  // manager
                    document.getElementById("restList").hidden = false;
                else
                    document.getElementById("restList").hidden = true;
            }

            function onSaveUser(){
                var data = {
                    id: editId,
                    name: document.getElementById("name").value,
                    role: $('select[id=role]').val(),
                    email: document.getElementById("email").value,
                    password: document.getElementById("password").value,
                    phone: document.getElementById("phone").value,
                    address: document.getElementById("address").value,
                    image: imageid,
                };
                @foreach($restaurants as $key => $value)
                    data.rest{{$value->id}} = (rest{{$value->id}}) ? "on" : "off"; //document.getElementById("rest{{$value->id}}").value;
                @endforeach

                if (!document.getElementById("name").value)
                    return showNotification("bg-red", "{{$lang->get(85)}}", "bottom", "center", "", "");  // The Name field is required.
                if (!document.getElementById("email").value)
                    return showNotification("bg-red", "{{$lang->get(200)}}", "bottom", "center", "", "");  // The `Email` field is required.
                if (!document.getElementById("password").value)
                    if (editId == 0)
                        return showNotification("bg-red", "{{$lang->get(482)}}", "bottom", "center", "", "");  // The `Password` field is required.

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ url("useradd") }}',
                    data: data,
                    success: function (data){
                        console.log(data);
                        if (data.error == "2")
                            return showNotification("bg-red", "{{$lang->get(484)}}", "bottom", "center", "", "");  // User with this email already exist
                        if (data.error != "0" || data.user == null)
                            return showNotification("bg-red", "{{$lang->get(479)}}", "bottom", "center", "", "");  // Something went wrong
                        if (editId != 0)
                            paginationGoPage(currentPage);
                        else{
                            var text = buildOneItem(data.user);
                            var text2 = document.getElementById("table_body").innerHTML;
                            document.getElementById("table_body").innerHTML = text+text2;
                        }
                        $('.nav-tabs a[href="#home"]').tab('show');
                        if (editId == 0)
                            showNotification("bg-teal", "{{$lang->get(483)}}", "bottom", "center", "", ""); // New user created
                        else
                            showNotification("bg-teal", "{{$lang->get(485)}}", "bottom", "center", "", ""); // Data saved
                        clearForm();
                    },
                    error: function(e) {
                        showNotification("bg-red", "{{$lang->get(479)}}", "bottom", "center", "", "");  // Something went wrong
                        console.log(e);
                    }}
                );
            }

            function clearForm(){
                document.getElementById("name").value = "";
                document.getElementById("email").value = "";
                document.getElementById("password").value = "";
                document.getElementById("phone").value = "";
                document.getElementById("address").value = "";
                clearDropZone();
                document.getElementById("element_password").hidden = false;
                document.getElementById("element_email").hidden = false;
                $('#role').val(0).change();
                editId = 0;
                document.getElementById("social_image").innerHTML = "";
            }
        </script>

@endsection
