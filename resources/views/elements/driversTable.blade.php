@inject('lang', 'App\Lang')

<div class="table-responsive">

    <div class="col-md-12" style="margin-bottom: 10px">
        <div class="q-btn-all q-color-second-bkg waves-effect" onClick="exportCSV()" style="height: 50px"><h4>{{$lang->get(613)}}</h4></div>       {{--Export to CSV--}}
    </div>

    <div class="col-md-12" style="margin-bottom: 10px">
        <div class="col-md-4" style="margin-bottom: 0px">
            @include('elements.search.selectStatus', array('text' => $lang->get(481), 'id' => "rest_search", 'onchange' => "onStatusSearchSelect(this)"))  {{--Filter--}}
        </div>
        <div class="col-md-4" style="margin-bottom: 0px">
            @include('elements.search.selectRest', array('text' => $lang->get(8), 'id' => "rest_search", 'onchange' => "onRestSearchSelect(this)"))  {{--Markets--}}
        </div>
        <div class="col-md-4" style="margin-bottom: 0px">
            @include('elements.search.textMax40', array('text' => $lang->get(480), 'id' => "users_search"))  {{--Search--}}
        </div>
    </div>

    <table id="data_table" class="table table-bordered table-striped table-hover">
        <thead>
        <tr id="table_header">
            {{--header items--}}
        </tr>
        </thead>
        <tfoot>
        <tr >
            <th>{{$lang->get(43)}}</th> {{--Order ID--}}
            <th>{{$lang->get(44)}}</th> {{--Total--}}
            <th>{{$lang->get(45)}}</th> {{--Client--}}
            <th>{{$lang->get(46)}}</th> {{--Order Status--}}
            <th>{{$lang->get(47)}}</th> {{--Market--}}
            <th>{{$lang->get(48)}}</th> {{--Details--}}
            <th>{{$lang->get(49)}}</th> {{--Updated At--}}
            <th>{{$lang->get(222)}}</th> {{--Status--}}
            <th>{{$lang->get(74)}}</th> {{--Action--}}


        </tr>
        </tfoot>
        <tbody id="table_body">

            @foreach($orders as $key => $data)
            @if ($data->send == 1)
                <tr id="tr{{$data->id}}">
                    <td>{{$data->id}}</td>
                    <td id="total{{$data->id}}">
                        @if ($rightSymbol == "false")
                            {{$currency}}{{sprintf('%0.' . $symbolDigits . 'f', $data->total)}}
                        @else
                            {{sprintf('%0.' . $symbolDigits . 'f', $data->total)}}{{$currency}}
                        @endif
                    </td>
                    <td>
                        @foreach($iusers as $key => $idata)
                            @if ($idata->id == $data->user)
                                {{$idata->name}}
                            @endif
                        @endforeach
                    </td>

                    <td>
                        @if ($userinfo->getUserPermission("Orders::Edit"))
                            <select name="role" id="role{{$data->id}}" class="form-control show-tick" onchange="checkStatus(event, {{$data->id}})" >
                                @if ($data->curbsidePickup == "true")
                                    @foreach($iorderstatus as $key => $idata)
                                        @if ($idata->id != 4)
                                            @if ($idata->id == $data->status)
                                                <option id="role{{$data->id}}_{{$idata->id}}" value="{{$idata->id}}" selected style="font-size: 16px  !important;">{{$idata->status}}</option>
                                            @else
                                                <option id="role{{$data->id}}_{{$idata->id}}" value="{{$idata->id}}" style="font-size: 16px  !important;">{{$idata->status}}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                @else
                                    @foreach($iorderstatus as $key => $idata)
                                        @if ($idata->id == $data->status)
                                            <option id="role{{$data->id}}_{{$idata->id}}" value="{{$idata->id}}" selected style="font-size: 16px  !important;">{{$idata->status}}</option>
                                        @else
                                            <option id="role{{$data->id}}_{{$idata->id}}" value="{{$idata->id}}" style="font-size: 16px  !important;">{{$idata->status}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        @else
                            @foreach($iorderstatus as $key => $idata)
                                @if ($idata->id == $data->status)
                                    {{$idata->status}}
                                @endif
                            @endforeach
                        @endif
                    </td>

                    <td>
                        @foreach($irestaurants as $key => $idata)
                            @if ($idata->id == $data->restaurant)
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
                    <td>
                        @if ($data->curbsidePickup == "true")
                            <span class="badge bg-red">{{$lang->get(213)}}</span>
                            @if ($data->arrived == "true")
                                <span class="badge bg-red">{{$lang->get(214)}}</span><br>
                            @else
                                <br>
                            @endif
                        @endif
                        <span class="badge bg-teal">{{$data->method}}</span>
                    </td>
                    <td>{{$data->updated_at}}</td>

                    <td>
                        @if ($userinfo->getUserPermission("Orders::Edit"))
                            <button type="button" class="btn btn-default waves-effect" onclick="viewItem('{{$data->id}}',
                                '{{$data->created_at}}', '{{$data->updated_at}}')">
                                <img src="img/iconview.png" width="25px">
                            </button>
                        @endif
                        @if ($userinfo->getUserPermission("Orders::Delete"))
                            <button type="button" class="btn btn-default waves-effect" onclick="showDeleteMessage('{{$data->id}}')">
                                <img src="img/icondelete.png" width="25px">
                            </button>
                        @endif

                    </td>
                </tr>
            @endif

        @endforeach

        </tbody>
    </table>

    <div align="center">
        <nav>
            <div id="paginationList" >
                {{-- pagination list--}}
            </div>
        </nav>
    </div>

