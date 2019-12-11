/// <reference types="node" />

declare module 'vis-react' {

	import { Component, ReactText } from 'react';

	export interface Edge {
		from: ReactText;
		to: ReactText;
	}

	export interface Node {
		id: ReactText;
		label: string;
	}

	interface Props {
		graph: { nodes: Node[], edges: Edge[] };
		options: { [key: string]: any };
		events: { [key: string]: (e: any) => void };
		vis?: (vis: any) => any;
		getNetwork?: (vis: any) => any;
	}

	export default class Graph extends Component<Props> { }

}