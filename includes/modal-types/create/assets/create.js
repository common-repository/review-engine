(function($) {
$(document).ready( function() {
	var ajax_flag = null;
    var currentPage = 1;
    var currentKeyword = '';
    var searchForm = $('form#search-form');
    var searchResults = $('#search-results');

    var search_handle = function( more = false ) {
        var buttonMore = searchResults.find('button.button-more');
        var locale = searchForm.find('select[name="search-locale"]').val();
        var keyword = searchForm.find('input[name="search-keyword"]').val();
            
        if( more == false ) {
            currentPage = 1;
            currentKeyword = keyword;
        } else {
            if( currentKeyword ) {
                keyword = currentKeyword;
            }
        }

        if( ajax_flag ) {
            ajax_flag.abort();
        }
            
        ajax_flag = $.ajax({  
            type: "POST",
            url: reviewengine.ajaxurl,  
            data : {
                action: 'ree_create_search',
                _page: currentPage,
                _locale: locale,
                _keyword: keyword
            },
            beforeSend: function() {
                ReviewEngine_Modal.loading('on');
                
                if( more == false ) {
                    searchResults.hide();
                }
            },
            success: function(response) {
                ReviewEngine_Modal.loading('off');
                
                if( response.success ) {
                    if( ReviewEngine_Modal.supports_html5_storage() ) {
                        if( response.data.locale ) {
                            sessionStorage.removeItem( 'ree_search_locale' );
                            sessionStorage.setItem( 'ree_search_locale', response.data.locale );   
                        }

                        if( more && response.data.items ) {
                            var product_data_json = sessionStorage.getItem( 'ree_product_data' );

                            if( product_data_json ) {
                                var product_data = JSON.parse( product_data_json );
                                
                                $.each( response.data.items, function(x, item) {
                                    product_data.push( item );
                                });
                                
                                sessionStorage.removeItem( 'ree_product_data' );
                                sessionStorage.setItem( 'ree_product_data', JSON.stringify( product_data ) );
                            }
                        } else if( response.data.items ) {
                            sessionStorage.removeItem( 'ree_product_data' );
                            sessionStorage.setItem( 'ree_product_data', JSON.stringify( response.data.items ) );
                        }
                    }
                    
                    if( response.data.items ) {
                        var template = $('#template-search-result').html();
                        var html = '';
                        $.each( response.data.items, function( x, item ) {
                            var template_data = {};

                            if( item.asin ) {
                                template_data.asin = item.asin;
                            }

                            if( item.title ) {
                                template_data.name = item.title;
                            }

                            if( item.url ) {
                                template_data.url = item.url;
                            }

                            if( item.offer.price ) {
                                template_data.price = item.offer.price;
                            }

                            if( item.reviews.star ) {
                                template_data.percent_rating = parseFloat(item.reviews.star) * 20;
                            }

                            if( item.reviews.total ) {
                                template_data.total_reviews = item.reviews.total;
                            }

                            if( item.images ) {
                                $.each( item.images, function( y, image ) {
                                    if( image.width >= 50 && image.width <= 150 ) {
                                        template_data.photo = image.url;
                                        return false;
                                    }
                                });
                            }

                            html += Mustache.render(template, template_data);
                        });

                        if( more ) {
                            searchResults.show().find('table tbody').append( html );
                        } else {
                            searchResults.show().find('table tbody').empty().html( html );
                        }
                    }

                    if( response.data.page < response.data.pages ) {
                        currentPage = currentPage + 1;
                        buttonMore.show();
                    } else {
                        buttonMore.hide();
                    }
                } else {
                    var str = '<tr><td colspan="3">' +response.data.message+'</td></tr>';
                    searchResults.show().find('table tbody').empty().html( str );
                    buttonMore.hide();
                }
            } 
        });
    }

	var search = function() {
		searchForm.on( 'submit', function(e) {
            search_handle( false );
            
            e.preventDefault();
            return false;
		});

        searchResults.on( 'click', 'button.button-more', function(e) {
            search_handle( true );
            
            e.preventDefault();
            return false;
        });
	};

	search();
});
})(jQuery);