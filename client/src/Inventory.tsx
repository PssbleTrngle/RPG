import React from 'react';
import { IStack, ISlot, ID } from './models';
import { Icon, Cell } from './Grid';
import { LoadingComponent } from './Connection';
import { Collapseable } from './Page';
import { Component } from "./Component";

enum StackState {
    LOCKED = 'locked',
    EMPTY = 'empty',
}
export class InfoStack extends Component<{state: StackState},{}> {

    template() {
        const {state } = this.props;
        return (
            <div className={`stack info ${state}`}>
                <p>{state}</p>
            </div>
        );
    }

}

export class Stack extends Component<IStack,{}> {

    template() {
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

export class Bag extends Component<{stacks: IStack[], slot?: ISlot},{}> {

    template() {
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

export class Inventory extends LoadingComponent<ISlot[], {stacks: IStack[]},{}> {

    initialState() { return {} };
    model() { return 'slot' }

    bagForName(id: ID) {
        const { result: slots } = this.state;

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

    template() {
        const { result: slots } = this.state;
        const { app } = this.props;

        if(!slots) return null;

        return (
            <Collapseable id='inventory' {...app}>
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