/*
	Editor
*/

$(window).ready(function() {

	onLoad('.search', function(element) {

		let func = function() {

			let search = $(this).val();
			$('.slot').each(function() {

				console.log(search);
				let slot = $(this);
				let name = slot.find('#name').text();
				let display = !search || name.toLowerCase().includes(search.toLowerCase());
				
				if(display)
					slot.removeClass('hidden');
				else
					slot.addClass('hidden');

			});

		};

		element.on('input', func);
		element.keyup(func);

	});

});