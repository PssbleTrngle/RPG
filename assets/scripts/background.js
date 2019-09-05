/*
	Handles the background moving with the mouse
*/

let inital_position = undefined;

function moveBG(x, y) {
	$('.box').css({ 'background-position' : (x - inital_position.x) + 'px ' + (y - inital_position.y) + 'px' });
}

$(window).mousemove(function(e) {

	let x = e.clientX * -0.02;
	let y = e.clientY * -0.02;

	if(!inital_position) inital_position = {x, y};

	moveBG(x, y);

});