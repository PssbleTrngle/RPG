$(window).ready(function() {
	
	/*
    var nodes = new vis.DataSet([
		{% for class in classes %}
       		{id: {{ class.id }}, label: '{{ class.name() }}'},
		{% endfor %}
    ]);
	
    var edges = new vis.DataSet([
		{% for class in classes %}
			{% for to in class.evolvesTo().get() %}
       			{from: {{ class.id }}, to: {{ to.id }}},
			{% endfor %}
		{% endfor %}
    ]);
    */

    let starter_colors = ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF8000']
	
    let classes = {
       	1: {label: 'Apprentice'},
       	2: {label: 'Fighter'},
       	3: {label: 'Wild'},
       	4: {label: 'Traveller'},
       	5: {label: 'Psychic'},

       	11: {label: 'Druid', from: 1},
       	12: {label: 'Mage', from: 1},
       	13: {label: 'Alchemist', from: 1},

       	21: {label: 'Duelist', from: 2},
       	22: {label: 'Warrior', from: [2, 3]},
       	23: {label: 'Focused', from: 2},

       	31: {label: 'Ranger', from: 3},
       	32: {label: 'Hermit', from: 3},

       	41: {label: 'Rogue', from: 4},
       	42: {label: 'Barde', from: 4},
       	43: {label: 'Smith', from: 4},

       	51: {label: 'Priest', from: 5},
       	52: {label: 'Medium', from: 5},
       	53: {label: 'Telepath', from: 5},

       	101: {label: 'Shaman', from: [11, 32]},
       	102: {label: 'Necromancer', from: [11, 52]},
       	104: {label: 'Wizard', from: [12, 53]},
       	105: {label: 'Elementalist', from: [12, 13]},
       	106: {label: 'Infused', from: 13},
       	107: {label: 'Ritualist', from: [13, 51]},

       	201: {label: 'Rebel', from: 21},
       	202: {label: 'Hero', from: 21},
       	203: {label: 'Knight', from: 22},
       	204: {label: 'Berserk', from: 22},

       	301: {label: 'Tamer', from: 31},
       	303: {label: 'Hunter', from: 31},
       	304: {label: 'Monk', from: [32, 23]},

       	401: {label: 'Assassin', from: 41},
       	402: {label: 'Swift', from: [41, 23]},
       	403: {label: 'Inventor', from: 43},
       	404: {label: 'Fool', from: 42},
       	405: {label: 'Performer', from: 42},

       	501: {label: 'Cleric', from: [51, 22]},
       	502: {label: 'Thaumaturge', from: 52},
       	503: {label: 'Summoner', from: 52},

       	1001: {label: 'Driven', from: [107, 204]},
       	1002: {label: 'Sage', from: [104, 304]},
       	1003: {label: 'Beast', from: [301, 107]},
       	1004: {label: 'Fallen', from: 203},
       	1005: {label: 'Guardian', from: 203},
       	1006: {label: 'Chosen', from: 202},
       	1007: {label: 'Insane', from: [404]},
       	1008: {label: 'Narrator', from: [405]},
    };

	let edges = [];
    for(let id in classes) {
    	let node = classes[id];
    	node.id = parseInt(id);
    	if(node.from) {
    		if(!Array.isArray(node.from)) node.from = [node.from];
    		node.origin = [];

    		for(let from of node.from) {
    			edges.push({from, to: node.id});
    			for(let origin of classes[from].origin)
    				node.origin.push(origin);
    		}

    		if(node.from.length == 1) node.colorCode = classes[node.from[0]].colorCode;
    		else node.colorCode = blend_colors(classes[node.from[0]].colorCode, classes[node.from[1]].colorCode);

    	} else {
    		node.colorCode = starter_colors[node.id - 1];
    		node.origin = [node.id];
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

		for(let node of nodesArray) {
	    	if(node.id > 9)
	    		node.colorCode = blend_colors(node.colorCode, '#FFFFFF', Math.floor(Math.log10(node.id)) * 0.1);
	    	node.color = {background: node.colorCode, border: node.colorCode};
		}

		let count = [];
		let index = [];
		for(let node of nodesArray) {
		  	let level = Math.floor(Math.log10(node.id));
		  	if(!count[level]) count[level] = 0;
		  	if(!index[level]) index[level] = 0;

		  	node.index = index[level];

		  	count[level]++;
		  	index[level]++;
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
				color: {
					highlight: {
						border: '#FFF'
					}
				},
				font: { color: '#000' }
			},
			edges: {
				smooth: def,
	    		arrows: 'to',
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
					let deg = 2 * Math.PI / count[level] * node.index;

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
			
			if(id)
				$('.classes').each(function() {

				let network = $(this)[0].network;
				let node = network.findNode(id);
				network.selectNodes(node);

			});

		});

		$(this)[0].network = network;

	});
	
});
