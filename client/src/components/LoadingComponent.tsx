import { Component } from "./Component";
import { SERVER_URL, UPDATE_TIME } from './config'

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

	private load(model = '', id = '', interval = true, retry = false) {

		const uri = `${model || ''}/${id || ''}`;
		const load = async () => {
			try {
				const r = await fetch(SERVER_URL + uri)
				const result = await r.json();
				this.setState({ result });
				return true;
			} catch(_) {
				console.error(`Error retrieving '${uri}'`);
				return false;
			}
		}
	
		load().then(success => {
			if((success || retry) && interval) window.setInterval(load, UPDATE_TIME);
		});
	}

}