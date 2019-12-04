import React, { ReactNode, ReactElement } from 'react';
import { BrowserRouter as Router, Switch, Route, useRouteMatch, useParams } from "react-router-dom";
import { Account, Point, ICharacter, ID } from './models';

import Profile from './Profile';
import Home from './Home';

import './style/App.scss';
import { List, View } from './View';
import Translator from './localization';
import { LoadingComponent } from './Connection';
import { Battle } from './Fields';
import { format } from 'path';

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

	template() {
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

class App extends LoadingComponent<Account, {},{lang?: string}> implements AppProps {

	initialState() { return {} };
	model() {return ''; }

	componentDidMount() {
		super.componentDidMount();
		this.setLang('en');
	}

	async setLang(lang: string) {
		await Translator.load(lang);
		this.setState({ lang });
	}

	async action(action: string, params: {[key: string]: string} = {}) {
		try {
			const response = await fetch(action, {
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

	format = Translator.format;

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
		const battle = account && account.selected && account.selected.battle;
		const inBattle = (c?: ICharacter) => c && c.battle;

		return (
			<Router>

				<BG />
				<div className='container'>
					{account ?

					<Switch>
						<Route path='/view/:model/:id?' render={p => this.apiView(p.match.params)} />

						<Route path='/profile'>
							<Profile app={this} {...{ account }} />
						</Route>

						<Route path='/'>
						{inBattle(account.selected) ?
							<Battle app={this} {...{ account }} />
						:
							<Home app={this} {...{ account }} />
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

}

type params = {[key: string]: string};
export interface AppProps {
	action: (url: string, params?: params) => void,
	format: (key: string | {id: ID}, ...params: string[]) => string,
}

export abstract class Component<P,S, SS = {}> extends React.Component<{app?: AppProps} & P,S, SS> implements AppProps {

	action(url: string, params?: params) {
		const { app } = this.props;
		if(app) app.action(url, params);
	}
	
	format(key: string | {id: ID}, ...params: string[]): string {
		const { app } = this.props;
		if(app) return app.format(key, ...params);
		return 'NO APP';
	}

	abstract template(): ReactNode;

	private assignProps(node: any) {
		const { app } = this.props;

		if(!app) return 'NO APP';

		if(typeof node === 'object') {
			const { type, props } = node;

			return <node.type {...props} {...{ app }}>
				{React.Children.map(props.children, child => this.assignProps(child) )}
			</node.type>
		}

		return node;
	}

	render() {
		const rendered = this.template();
		const { children, app } = this.props;

		return this.assignProps(rendered);
	}

}

export default App;
