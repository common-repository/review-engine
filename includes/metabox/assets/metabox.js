(function($) {
$(document).ready( function() {
	$('#reviewengine-product-informations a[href="#re-basic"]').on('click', function(e) {
		$('#reviewengine-product-informations a.nav-tab').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');

		$('div#re-gallery, div#re-attributes').hide();
		$('div#re-basic').show();
	});

	$('#reviewengine-product-informations a[href="#re-attributes"]').on('click', function(e) {
		$('#reviewengine-product-informations a.nav-tab').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');

		$('div#re-basic, div#re-gallery').hide();
		$('div#re-attributes').show();
	});

	$('#reviewengine-product-informations a[href="#re-gallery"]').on('click', function(e) {
		$('#reviewengine-product-informations a.nav-tab').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');

		$('div#re-basic, div#re-attributes').hide();
		$('div#re-gallery').show();
	});
});
})(jQuery);