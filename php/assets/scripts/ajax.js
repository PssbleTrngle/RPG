window.params = {};

/* 
	Used to send a user action to the server
	(for example 'character/evolve')
	The 'window.params' variable is sent as the
	request params and is altered by javascript code sending
	the request.
*/

function sendAction(action, func, source) {

	if(!func) func = function(result) {
		if(result.redirect) window.open(result.redirect, '_self');
		else if(result.success) {

			let reload = result.reload;
			if(reload) {
				let element = $('[data-reload=' + reload + ']');
				if(element) {

					$.get(window.location.href, function(result) {
						let newElement = $(result).find('[data-reload=' + reload + ']');

						if(newElement[0]) {
							element.html(newElement.html());
							element.reload();
						}
						else
							location.reload();

					});
				}
			} else location.reload();

		}
		else {

			console.log(result);
			$('.feedback').text(result.message);

		}
	}

	$('[data-action], .option').addClass('loading');
	
	$.post(action, window.params).done(function(result) {

		try {

			func(JSON.parse(result));

		} catch(err) {

			func({
				'success': false,
				'message': 'Invalid JSON',
				'json': result.escape()
			});

			if(source) source.addClass('error disabled');
			$('.feedback').text('An error occured');
			$('.admin-feedback').html(result.json);

		}
	}).fail(function(result) {

		if(source) source.addClass('error disabled');
		$('.feedback').text('An error occured');

		$('.admin-feedback').html(result.responseText);
		$('.admin-feedback').each(function() {
			$(this).html($(this).find('.container').html());
		});

	}).always(function(result) {		
		$('.loading').removeClass('loading');
	});
}

$.fn.sendAction = function(action, func) {
	let source = $(this);
    source.click(function() {
		sendAction(action, func, source);
	});
};

String.prototype.escape = function() {
    return this.replace(/\\n/g, "\\n")
               .replace(/\\'/g, "\\'")
               .replace(/\\"/g, '\\"')
               .replace(/\\&/g, "\\&")
               .replace(/\\r/g, "\\r")
               .replace(/\\t/g, "\\t")
               .replace(/\\b/g, "\\b")
               .replace(/\\f/g, "\\f");
};