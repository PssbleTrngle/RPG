import React from 'react';

export function Icon(props: { src: string, reverse?: boolean, className?: string }) {
	const { src, reverse, className } = props;

	let icon: string;
	try {
		icon = require(`./img/${src}.svg`);
	} catch (e) {
		try {
			icon = require(`./img/${src}.png`);
		} catch (e) {
			console.warn(`could not find image /img/${src}`)
			icon = require('./img/missing.png');
		}
	}

	return (
		<img title={src} alt={src} className={`icon ${reverse ? 'reverse' : ''} ${className || ''}`} src={icon} />
	)
}