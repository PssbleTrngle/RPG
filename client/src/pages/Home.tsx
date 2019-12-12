import React from 'react';
import { IClass, ISkill } from './models';
import { Link } from "react-router-dom";
import { Cell, Buttons, Icon } from './Grid';
import { WorldMap } from './Fields';
import { Inventory } from './Inventory';
import { Page, Collapseable, ToggleButton } from "./Page";
import { Component } from "./Component";

class Evolve extends Component<{classes: IClass[]},{}> {

    template() {
        const { classes } = this.props;
        return (
            <Cell className='evolve' area='evolve'>

                <p>{ this.format('message.evolve_1') }</p>
                <h3>{ this.format('message.evolve_2') }</h3>

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
    cost: number;
}
class Skills extends Component<{page: Page, skills: SkillProps[]},{}> {

    template() {
        const { skills, page } = this.props;;

        return (
            <Collapseable hidden={true} id='skills' {...{ page }}>
                <div className='learn'>
                    {skills.map(s => 
                        <div key={s.skill.id} className='skill'>
                            <span>{ s.cost }</span>
                            <span>{ s.skill.name }</span>
                        </div>
                    )}
                </div>
            </Collapseable>
        )
    }

}

class Home extends Page {
	
	template() {
		const { account } = this.props;
        const { selected } = account;

        const skills = [
            {skill: {id: 'test_1', name: 'Test 1'}, cost: 1},
            {skill: {id: 'test_2', name: 'Test 2'}, cost: 3},
            {skill: {id: 'test_3', name: 'Test 3'}, cost: 2},
            {skill: {id: 'test_4', name: 'Test 4'}, cost: 1},
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
                    <Inventory stacks={selected.inventory} />
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