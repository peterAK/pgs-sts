(function () {
    var input = document.getElementById("images"),
        formdata = false, url = $("#url").val();

    function showUploadedItem (source) {

    }

    if (window.FormData) {
        formdata = new FormData();
        document.getElementById("btn").style.display = "none";
    }

    input.addEventListener("change", function (evt) {
        var i = 0, len = this.files.length, img, reader, file;

        for ( ; i < len; i++ ) {
            file = this.files[i];

            if (!!file.type.match(/image.*/)) {
                if ( window.FileReader ) {
                    reader = new FileReader();
                    reader.onloadend = function (e) {
                        showUploadedItem(e.target.result, file.fileName);
                    };
                    reader.readAsDataURL(file);
                }
                if (formdata) {
                    formdata.append("images[]", file);
                }
            }
        }

        if (formdata) {
            $.ajax({
                url: url,
                type: "POST",
                data: formdata,
                processData: false,
                contentType: false,
                success: function (res) {
                    $("#uploaders").css('display','none');
//                    $("#uploadStatus").css('display','none');
//                    alert(res);
                    var location = $.parseJSON(res);
                    var imageDisplay = '<img src="'+location.path+location.filename+'" class="thumbnail" />';
                    $("#thumb").css({'display':'block', 'margin-bottom':'0px'});
                    $("#thumb-container").css({'display':'block', 'margin-bottom':'0px'});
                    $("#thumb").html(imageDisplay);
                    $("#"+location.inputId).val(location.filename);
                },
                beforeSend: function(){
                    $("#loader").css('display','inline');
                    $("#uploadStatus").css('display','inline');
                    $("#uploadStatus").html('Uploading...');
                    $(".fileUpload").hide();
                    $("#thumb").css('display', 'none');
                }
            });
        }
    }, false);
}());
