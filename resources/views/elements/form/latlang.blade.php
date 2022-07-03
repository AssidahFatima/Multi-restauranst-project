<div class="col-md-12 " style="margin-bottom: 0px">
    <div class="col-md-3 foodm">
        <label for="lat"><h4>{{$label}}
                @if ($request == "true")
                    <span class="col-red">*</span>
                @endif
                </h4></label>
    </div>
    <div class="col-md-9 foodm">
        <div class="form-group form-group-lg form-float">
            <div class="form-line">
                <input type="number" name="{{$id}}" id="{{$id}}" class="form-control" placeholder="" step="0.00000000000000001" value="{{$initvalue}}">
                <label class="form-label">{{$text}}</label>
            </div>
        </div>
    </div>
</div>
