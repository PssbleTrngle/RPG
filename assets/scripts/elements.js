/*
	Handles the functionality of certain often required elements like
		- Buttons
		- Healthbars
		- Minimize/Maximizeable content (for Example the inventory)
*/

$(window).ready(function() {
	
	$('.bar').each(function() {
		
		let bar = $(this);
		let inner = $(document.createElement('div'));
		let width = bar.attr('data-amount')*100 + '%';
		inner.css({width});
		bar.append(inner);
		
	});

	$('.grow').on('input', function() {
	    var width = $(this).textWidth();
	    $(this).css({width});
	}).trigger('input');

	$('[data-action]').each(function() {
		let action = $(this).attr('data-action');
		$(this).sendAction(action);		
	})
	
	$('.option').each(function() {
		let option = $(this);
		
		let options = option.closest('.options');
		let parent = option.attr('data-parent');
		if(parent) parent = $('.option#' + parent);
			
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

		let current = popup.innerHeight();
		popup.css({ height: 'auto' });
		let max = popup.innerHeight();
		popup.css({ height: current });
		
		if(btn) btn.click(function() {

			if(popup.innerHeight()) {

				popup.css({ height: 0 });
				btn.removeClass("open");

			} else {

				popup.css({ height: max });
				btn.addClass("open");

			}

			popup.addClass('moving');
			window.setTimeout(function() { popup.removeClass('moving'); }, 1000);

		});
		
	});
	
});

$.fn.textWidth = function(text, font) {
    
    if (!$.fn.textWidth.fakeEl) $.fn.textWidth.fakeEl = $('<span>').hide().appendTo(document.body);
    
    $.fn.textWidth.fakeEl.text(text || this.val() || this.text() || this.attr('placeholder')).css('font', font || this.css('font'));
    
    return $.fn.textWidth.fakeEl.width();
};