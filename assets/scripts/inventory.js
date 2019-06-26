$(window).ready(function() {
	
	$('.loot').find('.slot').each(function() {
		
		let slot = $(this);
		slot.click(function() {
			
			window.params.stack = slot.attr('id');
			sendAction('inventory/take');
			
		});
		
	});
	
});