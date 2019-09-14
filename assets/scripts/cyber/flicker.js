jQuery.fn.random = function() {
    var randomIndex = Math.floor(Math.random() * this.length);  
    return jQuery(this[randomIndex]);
};

jQuery.fn.isText = function() {
	return $(this).children().length == 0 && $(this).text().length > 0;
}

jQuery.fn.flicker = function() {

		let target = $(this);

	if(!target[0].reversed) {

		if(target.isText()) {

			let original = target.text();
			let scrambled = original.split('').reverse().join('');
			target.text(scrambled);
			window.setTimeout(function() { target.text(original); target[0].reversed = false; }, 200);

		} else {

			if(Math.random() > 0.7) {

				target.toggleClass('mirror');
				window.setTimeout(function() { target.toggleClass('mirror'); target[0].reversed = false; }, 100);

			} else {

				target.css({visibility: 'hidden'});
				window.setTimeout(function() { target.css({visibility: 'visible'});; target[0].reversed = false; }, 50);

			}
		}

		target[0].reversed = true;

	}

};

$(window).ready(function() {

	let text_elements = $('body').find('*')
			.filter(function () { return $(this).isText(); });

	let images = $('body').find('img');

	window.setInterval(function() {
	
		text_elements.random().flicker();

	}, 20 * 1000 / text_elements.length);

	window.setInterval(function() {
			images.random().flicker();

	}, 40 * 1000 / images.length);

});