
<div id="element_{{$id}}">
    <div class="col-md-12 " style="margin-bottom: 0px">
        <div class="col-md-4 form-control-label" style="margin-bottom: 0px">
            <label for="{{$id}}"><h4>{{$label}}
                @if ($request == "true")
                    <span class="col-red">*</span>
                @endif
            </h4></label>
        </div>
        <div class="col-md-8" style="margin-bottom: 0px">
            <div class="form-group form-group-lg form-float " style="margin-bottom: 0px">
                <div class="form-line">
                    <input type="number" name="{{$id}}" id="{{$id}}" class="form-control" value="0">
                </div>
                <p class="font-12 mdl-color-text--indigo-A700">{{$text}}</p>
            </div>
        </div>
    </div>
</div>


<script>

    var amount{{$id}} = document.getElementById('{{$id}}');
    amount{{$id}}.addEventListener('input',  function(e){inputHandler(e, amount{{$id}}, {{$min}}, {{$max}});});

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
</script>
