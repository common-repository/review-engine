(function($) {
$(document).ready( function() {
	var ajax_flag = null;
    var global_product_data = null;
    var global_product_locale = null;
    var container = $('#display-image');
    var form = $('form#display-image-form');

    $('#reviewengine-modal').on('click', 'button.button-display-image', function(e) {
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
            $('#network, #search').hide();
            container.show();
            container.find('a.name').attr( 'href', global_product_data.url ).html( global_product_data.title );
            container.find('input[name="alt"]').val( global_product_data.title );

            if( global_product_locale ) {
                var options = '<option value="">None</option>';
                $.each( reviewengine.tracking_ids, function(x, tracking_id) {
                    if( x == global_product_locale ) {
                        options += '<option value="' +tracking_id+ '" selected>' +tracking_id+ '</option>';
                    }
                });
                container.find('select[name="tracking-id"]').empty().html(options);
            }

            if( global_product_data.images ) {
                var html = '';
                $.each( global_product_data.images, function( x, image ) {
                    var data = {};

                    if( image.width >= 100 ) {
                        if( image.url ) {
                            data.image = image.url;
                        }

                        if( image.width ) {
                            data.width = image.width;
                        }

                        if( image.height ) {
                            data.height = image.height;
                        }
                        
                        html += Mustache.render( $('#template-display-image').html(), data );
                    }
                });
                container.find('div.checkbox-images').empty().html( html );
            }
        }

        e.preventDefault();
    });

    form.on('submit', function(e) {
        var image = form.find('input[name="image"]:checked').val(),
            alt = form.find('input[name="alt"]').val(),
            align = form.find('select[name="align"]').val(),
            width = form.find('input[name="image-width"]').val(),
            style = '';

        if( width ) {
            style = 'style="max-width: ' + width + 'px;height: auto;"';
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
                    if( response.data.url ) {
                        var shortcode = '<a href="' + response.data.url + '" class="reviewengine-image" ' + response.data.link_atts + '><img src="' + image + '" alt="' + alt + '" class="' + align + '" ' + style + ' /></a>';
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