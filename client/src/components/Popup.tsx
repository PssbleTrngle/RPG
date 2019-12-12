import React from 'react';
import { Component } from "./Component";
import { Page } from './Page';

export class Popup extends Component<{id: string},{}> {

	template() {
        const { children, id } = this.props;

		return (
			<div className={`popup ${id} ${this.isOpen(id) ? 'show' : ''}`}>
				{children}
			</div>
		);
	}

}

export class PopupOpen extends Component<{blocking: boolean}> {

    template() {
        const { blocking } = this.props;

        return <div onClick={() => this.open()} className={`blocked ${blocking ? 'show' : ''}`} />
    }

}