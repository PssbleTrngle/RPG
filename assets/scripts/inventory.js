$(window).ready(function() {
	
	$('.loot').find('.slot').each(function() {
		
		let slot = $(this);
		slot.click(function() {
			
			window.params.stack = slot.attr('id');
			sendAction('inventory/take');
			
		});
		
	});
	
	$('.inventory').not('.loot').find('.slot').each(function() {
		
		let slot = $(this);
		let popup = $('.item-popup[data-item=' + slot.attr('id') + ']');

		slot.click(function() {
			if(popup) {
				$('.item-popup').removeClass('active');
				$('.slot').removeClass('active');
				popup.addClass('active')
				slot.addClass('active')
			}
		});
		
	});
	
});