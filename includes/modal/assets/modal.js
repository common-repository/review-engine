(function($) {
window.ReviewEngine_Modal = {
    supports_html5_storage: function() {
        var $supports_html5_storage;

        try {
            $supports_html5_storage = ( 'sessionStorage' in window && window.sessionStorage !== null );
            window.sessionStorage.setItem( 'reviewengine', 'test' );
            window.sessionStorage.removeItem( 'reviewengine' );
            window.localStorage.setItem( 'reviewengine', 'test' );
            window.localStorage.removeItem( 'reviewengine' );
        } catch( err ) {
            $supports_html5_storage = false;
        }

        return $supports_html5_storage;
    },
    insert_shortcode: function(shortcode) {
        window.parent.ReviewEngine_Editor.send_to_editor( shortcode );
    },
    get_locale_from_storage: function() {
        if( ReviewEngine_Modal.supports_html5_storage() == false ) {
            return false;
        }

        var locale = sessionStorage.getItem( 'ree_search_locale' );
        if( locale ) {
            return locale;
        }

        return false;
    },
    get_product_from_asin: function( asin ) {
        if( ReviewEngine_Modal.supports_html5_storage() == false ) {
            return false;
        }

        var product_data_json = sessionStorage.getItem( 'ree_product_data' );
        if( product_data_json ) {
            var product_data = JSON.parse( product_data_json );
            var item_data = null;

            if( product_data ) {
                $.each( product_data, function(x, item) {
                    if( item.asin == asin ) {
                        item_data = item;
                        return false;
                    }
                });
            }

            return item_data;
        }

        return false;
    },
    get_product_from_post_id: function( post_id ) {
        if( ReviewEngine_Modal.supports_html5_storage() == false ) {
            return false;
        }

        var item_data = null;

        $.ajax({  
            type: "POST",
            url: reviewengine.ajaxurl,
            async: false,
            data : {
                action: 'ree_insert_data',
                _post_id: post_id,
            },
            beforeSend: function() {
                ReviewEngine_Modal.loading('on');
            },
            success: function(response) {
                ReviewEngine_Modal.loading('off');
                
                if( response.success && response.data ) {
                    item_data = response.data;
                }
            }
        });

        return item_data;
    },
    loading: function(status) {
        var loader = $('#loader');
        if( status == 'on' ) {
            loader.fadeIn(400);
        } else {
            loader.fadeOut(400);
        }
    }
}

$(document).ready( function() {
    var ajax_flag = null;
    
    var choose_network = function() {
        $('select#affiliate-network').on('change', function() {
            var value = $(this).val();

            switch( value ) {
                case 'amazon':
                    $('#affiliate-amazon').show();
                    $('#affiliate-custom').hide();
                    break;
                case 'custom':
                    $('#affiliate-custom').show();
                    $('#affiliate-amazon').hide();
                    break;
            }
        });
    }

    choose_network();
});
})(jQuery);