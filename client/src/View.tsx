import React from 'react';
import { LoadingComponent } from "./Connection";
import { ITranslated } from './models';
import { Icon } from './Grid';
import { Link } from 'react-router-dom';
import { Component } from "./Component";

type SeachProps<T> = {
    change?: (result: string) => any,
    filter: (t: T, search: string) => boolean,
    parent: React.Component<any,{filtered?: T[], result?: T[]}>
};
export class Searchbar<T> extends Component<SeachProps<T>,{value?: string}> {

    element: HTMLElement | null = null;

    constructor(props: any) {
        super(props);
        this.state = {};
    }

    focus() {
        if(this.element) this.element.focus();
    }

    componentDidMount() {
        this.focus();
        window.addEventListener('keydown', e => {
            if((e.keyCode === 70 && e.ctrlKey) || e.keyCode === 114) {
                e.preventDefault();
                this.focus();
            }
        })
    }

    change(value: string) {
        const { change, filter, parent } = this.props;
        const { result } = parent.state;
        
        if(change) change(value);
        if(result) {
            const filtered = value ? result.filter(t => filter(t, value.toLowerCase())) : undefined;
            parent.setState({ filtered });
        }

        this.setState({ value })
    }

    template() {
        const { value } = this.state;

        return <input
            ref={e => { this.element = e }}
            type='text'
            placeholder='Search'
            className='search'
            value={value || ''}
            onChange={e => this.change(e.target.value)}
        />
    }

}

export class List<T extends ITranslated> extends LoadingComponent<T[], {model: string},{filtered?: T[]}> {

    interval() { return false }
    model() { return this.props.model }
    initialState() { return {} }

    template() {
        const { model } = this.props;
        const { result, filtered } = this.state;
        const models = filtered || result;

        const name = model.charAt(0).toLowerCase() + model.slice(1);

        return (<>
            <h1>{models && models.length} Entrys for <span className='highlight'>{name}</span></h1>
            <Searchbar parent={this} filter={(v, s) => v.name.toLowerCase().includes(s) } />
            <div className='view-list'>
                { models && models.map(m =>
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

}

export class View<T extends ITranslated> extends LoadingComponent<T, {model: string, id: string},{}> {

    id() { return this.props.id }
    model() { return this.props.model }
    initialState() { return {} }

    template() {
        const { model } = this.props;
        const { result } = this.state;

        if(!result) return null;

        const name = model.charAt(0).toLowerCase() + model.slice(1);

        return (<>
            <Link to={`/view/${model}`} className='back'>{'<'}{name}</Link>
            <h1>{result.name}</h1>
        </>);
    }

}