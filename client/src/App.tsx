import React, { useContext, useState, ReactNode, useEffect } from 'react';
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";
import { IAccount, Point } from './Models';

import Profile from './pages/Profile';
import Home from './pages/Home';

import './style/App.scss';
import View from './pages/View';
import Battle from './components/Battle';
import { PopupOpen, PopupContext } from './components/Popup';
import { Graphs } from './pages/Graph';
import { LangContext, load, useLang } from './Localization';
import { CollapsedContext } from './components/Collapseable';
import { AccountContext, useSubscribe } from './Api';

function BG() {
	const initial = { x: 0, y: 0 };
	const [current, setCurrent] = useState(initial)

	useEffect(() => {
		const move = (e: MouseEvent) => {
			const factor = 0.01;
			const x = e.clientX * -1 * factor;
			const y = e.clientY * -1 * factor;
			setCurrent({ x, y });
		}
		window.addEventListener('mousemove', move);
		return () => window.removeEventListener('mouseover', move);
	});

	const x = current.x - initial.x;
	const y = current.y - initial.y;

	return (
		<div className='bg' style={{
			backgroundPosition: `${x}px ${y}px`
		}} />
	);

}

function Providers(props: { children?: ReactNode, account: IAccount }) {
	const { account, children } = props;
	const [popup, setPopup] = useState<string | undefined>();
	const [collapsed, setCollapsed] = useState(new Set<string>());
	const [loadLang, key, json] = useLang();

	return (

		<LangContext.Provider value={{ json, loadLang, key }}>
			<AccountContext.Provider value={account}>
				<CollapsedContext.Provider value={{ collapsed, setCollapsed }}>
					<PopupContext.Provider value={{ popup, setPopup }}>

						{children}

					</PopupContext.Provider>
				</CollapsedContext.Provider>
			</AccountContext.Provider>
		</LangContext.Provider>
	)

}

function App() {
	const account = useSubscribe<IAccount | undefined>('account');

	if (!account) return <h1 className='center'>Server not Found</h1>;

	return (
		<Providers {...{ account }}>
			<Router>

				<BG />
				<PopupOpen />

				<div className='container'>
					<Switch>
						<Route path='/view/classes'>
							<Graphs />
						</Route>
						<Route path='/view/:model/:id?'>
							<View />
						</Route>

						<Route path='/profile'>
							<Profile />
						</Route>

						<Route path='/'>
							{account.selected?.battle ?
								<Battle /> : <Home />
							}
						</Route>
					</Switch>
				</div>

			</Router>
		</Providers>
	);

}

export default App;
