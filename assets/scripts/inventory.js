$(window).ready(function() {
	
	onLoad('.loot', function(element) {
		element.find('.slot').each(function() {
			
			let slot = $(this);
			slot.click(function() {
				
				window.params.stack = slot.attr('id');
				window.params.slot = 1;
				sendAction('/inventory/take');
				
			});
			
		});
	});
	
	onLoad('.inventory', function(bar) {
		bar.not('.loot').find('.slot').each(function() {
		
			let element = $(this);
			let popup = $('.item-popup[data-item=' + element.attr('id') + ']');

			element.click(function() {
				if(popup) {
					$('.item-popup').removeClass('active');
					$('.slot').removeClass('active');
					popup.addClass('active')
					element.addClass('active')
				}
			
			});			
		});
	});

	onLoad('.option', function(element) {

		let slot = element.attr('data-slot');
		let action = element.attr('data-item-action');

		if(slot || action)
			element.click(function() {

				let stack = element.closest('.item-popup').attr('data-item');
				window.params.stack = stack;

				if(slot) {

					window.params.slot = slot;
					sendAction('/inventory/take');

				} else if(action) {

					let inBattle = element.closest('.battle')[0];

					window.params.slot = slot;
					window.params.action = action;

					if(inBattle)
						window.battle_action = '/inventory/action';
					else
						sendAction('/inventory/action');

				}

			});

	});
	
});