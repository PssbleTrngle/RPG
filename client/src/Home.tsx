import React from 'react';
import { Account, ICharacter, IClass, IArea, ISkill } from './models';
import { Link } from "react-router-dom";
import Translator from './localization';
import { Cell, Buttons, Icon } from './Grid';
import { WorldMap } from './Fields';
import { Inventory } from './Inventory';
import { Page, Collapseable, ToggleButton } from "./Page";
const { format } = Translator;

class Evolve extends React.Component<{classes: IClass[]},{}> {

    render() {
        const { classes } = this.props;
        return (
            <Cell className='evolve' area='evolve'>

                <p>{ format('message.evolve_1') }</p>
                <h3>{ format('message.evolve_2') }</h3>

                <div className='character-row'>
                    {classes.map(clazz =>
                        <div key={clazz.id} className='character-panel'>
                            <Icon src={`class/${clazz.id}`} />
                        </div>
                    )}
                </div>
            </Cell>
        );
    }

}

interface SkillProps {
    skill: ISkill;
}
class Skills extends React.Component<{page: Page, skills: SkillProps[]},{}> {

    render() {
        const { skills, page } = this.props;;

        return (
            <Collapseable hidden={true} id='skills' {...{ page }}>
                {skills.map(s => {
                    return <p key={s.skill.id}>{s.skill.name}</p>
                })}
            </Collapseable>
        )
    }

}

class Home extends Page {
	
	render() {
		const { account } = this.props;
        const { selected, characters } = account;

        const skills = [
            {skill: {id: 'test_1', name: 'Test 1'}},
            {skill: {id: 'test_2', name: 'Test 2'}},
            {skill: {id: 'test_3', name: 'Test 3'}},
            {skill: {id: 'test_4', name: 'Test 4'}},
        ]
        
        if(!selected) return null;

		return (
			<div id='home'>
                {selected.evolutions.length > 0 &&
                    <Evolve classes={selected.evolutions} />
                }
                <Cell area='position'>
                    <WorldMap page={this} />
                </Cell>
                <Cell area='inventory'>
                    <Inventory app={this.app()} stacks={selected.inventory} />
                </Cell>
                <Cell area='skills'>
                    <Skills page={this} skills={skills} />
                </Cell>

                <Buttons>
                    <Link to='/profile'><Icon src={`class/${selected.classes[selected.classes.length-1].id}`} /></Link>

                    <ToggleButton id='skills' page={this} />
                    <ToggleButton id='inventory' src='chest' page={this} />
                    <ToggleButton id='map' page={this} mobileOnly={true} />

                </Buttons>
			</div>
		);
	}

}

export default Home;