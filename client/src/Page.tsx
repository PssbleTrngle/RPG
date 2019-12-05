import React, { ReactNode } from 'react';
import AnimateHeight from 'react-animate-height';
import { Account } from './models';
import { Icon } from './Grid';
import { Component } from "./Component";

export abstract class Page extends Component<{account: Account}, {collapsed: Set<string>}> {

    constructor(props: any) {
        super(props);
        this.state = {collapsed: new Set<string>()};
    }

    toggle(id: string) {
        const collapsed = new Set(this.state.collapsed);
        
        if(this.isCollapsed(id))
            collapsed.delete(id);
        else
            collapsed.add(id);

        this.setState({ collapsed });
    }

    isCollapsed(id: string): boolean {
        return Array.from(this.state.collapsed.values()).includes(id);
    }

}

export class Collapseable extends Component<{hidden?: boolean, id: string, className?: string, page?: Page},{}> {

    template() {
        const { page, id, children, className, hidden } = this.props;
        const toggled = page && page.isCollapsed(id) !== (hidden || false);

        return (   
            <AnimateHeight
                duration={300}
                height={toggled ? 0 : 'auto'}
                contentClassName={`collapseable ${className || id} ${toggled ? 'hidden' : ''}`}>
                {children}
            </AnimateHeight>
        );
    }

}

export class ToggleButton extends Component<{page:Page, id: string, src?: string, mobileOnly?: boolean},{}> {

    template() {
        const { id, mobileOnly, src, page } = this.props;

        return (
            <button className={mobileOnly ? 'mobile' : ''} onClick={() => page.toggle(id)}>
                <Icon src={`icon/${src || id}`} />
            </button>
        );
    }

}