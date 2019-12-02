import React from 'react';
import { IField, ISkill, Point, IArea, ID } from './models'
import { Icon } from './Grid';
import { LoadingComponent } from "./Connection";

type FieldProps<T extends IField> = {
    onClick?: (f: T) => any,
    render?: (f: T) => any,
    onHover?: (f?: T) => any,
    className?: (f: T) => any,
}
class Field<T extends IField> extends React.Component<FieldProps<T> & { field: T, size: number }, {}> {

    render() {
        const { field, size, onClick, render, onHover, className } = this.props;
        const { x, y } = field;
        const left = (x + y / 2) * 110;
        const top = y * 90;

        const c = className ? className(field) : null;

        return (
            <div
                onClick={onClick && (() => onClick(field))}
                onMouseEnter={onHover && (() => onHover(field))}
                onMouseLeave={onHover && (() => onHover())}
                className={`field ${c ? c : ''}`}
                style={{
                    transform: `translate(${left - 50}%, ${top - 50}%)`,
                    width: size, height: size,
                    zIndex: y + 100,
                }}>
                {render ? render(field) : <div className='hex' />}
            </div>
        );
    }

}

export class Fields<T extends IField> extends React.Component<{fields: T[]} & FieldProps<T>,{width: number, height: number}> {

    element: HTMLElement | null = null;

    constructor(props: any) {
        super(props);
        this.state = { height: 0, width: 0 };
    }

    componentDidMount() {
        this.updateSize();
        window.addEventListener('resize', () => this.updateSize());
    }

    updateSize() {
        if(this.element) {
            const height = this.element.offsetHeight;
            const width = this.element.offsetWidth;
            this.setState({ height, width });
        }
    }

    render() {
        const { fields, onClick, render, onHover, className } = this.props;
        const { height, width } = this.state;

        const maxX = 1 + fields.reduce((m, f) => Math.max(Math.abs(f.x + f.y / 2), m), 0)
        const maxY = 1 + fields.reduce((m, f) => Math.max(Math.abs(f.y), m), 0)

        const ySize = height / (maxY * 2);
        const xSize = width / (maxX * 2);
        const size = Math.min(xSize, ySize);

        const p = { size, onClick, onHover, render, className };

        return (
            <div className='fields' ref={e => { this.element = e; } }>
                {fields.map(field =>
                    <Field key={field.id || `${field.x} ${field.y}`} field={field} {...p} />
                )}
            </div>
        )
    }
}

class Battle extends React.Component<{fields: IField[]},{skill?: ISkill}> {

    constructor(props: any) {
        super(props);
        this.state = {};
    }

    render() {
        const { fields } = this.props;
        const { skill } = this.state;

        const skills: ISkill[] = [
            { name: 'pulse', id: 'pulse', aoe: createHex(1) },
            { name: 'heal', id: 'heal' },
            { name: 'rumble', id: 'rumble', aoe: createHex(2) },
        ]

        const aoe = skill ? skill.aoe : undefined;

        const props = { fields, aoe };

        return (<div id='battle'>
            <BattleField {...props} />
            <Sidebar select={skill => this.setState({ skill })} skills={skills} />
        </div>);
    }

}

class Sidebar extends React.Component<{skills: ISkill[], select: ((skill: ISkill) => any)}> {

    render() {
        const { skills, select } = this.props;

        return (
            <div className='sidebar'>
                {skills.map(skill =>
                    <p
                        onClick={() => select(skill)}
                        className='option'
                        key={skill.id}>{skill.name}</p>
                )}
            </div>
        )
    }

}

class BattleField extends React.Component<{fields: IField[], aoe?: Point[]},{hover?: Point}> {

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
            for (let f of aoe)
                if (field.x === f.x + hover.x && field.y === f.y + hover.y)
                    return true;

        return false;
    }

    render() {
        let { fields, aoe } = this.props;
        const { hover } = this.state;

        return (<div className='battlefield'>
            <Fields
                fields={fields}
                onClick={a => this.select(a)}
                onHover={a => this.hover(a)}
                className={field => this.inAOE(field) ? 'hover' : null}
            />
        </div>);
    }

}

export class WorldMap extends LoadingComponent<IArea[], {},{message?: string, selected?: ID}> {

    model() { return 'area' }
    initialState() { return {} }

    select(area?: IArea) {
        if (!area || area.areas)
            this.setState({ message: undefined, selected: area ? area.id : undefined })
    }

    hover(area?: IArea) {
        const message = area ?
            (area.areas ? `View ${area.name}` : `Travel to ${area.name}`)
            : undefined;
        this.setState({ message })
    }

    find(areas: IArea[], id: ID): IArea | undefined {
        for (let a of areas) {
            if (a.id === id) return a;
            else if (a.areas) {
                const f = this.find(a.areas, id)
                if (f) return f;
            }
        }
        return undefined;
    }

    selectedAreas() {
        const { result, selected } = this.state;
        if(!result) return [];

        if(selected) {
            const area = this.find(result, selected);
            if(area) return area.areas || [];
        }
        return result.filter(a => a.areas);
    }

    render() {
        const { message, selected } = this.state;
        const selectedAreas = this.selectedAreas();

        return (<div className='map'>
            {selected ?
                <button onClick={() => this.select()} className='back'>Back</button>
                : null}
            {message ?
                <p>{message}</p>
                : null}
            {selectedAreas && <Fields
                fields={selectedAreas}
                onClick={a => this.select(a)}
                onHover={a => this.hover(a)}
                render={a => <Icon src={`position/area/${a.id}`} />}
            />}
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