import { Component } from "./Component";
import { SERVER_URL } from './config'

export abstract class LoadingComponent<T, PROPS, STATE> extends Component<PROPS,{result?: T} & STATE> {

	abstract model(): string;
	id(): string | undefined {
		return undefined;
	}
	abstract initialState(): STATE;
	interval(): boolean {
		return true;
	}

	constructor(props: any) {
		super(props);
		this.state = {...{result: undefined}, ...this.initialState()};
	}

	componentDidMount() {
		this.load(this.model(), this.id(), this.interval());
	}

	private load(model = '', id = '', interval = true, retry = true) {

		const uri = `${model || ''}/${id || ''}`;
		const load = async () => fetch(SERVER_URL + uri)
			.then(r => r.json())
			.then(result => this.setState({ result }))
			.catch(_ => {
				console.error(`Error retrieving '${uri}'`)
				if(retry && !interval) window.setTimeout(load, 1000)
			});
	
		load().then(() => {
			if(interval) window.setInterval(load, 1000 * 6);
		});
	}

}