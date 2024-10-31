( function($) {
$(document).ready( function($) {
	var links = $('.reviewengine-btn, .reviewengine-image, .reviewengine-link');

	if( reviewengine.disclaimer 
		&& reviewengine.disclaimer_text 
		&& reviewengine.disclaimer == 1 
		&& reviewengine.disclaimer_text.length > 1 ) {
		
		links.qtip({
			content: {
				text: reviewengine.disclaimer_text
			},
			style: {
				classes: 'reviewengine-qtip'
			},
			position: {
				target: 'mouse',
				adjust: { x: 5, y: 5 }
			}
		});

	}
});
})(jQuery);