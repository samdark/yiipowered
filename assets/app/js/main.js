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
        var imageContainer = $(this).closest('.image');

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
    });
    
    $('.image .primary-image').on('click', function (e) {
        e.preventDefault();

        var el = $(this);
        var endpoint = el.data('url');
        var imageId = el.data('image-id');

        $.ajax({
            url: endpoint,
            method: 'put',
            dataType: 'json',
            data: {
                imageId: imageId
            },
            success: function() {
                $('.image .primary-image').removeClass('hide').filter('[data-image-id=' + imageId + ']').addClass('hide');
            }
        });
    });
});

/**
 * 
 * @param {int} widthMin
 * @param {int} heightMin
 */
function initProjectImageUpload(widthMin, heightMin) {
    var uploadForm = $('#project-image-upload');
    var uploadButton = uploadForm.find('.custom-upload-button');
    var imageCropperBlock = uploadForm.find('.cropper-block');
    var imageBlock = imageCropperBlock.find('.image-block');

    var inputImageCropData = uploadForm.find('[name="ImageUploadForm[imageCropData]"]');
    var inputFile = uploadForm.find('[name="ImageUploadForm[file]"]');
    var inputImageId = uploadForm.find('[name="ImageUploadForm[imageId]"]');

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

    function clear() {
        imageCropperBlock.hide(0);
        uploadForm.removeClass('opened');

        inputImageCropData.val('');
        inputImageId.val('');

        return !!imageBlock.data('cropper');
    }

    inputFile.on('change', function (e) {
        e.stopPropagation();

        if (!clear()) {
            return false;
        }

        var files = this.files;
        if (files && files.length > 0) {
            var file = files[0];

            if (/^image\/\w+$/.test(file.type)) {
                var blobURL = URL.createObjectURL(file);
                imageBlock.one('built.cropper', function () {
                    URL.revokeObjectURL(blobURL);
                }).cropper('reset').cropper('replace', blobURL);
                imageCropperBlock.show(0);
                uploadForm.addClass('opened');
            }
        }
    });

    $('.js-project-image-recrop').on('click', function (e) {
        e.stopPropagation();

        if (!clear()) {
            return false;
        }

        inputFile.val('');

        var imageId = $(this).data('id');
        var imageUrl = $(this).data('url');

        inputImageId.val(imageId);
        imageBlock.cropper('reset').cropper('replace', imageUrl);

        imageCropperBlock.show(0);
        uploadForm.addClass('opened');
    });

    $('.js-project-image-reset').on('click', function (e) {
        e.stopPropagation();

        if (!clear()) {
            return false;
        }

        imageCropperBlock.cropper('reset')
    });
}
