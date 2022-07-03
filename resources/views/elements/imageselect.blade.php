@inject('util', 'App\Util')

<style>
    .swal-wide{
        position: fixed !important;
        width:70% !important;
{{--        height:400px !important;--}}
        left: 30% !important;
{{--        top: 50% !important;--}}

    }
</style>

<script>

    var editFileNameNotify;

    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone("div#dropzone2", {
        url: "{{url('image/upload/store')}}",
        maxFilesize: 12,
        renameFile: function(file) {
            var dt = new Date();
            var time = dt.getTime();
            return time+file.name;
        },
        init: function () {
            this.hiddenFileInput.removeAttribute('multiple');
        },
        sending: function(file, xhr, formData) {
            formData.append("_token", $('meta[name="_token"]').attr('content'));
        },
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        addRemoveLinks: true,
        timeout: 50000,
        removedfile: function(file)
        {
            console.log("removedfile " + this.files.length);
            if (this.files.length == 0)
                imageid = 0;
            var fileRef;
            return (fileRef = file.previewElement) != null ?
                fileRef.parentNode.removeChild(file.previewElement) : void 0;
        },
        success: async function (file, response) {
            if (this.files.length > 1){
                this.removeFile(this.files[0]);
            }
            imageid = response.id;
            console.log("success " + imageid);
        },
        error: function(file, response)
        {
            return showNotification("bg-red", response, "bottom", "center", "", "");
            return false;
        }
    });


    function fromLibrary(){
        lastEdit = "";
        lastJEdit = "";
        selectId = "";
        selectName = "";

        swal({
            title: "",
            text: "                                <div id=\"div1\" style=\"height: 400px;position:relative;\">\n" +
                "                                    <div id=\"div2\" style=\"max-height:100%;overflow:auto;border:3px solid grey;\">" +
                "<div id=\"thumbimagesEdit\" class=\"row\">\n" +
                "                                            @foreach($util->getImages() as $key => $data)\n" +
                "                                                <div class=\"col-sm-6 col-md-3\" style=\"position: relative; top: 10px; left: 20px;\">\n" +
                "                                                    <div id=\"thumbEdit{{$data->filename}}\" onclick=\"klikajEdit('thumbEdit{{$data->filename}}', 'iconokEdit{{$data->filename}}', {{$data->id}}, '{{$data->filename}}')\"  class=\"thumbnail\">\n" +
                "                                                        <img src=\"images/{{$data->filename}}\" class=\"img-thumbnail\" height=\"200\" style='min-height: 200px; object-fit: contain; z-index: 10; ' alt=\"\">\n" +
                "                                                        <img id=\"iconokEdit{{$data->filename}}\"  src=\"img/iconok.png\" style='visibility:hidden; width: 40px; position: relative; bottom: 200px; left: 70px; z-index: 100;' alt=\"\">\n" +
                "                                                        <div class=\"caption\" style=\"\">\n" +
                "                                                            <p>{{ \Illuminate\Support\Str::substr($data->filename, 13, \Illuminate\Support\Str::length($data->filename)-13) }} </p>\n" +
                "                                                            <p>{{$data->updated_at}}</p>\n" +
                "                                                        </div>\n" +
                "                                                    </div>\n" +
                "                                                </div>\n" +
                "                                            @endforeach\n" +
                "                                        </div>\n" +
                "</div>\n" +
                "                                </div>",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ok",
            cancelButtonText: "Cancel",
            customClass: 'swal-wide',
            closeOnConfirm: true,
            closeOnCancel: true,
            html: true
        }, function (isConfirm) {
            if (isConfirm) {
                if (selectId != "") {
                    imageid = selectId;
                    mockFile = {
                        name: "images/" + selectName,
                        size: 0,
                        dataURL: "images/" + selectName,
                    };
                    myDropzone.createThumbnailFromUrl(mockFile, myDropzone.options.thumbnailWidth, myDropzone.options.thumbnailHeight, myDropzone.options.thumbnailMethod, true, function (dataUrl) {
                        myDropzone.emit("thumbnail", mockFile, dataUrl);
                    });
                    myDropzone.emit("addedfile", mockFile);
                    myDropzone.emit("complete", mockFile);
                    myDropzone.files.push(mockFile);
                    if (myDropzone.files.length > 1){
                        myDropzone.removeFile(myDropzone.files[0]);
                    }
                    editFileNameNotify = selectName;
                }
            } else {

            }
        })
    }

    var lastEdit = "";
    var lastJEdit = "";
    var selectId = "";
    var selectName = "";

    function klikajEdit(i, j, id, name) {
        selectName = name;
        if (lastEdit !== "")
            document.getElementById(lastEdit).style.borderColor = "#e0e0e0";
        if (lastJEdit !== "")
            document.getElementById(lastJEdit).style.visibility ='hidden';
        lastJEdit = j;
        lastEdit = i;
        document.getElementById(i).style.border = "3";
        document.getElementById(i).style.borderColor = "#00FF00";
        document.getElementById(i).style.borderStyle = "solid";
        document.getElementById(j).style.visibility ='visible';
        selectId = id;
    }

    var editFileName;
    var imageid = 0;

    function clearDropZone(){
        imageid = 0;
        if (myDropzone.files.length == 1){
            myDropzone.removeFile(myDropzone.files[0]);
        }
    }

    function addEditImage(id, fileImage) {
        console.log(fileImage);
        if (myDropzone.files.length == 1){
            myDropzone.removeFile(myDropzone.files[0]);
        }
        if (id == 0 || fileImage == "noimage.png")
            return;
        editFileName = fileImage;
        imageid = id;
        mockFile = {
            name: "images/"+fileImage,
            size: 0,
            dataURL: "images/"+fileImage
        };
        myDropzone.createThumbnailFromUrl(mockFile, myDropzone.options.thumbnailWidth, myDropzone.options.thumbnailHeight, myDropzone.options.thumbnailMethod, true, function (dataUrl) {
            myDropzone.emit("thumbnail", mockFile, dataUrl);
        });
        myDropzone.emit("addedfile", mockFile);
        myDropzone.emit("complete", mockFile);
        myDropzone.files.push(mockFile);
    }

</script>
