/*
	Handles the background moving with the mouse
*/

let inital_position = undefined;

function moveBG(x, y) {
	$('.box').css({ 'background-position' : (x - inital_position.x) + 'px ' + (y - inital_position.y) + 'px' });
}

$(window).mousemove(function(e) {

	let factor = 0.01;
	let x = e.clientX * -1 * factor;
	let y = e.clientY * -1 * factor;

	if(!inital_position) inital_position = {x, y};

	moveBG(x, y);

});