import React from 'react';

export class Cell extends React.Component<{area: string, className?: string},{}> {

	render() {
		const { area, children, className } = this.props;

		return (
			<div className={className} style={{ gridArea: area }}>{children}</div>
		)
	}
}

export class Buttons extends React.Component<{},{}> {

	render() {
        const { children } = this.props;

		return (
			<Cell className='buttons' area='buttons'>
				{children}
			</Cell>
		)
	}

}

export class Icon extends React.Component<{src: string},{}> {

	render() {
		const { src } = this.props;

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
			<img title={src} alt={src} className='icon' src={icon} />
		)
	}
}