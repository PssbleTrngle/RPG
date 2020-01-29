import React, { ReactNode } from 'react';
import { Cell } from './Cell';

export function Buttons(props: { children?: ReactNode }) {
	return (
		<Cell className='buttons' area='buttons'>
			{props.children}
		</Cell>
	)
}