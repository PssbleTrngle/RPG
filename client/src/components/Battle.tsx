import React, { useState } from 'react';
import { Fields } from './Fields';
import { IField, Point, ISkill, IStack, ITranslated, ICharacter } from '../Models';
import { Cell } from './Cell';
import { Bag } from './Inventory';
import { useAccount } from '../Api';

function Battle() {
    const [active, select] = useState<ISkill | undefined>();
    const { selected } = useAccount();
    if (!selected?.battle) return null;

    const { fields } = selected.battle;

    const skills: ISkill[] = [
        { name: 'pulse', id: 'pulse', aoe: createHex(1) },
        { name: 'heal', id: 'heal' },
        { name: 'rumble', id: 'rumble', aoe: createHex(2) },
    ]

    return (<div id='battle'>
        <Battlefield aoe={active?.aoe} {...{ fields }} />
        <Sidebar {...{ select, skills, active }} />
        <Cell area='items'>
            <Bag stacks={selected.inventory.filter((s: IStack) => s.slot.id === 'bag')} />
        </Cell>
        <Cell area='info' className='center info'>
            {active && <Info {...active} />}
        </Cell>
    </div>);

}

function Info(props: ITranslated) {
    const { name } = props;

    return (
        <h3>{name}</h3>
    );

}

function Sidebar(props: { skills: ISkill[], active?: ISkill, select: ((skill: ISkill) => any) }) {
    const { skills, active, select } = props;

    return (
        <div className='sidebar'>
            {skills.map(skill =>
                <p
                    onClick={() => select(skill)}
                    className={`option ${active && active.id === skill.id ? 'active' : ''}`}
                    key={skill.id}>
                    {skill.name}
                </p>
            )}
        </div>
    )

}

function Battlefield(props: { fields: IField[], aoe?: Point[] }) {
    const [hovered, hover] = useState<IField | undefined>();
    const { fields } = props;

    const inAOE = (field: IField) => {
        const aoe = props.aoe ?? createHex(0);
        return hovered && !!aoe.find(f => field.x === f.x + hovered.x && field.y === f.y + hovered.y);
    }

    const renderField = (f: IField) => {
        const { participant } = f;

        return (
            <div className='hex'>
                {participant &&
                    <p>{participant.name}</p>
                }
            </div>
        );
    }

    return (<div className='battlefield'>
        <Fields
            render={f => renderField(f)}
            fields={fields}
            onClick={() => { }}
            onHover={hover}
            className={field => inAOE(field) ? 'hover' : null}
        />
    </div>);

}

const createHex = (r: number): IField[] => {
    const fields: IField[] = [];
    let i = 0;
    for (let x = -r; x <= r; x++)
        for (let y = -r; y <= r; y++)
            if (Math.abs(x + y) <= r)
                fields.push({
                    id: i++,
                    x, y,
                });
    return fields;
};

export default Battle;