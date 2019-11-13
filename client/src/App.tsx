import React from 'react';
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";
import { Account, Character } from './models';

import Profile from './Profile';

import './style/App.scss';

class App extends React.Component<{},{account?: Account}> {

	constructor(props: any) {
		super(props);
		this.state = {};
	}

	componentDidMount() {
		fetch('/api/account')
			.then(r => r.json())
			.then(account => this.setState({ account }))	
	}

	render() {
		const { account } = this.state;

		if(!account) return null;

		return (
			<Router>

				<div className='bg'></div>
				<div className='container'>

					<Switch>
						<Route path="/profile">
							<Profile account={account} />
						</Route>
						<Route path="/users">
							<div />
						</Route>
						<Route path="/">
							<div />
						</Route>
					</Switch>

				</div>

			</Router>
		);
	}

}

export default App;
