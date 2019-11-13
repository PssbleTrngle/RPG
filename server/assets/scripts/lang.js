/*
	Language Settings Popup
*/

$(window).ready(function() {

	$('[data-window]').click(function() {

		$('.window-open').css({ display: 'block' });
		$('#' + $(this).attr('data-window')).css({ transform: 'translate(-50%, -50%)' });

	});

	$('.window-open').click(function() {

		$('.window-open').css({ display: 'none' });
		$('.window').css({ transform: 'translate(-50%, 100vh)' });

	});

});