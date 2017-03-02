jQuery(function ($) {
    "use strict";

    $('.image a').magnificPopup({
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

/**
 * 
 * @param {int} widthMin
 * @param {int} heightMin
 */
function initProjectImageUpload(widthMin, heightMin) {
    var imageCropperBlock = $('#image-cropper-block');
    var imageBlock = imageCropperBlock.find('.image-block');
    var inputImageCropData = $('#project-image-upload [name="ImageUploadForm[imageCropData]"]');
    var inputFile = $('#project-image-upload [name="ImageUploadForm[file]"]');

    imageBlock.cropper({
        viewMode: 1,
        minCropBoxWidth: widthMin,
        minCropBoxHeight: heightMin,
        autoCropArea: 1,
        aspectRatio: widthMin / heightMin,
        zoomable: false,
        crop: function (e) {
            var data = e;

            if (typeof data === "undefined") {
                return false;
            }

            var imageCropData = {
                x: Math.round(data.x),
                y: Math.round(data.y),
                width: Math.round(data.width),
                height: Math.round(data.height),
                rotate: typeof data.rotate !== 'undefined' ? data.rotate : null,
                scaleX: typeof data.rotate !== 'undefined' ? data.scaleX : null,
                scaleY: typeof data.rotate !== 'undefined' ? data.scaleY : null
            };

            inputImageCropData.val(JSON.stringify(imageCropData));
        }
    });

    inputFile.on('change', function() {
        imageCropperBlock.hide(0);
        inputImageCropData.val('');

        var files = this.files;

        if (!imageBlock.data('cropper')) {
            return;
        }

        if (files && files.length > 0) {
            var file = files[0];

            if (/^image\/\w+$/.test(file.type)) {
                var blobURL = URL.createObjectURL(file);
                imageBlock.one('built.cropper', function () {
                    URL.revokeObjectURL(blobURL);
                }).cropper('reset').cropper('replace', blobURL);
                imageCropperBlock.show(0);
            }
        }
    });

    $('.js-project-image-reset').on('click', function () {
        imageCropperBlock.hide(0);
        inputImageCropData.val('');
        inputFile.val('');

        if (!imageCropperBlock.data('cropper')) {
            return;
        }

        imageCropperBlock.cropper('reset')
    });
}
