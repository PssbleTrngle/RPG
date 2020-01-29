import React from 'react';
import { IClass, ISkill } from '../models';
import { Link, Redirect } from "react-router-dom";
import { WorldMap } from '../components/Fields';
import { Inventory } from '../components/Inventory';
import { Collapseable, ToggleButton } from "./Page";
import { Cell } from '../components/Cell';
import { useLocalization } from '../Localization';
import { Icon } from '../components/Icon';
import { useAccount } from '../App';
import { Buttons } from '../components/Buttons';

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

interface SkillProps {
    skill: ISkill;
    cost: number;
}
function Skills(props: { skills: SkillProps[] }) {
    const { skills } = props;

    return (
        <Collapseable hidden={true} id='skills'>
            <div className='learn'>
                {skills.map(s =>
                    <div key={s.skill.id} className='skill'>
                        <span>{s.cost}</span>
                        <span>{s.skill.name}</span>
                    </div>
                )}
            </div>
        </Collapseable>
    )
}

function Home() {
    const { selected } = useAccount();

    const skills = [
        { skill: { id: 'test_1', name: 'Test 1' }, cost: 1 },
        { skill: { id: 'test_2', name: 'Test 2' }, cost: 3 },
        { skill: { id: 'test_3', name: 'Test 3' }, cost: 2 },
        { skill: { id: 'test_4', name: 'Test 4' }, cost: 1 },
    ]

    if (!selected) return <Redirect to='/profile' />;

    return (
        <div id='home'>
            {selected.evolutions.length > 0 &&
                <Evolve classes={selected.evolutions} />
            }
            <Cell area='position'>
                <WorldMap />
            </Cell>
            <Cell area='inventory'>
                <Inventory stacks={selected.inventory} />
            </Cell>
            <Cell area='skills'>
                <Skills skills={skills} />
            </Cell>

            <Buttons>
                <Link to='/profile'><Icon src={`class/${selected.classes[selected.classes.length - 1].id}`} /></Link>

                <ToggleButton id='skills' />
                <ToggleButton id='inventory' src='chest' />
                <ToggleButton id='map' mobileOnly={true} />

            </Buttons>
        </div>
    );
}

export default Home;