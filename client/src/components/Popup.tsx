import React, { ReactNode, useContext } from 'react';

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

export const PopupContext = React.createContext<{
	popup: string | undefined,
	setPopup(id?: string): void,
}>({ popup: undefined, setPopup: () => { } });

export function usePopup(id?: string) {
	const { popup, setPopup } = useContext(PopupContext);
	return {
		isOpen: popup === id,
		open: id ? () => setPopup(id) : () => { },
		close: () => setPopup()
	}
}