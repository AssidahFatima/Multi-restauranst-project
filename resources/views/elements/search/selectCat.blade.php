@inject('utils', 'App\Util')
@inject('lang', 'App\Lang')

<div class="col-md-12">
    <div class="col-md-4 align-self-right q-form-label">
        {{$text}}
    </div>
    <div class="col-md-8" style="margin-bottom: 0px">
        <select name="{{$id}}" id="{{$id}}" class="q-form-s show-tick" onchange="{{$onchange}};" >
            <option value="0">{{$lang->get(114)}}</option> {{--No--}}
            @foreach($utils->getCategories() as $key => $data)
                <option value="{{$data->id}}">{{$data->name}}</option>
            @endforeach
        </select>
    </div>
</div>


