@inject('util', 'App\Util')
@inject('lang', 'App\Lang')

<div class="col-md-12">
    <div class="col-md-4 text-right">
        <h4>{{$label}}
            @if ($request == "true")
                <span class="col-red">*</span>
            @endif
        </h4>
    </div>
    <div class="col-md-8">
        <select name="{{$id}}" id="{{$id}}" class="q-form-s show-tick q-radius" onchange="{{$onchange}};" >
            @if ($noitem == "true")
                <option value="0">{{$lang->get(114)}}</option>  {{--No--}}
            @endif
            @foreach($util->getNutritions() as $key => $data)
                <option value="{{$data->id}}">{{$data->name}}</option>
            @endforeach
        </select>
    </div>
</div>
