jQuery(function($) {
    "use strict";

    $('.bookmark .delete').on('click', function (e) {
        e.preventDefault();

        var el = $(this);
        var container = el.parents('.bookmark');
        var endpoint = el.data('endpoint');

        $.ajax({
            url: endpoint,
            method: 'delete',
            dataType: 'json',
            success: function() {
                el.addClass('hide');
                container.find('.create').removeClass('hide');
            },
            error: function() {
                alert('There was an error while processing bookmark.');
            }
        });
    });

    $('.bookmark .create').on('click', function (e) {
        e.preventDefault();

        var el = $(this);
        var container = el.parents('.bookmark');
        var endpoint = el.data('endpoint');
        var id = el.data('id');

        $.ajax({
            url: endpoint,
            method: 'post',
            dataType: 'json',
            data: {
                id: id
            },
            success: function() {
                el.addClass('hide');
                container.find('.delete').removeClass('hide');
            },
            error: function() {
                alert('There was an error while processing bookmark.');
            }
        });
    });
});


    