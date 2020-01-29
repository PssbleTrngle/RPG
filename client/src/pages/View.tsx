import React, { useState, useRef, useEffect } from 'react';
import { ITranslated } from '../Models';
import { Icon } from '../components/Icon';
import { Link, useParams } from 'react-router-dom';
import { useSubscribe } from '../Api';

type SeachProps<T> = {
    updateValue?: (result: string) => unknown,
    filter: (t: T) => string,
    values: T[],
    setFiltered: React.Dispatch<React.SetStateAction<T[]>>
};
export function Searchbar<T>(props: SeachProps<T>) {
    const { updateValue, filter, values, setFiltered } = props;
    const [value, changeValue] = useState('');
    const element = useRef<HTMLInputElement>(null);

    useEffect(() => {
        element.current?.focus();
        window.addEventListener('keydown', e => {
            if ((e.keyCode === 70 && e.ctrlKey) || e.keyCode === 114) {
                e.preventDefault();
                element.current?.focus();
            }
        })
    });

    const change = (value: string) => {
        if (updateValue) updateValue(value);
        changeValue(value);
        const s = value.toLowerCase();
        const filtered = values.filter(t => filter(t).toLowerCase().includes(s))
        setFiltered(filtered);
    }

    return <input
        ref={element}
        type='text'
        placeholder='Search'
        className='search'
        value={value}
        onChange={e => change(e.target.value)}
    />

}

export function List<T extends ITranslated>(props: { model: string }) {
    const { model } = props;
    const values: T[] = useSubscribe(model) ?? [];
    const [filtered, setFiltered] = useState(values);

    const name = model.charAt(0).toLowerCase() + model.slice(1);

    return (<>
        <h1>{filtered} Entrys for <span className='highlight'>{name}</span></h1>
        <Searchbar filter={v => v.name} {...{ setFiltered, values }} />
        <div className='view-list'>
            {filtered.map(m =>
                <Link key={m.id} to={`${model}/${m.id}`}>
                    <div>
                        <Icon src={`${model}/${m.id}`} />
                        <p>{m.name}</p>
                    </div>
                </Link>
            )}
        </div>
    </>);

}

export function Single<T extends ITranslated>(props: { model: string, id: string }) {
    const { model, id } = props;
    const result = useSubscribe<T>(`${model}/${id}`)

    const name = model.charAt(0).toLowerCase() + model.slice(1);

    return (<>
        <Link to={`/view/${model}`} className='back'>{'<'}{name}</Link>
        {result &&
            <h1>{result.name}</h1>
        }
    </>);

}

function View() {
	const { id, model } = useParams();
	if (model) {
		if (id) return <Single {...{ model, id }} />
		return <List {...{ model }} />;
	}
	return null;
}

export default View;