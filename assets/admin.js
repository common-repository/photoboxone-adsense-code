/*
 * Adsense Code: http://photoboxone.com/
 */
(function($){
	$(document).ready(function(){

		$('.adsense_code_settings').each(function(){
			var ad = $('.ad-unit-link'),
				input = $('#adsense_publisher_id'),
				link = ad.attr('href');

			if( input.val()!='' ) {
				ad.attr( 'href', link.replace('pub-id', input.val() ) );
			}
			
			input.change(function(){
				var v = $(this).val();

				if( v!='' ) {
					ad.attr( 'href', link.replace('pub-id', v) );
				}
			});
		});

	} );
})(jQuery);