(function ($) {
    "use strict";
    
    $(document).ready(function () {
        $('[data-star-url]').on('click', function() {
            var el = $(this);
            $.ajax({
                url: el.data('star-url'),
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    var icon = el.find('.icon');
                    icon.removeClass('glyphicon-star glyphicon-star-empty');
                    icon.addClass(data['star'] ? 'glyphicon-star' : 'glyphicon-star-empty');
                    
                    var count = el.parent().find('.star-count');
                    if (count) {
                        count.text(data['starCount']);
                    }
                },
                error: function() {
                    //todo: redirect when user is not logged in.
                }
            });
        }); 
    });
}) (jQuery);
    