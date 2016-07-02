$('.comment').keypress(function (e) {
   if(e.which == 13 && this.value != ''){
       var postId = $(this).parent('.post-comment__input').parent('.comment_post').parent('.post').attr('id');
       var a = this.value;
       var _this = this;
       $.ajax({
           type : "GET",
           url : "/postComment.php",
           data : {
               comment : a,
               post_id : postId,
               user_id : $('.user_id').val()
           },
           success : function (res) {
                   var $comment = '<div class="post-comments">'
                       +'<div class="post_comment--body">'
                       +'<div class="post_comment--username">'
                       +'<span class="post_comment--username__text">'+ $('.profile').attr('id') +''
                       +'</span>'
                       +'</div>'
                       +'<div class="post_comment__body">'
                       +'<span class="post-comment__body--text">'+a+''
                       +'</span>'
                       +'</div>'
                       +'</div>'
                       +'</div>';
                   $('#'+postId).append($comment);
                   $(_this).val('');
           }
       });


   }
});