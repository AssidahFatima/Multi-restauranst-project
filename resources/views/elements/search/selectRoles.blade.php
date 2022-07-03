@inject('util', 'App\Util')
@inject('lang', 'App\Lang')

<div class="col-md-12">
    <div class="col-md-4 align-self-right q-form-label">
        {{$text}}
    </div>
    <div class="col-md-8" style="margin-bottom: 0px">
        <select name="{{$id}}" id="{{$id}}" class="q-form-s show-tick" onchange="{{$onchange}};" >
            <option value="0">{{$lang->get(114)}}</option> {{--No--}}
            @foreach($util->getRoles() as $key => $dataroles)
                <option value="{{$dataroles->id}}">{{$dataroles->role}}</option>
            @endforeach
        </select>
    </div>
</div>


