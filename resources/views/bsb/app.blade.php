@inject('userinfo', 'App\UserInfo')
@inject('settings', 'App\Settings')
@inject('lang', 'App\Lang')

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome To | {{config('app.name')}}</title>

<meta name="_token" content="{{csrf_token()}}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>

    <!-- Favicon-->
    <link rel="icon" href="img/logo.png" type="image/png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">


    <link href="css/markets.css" rel="stylesheet">


    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="css/themes/all-themes.css" rel="stylesheet" />

    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Demo Js -->
    <script src="js/demo.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

    <!-- JQuery DataTable Css -->
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Sweetalert Css -->
    <link href="plugins/sweetalert/sweetalert.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <script src="js/companion.js"></script>



 <!-- script click notification -->
    <script>
        $(document).ready(function(){
            var down = false;
            $('#bell').click(function(e){
                var color = $(this).text();
                if(down){
                    $('#box').css('height','0px');
                    $('#box').css('opacity','0');
                    down = false;
                }else{
                    $('#box').css('height','auto');
                    $('#box').css('opacity','1');
                    down = true;
                }
            });
                });
    </script>


<script>
    $(document).ready(function(){
        var drop = false;
        $('#admin').click(function(e){
            var color = $(this).text();
            if(drop){
                $('#dropdown-admin').css('height','0px');
                $('#dropdown-admin').css('opacity','0');
                drop = false;
            }else{
                $('#dropdown-admin').css('height','auto');
                $('#dropdown-admin').css('opacity','1');
                drop = true;
            }
        });
            });
</script>

    @include('bsb.style', array())

    <style>
    .swal-wide{
        position: fixed !important;
        width:70% !important;
        border-radius: 20px !important;
{{--        height:400px !important;--}}
        left: 30% !important;
{{--        top: 50% !important;--}}
        }
body{
font-family: 'Poppins', sans-serif !important;
}
.checkbox{
    border-radius: 10px !important;
}
.dropzone {
    border-radius: 10px !important;
}
.card{
    border-radius: 10px;
}
.info{
    border-left: 2px solid red;
    padding-left: 10px;
}
.dropdown-toggle {
	font-size: 16px  !important;
	top: 10px;
}
.checkmark {
    position: absolute;
    top: 5px;
    left: 0;
    height: 20px;
    width: 20px;
    border-radius:5px;
}
.checkmark2 {
    margin: 10px 0px 10px 30px !important;
    top: 5px;
    left: 0;
    height: 20px;
    width: 20px;
    border-radius:5px;
}
    .foodm{
        margin-bottom: 0px !important;
        border: none;
    }
    .foodlabel{
      font-weight: normal;
      color: #aaa;
      position: absolute;
      /* top: 10px; */
      left: 0;
      cursor: text;
      -moz-transition: 0.2s;
      -o-transition: 0.2s;
      -webkit-transition: 0.2s;
      transition: 0.2s;
    }
    </style>


</head>

<body class="theme-teal" dir="{{$lang->direction()}}">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->


    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <div> <img src="img/logo.png" height="50px"><a class="navbar-brand" href="home">Multi Restaurants Food Delivery</a></div>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">

                    <li class="dropdown ">
                        <a href="{{route('orders')}}"  role="button">
                            <i class="material-icons" >folder_open</i>
                            <span id="countNewOrders" class="label-count">0</span>
                        </a>
                    </li>




            @if ($userinfo->getUserPermission("Chat::View") )
                    <li class="dropdown ">
                        <a href="{{route('chat')}}"  role="button">
                            <i class="material-icons">chat_bubble_outline</i>
                            <span id="countChatNewMessages" class="label-count">0</span>
                        </a>
                    </li>
            @endif
            <li class="dropdown ">
                <a  role="button">

            <div class="icon3" id="bell"> <i class="material-icons">notifications</i> </div>
            <span id="countNewOrders" class="label-count">0</span></a>
                <div class="notifications" id="box">
                    <div class="d-flex">
                        <h6 class="flex">Notifications</h6>
                        <a href="#" class="read-all" target="_blank"><span>Set read all</span></a>

                          <p>Number of unread notifications: <span>2</span></p>
                    </div>
                    <div class="notifications-item"><img src="https://www.pngmart.com/files/21/Admin-Profile-Vector-PNG-File.png" alt="img">
                        <div class="text">
                            <h4>Samso aliao</h4>
                            <p>Samso Nagaro Like your home work</p>
                        </div>
                    </div>
                    <div class="notifications-item"> <img src="https://www.pngmart.com/files/21/Admin-Profile-Vector-PNG-File.png" alt="img">
                        <div class="text">
                            <h4>John Silvester</h4>
                            <p>+20 vista badge earned</p>
                        </div>
                    </div>
                </div>
            </li>


            <li class="dropdown ">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="margin-top:9px ;"><div class="drop-toggle" id="admin">{{ $userinfo->getUserRole() }}<span class="caret"></span></div>
        </a>
        <ul class="dropdown-menu dropadmin"id="dropdown-admin" style="margin-top:30px !important ; margin-right:-50px;">
        <li><a href="users?user_id={{ Auth::user()->id }}"role="button"><i class="material-icons">person</i>Profile</a></li>

        <li><a href="{{route('home')}}" role="button"> <i class="material-icons">help</i>Help</a></li>
        <li><a href="{{route('logout')}}"  role="button">
                            <i class="material-icons">input</i>Log Out</a></li>


        </ul>
      </li>



                </ul>
            </div>

        </div>
    </nav>

    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info q-mt20" >
                <div class="image">
                    <img src="{{$userinfo->getUserAvatar()}}" width="48" height="48" alt="User" />
                </div>
                <div class="user_info1">
                           <h6>{{Auth::user()->name}}</h6>
                          <div class="online-div"> <h2 class="online"> </h2><p class="fati">Online</p></div>
                        </div>

            </div>
            <!-- #User Info -->

            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">{{$lang->get(37)}}</li>
                    @include('elements.menu', array())
                </ul>
            </div>

            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; {{ $settings->getCopyright() }}
                </div>
                <div class="version">
                    <b>{{$lang->get(36)}}: </b> {{ $settings->getVersion() }}
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>

    <section class="content">
        <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        @yield('content')
                    </div>
                </div>
            </div>


            @yield('content2')


        </div>
    </section>

    <!-- Bootstrap Notify Plugin Js -->
    <script src="plugins/bootstrap-notify/bootstrap-notify.js"></script>

