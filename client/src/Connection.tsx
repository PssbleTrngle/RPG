import React from 'react';

export abstract class LoadingComponent<T, PROPS, STATE> extends React.Component<PROPS,{result?: T} & STATE> {

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

	load(model = '', id = '', interval = true, retry = true) {

		const uri = `/${model}/${id || ''}`;
		const load = () => fetch(uri)
			.then(r => r.json())
			.then(result => this.setState({ result }))
			.catch(e => {
				console.error(`Error retrieving '${uri}'`)
				if(retry && !interval) window.setTimeout(load, 1000)
			});
	
		load();
		if(interval) window.setInterval(load, 1000);
	
	}

}