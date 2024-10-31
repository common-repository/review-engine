(function($) {
$(document).ready( function() {
	var ajax_flag = null;
    var currentPage = 1;
    var currentKeyword = '';
    var searchForm = $('form#search-form');
    var searchResults = $('#search-results');

	var search_handle = function( more = false ) {
        var buttonMore = searchResults.find('button.button-more');
        var keyword = searchForm.find('input[name="search-keyword"]').val();
            
        if( more == false ) {
            currentPage = 1;
            currentKeyword = keyword;
        } else {
        	if( !keyword && !currentKeyword && currentPage == 1 ) {
        		currentPage = 2;
        	}
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
                action: 'ree_insert_search',
                _page: currentPage,
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
                
                console.log( response );
                if( response.success ) {
                	if( response.data.products ) {
                		var template = $('#template-search-result').html();
                        var html = '';
                        $.each( response.data.products, function( id, item ) {
                        	var template_data = {};

                        	template_data.id = id;

                            if( item.asin ) {
                                template_data.asin = item.asin;
                            }

                            if( item.title ) {
                                template_data.name = item.title;
                            }

                            if( item.url ) {
                                template_data.url = item.url;
                            }

                            if( item.price ) {
                                template_data.price = item.price;
                            }

                            if( item.reviews_star ) {
                                template_data.percent_rating = parseFloat(item.reviews_star) * 20;
                            }

                            if( item.reviews_total ) {
                                template_data.total_reviews = item.reviews_total;
                            }

                            if( item.photo ) {
                                template_data.photo = item.photo;
                            }

                            html += Mustache.render(template, template_data);
                        });

                        if( more ) {
                            searchResults.show().find('table tbody').append( html );
                        } else {
                            searchResults.show().find('table tbody').empty().html( html );
                        }
                	}

                	if( currentPage < response.data.max_pages ) {
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
        $('a.ree-link-quickcreate').on('click', function(e) {
            parent.ReviewEngine_Editor.open_thickbox_create();
            e.preventDefault();
        });

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
	}

	search();
});
})(jQuery);