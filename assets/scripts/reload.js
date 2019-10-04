var FUNCTIONS_LOAD = [];
var FUNCTIONS_RELOAD = [];

function onLoad(query, func) {

	if(!FUNCTIONS_LOAD[query]) FUNCTIONS_LOAD[query] = [];
	FUNCTIONS_LOAD[query].push(func);

}

function onReload(query, func) {

	if(!FUNCTIONS_RELOAD[query]) FUNCTIONS_RELOAD[query] = [];
	FUNCTIONS_RELOAD[query].push(func);

	if(!FUNCTIONS_LOAD[query]) FUNCTIONS_LOAD[query] = [];
	FUNCTIONS_LOAD[query].push(func);

}

$(window).resize(function() {
	$('.container').reload();
})

$.fn.reload = function() {

	for(query in FUNCTIONS_LOAD) {
		for(let e of $(this).filter(query)) {

			if(!e.loaded)
				for(func of FUNCTIONS_LOAD[query])
					func($(e));

			if(FUNCTIONS_RELOAD[query])
				for(func of FUNCTIONS_RELOAD[query])
					func($(e));

		}
	}

	for(e of $(this))
		for(child of e.children) $(child).reload();

	for(let e of $(this))
		e.loaded = true;

};