import React from 'react';
import { IClass, ISkill } from '../models';
import { Link, Redirect } from "react-router-dom";
import { WorldMap } from '../components/Fields';
import { Inventory } from '../components/Inventory';
import { Collapseable, ToggleButton } from "../components/Collapseable";
import { Cell } from '../components/Cell';
import { useLocalization } from '../Localization';
import { Icon } from '../components/Icon';
import { useAccount } from '../App';

function Evolve(props: { classes: IClass[] }) {
    const { classes } = props;
    const { format } = useLocalization();

    return (
        <Cell className='evolve' area='evolve'>

            <p>{format('message.evolve_1')}</p>
            <h3>{format('message.evolve_2')}</h3>

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

interface LearnProp {
    skill: ISkill;
    cost: number;
}
function LearnSkills(props: { skills: LearnProp[] }) {
    const { skills } = props;

    return (
        <Collapseable hidden={true} id='skills'>
            <div className='learn'>
                {skills.map(({ skill, cost }) =>
                    <div key={skill.id} className='skill'>
                        <span>{cost}</span>
                        <span>{skill.name}</span>
                    </div>
                )}
            </div>
        </Collapseable>
    )
}

function Home() {
    const { selected } = useAccount();

    if (!selected) return <Redirect to='/profile' />;
    const { classes, inventory, evolutions } = selected;
    const last = classes[classes.length - 1];

    const skills = [
        { skill: { id: 'test_1', name: 'Test 1' }, cost: 1 },
        { skill: { id: 'test_2', name: 'Test 2' }, cost: 3 },
        { skill: { id: 'test_3', name: 'Test 3' }, cost: 2 },
        { skill: { id: 'test_4', name: 'Test 4' }, cost: 1 },
    ]

    return (
        <div id='home'>
            {evolutions.length > 0 &&
                <Evolve classes={evolutions} />
            }
            <Cell area='position'>
                <WorldMap />
            </Cell>
            <Cell area='inventory'>
                <Inventory stacks={inventory} />
            </Cell>
            <Cell area='skills'>
                <LearnSkills {...{ skills }} />
            </Cell>

            <Cell area='buttons'>
                <Link to='/profile'><Icon src={`class/${last.id}`} /></Link>

                <ToggleButton id='skills' />
                <ToggleButton id='inventory' src='chest' />
                <ToggleButton id='map' mobileOnly={true} />

            </Cell>
        </div>
    );
}

export default Home;