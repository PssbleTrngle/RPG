import React from 'react';
import { Account, ICharacter, IClass, IArea } from './models';
import { Link } from "react-router-dom";
import format from './localization';
import { Cell, Buttons, Icon } from './Grid';
import { WorldMap } from './Fields';
import { Inventory } from './Inventory';

const action = (url: string) => {

};

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

class Home extends React.Component<{account: Account},{}> {
	
	render() {
		const { account } = this.props;
        const { selected, characters } = account;
        
        if(!selected) return null;

		return (
			<div id='home'>
                {selected.evolutions.length > 0 &&
                    <Evolve classes={selected.evolutions} />
                }
                <Cell area='position'>
                    <WorldMap />
                </Cell>
                <Cell area='inventory'>
                    <Inventory {...selected.inventory} />
                </Cell>
                <Cell area='skills'>
                </Cell>

                <Buttons>
                    <Link to='/profile'><Icon src={`class/${selected.classes[selected.classes.length-1].id}`} /></Link>

                    <Icon src={'icon/skills'} />
                    <Icon src={'icon/chest'} />

                </Buttons>
			</div>
		);
	}

}

export default Home;