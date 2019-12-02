import React from 'react';
import { Account, ICharacter } from './models';
import { Link } from "react-router-dom";
import format from './localization';
import { Cell, Buttons, Icon } from './Grid';
import { Page } from "./Page";

const action = (url: string) => {

};

class CharacterRow extends React.Component<{characters: ICharacter[], selected?: ICharacter},{}> {

	render() {
		const { characters, selected } = this.props;

		return (
			<div className='character-row'>
				{characters.map(character => {
					const last = character.classes[character.classes.length-1];
					return (
						<div 
							key={character.id}
							className={`character-panel ${selected && selected.id === character.id && 'selected'}`}
							onClick={() => action(`character/select/${character.id}`)}>
							<h4>{ character.name }</h4>
							<small>
								<p>{ last.name }</p>
								<p>Level { character.level }</p>
								<p>{ character.birth }</p>
							</small>
							<Icon src={`class/${last.id}`} />
						</div>
					);
				})}
			</div>
		);
	}

}

class Greeter extends React.Component<{icon: string, message: string},{}> {

	render() {
		const { icon, message } = this.props;

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

}

class Bar extends React.Component<{amount: number, type?: string},{}> {

	render() {
		const { type, amount } = this.props;

		const width = 100 * amount + '%';

		return (
			<div className={`bar ${type || ''}`} >
				<div style={{ width }} />
			</div>
		)
	}
}

class SelectedInfo extends React.Component<{selected: ICharacter},{}> {

	render() {
		const { selected } = this.props;

		return (
			<>
			<Bar type='health' amount={selected.health / selected.maxHealth}></Bar>
			<div className='mt-1'></div>
			<Bar type='xp' amount={selected.xp / selected.requiredXp}></Bar>
			
			<table className='stats mt-2 w-100'><tbody>
				{Object.keys(selected.stats).map(stat =>
					<tr key={stat}>
						<td className='stat'>{stat}:</td>
						<td className='stat highlight'>{selected.stats[stat]}</td>
					</tr>
				)}
			</tbody></table>

			<div className='mt-2'>
				{selected.skills.map(skill => 
					<p>{ skill.name }</p>
				)}
			</div>
			</>
		)
	}

}

class Journey extends React.Component<{character: ICharacter},{}> {

	render() {
		const { character } = this.props;

		return (
			<div className='journey'>
				<h2>Your Journey</h2>
				<p>{ format('journey.start')}</p>
				{character.classes.map(clazz => 
					<p key={clazz.id}>{clazz.name}</p>
				)}
				<p>{ format('journey.end')}</p>
			</div>
		)
	}

}

class Profile extends Page {
	
	render() {
		const account = this.props;
		const { selected, characters } = account;

		return (
			<>
			<h1 className='banner highlight'>{ format('message.welcome', account.username)}</h1>
			<div id='profile'>

				<Cell area='chars'>
					<CharacterRow characters={characters} selected={selected} />
					{characters.length === 0 && <Greeter icon={'class/death'} message={format('message.new_account')} />}
				</Cell>

				<Buttons>
					{ selected ?
						<Link to='/'><Icon src={'position/dungeon/moss'} /></Link>
					:
						<Link to='create'><Icon src={'icon/create'} /></Link>
					}
					<Icon src={'icon/options'} />
					<Icon src={'icon/lang'} />
					
					<Link to='/profile/create'><Icon src={'icon/create'} /></Link>
				</Buttons>
				
				<Cell area='info'>
					{selected && <SelectedInfo selected={selected} />}
				</Cell>

				<Cell area='journey'>
					{selected && <Journey character={selected} />}
				</Cell>

				<Popup>
					<div className='row'>
						{['en', 'de', 'cyber'].map(lang =>
							<div key={lang} className='lang' data-action='language/{{ lang }}'>{lang.toLowerCase()}</div>
						)}
					</div>
				</Popup>

				<Popup>
					<h1>Options</h1>
				</Popup>
			</div>
			</>
		);
	}

}

export class Popup extends React.Component<{},{}> {

	render() {
		const { children } = this.props;

		return (
			<div className='popup'>
				{children}
			</div>
		);
	}

}

export default Profile;