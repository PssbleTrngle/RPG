import React, { useState, useEffect, useRef } from 'react';
import { IField, IArea } from '../models'
import { Icon } from './Icon';
import { Collapseable } from '../components/Collapseable';
import { useSubscribe } from '../App';

type FieldProps<T extends IField> = {
    onClick?: (f: T) => any,
    render: (f: T) => any,
    onHover?: (f?: T) => any,
    className?: (f: T) => any,
}
function Field<T extends IField>(props: FieldProps<T> & { field: T, size: number }) {
    const { field, size, onClick, render, onHover, className } = props;
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

export function Fields<T extends IField>(props: { fields: T[] } & FieldProps<T>) {
    const { fields, onClick, render, onHover, className } = props;
    const [{ width, height }, resize] = useState({ width: 0, height: 0 });

    const element = useRef(null);
    useEffect(() => {
        window.addEventListener('resize', () => {
            const e = element.current;
            if (e) {
                const { offsetHeight: height, offsetHeight: width } = e;
                resize({ width, height })
            }
        })
    })

    const maxX = 1 + fields.reduce((m, f) => Math.max(Math.abs(f.x + f.y / 2), m), 0)
    const maxY = 1 + fields.reduce((m, f) => Math.max(Math.abs(f.y), m), 0)

    const ySize = height / (maxY * 2);
    const xSize = width / (maxX * 2);
    const size = Math.min(xSize, ySize);

    const p = { size, onClick, onHover, render, className };

    return (
        <div className='fields' ref={element}>
            {fields.map(field =>
                <Field key={field.id || `${field.x} ${field.y}`} field={field} {...p} />
            )}
        </div>
    )
}

export function WorldMap() {
    const [message, setMessage] = useState<string | undefined>();
    const [selected, setSelected] = useState<string | undefined>();
    const areas: IArea[] = useSubscribe('area') ?? [];

    const select = (area?: IArea) => {
        if (!area || area.areas) {
            setMessage(undefined);
            setSelected(area ?.id);
        }
    }

    const hover = (area?: IArea) => {
        if (!area) setMessage(undefined);
        setMessage(area.areas ? `View ${area.name}` : `Travel to ${area.name}`);
    }

    const find = (areas: IArea[], id: IArea['id']): IArea | undefined => {
        for (let a of areas) {
            if (a.id === id) return a;
            else if (a.areas) {
                const f = find(a.areas, id)
                if (f) return f;
            }
        }
        return undefined;
    }

    const selectedAreas = selected
        ? find(areas, selected)?.areas ?? []
        : areas.filter(a => a.areas);

    return (
        <Collapseable id='map'>
            {selected ?
                <button onClick={() => select()} className='back'>Back</button>
                : null}
            {message ?
                <p>{message}</p>
                : null}
            {selectedAreas && <Fields
                fields={selectedAreas}
                onClick={select}
                onHover={hover}
                render={a => <Icon src={`position/area/${a.id}`} />}
            />}
        </Collapseable>
    );

}