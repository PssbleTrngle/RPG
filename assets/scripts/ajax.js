window.params = {};

/* 
	Used to send a user action to the server
	(for example 'character/evolve')
	The 'window.params' variable is sent as the
	request params and is altered by javascript code sending
	the request.
*/

function sendAction(action, func) {
	if(!func) func = function(result) {
		if(result.redirect) window.open(result.redirect, '_self');
		else if(result.success) location.reload();
		else {
			console.log(result);
			$('.feedback').text(result.message);
		}
	}
	
	$.post( action, window.params ).done(function(d) {

		try {
			func(JSON.parse(d));
		} catch(err) {
			func({
				'success': false,
				'message': 'Invalid JSON',
				'json': d
			})
		}
	});
}

$.fn.sendAction = function(action, func) {
    $(this).click(function() {
		sendAction(action, func);
	});
};