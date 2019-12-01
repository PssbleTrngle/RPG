import React from 'react';
import { BrowserRouter as Router, Switch, Route, useRouteMatch, useParams } from "react-router-dom";
import { Account, Point } from './models';

import Profile from './Profile';
import Home from './Home';

import './style/App.scss';
import { unwatchFile } from 'fs';
import { List, View } from './View';

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

class App extends React.Component<{},{account?: Account}> {

	constructor(props: any) {
		super(props);
		this.state = {};
	}

	update() {
		fetch('http://localhost:8080')
			.then(r => r.json())
			.then(account => this.setState({ account }))
			.catch(e => this.setState({ account: undefined }))
	}

	componentDidMount() {
		this.update();
		window.setInterval(() => this.update(), 1000)
	}

	render() {
		const { account } = this.state;

		return (
			<Router>

				<BG />
				<div className='container'>
					{account ?

					<Switch>
						<Route path='/profile'>
							<Profile account={account} />
						</Route>
						<Route path='/view/:model/:id?' render={p => {
							const {id, model} = p.match.params;
							if(model) {
								if(id) return <View {...{model, id}} />
								return <List {...{model}} />;
							}							
							return null;
						}} />
						<Route path='/'>
							<Home account={account} />
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

export default App;
