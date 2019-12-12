import React from 'react';
import { Component } from './Component';

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