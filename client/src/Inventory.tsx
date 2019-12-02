import React from 'react';
import { IItem, IStack, ISlot, ID } from './models';
import { Icon } from './Grid';

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

        return (
            <>
            {slot && <h3 className='center'>{slot.name}</h3>}
            <div className='bag'>
                {stacks.map(stack => 
                    <Stack key={stack.id} {...stack} />    
                )}
            </div>
            </>
        );
    }

}

export class Inventory extends React.Component<IStack[],{}> {

    render() {
        const stacks = Object.values(this.props);

        const bags = new Map<ID, IStack[]>();
        const slots: ISlot[] = [];

        stacks.forEach(stack => {
            if(!slots.find(s => s.id == stack.slot.id)) {
                bags.set(stack.slot.id, []);
                slots.push(stack.slot);
            }
        })

        stacks.forEach(stack => {
            const bag = bags.get(stack.slot.id) || [];
            bag.push(stack);
        });

        return (
            <>
                {slots.map(slot => 
                    <Bag key={slot.id} slot={slot} stacks={bags.get(slot.id) || []} />    
                )}
            </>
        );
    }

}