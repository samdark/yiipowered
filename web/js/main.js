jQuery(function ($) {
    "use strict";

    $('.image').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        mainClass: 'mfp-img-mobile',
        image: {
            verticalFit: true
        }
    });

    $('.image .delete').on('click', function (e) {
        e.stopPropagation();
        var imageContainer = $(this).parents('.image').parent();

        var url = $(this).data('url');
        var imageID = $(this).data('id');

        if (confirm($(this).data('confirm'))) {
            $.ajax({
                method: 'post',
                url: url,
                data: {
                    id: imageID
                },
                success: function () {
                    imageContainer.remove();
                }
            });
        }
    })
});
