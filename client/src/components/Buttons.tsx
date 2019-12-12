import React from 'react';
import { Component } from './Component';
import { Cell } from './Cell';

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