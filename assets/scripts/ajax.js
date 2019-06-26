window.params = {};

function sendAction(action, func) {
	if(!func) func = function(result) {
		if(result.success) location.reload();
		else {
			console.log(result);
			$('.feedback').text(result.message);
		}
	}
	
	$.post( action, window.params ).done(function(d) {
		func(JSON.parse(d));
	});
}

$.fn.sendAction = function(action, func) {
    $(this).click(function() {
		sendAction(action, func);
	});
};