/*
	Handles the functionality of certain often required elements like
		- Buttons
		- Healthbars
		- Minimize/Maximizeable content (for Example the inventory)
*/

$(window).ready(function() {
	
	onLoad('.bar', function(element) {
		
		let inner = $(document.createElement('div'));
		let width = element.attr('data-amount')*100 + '%';
		inner.css({width});
		element.append(inner);
		
	});
	
	onLoad('.feedback', function(element) {
		element.bind("DOMSubtreeModified",function() {
			
			element.css({ opacity: 1 });
			element.addClass('flashed');
			window.setTimeout(function() {
				element.removeClass('flashed');
			}, 500);
			window.setTimeout(function() {
				element.animate({ opacity: 0 }, 300);
			}, 1000);
		});	
	});

	onLoad('[data-action]', function(element) {
		let action = element.attr('data-action');
		element.sendAction(action);		
	});

	onLoad('.option', function(element) {
		
		let options = element.closest('.options');
		let parent = element.attr('data-parent');
		if(parent) parent = $('.option#' + parent);
			
		if(parent && parent[0] && options[0]) {
		
			parent.click(function() {
				
				options.find('.option').css({'display': 'none'});
				options.find('.option[data-parent=' + parent.attr('id') + ']').css({'display': 'block'});
				options.find('.option.back').css({'display': 'block'});
				
			});
			
		}	
	});
	
	onLoad('.options', function(element) {
		
		if(element.find('.option[data-parent]').length) {
		
			let back = $(document.createElement('div'));
			back.addClass('back');
			back.addClass('option');
			back.text('Back');
			back.click(function() {
				element.find('.option').css({'display': 'block'});
				element.find('.option[data-parent]').css({'display': 'none'});
				element.find('.option.back').css({'display': 'none'});
			});
			
			element.prepend(back);
			
		}
		
	});

	onLoad('[data-popup]', function(popup) {
		window.setTimeout(function() {

			popup.attr('data-max', popup.maxHeight());
			if(popup.innerHeight())
				popup.css({ height: popup.attr('data-max') });

		}, 500);
		
		let identifier = popup.attr('data-popup');
		let btn = $(identifier);

		if(btn) btn.click(function() {

			popup = $('[data-popup=\'' + identifier + '\']');

			if(popup.innerHeight()) {

				popup.css({ height: 0 });
				btn.removeClass("open");

			} else {

				popup.css({ height: popup.attr('data-max') });
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

$.fn.maxHeight = function() {

	let current = $(this).innerHeight();
	$(this).css({ height: 'auto' });
	let max = $(this).innerHeight();
	$(this).css({ height: current });

	return max;

};