import React from 'react';
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";
import { Account, Point, ICharacter } from './models';
import { SERVER_URL } from './config'

import Profile from './Profile';
import Home from './Home';

import './style/App.scss';
import { List, View } from './View';
import { LoadingComponent } from './Connection';
import { Battle } from './Fields';
import { PopupOpen } from './Popup';

class BG extends React.Component<{},{current: Point, initial: Point}> {

	constructor(props: any) {
		super(props);
		const initial = {x: 0, y: 0}
		this.state = {current: initial, initial};
	}

	componentDidMount() {
		window.addEventListener('mousemove', e => {	
			const factor = 0.01;
			const x = e.clientX * -1 * factor;
			const y = e.clientY * -1 * factor;
			const current = {x, y};
			this.setState({ current });
		});
	}

	render() {
		const { current, initial } = this.state;
		const x = current.x - initial.x;
		const y = current.y - initial.y;

		return (
			<div className='bg' style={{
				backgroundPosition: `${x}px ${y}px`
			}} />
		)
	}

}
	
const fallback = require('./lang/en.json');

type Lang = {[key: string]: string | Lang};

class App extends LoadingComponent<Account, {},{lang: Lang, popup?: string}> implements AppProps {

	initialState() { return { lang: fallback } };
	model() {return ''; }

	componentDidMount() {
		super.componentDidMount();
		this.setLang(this.getLang());
	}

	private async loadLang(lang: string): Promise<Lang | false> {
		
		try {
			const json = require(`./lang/${lang}.json`);
			return json;
		} catch(_) {
			return false;
		}

	}

	async setLang(key: string): Promise<boolean> {
		const lang = await this.loadLang(key);
		if(lang) {
			localStorage.setItem('lang', key);
			this.setState({ lang });
		}
		return !!lang;
	}

	getLang(): string {
		return localStorage.getItem('lang') || 'en';
	}

	async action(action: string, params: {[key: string]: string} = {}) {
		try {
			const response = await fetch(SERVER_URL + action, {
				method: 'POST',
				headers: {
						'Accept': 'application/json',
						'Content-Type': 'application/json',
				},
				body: JSON.stringify(params)}
			);
	
			const json = await response.json();
			return json.success;
	
		} catch(_) {
			return false;
		}
	}

	apiView(params: any) {
		const {id, model} = params;
		if(model) {
			if(id) return <View {...{model, id}} />
			return <List {...{model}} />;
		}						
		return null;
	}

	template() {
		const { result: account } = this.state;
		const inBattle = (c?: ICharacter) => c && c.battle;

		return (
			<Router>

				<BG />
				<PopupOpen blocking={!!this.state.popup} /> 

				<div className='container'>
					{account ?

					<Switch>
						<Route path='/view/:model/:id?' render={p => this.apiView(p.match.params)} />

						<Route path='/profile'>
							<Profile {...{ account }} />
						</Route>

						<Route path='/'>
						{inBattle(account.selected) ?
							<Battle {...{ account }} />
						:
							<Home {...{ account }} />
						}
						</Route>
					</Switch>

					:
						<h1 className='center'>Server not Found</h1>
					}
				</div>

			</Router>
		);
	}

	find(key: string, object?: Lang): string | undefined {
		if(!object) return this.find('', {});
	
		if(key.match(/.\./)) {
			const [nextKey, ...child] = key.split('.');
			const group: any = object[nextKey];
			return this.find(child.join('.'), group);
		}

		const value = object[key];
		if(!value || typeof value === 'object')
			return undefined;

		return value.toString();
	}

	format(key: string, ...params: string[]): string {

		key = key.toString();
		const translation = this.find(key, this.state.lang) || this.find(key, fallback) || key;
		const parsed = translation.replace(/\$([0-9])+/, (_, i) => params[parseInt(i) - 1]);
		return parsed;

	}

    open(popup?: string) {
        this.setState({ popup });
    }

    isOpen(popup: string): boolean {
        return this.state.popup === popup;
    }

}

export type params = {[key: string]: any};
export interface AppProps {
	action: (url: string, params?: params) => void,
	format: (key: string, ...params: string[]) => string,
	setLang(key: string): Promise<boolean>,
	isOpen: (popup: string) => boolean,
	open: (popup?: string) => void,
	getLang: () => string;
}

export default App;
