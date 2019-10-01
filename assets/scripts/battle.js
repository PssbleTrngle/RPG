$(window).ready(function() {

	let charX = parseInt($('#x').val());
	let charY = parseInt($('#y').val());

	onLoad('.participant', function(element) {

		let x = parseInt(element.attr('data-x'));
		let y = parseInt(element.attr('data-y'));

		element.mouseover(function() {

			let selected = $('.selected');
			let skill = selected.attr('data-skill');
			let item = selected.attr('id');
			let area = selected.attr('data-area');
			let range = selected.attr('data-range');
			
			if(!range) range = 1;
			if(area) area = JSON.parse(area);

			if(selected && window.battle_action) {

				$('.field').removeClass('hover');

				let inRange = (Math.abs(charX - x) <= range) && (Math.abs(charY - y) <= range) && (Math.abs((charX - x) + (charY - y)) <= range);

				if(inRange) {
					if(area)
						for(let pos of area)
							element.parent()
								.find('.field[data-x=' + (x + pos.x) + '][data-y=' + (y + pos.y) + ']')
								.addClass('hover');
					else
						element.addClass('hover');
				}

			}

		});

		element.click(function() {
		
			if(window.battle_action) {

				window.params.target = element.attr('id');
				sendAction(window.battle_action);

			}

		});

	});
	
	$('.skill').click(function() {

		window.params = { skill: $(this).attr('data-skill') };

	});

	onLoad('[data-select-action]', function(element) {
		element.click(function() {

			$(window).unselect();
			window.battle_action = element.attr('data-select-action');
			element.addClass('selected');

			if(element.hasClass('selected')) {
				let range = element.attr('data-range');

				$('.field').removeClass('disabled');

				$('.field').filter(function() {
					return !$(this).inRange(charX, charY, range);
				}).addClass('disabled');
			}

		});
	});

	onLoad('.field', function(element) {

		let battlefield = element.parent();
		let fieldsize = battlefield.size();

		let totalW = battlefield.innerWidth();
		let totalH = battlefield.innerHeight();
		let sizeW = totalW / (fieldsize.x * 2 + 1) * 0.8;
		let sizeH = totalH / (fieldsize.y * 2 + 1) * 0.8;
		let size = Math.min(sizeW, sizeH);

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

$.fn.inRange = function(charX, charY, range) {
	if(!range) range = 1;

	let x = parseInt($(this).attr('data-x'));
	let y = parseInt($(this).attr('data-y'));

	return (Math.abs(charX - x) <= range) && (Math.abs(charY - y) <= range) && (Math.abs((charX - x) + (charY - y)) <= range);

}

$.fn.size = function() {
	
	try {
		return JSON.parse($(this).attr('data-size'));
	} catch(e) {}

	let maxX = 0;
	let maxY = 0;

	for(let field of $(this).find('.field')) {

		let x = parseInt($(field).attr('data-x'));
		let y = parseInt($(field).attr('data-y'));
		maxX = Math.max(maxX, x);
		maxY = Math.max(maxY, y);

	}


	size = {x: maxX, y: maxY};
	$(this).attr('data-size', JSON.stringify(size));
	return size;

};