$(window).ready(function() {

	onLoad('.participant', function(element) {

		element.mouseover(function() {

			let skill = $('.skill.selected').attr('data-skill');
			let group = $('.skill.selected').attr('data-group') != 0;
			if(skill) {

				$('.field').removeClass('hover');
				
				element.addClass('hover');
				if(group)
					for(let neighboor of element.neighboors(1))
						neighboor.addClass('hover');

			}

		});

		element.click(function() {
		
			if(window.battle_action) {
				window.params.target = $(this).attr('id');		
				sendAction(window.battle_action);
			}

		});

	});
	
	$('.skill').click(function() {

		window.params.skill = $(this).attr('data-skill');
		window.battle_action = 'battle/skill';

	});

	onLoad('.field', function(element) {

		let battlefield = element.parent();
		let radius = battlefield.radius();

		let totalW = battlefield.innerWidth();
		let totalH = battlefield.innerHeight();
		let size = Math.min(totalW, totalH) / (radius * 2 + 1) * 0.8;

		let y = parseInt(element.attr('data-y'));
		let x = parseInt(element.attr('data-x')) + 0.5 * y;
		let marg = size * 0.1;

		element.css({
			top: totalH / 2 + (size + marg) * y + 'px',
			left: totalW / 2 + (size + marg * 2) * x + 'px', '--size':
			size + 'px',
			display: 'block'
		});

		element.mouseout(function() {

			$('.field').removeClass('hover');

		});

	});
	
});

$.fn.neighboors = function(radius) {
	if(!radius) radius = 1;

	let y = parseInt($(this).attr('data-y'));
	let x = parseInt($(this).attr('data-x'));

	let neighboors = [];

	for(let x1 = -radius; x1 <= radius; x1++)
		for(let y1 = -radius; y1 <= radius; y1++)
			if(Math.abs(x1 + y1) <= radius)
				neighboors.push($('.field[data-x=' + (x + x1) + '][data-y=' + (y + y1) + ']'));

	return neighboors;

};

$.fn.radius = function() {
	
	let radius = $(this).attr('data-radius');
	if(radius) return radius;

	let maxX = 0;
	let maxY = 0;

	for(let field of $(this).find('.field')) {

		let x = parseInt($(field).attr('data-x'));
		let y = parseInt($(field).attr('data-y'));
		maxX = Math.max(maxX, x);
		maxY = Math.max(maxY, y);

	}


	radius = Math.max(maxX, maxY);
	$(this).attr('data-radius', radius);
	return radius;

};