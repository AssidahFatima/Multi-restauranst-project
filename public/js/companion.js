function addStartForm(text, url, csrf){
    text = text + '<form id="form" method="post" action="' + url + '"  >' + csrf;
    return text;
}

function addSubBlock(firstText, firstType, firstHint, firstName, firstId){
    var text = '<div class="col-md-2 form-control-label">';
    text = text + '<label for="name"><h4>'+firstText+'</h4></label>';
    text = text + '</div>';
    text = text + '<div class="col-md-4">';
    if (firstType == 1) {  // text
        text = text + '<div class="form-group form-group-lg form-float">';
        text = text + '<div class="form-line">';
        text = text + '<input type="text" name="'+firstName+'" id="'+firstId+'" class="form-control" placeholder="" maxlength="100">'
        text = text + '</div>';
        text = text + '<label class="form-label font-12">'+firstHint+'</label>';
        text = text + '</div>';
    }
    text = text + '</div>';
    return text;
}

function addBlock(text, first){
    text = text + '<div class="row clearfix">';
    text = text + first;
    text = text + '<div class="col-md-2 form-control-label">';

        text = text + '</div>';

        text = text + '<div class="col-md-4">';

        text = text + '</div>';

    text = text + '</div>';
    return text;
}

function addSubmitButton(text, textForButton){
    text = text + ' <div class="row clearfix">\
                <div class="col-md-12 form-control-label">\
                <div align="center">\
                <button type="submit" class="btn btn-primary m-t-15 waves-effect "><h5>'+ textForButton+ '</h5></button>\
            </div>\
            </div>\
            </div>';

    return text;
}

function addEndForm(text){
    text = text + '</form>';
    return text;
}
