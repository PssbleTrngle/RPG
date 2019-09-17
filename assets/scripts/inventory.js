$(window).ready(function() {
	
	onLoad('.loot', function(element) {
		element.find('.slot').each(function() {
			
			let slot = $(this);
			slot.click(function() {
				
				window.params.stack = slot.attr('id');
				sendAction('inventory/take');
				
			});
			
		});
	});
	
	onLoad('.inventory', function(element) {
		element.not('.loot').find('.slot').each(function() {
		
			let popup = $('.item-popup[data-item=' + element.attr('id') + ']');

			element.click(function() {
				if(popup) {
					$('.item-popup').removeClass('active');
					$('.slot').removeClass('active');
					element.addClass('active')
					element.addClass('active')
				}
			
			});			
		});
	});
	
});