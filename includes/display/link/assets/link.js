(function($) {
$(document).ready( function() {
	var ajax_flag = null;
    var global_product_data = null;
    var global_product_locale = null;
    var container = $('#display-link');
    var form = $('form#display-link-form');

    $('#reviewengine-modal').on('click', 'button.button-display-link', function(e) {
        var asin = $(this).parents('tr').attr('data-asin');
        var post_id = $(this).parents('tr').attr('data-post_id');
        
        if( asin ) {
            global_product_data = ReviewEngine_Modal.get_product_from_asin( asin );
        } else if( post_id ) {
            global_product_data = ReviewEngine_Modal.get_product_from_post_id( post_id );
        }

        if( post_id && global_product_data ) {
            global_product_locale = global_product_data.locale;
        } else {
            global_product_locale = ReviewEngine_Modal.get_locale_from_storage();
        }
        
        if( global_product_data ) {
            var anchor_text = global_product_data.title;

            $('#network, #search').hide();
            container.show();
            container.find('a.name').attr( 'href', global_product_data.url ).html( global_product_data.title );

            if( global_product_locale ) {
                var options = '<option value="">None</option>';
                $.each( reviewengine.tracking_ids, function(x, tracking_id) {
                    if( x == global_product_locale ) {
                        options += '<option value="' +tracking_id+ '" selected>' +tracking_id+ '</option>';
                    }
                });
                container.find('select[name="tracking-id"]').empty().html(options);
            }

            if( reviewengine.selection ) {
                anchor_text = reviewengine.selection;
            }

            container.find('input[name="anchor-text"]').val( anchor_text );
        }

        e.preventDefault();
    });

    form.on('submit', function(e) {
        var anchor_text = form.find('input[name="anchor-text"]').val();

        if( !anchor_text ) {
            anchor_text = reviewengine.selection;
        }

        if( ajax_flag ) {
            ajax_flag.abort();
        }

        ajax_flag = $.ajax({
            type: "POST",
            url: reviewengine.ajaxurl,  
            data : {
                action: 'ree_insert_link',
                _locale: global_product_locale,
                _product_data: global_product_data,
                _post_id: reviewengine.post_id,
                _form: form.serialize()
            },
            beforeSend: function() {
                ReviewEngine_Modal.loading('on');
            },
            success: function(response) {
                ReviewEngine_Modal.loading('off');
                
                if( response.success ) {
                    if( response.data.product_id && response.data.url ) {
                        var shortcode = '[reviewengine_link id="' + response.data.product_id + '" url="' + response.data.url + '"]' + anchor_text + '[/reviewengine_link]';
                        ReviewEngine_Modal.insert_shortcode( shortcode );
                    }
                }
            }
        });

        e.preventDefault();
        return false;
    });

    container.on('click', 'button.button-display-cancel', function(e) {
        container.hide();
        $('#network, #search').show();

        e.preventDefault();
    });
});
})(jQuery);