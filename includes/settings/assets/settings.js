(function($) {
$(document).ready(function() {
	var ajax_flag = null;
	var view_tab = location.hash;

	if( view_tab ) {
		$('.ree-nav-tab > a').removeClass('nav-tab-active');
		$('.ree-nav-tab > a[href="' +view_tab+ '"]').addClass('nav-tab-active');

		$('div.ree-tab-content div.ree-tab-pane').removeClass('active');
		$('div.ree-tab-content div'+view_tab).addClass('active');
	}
	
	$('.ree-nav-tab > a').on( 'click', function(e) {
		var tab = $(this).attr('href');
		
		if( tab ) {
			$('.ree-nav-tab > a').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');

			$('div.ree-tab-content div.ree-tab-pane').removeClass('active');
			$('div.ree-tab-content div'+tab).addClass('active');
		}

		e.preventDefault();
	});

	$('#reviewengine-settings-wrap form').on('submit', function(e) {
		var access_key = $('#reviewengine-settings-access_key').val(),
			secret_key = $('#reviewengine-settings-secret_key').val(),
			tracking_id = $('#reviewengine-settings-associates_us').val();

		if( !access_key ) {
			alert('Access Key ID is missing. Please enter your Access Key ID below and click "Connect" again.');	
			return false;
		}

		if( !secret_key ) {
			alert('Secret Access Key is missing. Please enter your Secret Access Key below and click "Connect" again.');	
			return false;
		}
		
		if( !tracking_id ) {
			alert('Tracking ID is missing. Please enter your tracking ID below and click "Connect" again.');	
			return false;
		}
	});
});
})(jQuery);
