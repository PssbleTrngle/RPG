import React, { ReactNodeArray, ReactElement } from 'react';

export function Cell(props: { area: string, className?: string, children?: ReactNodeArray | ReactElement }) {
	const { area: gridArea, children: content, className } = props;
	if (!content) return null;

	function isArray(c: ReactNodeArray | ReactElement): c is ReactNodeArray {
		return Array.isArray(c);
	}

	if (isArray(content))
		return <div style={{ gridArea }} {...{ className }}>{content}</div>

	else return <content.type style={{ gridArea }} {...content.props}>{content.props.children}</content.type>

}