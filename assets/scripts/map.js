/*
	Handles the map
*/

$(window).ready(function() {
	
	onLoad('.map', function(map) {

		map.find('.map-back').click(function() {

			$(this).removeClass('shown');
			map[0].selected_area = null;
			map.reload();

		});

		for(loc of map.find('.location')) {

			let element = $(loc);
			let isArea = element.hasClass('area');

			if(isArea)
				element.click(function() {

					map[0].selected_area = element.attr('id');
					map.find('.map-back').addClass('shown');
					map.reload();

				});
			else if(element.hasClass('dungeon'))
				element.sendAction('/character/enter/' + element.attr('id'));
			else
				element.sendAction('/character/travel/' + element.attr('id'));

			let showAreas = isArea && !map[0].selected_area;
			let showLocation = !isArea && element.attr('data-area') == map[0].selected_area;

			if(showAreas || showLocation) {

				let x = map.innerWidth() / 2;
				let y = map.innerHeight() / 2;

				x += element.attr('data-map-x') * 10;
				y += element.attr('data-map-y') * 10;

				element.css({ top: y, left: x });
				element.addClass('shown');

			} else
				element.removeClass('shown');

		}

		
	});
	
});