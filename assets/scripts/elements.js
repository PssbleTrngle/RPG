$(window).ready(function() {
	
	$('.healthbar').each(function() {
		
		let bar = $(this);
		let inner = $(document.createElement('div'));
		let width = bar.attr('data-amount')*100 + '%';
		inner.css({width});
		bar.append(inner);
		
	});
	
	$('.option').each(function() {
		let option = $(this);
		
		let options = option.closest('.options');
		let action = option.attr('data-action');
		let parent = option.attr('data-parent');
		if(parent) parent = $('.option#' + parent);
		
		if(action) option.sendAction(action);
			
		if(parent && options) {
		
			parent.click(function() {
				
				options.find('.option').css({'display': 'none'});
				options.find('.option[data-parent=' + parent.attr('id') + ']').css({'display': 'block'});
				options.find('.option.back').css({'display': 'block'});
				
			});
			
		}
	
	});
	
	$('.options').each(function() {
		let options = $(this);
		
		if(options.find('.option[data-parent]').length) {
		
			let back = $(document.createElement('div'));
			back.addClass('back');
			back.addClass('option');
			back.text('Back');
			back.click(function() {
				options.find('.option').css({'display': 'block'});
				options.find('.option[data-parent]').css({'display': 'none'});
				options.find('.option.back').css({'display': 'none'});
			});
			
			options.prepend(back);
			
		}
		
	});
	
});