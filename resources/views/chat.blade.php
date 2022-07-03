@extends('bsb.app')
@inject('lang', 'App\Lang')

@section('content')
    <style>
        .container {
            border: 2px solid #dedede;
            background-color: #f1f1f1;
            border-radius: 15px;
            padding: 10px;
            margin: 10px 0;
        }

        .dot {
            height: 20px;
            width: 20px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
        }

    </style>

    <div class="header">
        <div class="row clearfix">
            <div class="col-md-12">
                <h3 class="">{{$lang->get(292)}}</h3>
            </div>
        </div>
    </div>
    <div class="body">

        <div class="row clearfix">
            <div class="col-md-12">

                <div class="col-md-4">
                    <div class="col-md-12">
                        @include('elements.search.textMax40', array('text' => $lang->get(480), 'id' => "users_search"))  {{--Search--}}
                    </div>
                    <div class="col-md-12">
                        <div style="height: 75vh; min-height: 75vh; width: 100%; position:relative;">
                            <div id="chat-users" style="max-height:100%; min-height: 75vh; overflow:auto;border:1px solid grey;">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div style="height: 75vh; min-height: 75vh; width: 100%; position:relative;">
                        <div id="messagesWindow" style="max-height:65vh; min-height: 65vh; overflow:auto;border:1px solid grey;">
                        </div>
                        <div id="sendMsg" style="height: 10vh; border:1px solid grey;" hidden>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="text" id="messageText" class="form-control">
                                    </div>
                                    <p>{{$lang->get(293)}}</p>
                                </div>
                            </div>
                            <div class="col-md-2" style="height: 100%;">
                                <div style="margin-top: 10%;">
                                    <button type="button" class="btn btn-default waves-effect" onclick="sendMsg()" >
                                        <img src="img/iconsend.png" width="25px">
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <script>
            var currentId = 0;

            function selectUser(id){
                console.log(id);
                // load messages

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ url("getChatMessages") }}',
                    data: {
                        user_id: id,
                    },
                    success: function (data){
                        console.log(data);
                        buildChatUsers();
                        document.getElementById("sendMsg").hidden = false;
                        currentId = id;
                        drawMsg(data);
                    },
                    error: function(e) {
                        console.log(e);
                    }}
                );
            }

            function myGet() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ url("getChatMessages") }}',
                    data: {
                        user_id: currentId,
                    },
                    success: function (data){
                        console.log(data);
                        if (currentLength != data.messages.length)
                            drawMsg(data);
                    },
                    error: function(e) {
                        console.log(e);
                    }}
                );
            }

            setInterval(myGet, 10000); // one time in 10 sec

            function sendMsg(){
                var text = document.getElementById("messageText").value;
                if (text == "")
                    return;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ url("chatNewMessage") }}',
                    data: {
                        user_id: currentId,
                        text: text,
                    },
                    success: function (data){
                        console.log(data);
                        document.getElementById("messageText").value = "";
                        drawMsg(data)
                    },
                    error: function(e) {
                        console.log(e);
                    }}
                );
            }

            var currentLength = 0;

            function drawMsg(data, id){
                var last = "";
                var msg = document.getElementById("messagesWindow");
                msg.innerHTML = "";

                currentLength = data.messages.length;
                data.messages.forEach(function(entry){
                    var now = entry.created_at.substr(0, 11);
                    if (now != last) {
                        var div = document.createElement("div");
                        div.innerHTML = `
                        <div class="container" style="width:20%; margin-left: 40%; margin-right: 40%;">
                            <div style="text-align: center;">
                                <div class="font-14">`+ now +`</div>
                            </div>
                        </div>
                        `;
                        last = now;
                        msg.appendChild(div);
                    }
                    var div = document.createElement("div");
                    var date = entry.created_at.substr(11,5);
                    if (entry.author == "customer"){
                        div.innerHTML = `
                        <div class="container" style="width:60%; margin-left: 5%; margin-right: 35%; ">
                                    <h4>`+ entry.text +`</h4>
                                    <div align="right"><h5>` + date + `</h5></div>
                            </div>
                        `;
                    }else{
                        div.innerHTML = `
                            <div class="container" style="width:60%; margin-left: 35%; margin-right: 5%; background-color: #cbecff">
                                <div style="float: right;">
                                    <h4>`+ entry.text +`</h4>
                                    <div align="right"><h5>` + date + `</h5></div>
                                </div>
                            </div>
                        `;
                    }
                    msg.appendChild(div);
                });
                msg.scrollTop = msg.scrollHeight;
            }

            buildChatUsers();

            function buildChatUsers(){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ url("chatNewUsers") }}',
                    data: {
                    },
                    success: function (data){
                        console.log(data);
                        if (data.error != "0")
                            return showNotification("bg-red", "{{$lang->get(479)}}", "bottom", "center", "", "");  // Something went wrong
                        $text = "";
                        data.users.forEach(function(user, i, arr) {
                            if (!user.name.toUpperCase().includes(searchText.toUpperCase()))
                                return;
                            var messages = "";
                            if (user.messages != 0)
                                messages = `<div id="user${user.id}msgCountDotAll" class="dot" style="float: right; background-color: green;">
                                         <div style="display: table; margin: 0 auto; color: white; vertical-align: middle; text-align: center;" id="user${user.id}msgCountAll">${user.messages}</div>
                                    </div>`;
                            var unread = "";
                            if (user.unread != 0)
                                unread = `<div id="user${user.id}msgCountDot" class="dot" style="float: right; background-color: red; margin-right: 0px; margin-left: 5px">
                                    <div style="display: table; margin: 0 auto; color: white; vertical-align: middle; text-align: center;" id="user${user.id}msgCount">${user.unread}</div>
                                </div>`;
                            var bkg = "#f1f1f1";
                            if (user.id == currentId)
                                bkg = "#cbecff";
                            $text = $text + `<div id="user${user.id}" class="container" style="width:90%; margin-left: 5%; background-color: ${bkg}" onclick="selectUser(${user.id})">
                                        <div class="col-md-6" style="padding: 0px;">
                                            <div class=\"image-cropper\">
                                                <img src="images/${user.image}" width="20px" class='rounded'>
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="padding-right: 0px;">
                                            <div class="col-md-12" style="padding-right: 0px;">
                                                ${unread}
                                                ${messages}
                                            </div>
                                            <div class="col-md-12" style="margin-bottom: 0px; background-color: transparent">
                                                <div style="text-align: right">
                                                    <h4>${user.name}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                `;
                        });
                        document.getElementById("chat-users").innerHTML = $text;
                    },
                    error: function(e) {
                        console.log(e);
                    }}
                );
            }

            var searchText = "";

            $(document).on('input', '#users_search', function(){
                searchText = document.getElementById("users_search").value;
                buildChatUsers();
            });

        </script>

@endsection
