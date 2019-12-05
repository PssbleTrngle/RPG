import React from 'react';
import { ICharacter } from './models';
import { Link } from "react-router-dom";
import { Cell, Buttons, Icon } from './Grid';
import { Page } from './Page';
import { Component } from "./Component";
import { Popup } from './Popup';

class CharacterRow extends Component<{characters: ICharacter[], selected?: ICharacter},{}> {

	template() {
		const { characters, selected } = this.props;

		return (
			<div className='character-row'>
				{characters.map(character => {
					const last = character.classes[character.classes.length-1];
					return (
						<div 
							key={character.id}
							className={`character-panel ${selected && selected.id === character.id && 'selected'}`}
							onClick={() => this.action('account/select', { character: character.id })}>
							<h4>{ character.name }</h4>
							<small>
								<p>{ this.format(`class.${last.id}.name`) }</p>
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

class Greeter extends Component<{icon: string, message: string},{}> {

	template() {
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

class Bar extends Component<{amount: number, type?: string},{}> {

	template() {
		const { type, amount } = this.props;

		const width = 100 * amount + '%';

		return (
			<div className={`bar ${type || ''}`} >
				<div style={{ width }} />
			</div>
		)
	}
}

class SelectedInfo extends Component<{selected: ICharacter},{}> {

	template() {
		const { selected } = this.props;

		return (
			<>
			<Bar type='health' amount={selected.health / selected.maxHealth}></Bar>
			<div className='mt-1'></div>
			<Bar type='xp' amount={selected.xp / selected.requiredXp}></Bar>
			
			<table className='stats mt-2 w-100'><tbody>
				{Object.keys(selected.stats).map(stat =>
					<tr key={stat}>
						<td className='stat'>{this.format(`stat.${stat}`)}:</td>
						<td className='stat highlight'>{selected.stats[stat]}</td>
					</tr>
				)}
			</tbody></table>
			</>
		)
	}

}

class Journey extends Component<{character: ICharacter},{}> {

	template() {
		const { character } = this.props;

		return (
			<div className='journey'>
				<h2>Your Journey</h2>
				<p>{ this.format('journey.start')}</p>
				{character.classes.map(clazz => 
					<p key={clazz.id}>{this.format(`class.${clazz.id}.name`)}</p>
				)}
				<p>{ this.format('journey.end')}</p>
			</div>
		)
	}

}

class Profile extends Page {
	
	template() {
		const { account } = this.props;
		const { selected, characters } = account;

		return (
			<>
			<h1 className='banner highlight'>{ this.format('message.welcome', account.username)}</h1>
			<div id='profile'>

				<Cell area='chars'>
					<CharacterRow {...{ selected, characters }} />
					{characters.length === 0 && <Greeter icon={'class/death'} message={this.format('message.new_account')} />}
				</Cell>

				<Buttons>
					{ selected ?
						<Link to='/'><Icon src={'position/dungeon/moss'} /></Link>
					:
						<Link to='create'><Icon src={'icon/create'} /></Link>
					}
					<button onClick={() => this.open('lang')}><Icon src={'icon/lang'} /></button>
					<button onClick={() => this.open('options')}><Icon src={'icon/options'} /></button>
					
					<Link to='/profile/create'><Icon src={'icon/create'} /></Link>
				</Buttons>
				
				<Cell area='info'>
					{selected && <SelectedInfo selected={selected} />}
				</Cell>

				<Cell area='journey'>
					{selected && <Journey character={selected} />}
				</Cell>

				<Popup id='lang'>
					{['en', 'de', 'cyber'].map(lang =>
						<div
							key={lang}
							className={`lang ${this.getLang() === lang ? 'active' : ''}`}
							onClick={() => {
								this.setLang(lang);
								this.open()
							}}>
							{lang.toLowerCase()}
						</div>
					)}
				</Popup>

				<Popup id='options'>
					<h1>Options</h1>
				</Popup>
			</div>
			</>
		);
	}

}

export default Profile;