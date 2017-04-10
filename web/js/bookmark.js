(function ($) {
    "use strict";
    
    $(document).ready(function () {
        $('[data-bookmark-url]').on('click', function () {
            var el = $(this);
            
            $.ajax({
                url: el.data('bookmark-url'),
                data: {
                    state: !el.data('bookmark-exists') * 1
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        var bookmarkExists = data['bookmarkExists'];
                        if (el.data('bookmark-exists') != bookmarkExists) {
                            el.data('bookmark-exists', bookmarkExists);
                            el.find('.action-item').toggleClass('hide');   
                        }
                    }
                },
                error: function(data) {
                    if (data && data.status == 403) {
                        alert('You must log in.');   
                    }
                }
            });
        }); 
    });
}) (jQuery);
    