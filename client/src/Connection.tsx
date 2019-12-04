import React, { ReactNode } from 'react';
import { any } from 'prop-types';
import { SSL_OP_NETSCAPE_DEMO_CIPHER_CHANGE_BUG } from 'constants';
import App, { Component } from './App';

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

	load(model = '', id = '', interval = true, retry = true) {

		const uri = `/${model}/${id || ''}`;
		const load = async () => fetch(uri)
			.then(r => r.json())
			.then(result => this.setState({ result }))
			.catch(e => {
				console.error(`Error retrieving '${uri}'`)
				if(retry && !interval) window.setTimeout(load, 1000)
			});
	
		load().then(() => {
			if(interval) window.setInterval(load, 1000 * 6);
		});
	}

}