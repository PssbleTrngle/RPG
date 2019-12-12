import React, { ReactNode } from 'react';
import { AppProps, params } from '../App';

export abstract class Component<P = {},S = {}, SS = {}> extends React.PureComponent<{app?: AppProps} & P,S, SS> implements AppProps {

	action(url: string, params?: params) {
		const { app } = this.props;
		if(app) app.action(url, params);
	}
	
	format(key: string, ...params: string[]): string {
		const { app } = this.props;
		if(app) return app.format(key, ...params);
		return 'NO APP';
    }

    open(popup?: string) {
        const { app } = this.props;
        if(app) app.open(popup);
    }

    isOpen(popup: string): boolean {
        const { app } = this.props;
        return !!app && app.isOpen(popup);
    }

    async setLang(key: string): Promise<boolean> {
        const { app } = this.props;
        if(app) return app.setLang(key);
        return false;
    }

    getLang() {
        const { app } = this.props;
        return app ? app.getLang() : 'en';
    }

	abstract template(): ReactNode;

	private assignProps(node: any) {

		if(node && typeof node === 'object') {
            const { props } = node;
            const fragment = (node.type === React.Fragment);
            const children = React.Children.map(props.children, child => this.assignProps(child));

            if(fragment)
			    return <node.type {...props} >{ children }</node.type>

			return <node.type {...props} app={this} >{ children }</node.type>
		}

		return node;
	}

	render() {
		const rendered = this.template();
		return this.assignProps(rendered);
	}

}