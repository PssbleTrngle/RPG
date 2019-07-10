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

	$('[data-popup]').each(function() {
	
		let popup = $(this);
		let btn = $(popup.attr('data-popup'));
		
		if(btn)
			btn.click(function() {		
				let current = popup.innerHeight();

				if(current) {

					let max = popup.innerHeight();
					popup.css({ height: max });
					window.setTimeout(function() {
						popup.css({ height: 0 });
					}, 1);

				} else {

					popup.css({ height: 'auto' });
					let max = popup.innerHeight();
					popup.css({ height: 0 });
					window.setTimeout(function() {
						popup.css({ height: max });
					}, 1);

				}

			});
		
	});
	
});