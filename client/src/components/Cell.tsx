import React from 'react';
import { Component } from './Component';

export class Cell extends Component<{area?: any, className?: string},{}> {

	template() {
		const { area, children, className } = this.props;

		return (
			<div className={className} style={area ? { gridArea: area } : {}}>{children}</div>
		)
	}
}