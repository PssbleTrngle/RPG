import React, { Component } from 'react';

export class Cell extends Component<{area?: any, className?: string},{}> {

	template() {
		const { area, children, className } = this.props;

		return (
			<div className={className} style={area ? { gridArea: area } : {}}>{children}</div>
		)
	}
}

export class Buttons extends Component<{},{}> {

	template() {
        const { children } = this.props;

		return (
			<Cell className='buttons' area='buttons'>
				{children}
			</Cell>
		)
	}

}

export class Icon extends Component<{src: string, reverse?: boolean, className?: string},{}> {

	template() {
		const { src, reverse, className } = this.props;

		let icon: any;
		try {
			icon = require(`./img/${src}.svg`);
		} catch(e) {
			try {
				icon = require(`./img/${src}.png`);
			} catch(e) {
				console.warn(`could not find image /img/${src}`)
				icon = require('./img/missing.png');
			}
		}

		return (
			<img title={src} alt={src} className={`icon ${reverse ? 'reverse' : ''} ${className || ''}`} src={icon} />
		)
	}
}