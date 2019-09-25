/*
	Editor
*/

$(window).ready(function() {

	onLoad('.save', function(element) {

		element.click(function() {
				
			let value = $('#value').val();

			if(value) {

				$('[data-table]').each(function() {

					let table = $(this).attr('data-table');
					let key = $(this).attr('data-key');
					let pivots = {};

					$(this).find('select.pivot').each(function() {
						let val = $(this).find('option:selected').attr('id');
						if(val) pivots[$(this).attr('id')] = val;
					});

					$(this).find('input.pivot').each(function() {
						let val = $(this).val();
						pivots[$(this).attr('id')] = val;
					});

					window.params = {value, key, pivots, table};
					sendAction('/editor/update');

				});

			}

		});

	});

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