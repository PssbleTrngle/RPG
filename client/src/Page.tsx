import React from 'react';
import AnimateHeight from 'react-animate-height';
import { Account } from './models';
import { Icon } from './Grid';
import App, { IApp } from './App';

type collapseable = string;
export abstract class Page extends React.Component<{account: Account, app: App}, {collapsed: Set<collapseable>}> {

    constructor(props: any) {
        super(props);
        this.state = {collapsed: new Set<collapseable>()};
    }

    app(): IApp {
        const { app } = this.props;
        return {action: app.action, page: this};
    }

    toggle(id: collapseable) {
        const collapsed = new Set(this.state.collapsed);
        
        if(this.collapsed(id))
            collapsed.delete(id);
        else
            collapsed.add(id);

        this.setState({ collapsed });
    }

    collapsed(id: collapseable): boolean {
        return Array.from(this.state.collapsed.values()).includes(id);
    }

}

export class Collapseable extends React.Component<{hidden?: boolean, id: string, className?: string, page?: Page},{}> {

    render() {
        const { page, id, children, className, hidden } = this.props;
        const toggled = page && page.collapsed(id) !== (hidden || false);

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

export class ToggleButton extends React.Component<{page:Page, id: string, src?: string, mobileOnly?: boolean},{}> {

    render() {
        const { id, mobileOnly, src, page } = this.props;

        return (
            <button className={mobileOnly ? 'mobile' : ''} onClick={() => page.toggle(id)}>
                <Icon src={`icon/${src || id}`} />
            </button>
        );
    }

}