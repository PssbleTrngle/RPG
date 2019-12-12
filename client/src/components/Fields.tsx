import React from 'react';
import { IField, IArea, ID } from '../models'
import { Icon } from './Icon';
import { LoadingComponent } from "./LoadingComponent";
import { Collapseable, Page } from '../pages/Page';
import { Component } from './Component';

type FieldProps<T extends IField> = {
    onClick?: (f: T) => any,
    render: (f: T) => any,
    onHover?: (f?: T) => any,
    className?: (f: T) => any,
}
class Field<T extends IField> extends Component<FieldProps<T> & { field: T, size: number }, {}> {

    template() {
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
                {render(field)}
            </div>
        );
    }

}

export class Fields<T extends IField> extends React.Component<{ fields: T[] } & FieldProps<T>, { width: number, height: number }> {

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
        if (this.element) {
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
            <div className='fields' ref={e => this.element = e}>
                {fields.map(field =>
                    <Field key={field.id || `${field.x} ${field.y}`} field={field} {...p} />
                )}
            </div>
        )
    }
}

export class WorldMap extends LoadingComponent<IArea[], { page: Page }, { message?: string, selected?: ID }> {

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
        const { result: areas, selected } = this.state;
        if (!areas) return [];

        if (selected) {
            const area = this.find(areas, selected);
            if (area) return area.areas || [];
        }
        return areas.filter(a => a.areas);
    }

    template() {
        const { message, selected } = this.state;
        const { page } = this.props;
        const selectedAreas = this.selectedAreas();

        return (
            <Collapseable page={page} id='map'>
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
            </Collapseable>
        );
    }

}