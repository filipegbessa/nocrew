/* Box-sizing - Fallback */
Modernizr.addTest("boxsizing", function () {
	return Modernizr.testAllProps("boxSizing") && (document.documentMode === undefined || document.documentMode > 7);
});
$(function () {
	if (!($('html').hasClass('boxsizing'))) {
		$('*').each(function () {
			var fullW = $(this).outerWidth(),
				actualW = $(this).width(),
				wDiff = fullW - actualW,
				newW = actualW - wDiff;

			$(this).css('width', newW);
		});
	}
});



/* Placeholder - Fallback | Warning - Check your type of validate */
if (!Modernizr.input.placeholder) {
	$('[placeholder]').focus(function () {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
			input.val('');
			input.removeClass('placeholder');
		}
	}).blur(function () {
		var input = $(this);
		if (input.val() == '' || input.val() == input.attr('placeholder')) {
			input.addClass('placeholder');
			input.val(input.attr('placeholder'));
		}
	}).blur();
	$('[placeholder]').parents('form').submit(function () {
		$(this).find('[placeholder]').each(function () {
			var input = $(this);
			if (input.val() == input.attr('placeholder')) {
				input.val('');
			}
		})
	});
}
