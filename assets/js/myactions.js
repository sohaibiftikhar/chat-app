/**
 * Created by sohaib on 09/10/15.
 */
function scrollToBottom(){
    var d = $(".chat-box-main");
    d.scrollTop(d.prop("scrollHeight"));
}

function update(){
    var id = $('#chat-box-main').children().last().attr('id');
    $.get( "index.php?refresh=" + id, function( data ) {
        //console.log(data);
        $('#chat-box-main').append(data);
        if(data.length>0) scrollToBottom();
    });
}

$('#document').ready(function(){
    scrollToBottom();
    setInterval(update, 2000);
    $('#send-message').on('click', function() {
        var message = $('#message-body').val();
        if(message.length > 0){
            $.post("index.php",
                {
                    data: message
                },
                function(data, status){
                    $('#message-body').val("");
                    $('#chat-box-main').append(data);
                    scrollToBottom();
                });
        }
    });
    $('#message-body').keypress(function (e) {
        if (e.which == 13) {
            $("#send-message").click();
            return false;    //<---- Add this line
        }
    });
    $('#img-send').on('click', function(){
        $('#img-send-hidden').click();
    });
    $('#img-send-hidden').on('change', function(e){
        var files = e.target.files;
        var formData = new FormData();
        if(files.length>0){
            $.each(files, function(key, value)
            {
                formData.append(key, value);

            });
            formData.append("action", "upload_pic");
            console.log(formData);
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: formData,
                cache: false,
                //dataType: 'json',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                success: function(data, textStatus, jqXHR)
                {
                    console.log(data);
                    /*if(typeof data.error === 'undefined') {
                        // Success so call function to process the form
                        console.log("SUCCESS");
                        //submitForm(event, data);
                    }
                    else {
                        // Handle errors here
                        console.log('ERRORS: ' + data.error);
                    }*/
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle errors here
                    console.log('ERRORS: ' + textStatus);
                    // STOP LOADING SPINNER
                }
            });
        }

    });
});