</div>




<script>

    var pages = 1;
    var currentPage = 1;
    var sortRest = 0;
    var sortCat = 0;
    var searchText = "";
    var sort = "updated_at";
    var sortBy = "desc";

    paginationGoPage(1);
    initPaginationLine(pages, currentPage);
    initTableHeader();

    function paginationGoPage(page){
        var data = {
            page: page,
            sortAscDesc: sortBy,
            sortBy : sort,
            rest: sortRest,
            cat: sortCat,
            search: searchText,
        };
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'POST',
            url: '{{ url("ordersGoPage") }}',
            data: data,
            success: function (data){
                console.log(data);
                currentPage = data.page;
                pages = data.pages;
                if (data.error != "0" || data.idata == null)
                    return showNotification("bg-red", "{{$lang->get(479)}}", "bottom", "center", "", "");  // Something went wrong
                initUsersDataTable(data.idata);
                initPaginationLine(pages, data.page);
                initTableHeader();
            },
            error: function(e) {
                dataLoading = false;
                console.log(e);
            }}
        );
    }

    function initUsersDataTable(data){
        html = "";
        data.forEach(function (item, i, arr) {
            html += buildOneItem(item);
        });
        document.getElementById("table_body").innerHTML = html;
        $('.show-tick').selectpicker('refresh');
    }

    function buildOneItem(item){
        //
         var status="";
         if (item.status==6 ){
            var status = `<img src="img/iconnoo.jpg" height="23px">`;
        } else if (item.status== 5){
            var status = `<img src="img/iconok.png" height="25px">`;
        }
        else{
            var status = `<img src="img/symbole.png" height="30px">`;
        }


        //

        var text = ``;
        if (item.curbsidePickup == "true") {
            text = `<span class="badge bg-red">{{$lang->get(213)}}</span>`;
            if (item.arrived == "true")
                text += `<span class="badge bg-red">{{$lang->get(214)}}</span>`;
            text += "<br>";
        }
        text += `<span class="badge bg-teal">${item.method}</span>`;
        //
        var text2 = "";
        @foreach($iorderstatus as $key => $idata)
            if ({{$idata->id}} == item.status)
                text2 = item.status;
        @endforeach
        //
        var text3 = "";
        if (item.curbsidePickup == "true"){
            @foreach($iorderstatus as $key => $idata)
                if ({{$idata->id}} != 4){
                    if ({{$idata->id}} == item.status)
                        text3 += `<option id="role${item.id}_{{$idata->id}}" value="{{$idata->id}}" selected style="font-size: 16px  !important;">{{$idata->status}}</option>`;
                    else
                        text3 += `<option id="role${item.id}_{{$idata->id}}" value="{{$idata->id}}" style="font-size: 16px  !important;">{{$idata->status}}</option>`;
                }
            @endforeach
        } else {
            @foreach($iorderstatus as $key => $idata)
                if ({{$idata->id}} == item.status)
                    text3 += `<option id="role${item.id}_{{$idata->id}}" value="{{$idata->id}}" selected style="font-size: 16px  !important;">{{$idata->status}}</option>`;
                else
                    text3 += `<option id="role${item.id}_{{$idata->id}}" value="{{$idata->id}}" style="font-size: 16px  !important;">{{$idata->status}}</option>`;
            @endforeach
        }


        return `
            <tr>
                <td>${item.id}</td>
                <td>${item.totalFull}</td>
                <td>${item.name}</td>
                <td>

                @if ($userinfo->getUserPermission("Orders::Edit"))
                    <select name="role" id="role" class="form-control show-tick" onchange="checkStatus(event, ${item.id})" >
                        ${text3}
                    </select>
                @else
                        ${text2}
                @endif

                </td>
                <td>${item.restaurantName}</td>
                <td>${text}</td>

                <td><div class="font-bold col-teal">${item.timeago}</div>${item.updated_at}</td>
                <td>
                    ${status}
                    </td> {{--Status--}}
                <td>
            @if ($userinfo->getUserPermission("Orders::Edit"))
                <button type="button"  class="btn btn-default waves-effect"  onclick="viewItem('${item.id}')">
                    <img src="img/iconview.png" width="25px">
                </button>
            @endif

        </td>

    </tr>
    `;
    }

    function initPaginationLine(pages, page){
        var html = "<ul class=\"pagination\">";
        for (var i = 1; i <= pages; i++) {
            if (i == page)
                html += `<li class="active"><a href="javascript:void(0);">${i}</a></li>`;
            else
                html += `<li><a href="javascript:void(0);" onClick="paginationGoPage(${i})" class="waves-effect">${i}</a></li>`;
        };
        html += "</ul>";
        document.getElementById("paginationList").innerHTML = html;
    }

    function tableHeaderSort(newsort){
        if (newsort == sort) {
            if (sortBy == "asc")
                sortBy = "desc";
            else
                sortBy = "asc";
        }
        else{
            sort = newsort
            sortBy = "asc";
        }
        paginationGoPage(currentPage);
    }


    function initTableHeader(){
        var html = `
            <th>{{$lang->get(43)}} <img onclick="tableHeaderSort('orders.id');" src="${utilGetImg('orders.id')}" class="img-fluid" style="margin-left: 10px; width: 20px; float: right;"></th> {{--Id--}}
            <th>{{$lang->get(44)}} <img onclick="tableHeaderSort('orders.total');" src="${utilGetImg('orders.total')}" class="img-fluid" style="margin-left: 10px; width: 20px; float: right;"></th> {{--Total--}}
            <th>{{$lang->get(45)}} <img onclick="tableHeaderSort('users.name');" src="${utilGetImg('users.name')}" class="img-fluid" style="margin-left: 10px; width: 20px; float: right;"></th> {{--Client--}}
            <th>{{$lang->get(46)}} </th> {{--Order Status--}}
            <th>{{$lang->get(47)}} <img onclick="tableHeaderSort('orders.restaurant');" src="${utilGetImg('orders.restaurant')}" class="img-fluid" style="margin-left: 10px; width: 20px; float: right;"></th> {{--Market--}}
            <th>{{$lang->get(48)}} </th> {{--Details--}}
            <th>{{$lang->get(49)}} <img onclick="tableHeaderSort('orders.updated_at');" src="${utilGetImg('orders.updated_at')}" class="img-fluid" style="margin-left: 10px; width: 20px; float: right;"></th> {{--Updated At--}}
            <th>{{$lang->get(222)}}</th> {{--Status--}}
            <th>{{$lang->get(74)}} </th> {{--Action--}}


        `;
        document.getElementById("table_header").innerHTML = html;
    }

    function utilGetImg(value){
        var img = "img/arrow_noactive.png";
        if (sort == value && sortBy == "asc") img = "img/asc_arrow.png";
        if (sort == value && sortBy == "desc") img = "img/desc_arrow.png";
        return img;
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
                    url: '{{ url("userdelete") }}',
                    data: {id: id},
                    success: function (data){
                        if (!data.ret)
                            return showNotification("bg-red", data.text, "bottom", "center", "", "");
                        //
                        // remove from ui
                        //
                        paginationGoPage(currentPage);
                    },
                    error: function(e) {
                        console.log(e);
                    }}
                );
            } else {

            }
        });
    }

    function onRestSearchSelect(object){
        sortRest = object.value;
        currentPage = 1;
        paginationGoPage(currentPage);
    }

    function onStatusSearchSelect(object){
        sortCat = object.value;
        currentPage = 1;
        paginationGoPage(currentPage);
    }

    $(document).on('input', '#users_search', function(){
        searchText = document.getElementById("users_search").value;
        console.log(searchText);
        currentPage = 1;
        paginationGoPage(1);
    });

    function exportCSV(){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'POST',
            url: '{{ url("exportCSVOrders") }}',
            data: {},
            success: function (data){
                console.log("exportCSVOrders", data);
                if (data.error !== '0')
                    return showNotification("bg-red", "{{$lang->get(479)}}", "bottom", "center", "", "");  // Something went wrong
                var link = document.createElement("a");
                link.download = data.file;
                link.href = data.file;
                link.click();
            },
            error: function(e) {
                console.log(e);
            }});
    }

</script>
