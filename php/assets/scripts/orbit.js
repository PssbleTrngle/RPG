/*
	Not needed solved by CSS
*/

let MAX_EFFECTS = 3;

function posOne(moon, planet, time) {

	let h = planet.innerHeight();
	let w = planet.innerWidth();

	let x = w, y = h * 0.7;

	moon.animate({left: x, top: y}, time, function() {
		moon.css({ 'z-index': 2 });
		posTwo(moon, planet, time);
	});

}

function posTwo(moon, planet, time) {

	let h = planet.innerHeight();
	let w = planet.innerWidth();

	let x = 0, y = h * 0.3;

	moon.animate({left: x, top: y}, time, function() {
		moon.css({ 'z-index': 0 });
		posOne(moon, planet, time);
	});

}

$(window).ready(function() {

	let offsets = [];
	let count = [];

	$('.orbit[data-orbit]').each(function() {

		let moon = $(this);
		let orbitid = moon.attr('data-orbit');
		let planet = $('#' + orbitid);

		let find = moon.attr('data-orbit-find');
		if(find) planet = planet.find(find);
		
		let time = moon.attr('data-orbit-time');
		if(!time) time = 2000;

		if(planet) {

			if(!offsets[orbitid]) offsets[orbitid] = 0;

			let h = planet.innerHeight();
			let w = planet.innerWidth();
			moon.css({ opacity: 1, left: w / 2, top: h / 2 });

			window.setTimeout(function() {
				posOne(moon, planet, time);
			}, offsets[orbitid] * (time / MAX_EFFECTS) * 2);

			offsets[orbitid]++;

		}

	});

});