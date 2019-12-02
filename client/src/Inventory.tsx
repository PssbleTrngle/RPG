import React from 'react';
import { IItem, IStack, ISlot, ID } from './models';
import { Icon, Cell } from './Grid';
import { LoadingComponent } from './Connection';
import { JSXElement } from '@babel/types';
import { Collapseable, Page } from './Page';

enum StackState {
    LOCKED = 'locked',
    EMPTY = 'empty',
}
export class InfoStack extends React.Component<{state: StackState},{}> {

    render() {
        const {state } = this.props;
        return (
            <div className={`stack info ${state}`}>
                <p>{state}</p>
            </div>
        );
    }

}

export class Stack extends React.Component<IStack,{}> {

    render() {
        const { item, amount } = this.props;
        const { type } = item
        const { ancestors } = type;

        let types = [item.type];
        if(ancestors) ancestors.forEach(t => types.push(t));
        const defining = types.filter(t => t.icon)[0];

        return (
            <div className='stack'>
                <Icon src={`item/${defining ? defining.id : ''}/${item.id}`} />
                <p>{item.name} x{amount}</p>
            </div>
        )
    }

}

export class Bag extends React.Component<{stacks: IStack[], slot?: ISlot},{}> {

    render() {
        const { stacks, slot } = this.props;
        const singleton = slot && slot.size === 1;
        const empty = stacks.length === 0;

        if(!singleton && stacks.length === 0) return null;

        return (
            <Cell area={slot ? slot.id : undefined}>
                {slot && !singleton && <h3 className='center'>{slot.name}</h3>}
                <div className='bag'>
                    {stacks.map(stack => 
                        <Stack key={stack.id} {...stack} />    
                    )}
                    {singleton && empty &&
                        <InfoStack state={StackState.EMPTY} /> 
                    }
                </div>
            </Cell>
        );
    }

}

export class Inventory extends LoadingComponent<ISlot[], {stacks: IStack[], page?: Page},{}> {

    initialState() { return {} };
    model() { return 'slot' }

    bagForName(id: ID) {
        const slots = this.state.result;

        if(slots) {
            const slot = slots.find(s => s.id === id);
            if(slot)
                return this.bagFor(slot);
            else {
                console.warn(`Slot '${id} not found'`)
                return null
            }
        }
    }

    bagFor(slot: ISlot): any {
        const stacks = Object.values(this.props.stacks).filter(s => slot.id === s.slot.id);
        return <Bag key={slot.id} {...{stacks, slot}} />
    }

    render() {
        const slots = this.state.result;
        const { page } = this.props;

        if(!slots) return null;

        return (
            <Collapseable id='inventory' {...{ page }}>
                {this.bagForName('loot')}

                {['right', 'left'].map((hand, i) =>
                    <React.Fragment key={hand}>
                    <Cell area={`${hand}_hand-icon`}>
                        <Icon src='slot/hand' reverse={i > 0} />
                    </Cell>
                    {this.bagForName(`${hand}_hand`)}
                    </React.Fragment>
                )}

                {this.bagForName('bag')}
            </Collapseable>
        );
    }

}