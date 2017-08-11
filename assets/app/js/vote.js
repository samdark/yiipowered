jQuery(function($) {
    "use strict";

    $('.vote .button').on('click', function (e) {
        e.preventDefault();

        var el = $(this);
        var container = el.parents('.vote');
        var endpoint = el.data('endpoint');
        var value = el.data('value');

        if (el.is('.disabled')) {
            return false
        }
        
        $.ajax({
            url: endpoint,
            method: 'put',
            dataType: 'json',
            data: {
               'value': value 
            },
            success: function(data) {
                container.find('.button').removeClass('disabled');
                el.addClass('disabled');
                container.find('.value').text(data['totalValue']);
            },
            error: function() {
                alert('There was an error while voting.');
            }
        });
    });
});
