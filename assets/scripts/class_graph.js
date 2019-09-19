$(window).ready(function() {

    let starter_colors = ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF8000'];
    let classes = window.classes;

	let edges = [];
    for(let id in classes) {

    	let node = classes[id];
    	node.id = parseInt(id);

    	if(node.from.length > 0) {
    		if(!Array.isArray(node.from)) node.from = [node.from];
    		node.origin = [];

    		for(let from of node.from) {
    			edges.push({from, to: node.id});
    			for(let origin of classes[from].origin)
    				if(!node.origin.includes(origin)) node.origin.push(origin);
    		}

    		node.deg = 0;
    		for(let origin of node.origin)
    			node.deg += node.origin;

    		node.deg = node.deg / node.origin.length * Math.pow(10, Math.floor(Math.log10(node.id)));

    		if(node.from.length == 1) node.colorCode = classes[node.from[0]].colorCode;
    		else node.colorCode = blend_colors(classes[node.from[0]].colorCode, classes[node.from[1]].colorCode);

    	} else {
    		node.colorCode = starter_colors[node.id - 1];
    		node.origin = [node.id];
    		node.deg = 1;
    	}
    	
    }
	
    $('.classes').each(function() {

	    let display = [];
	    let s = $(this).attr('data-display');
	    if(!s) s = '1,2,3,4,5';

	    for(let i of s.split(','))
	    	display.push(parseInt(i));
		
		let nodesArray = [];

	    for(let id in classes) {
	    	let node = classes[id];
	    	for(let origin of node.origin)
		    	if(display.includes(origin)) {
		    		nodesArray.push(node);
		    		break;
		    	}
	    }

		nodesArray = nodesArray.sort(function(n1, n2) {
			return n1.deg - n2.deg;
		});

		for(let deg = 0; deg < nodesArray.length; deg++) {
			nodesArray[deg].deg = deg + 1;
		}

		for(let node of nodesArray) {
	    	if(node.id > 9)
	    		node.colorCode = blend_colors(node.colorCode, '#FFFFFF', Math.floor(Math.log10(node.id)) * 0.1);

	    	node.color = {background: node.colorCode, border: node.colorCode};
	    	if(node.origin.length == 1) node.shape = 'box';
		}

		let start = [];
		let count = [];
		for(let node of nodesArray) {
		  	let level = Math.floor(Math.log10(node.id));
		  	if(!count[level]) count[level] = 0;
		  	if(!start[level])
		  		start[level] = node.deg;
		  	node.deg -= start[level];
		  	count[level]++;
		}

		let def = !!$(this).attr('data-display');
		
		let options = {
			autoResize: false,
			physics: def,
			locale: 'en',
			clickToUse: false,
			interaction:{
				keyboard: false,
				dragNodes: true
			},
			nodes: {
				font: {
					color: '#000',
					size: 20
				},
				color: {
					highlight: {
						background: '#FFF',
						border: '#FFF',
					}
				},
			},
			edges: {
				smooth: def,
	    		arrows: 'to',
				color: {
					inherit: 'both',
				},
			},
			layout: {
				hierarchical: {
					enabled: false,
					direction: 'UD',
				}
			}
		}
		
		let nodes = new vis.DataSet(nodesArray);
		let network = new vis.Network($(this)[0], { nodes, edges: new vis.DataSet(edges), options });

		if(!def) {
			network.once('initRedraw', function () {

				nodes.forEach(function(node, i) {

					let id = node.id;
					let level = Math.floor(Math.log10(id));
					let radius = 200 * (4 - level) + 10;
					let deg = 2 * Math.PI / count[level] * node.deg;

					var x = radius * Math.cos(deg);
					var y = radius * Math.sin(deg);
					network.moveNode(id, x, y);

				});

			});

			network.on('beforeDrawing', function (context) {

				for(let level = 1; level < 5; level++) {

					var radius = 100 + 200 * level;

					context.beginPath();
					context.arc(0, 0, radius, 0, 2 * Math.PI, false);
					context.fillStyle = level % 2 == 0 ? '#0002' : '#FFF2';
					context.fill();

				}
			});
				
		}

		network.on('click', function(e) {
			
			let id = e.nodes[0];
			
			$('.classes').each(function() {

				let network = $(this)[0].network;
				let node = id ? network.findNode(id) : [];
				network.selectNodes(node);

			});

		});

		$(this)[0].network = network;

	});
	
});
