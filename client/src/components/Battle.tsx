import React from 'react';
import { Component } from './Component';
import { Fields } from './Fields';
import { IField, Point, ISkill, IStack, ITranslated, ICharacter } from '../models';
import { Page } from '../pages/Page';
import { Cell } from './Cell';
import { Bag } from './Inventory';

class BattleWrapper extends Component<{ character: ICharacter }, { skill?: ISkill }> {

    constructor(props: any) {
        super(props);
        this.state = {};
    }

    template() {
        const { character } = this.props;
        const { battle } = character;
        if (!battle) return null;

        const { fields } = battle;
        const { skill } = this.state;

        const skills: ISkill[] = [
            { name: 'pulse', id: 'pulse', aoe: createHex(1) },
            { name: 'heal', id: 'heal' },
            { name: 'rumble', id: 'rumble', aoe: createHex(2) },
        ]

        const aoe = skill ? skill.aoe : undefined;

        return (<div id='battle'>
            <BattleField {...{ fields, aoe }} />
            <Sidebar select={skill => this.setState({ skill })} skills={skills} active={skill} />
            <Cell area='items'>
                <Bag stacks={character.inventory.filter((s: IStack) => s.slot.id === 'bag')} />
            </Cell>
            <Cell area='info' className='center info'>
                {skill && <Info {...skill} />}
            </Cell>
        </div>);
    }

}

class Info extends Component<ITranslated> {

    template() {
        const { name } = this.props;

        return (
            <>
                <h3>{name}</h3>
            </>
        );
    }

}

export class Battle extends Page {

    template() {
        const { account } = this.props;
        const { selected } = account;
        if (!selected) return null;

        return <BattleWrapper character={selected} />

    }

}

class Sidebar extends Component<{ skills: ISkill[], active?: ISkill, select: ((skill: ISkill) => any) }> {

    template() {
        const { skills, active, select } = this.props;

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

}

class BattleField extends Component<{ fields: IField[], aoe?: Point[] }, { hover?: Point }> {

    constructor(props: any) {
        super(props);
        this.state = {};
    }

    select(field: IField) {
    }

    hover(field?: IField) {
        this.setState({ hover: field });
    }

    inAOE(field: IField): boolean {
        const aoe = this.props.aoe || createHex(0);
        const { hover } = this.state;

        if (hover)
            return !!aoe.find(f => field.x === f.x + hover.x && field.y === f.y + hover.y);

        return false;
    }

    renderField(f: IField) {
        const { participant } = f;

        return (
            <div className='hex'>
                {participant &&
                    <p>{participant.name}</p>
                }
            </div>
        );
    }

    template() {
        let { fields } = this.props;

        return (<div className='battlefield'>
            <Fields
                render={f => this.renderField(f)}
                fields={fields}
                onClick={a => this.select(a)}
                onHover={a => this.hover(a)}
                className={field => this.inAOE(field) ? 'hover' : null}
            />
        </div>);
    }

}

const createHex = (r: number): IField[] => {
    const fields = [];
    for (let x = -r; x <= r; x++)
        for (let y = -r; y <= r; y++)
            if (Math.abs(x + y) <= r)
                fields.push({
                    id: `${x}|${y}`,
                    x, y,
                });
    return fields;
};