$(window).ready(function() {
	
	$('.evolve, .starter').each(function() {
		let panel = $(this);

		panel.click(function() {
			window.params.class = panel.attr('data-class');
			window.params.name = $('#name').val();
			let action = panel.hasClass('starter') ? '/character/create' : '/character/evolve';
			sendAction(action);
		});

		let out = function() {
			$('.evolve-description[data-class]').addClass('hidden');
			$('.evolve-description').not('[data-class]').removeClass('hidden');
		};

		panel.mouseover(function() {
			out();
			$('.evolve-description').not('[data-class]').addClass('hidden');
			$('.evolve-description[data-class=' + panel.attr('data-class') + ']').removeClass('hidden');
		});

		panel.mouseout(out);

	});

});