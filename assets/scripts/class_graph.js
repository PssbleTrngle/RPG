$(window).ready(function() {

    let starter_colors = ['#FF0000', '#00FF00', '#0061ff', '#FFFF00', '#FF8000'];
    let max = 4;

	let edges = [];
    for(let id in window.classes) {

    	let node = window.classes[id];
    	node.id = parseInt(id);

    	if(node.from.length > 0) {
    		if(!Array.isArray(node.from)) node.from = [node.from];
    		node.origin = [];

    		node.deg = 0;

    		for(let from of node.from) {
    			edges.push({from, to: node.id});
    			for(let origin of window.classes[from].origin)
    				if(!node.origin.includes(origin)) node.origin.push(origin);
    		}

    		if(node.from.length == 1) node.deg = window.classes[node.from[0]].deg;
    		else {
    			let degMin = Math.min(window.classes[node.from[0]].deg);
    			let degMax = Math.max(window.classes[node.from[1]].deg);
    			let degNext = degMin + 5;

    			let dist1 = (degMax - degMin);
    			let dist2 = (degNext - degMax);

    			node.deg = (dist1 < dist2) ? (degMax - dist1 / 2) : (degNext - dist2 / 2);
			}

    		if(node.from.length == 1) node.colorCode = window.classes[node.from[0]].colorCode;
    		else node.colorCode = blend_colors(window.classes[node.from[0]].colorCode, window.classes[node.from[1]].colorCode);

    	} else {
    		node.colorCode = starter_colors[node.id - 1];
    		node.origin = [parseInt(node.id.toString().substring(0, 1))];
    		node.deg = node.id;
		}

		node.level = Math.floor(Math.log10(node.id));

    	if(node.id > 9)
    		node.colorCode = blend_colors(node.colorCode, '#FFFFFF', 0.15);

    	node.color = {background: node.colorCode, border: node.colorCode};
    	node.shape = node.image ? 'image' : 'box';
	
    }

    let classes = [];

    for(let id in window.classes) {
    	let node = window.classes[id];
    	classes.push(node);
    }

    classes.sort(function(n1, n2) {
    	return (n1.deg - n2.deg) + (n1.level - n2.level) * 1000;
    });

    let start = [];
    for(let i = 0; i < classes.length; i++) {
    	let node = classes[i];
    	node.deg = i+1;
    	if(!start[node.level]) start[node.level] = node.deg;
    	node.deg -= start[node.level];
    }
	
    $('.classes').each(function() {

	    let display = [];
	    let s = $(this).attr('data-display');
	    if(!s) s = '1,2,3,4,5';

	    for(let i of s.split(','))
	    	display.push(parseInt(i));
		
		let nodesArray = [];

	    for(let node of classes) {
	    	for(let origin of node.origin)
		    	if(display.includes(origin)) {
		    		nodesArray.push(node);
		    		break;
		    	}
	    }

		let count = [];
		for(let node of nodesArray) {
		  	if(!count[node.level]) count[node.level] = 0;
		  	count[node.level]++;
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
				brokenImage: '/assets/img/missing.png',
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
					let radius = 200 * (max - node.level) + 100;
					let deg = 2 * Math.PI / count[node.level] * node.deg;

					var x = radius * Math.cos(deg);
					var y = radius * Math.sin(deg);
					network.moveNode(id, x, y);

				});

			});

			network.on('beforeDrawing', function (context) {

				for(let level = 1; level < 5; level++) {

					var radius = 200 * (level + 1);

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