<script>
    function showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit) {
        if (colorName === null || colorName === '') { colorName = 'bg-black'; }
        if (text === null || text === '') { text = 'alert'; }
        if (animateEnter === null || animateEnter === '') { animateEnter = 'animated fadeInDown'; }
        if (animateExit === null || animateExit === '') { animateExit = 'animated fadeOutUp'; }
        var allowDismiss = true;
        $.notify({
                message: text
            },
            {
                type: colorName,
                allow_dismiss: allowDismiss,
                newest_on_top: true,
                timer: 1000,
                placement: {
                    from: placementFrom,
                    align: placementAlign
                },
                animate: {
                    enter: animateEnter,
                    exit: animateExit
                },
                template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0} ' + (allowDismiss ? "p-r-35" : "") + '" role="alert">' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                    '<span data-notify="icon"></span> ' +
                    '<span data-notify="title">{1}</span> ' +
                    '<span data-notify="message">{2}</span>' +
                    '<div class="progress" data-notify="progressbar">' +
                    '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                    '</div>' +
                    '<a href="{3}" target="{4}" data-notify="url"></a>' +
                    '</div>'
            });
    }
    function inputHandler(e, parent, min, max) {
        var value = parseInt(e.target.value);
        if (value.isEmpty)
            value = 0;
        if (isNaN(value))
            value = 0;
        if (value > max)
            value = max;
        if (value < min)
            value = min;
        parent.value = value;
    }
    var lastOrders = 0;
    function getChatNewMessages() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'POST',
            url: '{{ url("getChatMessagesNewCount") }}',
            data: {
            },
            success: function (data){
                console.log(data);
                if (document.getElementById("countChatNewMessages") != null)
                    document.getElementById("countChatNewMessages").innerHTML = data.count;
                document.getElementById("countNewOrders").innerHTML = data.orders;
                if (data.orders != lastOrders){
                    lastOrders = data.orders;
                    const audio = new Audio("img/sound.mp3");
                    audio.play();
                }
                if (document.getElementById("messagesWindow") != null)
                    buildChatUsers();
            },
            error: function(e) {
                console.log(e);
            }}
        );
    }
    setInterval(getChatNewMessages, 10000); // one time in 10 sec
    getChatNewMessages();
    function moveToPageWithSelectedItem(id) {
        var itemsTable = $('#data_table').DataTable();
        var indexes = itemsTable
            .rows()
            .indexes()
            .filter( function ( value, index ) {
                return id === itemsTable.row(value).data()[0];
            } );
        var numberOfRows = itemsTable.data().length;
        var rowsOnOnePage = itemsTable.page.len();
        if (rowsOnOnePage < numberOfRows) {
            var selectedNode = itemsTable.row(indexes).node();
            var nodePosition = itemsTable.rows({order: 'current'}).nodes().indexOf(selectedNode);
            var pageNumber = Math.floor(nodePosition / rowsOnOnePage);
            itemsTable.page(pageNumber).draw(false); //move to page with the element
            return pageNumber;
        }
        return 0;
    }
</script>


</body>


</html>
