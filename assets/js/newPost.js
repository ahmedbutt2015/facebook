$('#newpost--btn').on('click',function () {

    var post_text = $("#newpost__text--holder").val();
    var image = $('.newpost__file--name')[0].files[0];
    $.ajax({
        url : '/upload',
        type : 'POST',
        data : {
            post_text : post_text,
            image : image
        },
        datatype: "html",
        success : function (res) {
            console.log("dsf");
        }
    });
});