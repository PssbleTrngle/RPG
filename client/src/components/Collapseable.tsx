import React, { ReactNode, useState, useContext } from 'react';
import AnimateHeight from 'react-animate-height';
import { Icon } from './Icon';

export function Collapseable(props: { hidden?: boolean, id: string, className?: string, children?: ReactNode }) {
    const { id, children, className, hidden: h } = props;
    const [hidden] = useCollapse(id)

    return (
        <AnimateHeight
            duration={300}
            height={hidden ? 0 : 'auto'}
            contentClassName={`collapseable ${className || id} ${hidden ? 'hidden' : ''}`} >
            {children}
        </AnimateHeight >
    );

}

export function ToggleButton(props: { id: string, src?: string, mobileOnly?: boolean }) {
    const { id, mobileOnly, src } = props;
    const [_, toggle] = useCollapse(id);

    return (
        <button className={mobileOnly ? 'mobile' : ''} onClick={toggle}>
            <Icon src={`icon/${src || id}`} />
        </button>
    );
}

export const CollapsedContext = React.createContext<{
	collapsed: Set<string>,
	setCollapsed(set: Set<string>): void,
}>({
	collapsed: new Set(),
	setCollapsed: () => { }
});

export function useCollapse(id: string) {
	const { collapsed, setCollapsed } = useContext(CollapsedContext);
	const toggle = (id: string) => {
		const set = new Set(collapsed);
		set.delete(id)
		setCollapsed(set);
	}

	return [
		collapsed.has(id),
		() => toggle(id),
	] as [boolean, () => void];

}