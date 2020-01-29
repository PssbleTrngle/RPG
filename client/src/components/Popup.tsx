import React, { ReactNode } from 'react';
import { Component } from "./Component";
import { Page } from './Page';
import { usePopup } from '../pages/Page';

export function Popup(props: { id: string, children?: ReactNode }) {
	const { children, id } = props;
	const { isOpen } = usePopup(id);

	return (
		<div className={`popup ${id} ${isOpen ? 'show' : ''}`}>
			{children}
		</div>
	);
}

export function PopupOpen() {
	const { close } = usePopup();
	return <div onClick={close} className='blocked show' />
}