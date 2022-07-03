
<div id="element_{{$id}}">
    <div class="col-md-12">
        <div class="col-md-4 text-right">
            <h4>{{$label}}
                @if ($request == "true")
                    <span class="col-red">*</span>
                @endif
            </h4>
        </div>
        <div class="col-md-8">
            <div class="form-line">
                <input type="text" name="{{$id}}" id="{{$id}}" class="q-form" placeholder="" maxlength="{{$maxlength}}">
            </div>
            <p class="font-12 mdl-color-text--indigo-A700">{{$text}}</p>
        </div>
    </div>
</div>
