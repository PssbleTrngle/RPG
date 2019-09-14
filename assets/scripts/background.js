/*
	Handles the background moving with the mouse
*/

window.inital_position = undefined;

function moveBG(x, y) {
	$('.bg').css({ 'background-position' : (x - window.inital_position.x) + 'px ' + (y - window.inital_position.y) + 'px' });
}

$(window).mousemove(function(e) {

	let factor = 0.01;
	let x = e.clientX * -1 * factor;
	let y = e.clientY * -1 * factor;

	if(!window.inital_position) window.inital_position = {x, y};

	moveBG(x, y);

});