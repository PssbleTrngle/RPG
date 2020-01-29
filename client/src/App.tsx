import React, { useContext, useState } from 'react';
import { BrowserRouter as Router, Switch, Route, useParams } from "react-router-dom";
import { Account, Point, ICharacter } from './models';
import { SERVER_URL } from './config'

import Profile from './pages/Profile';
import Home from './pages/Home';

import './style/App.scss';
import { List, View } from './pages/View';
import { Battle } from './components/Battle';
import { PopupOpen } from './components/Popup';
import { Graphs } from './pages/Graph';
import { LangContext, fallback, load, useLang } from './Localization';

const AccountContext = React.createContext<Account | null>(null);

export function useAccount() {
	const account = useContext(AccountContext);
	if (account) return account;
	throw new Error('Account not in context');
}

export function useSubscribe<M>(endpoint: string) {
	const [result, setResult] = useState<M | undefined>();
	if (result) return result;
	fetch(`/${endpoint}`)
		.then(r => r.json())
		.then(r => setResult(r))
		.catch(() => console.warn(`Could not load ${endpoint}`);
}

export async function action(action: string, params: any = {}) {
	try {
		const response = await fetch(SERVER_URL + action, {
			method: 'POST',
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(params)
		}
		);

		const json = await response.json();
		return json.success;

	} catch (_) {
		return false;
	}
}

class BG extends React.Component<{}, { current: Point, initial: Point }> {

	constructor(props: any) {
		super(props);
		const initial = { x: 0, y: 0 }
		this.state = { current: initial, initial };
	}

	componentDidMount() {
		window.addEventListener('mousemove', e => {
			const factor = 0.01;
			const x = e.clientX * -1 * factor;
			const y = e.clientY * -1 * factor;
			const current = { x, y };
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

function ApiView() {
	const { id, model } = useParams();
	if (model) {
		if (id) return <View {...{ model, id }} />
		return <List {...{ model }} />;
	}
	return null;
}

function App() {
	const account = useSubscribe<Account | undefined>('account');
	const [popup, setPopup] = useState<string | undefined>();
	const [collapsed, setCollapsed] = useState(new Set<string>());
	const [loadLang, key, json] = useLang();

	if (!account) return <h1 className='center'>Server not Found</h1>;

	return (
		<LangContext.Provider value={{ json, loadLang, key }}>
			<AccountContext.Provider value={account}>
				<Collapsed.Provider value={{ collapsed, setCollapsed }}>
					<Popup.Provider value={{ popup, setPopup }}>
						<Router>

							<BG />
							<PopupOpen />

							<div className='container'>
								<Switch>
									<Route path='/view/classes'>
										<Graphs />
									</Route>
									<Route path='/view/:model/:id?'>
										<ApiView />
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
					</Popup.Provider>
				</Collapsed.Provider>
			</AccountContext.Provider>
		</LangContext.Provider>
	);

}

const Collapsed = React.createContext<{
	collapsed: Set<string>,
	setCollapsed(set: Set<string>): void,
}>({
	collapsed: new Set(),
	setCollapsed: () => { }
});
export function useCollapse(id: string) {
	const { collapsed, setCollapsed } = useContext(Collapsed);
	const toggle = (id: string) => {
		const set = new Set(collapsed);
		set.delete(id)
		setCollapsed(set);
	}

	return [
		collapsed.has(id),
		() => toggle(id),
	] as [boolean, () => void];

}

const Popup = React.createContext<{
	popup: string | undefined,
	setPopup(id?: string): void,
}>({ popup: undefined, setPopup: () => { } });
export function usePopup(id?: string) {
	const { popup, setPopup } = useContext(Popup);
	return {
		isOpen: popup === id,
		open: id ? () => setPopup(id) : () => { },
		close: () => setPopup()
	}
}

export default App;
