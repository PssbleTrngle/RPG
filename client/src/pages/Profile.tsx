import React from 'react';
import { ICharacter } from '../Models';
import { Link } from "react-router-dom";
import { Icon } from '../components/Icon';
import { action, useAccount } from '../Api';
import { useLocalization } from '../Localization';
import { Cell } from '../components/Cell';
import { Popup, usePopup } from '../components/Popup';

function CharacterRow(props: { characters: ICharacter[], selected?: ICharacter }) {
	const { characters, selected } = props;
	const { format } = useLocalization();

	return (
		<div className='character-row'>
			{characters.map(character => {
				const last = character.classes[character.classes.length - 1];
				return (
					<div
						key={character.id}
						className={`character-panel ${selected && selected.id === character.id && 'selected'}`}
						onClick={() => action('account/select', { character: character.id })}>
						<h4>{character.name}</h4>
						<small>
							<p>{format(`function.${last.id}.name`)}</p>
							<p>Level {character.level}</p>
							<p>{character.birth}</p>
						</small>
						<Icon src={`class/${last.id}`} />
					</div>
				);
			})}
		</div>
	);
}

function Greeter(props: { icon: string, message: string }) {
	const { icon, message } = props;

	return (
		<div className='w-33 pl-2'>
			<table>
				<tbody>
					<tr>
						<td colSpan={2}><h2 className='speech'>{message}</h2></td>
					</tr>
					<tr>
						<td></td>
						<td className='greeter'><Icon src={icon} /></td>
					</tr>
				</tbody>
			</table>
		</div>
	);
}

function Bar(props: { amount: number, type?: string }) {
	const { type, amount } = props;
	const width = 100 * amount + '%';

	return (
		<div className={`bar ${type || ''}`} >
			<div style={{ width }} />
		</div>
	)
}

function SelectedInfo(props: { selected: ICharacter }) {
	const { selected } = props;
	const { format } = useLocalization()

	return (
		<>
			<Bar type='health' amount={selected.health / selected.maxHealth}></Bar>
			<div className='mt-1'></div>
			<Bar type='xp' amount={selected.xp / selected.requiredXp}></Bar>

			<table className='stats mt-2 w-100'><tbody>
				{Object.keys(selected.stats).map(stat =>
					<tr key={stat}>
						<td className='stat'>{format(`stat.${stat}`)}:</td>
						<td className='stat highlight'>{selected.stats[stat]}</td>
					</tr>
				)}
			</tbody></table>
		</>
	)

}

function Journey(props: { character: ICharacter }) {
	const { character } = props;
	const { format } = useLocalization();

	return (
		<div className='journey'>
			<h2>Your Journey</h2>
			<p>{format('journey.start')}</p>
			{character.classes.map(({ id }) =>
				<p key={id}>{format(`class.${id}.name`)}</p>
			)}
			<p>{format('journey.end')}</p>
		</div>
	)
}

function Profile() {
	const { selected, characters, username } = useAccount();
	const { format } = useLocalization();
	const lang = usePopup('lang');
	const options = usePopup('options');

	return (
		<>
			<h1 className='banner highlight'>{format('message.welcome', username)}</h1>
			<div id='profile'>

				<Cell area='chars'>
					<CharacterRow {...{ selected, characters }} />
					{characters.length === 0 && <Greeter icon={'class/death'} message={format('message.new_account')} />}
				</Cell>

				<Cell area='buttons'>
					{selected ?
						<Link to='/'><Icon src={'position/dungeon/moss'} /></Link>
						:
						<Link to='create'><Icon src={'icon/create'} /></Link>
					}
					<button onClick={lang.open}><Icon src={'icon/lang'} /></button>
					<button onClick={options.open}><Icon src={'icon/options'} /></button>

					<Link to='/profile/create'><Icon src={'icon/create'} /></Link>
				</Cell>

				<Cell area='info'>
					{selected && <SelectedInfo selected={selected} />}
				</Cell>

				<Cell area='journey'>
					{selected && <Journey character={selected} />}
				</Cell>

				<LangPopup />
				<OptionsPopup />

			</div>
		</>
	);

}

function OptionsPopup() {
	return (
		<Popup id='options'>
			<h1>Options</h1>
		</Popup>
	);
}

function LangPopup() {
	const { key: currentLang, loadLang } = useLocalization();
	const { close } = usePopup();

	return (
		<Popup id='lang'>
			{['en', 'de', 'cyber'].map(lang =>
				<div
					key={lang}
					className={`lang ${currentLang === lang ? 'active' : ''}`}
					onClick={() => {
						loadLang(lang);
						close()
					}}>
					{lang.toLowerCase()}
				</div>
			)}
		</Popup>
	);
}

export default Profile;