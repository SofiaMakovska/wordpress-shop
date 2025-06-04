jQuery(document).ready(function ($) {

    $( "#accordion" ).accordion({
        collapsible: true,
        active: false
    });

    $('.cocojambo-select').on('change', function () {
        let $this = $(this),
            slideId = $this.val(),
            articleId = $this.data('article');
        $("#overlay").fadeIn(300);
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                slide_id: slideId,
                article_id: articleId,
                action: "cocojambo_change_slide",
                cocojambo_change_slide: cocojamboSlide.nonce
            },
            beforeSend: function () {

            },
            success: function (res) {
                res = JSON.parse(res)
                console.log(res);
                Swal.fire({
                    title: res.text,
                    icon: res.answer,
                    confirmButtonText: 'OK'
                })
            },
            error: function () {
                alert('Error!');
            }
        }).done(function() {
            setTimeout(function(){
                $("#overlay").fadeOut(300);
            },500);
        });
    });

    // $(document).ajaxSend(function() {
    //     $("#overlay").fadeIn(300);
    // });
    //
    // $('#button').click(function(){
    //     $.ajax({
    //         type: 'GET',
    //         success: function(data){
    //             console.log(data);
    //         }
    //     }).done(function() {
    //         setTimeout(function(){
    //             $("#overlay").fadeOut(300);
    //         },500);
    //     });
    // });

});
