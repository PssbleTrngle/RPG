import React from 'react';
import { IStack, ISlot } from '../Models';
import { Icon } from './Icon';
import { Cell } from './Cell';
import { useSubscribe } from '../Api';
import { Collapseable } from '../components/Collapseable';

enum StackState {
    LOCKED = 'locked',
    EMPTY = 'empty',
}
export function InfoStack(props: { state: StackState }) {
    const { state } = props;
    return (
        <div className={`stack info ${state}`}>
            <p>{state}</p>
        </div>
    );
}

export function Stack(stack: IStack) {
    const { item, amount } = stack;
    const { type } = item
    const { ancestors } = type;

    let types = [item.type];
    if (ancestors) ancestors.forEach(t => types.push(t));
    const defining = types.filter(t => t.icon)[0];

    return (
        <div className='stack'>
            <Icon src={`item/${defining ? defining.id : ''}/${item.id}`} />
            <p>{item.name} x{amount}</p>
        </div>
    )

}

export function Bag(props: { stacks: IStack[], slot?: ISlot }) {
    const { stacks, slot } = props;
    const singleton = slot && slot.size === 1;
    const empty = stacks.length === 0;

    if (!singleton && stacks.length === 0) return null;

    return (
        <Cell area={slot?.id}>
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

export function Inventory(props: { stacks: IStack[] }) {
    const slots: ISlot[] = useSubscribe('slot') ?? [];

    const bagFor = (id: ISlot['id']) => {
        const slot = slots.find(s => s.id === id);
        if(!slot) return null;
        const stacks = Object.values(props.stacks).filter(s => id === s.slot.id);
        return <Bag key={slot.id} {...{ stacks, slot }} />
    }

    if (!slots) return null;

    return (
        <Collapseable id='inventory'>
            {bagFor('loot')}

            {['right', 'left'].map((hand, i) =>
                <React.Fragment key={hand}>
                    <Cell area={`${hand}_hand-icon`}>
                        <Icon src='slot/hand' reverse={i > 0} />
                    </Cell>
                    {bagFor(`${hand}_hand`)}
                </React.Fragment>
            )}

            {bagFor('bag')}
        </Collapseable>
    );

}