var FUNCTIONS = [];

function onLoad(query, func) {

	if(!FUNCTIONS[query]) FUNCTIONS[query] = [];
	FUNCTIONS[query].push(func);

}

/*
$.fn.click = function(func) {
	
	if(func) for(e of $(this)) if(!e.hasClickEvent) {
		e.hasClickEvent = true;
		e.onclick = func;
	}

};
*/

$.fn.reload = function() {
	
	for(e of $(this))
		for(child of e.children) $(child).reload();

	for(query in FUNCTIONS) {
		for(e of $(this).filter(query))
			for(func of FUNCTIONS[query])
				func($(e));
	}

};