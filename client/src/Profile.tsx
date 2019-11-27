import React from 'react';
import { Account, Character } from './models';
import format from './localization';

const action = (url: string) => {

};

class CharacterRow extends React.Component<{characters: Character[], selected?: Character},{}> {

	render() {
		const { characters, selected } = this.props;

		return (
			<div className='character-row'>
				{characters.map(character => 	
					<div className={`character-panel ${selected && selected.id == character.id}`} onClick={() => action(`character/select/${character.id}`)}>
						<h4>{ character.name }</h4>
						<small>
							<p>{ /* DESCRIPTION */ }</p>
							<p>Level { /* LEVEL */ }</p>
							<p>{ character.birth }</p>
						</small>
						/* ICON */
					</div>
				)}
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
					<tr>
						<td colSpan={2}><h2 className='speech'>{message}</h2></td>
					</tr>
					<tr>
						<td></td>
						<td className='greeter'>{ /* GREETER ICON */}</td>
					</tr>
				</table>
			</div>
		);
	}

}

class Icon extends React.Component<{src: string},{}> {

	render() {
		const { src } = this.props;

		let icon: any;
		try {
			icon = require(`./img/${src}`);
		} catch(e) {
			icon = require('./img/missing.png');
		}

		return (
			<img className='icon' src={icon} />
		)
	}
}

class Buttons extends React.Component<{account: Account},{}> {

	render() {
		const { characters, selected } = this.props.account;

		console.log(selected);

		return (
			<div className='row'>
				{ selected ?
					<a href='/'><div className='profile-btn fade big-btn'><Icon src={selected.position.icon} /></div></a>
				:
					<a href='/profile/create'><div className='profile-btn big-btn fade'><Icon src={'icon/create'} /></div></a>
				}
				<div className='top'>
					<div className='profile-btn fade' data-window='options-window'><Icon src={'icon/options'} /></div>
					<div className='profile-btn fade' data-window='lang-window'><Icon src={'icon/lang'} /></div>
				</div>
				<div className='top'>
					<a href='/profile/create'><div className='profile-btn fade'><Icon src={'icon/create'} /></div></a>
				</div>
			</div>
		)
	}

}

class SelectedInfo extends React.Component<{selected: Character},{}> {

	render() {
		const { selected } = this.props;

		return (
			<>
			<div className='bar health' data-amount={selected.health / selected.maxHealth}></div>
			<div className='mt-1'></div>
			<div className='bar xp' data-amount={selected.xp / selected.requiredXp}></div>
			
			<table className='stats mt-2 w-100'>
				{Object.keys(selected.stats).map( stat =>
					<tr>
						<td className='stat'>{ format('stat.' + stat)}:</td>
						<td className='stat highlight'>{selected.stats[stat]}</td>
					</tr>
				)}
			</table>

			<div className='mt-2'>
				{selected.skills.map(skill => 
					<p>{ skill.name }</p>
				)}
			</div>
			</>
		)
	}

}

class Journey extends React.Component<{character: Character},{}> {

	render() {
		const { character } = this.props;

		return (
			<div className='journey'>
				<h2>Your Journey</h2>
				<p>{ format('journey.start')}</p>
				{character.classes.map(clazz => 
					<p>{clazz.name}</p>
				)}
				<p>{ format('journey.end')}</p>
			</div>
		)
	}

}

class Profile extends React.Component<{account: Account},{}> {
	
	render() {
		const { account } = this.props;
		const { selected, characters } = account;

		return (
			<>
			<h1 className='banner highlight w-66 mb-4'>{ format('message.welcome', account.username)}</h1>
			<div id='profile'>

				<div className='row collapse'>
					<CharacterRow characters={characters} selected={selected} />
					<Greeter icon={'death'} message={format('message.new_account')} />
					
					<div className='col'>
						<div className='row top'>
							<div className='pl-4'>

								<Buttons account={account} />
								{selected && <SelectedInfo selected={selected} />}

							</div>
							<div className='pl-2 middle'>
								{selected && <Journey character={selected} />}
							</div>
						</div>
					</div>

				</div>
				<div className='window' id='lang-window'>
					<div className='row'>
						{['en', 'de', 'cyber'].map((lang, i) =>
							<div className='lang' data-action='language/{{ lang }}'>{lang.toLowerCase()}</div>
						)}
					</div>
				</div>

				<div className='window' id='options-window'>
					<h1>Options</h1>
				</div>
			</div>
			</>
		);
	}

}

export default Profile;