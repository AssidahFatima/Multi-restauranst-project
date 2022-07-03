@inject('util', 'App\Util')

<div class="col-md-12 q-mb10">
    <div class="col-md-4 text-right">
        <h4>{{$label}}
            @if ($request == "true")
                <span class="col-red">*</span>
            @endif
        </h4>
    </div>
    <div class="col-md-8">
        <select name="{{$id}}" id="{{$id}}" class="q-form-s show-tick q-radius" onchange="{{$onchange}};" >
            @foreach($util->getRoles() as $key => $dataroles)
                <option value="{{$dataroles->id}}">{{$dataroles->role}}</option>
            @endforeach
        </select>
    </div>
</div>
