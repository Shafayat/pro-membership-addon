jQuery(function ($) {
    var plan_id = $(".wppm-plan:checked").val();
    $(".list-group-item.ttip").hide();
    $(".wppm-plan:checked").closest('.list-group-item.ttip').show();

    $(".wppm-plan").change(function () {
        plan_id = $(".wppm-plan:checked").val();
        var host = document.location.origin === 'http://localhost' ? 'http://localhost/wordpress' : document.location.origin;
        window.location.href = updateURLParameter(document.URL, 'plan_id', plan_id)
    });

    function updateURLParameter(url, param, paramVal) {
        var newAdditionalURL = "";
        var tempArray = url.split("?");
        var baseURL = tempArray[0];
        var additionalURL = tempArray[1];
        var temp = "";
        if (additionalURL) {
            tempArray = additionalURL.split("&");
            for (var i = 0; i < tempArray.length; i++) {
                if (tempArray[i].split('=')[0] != param) {
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        }

        var rows_txt = temp + "" + param + "=" + paramVal;
        return baseURL + "?" + newAdditionalURL + rows_txt;
    }


    var file_frame; // variable for the wp.media file_frame

    // attach a click event (or whatever you want) to some element on your page
    $('#frontend-button,#frontend-image').on('click', function (event) {
        event.preventDefault();

        // if the file_frame has already been created, just reuse it
        if (file_frame) {
            file_frame.open();
            return;
        }

        file_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text'),
            },
            multiple: false // set this to true for multiple file selection
        });

        file_frame.on('select', function () {
            attachment = file_frame.state().get('selection').first().toJSON();
            // do something with the file here
            // $('#frontend-button').hide();
            $('#pma_label_logo').val(attachment.url);
            $('#frontend-image').attr('src', attachment.url);
        });

        file_frame.open();
    });
});
