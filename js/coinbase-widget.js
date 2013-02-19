jQuery(document).ready(function($) {

	function coinbaseToggle() {
		var openText = "Hide Advanced Options";
		var closedText = "Show Advanced Options";

		$('.coinbase-toggle').click(function() {
			var parent = $(this).parent();
			if ($(this).hasClass('open')) {
				$(this).removeClass('open');
				$(this).text(closedText);
				$(parent).children('.coinbase-advanced').hide();
			} else {
				$(this).addClass('open');
				$(this).text(openText);
				$(parent).children('.coinbase-advanced').show();

			}
		});
	}

	$('.widget').hover(coinbaseToggle);

});