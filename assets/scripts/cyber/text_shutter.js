jQuery.fn.random = function() {
    var randomIndex = Math.floor(Math.random() * this.length);  
    return jQuery(this[randomIndex]);
};

jQuery.fn.flicker = function() {

	let target = $(this);
	let original = target.text();

	if(original && original.length > 0) {

		if(!target[0].reversed) {
			let scrambled = original.split('').reverse().join('');
			target.text(scrambled);
			target[0].reversed = true;
			window.setTimeout(function() { target.text(original); target[0].reversed = false; }, 200);
		}
	}

};

$(window).ready(function() {

	let text_elements = $('p, a, h1, h2, h3, h4, h5, span, small')
			.filter(function () { return $(this).children().length == 0; });

	window.setInterval(function() {
	
		text_elements.random().flicker();

	}, 10000 / text_elements.length);

});