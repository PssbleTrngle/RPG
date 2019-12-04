/**
 * 	This file contains defines the form
 * 	in which the data is expected to by returned
 * 	by the server
 */

export type time = string;
export type ID = string | number;

export interface ITranslated {
	id: ID;
	name: string;
}

export interface Point {
	x: number;
	y: number;
}

export interface IStats {
	[key: string]: number;
}

export interface IArea extends ITranslated {
	areas?: IArea[];
	x: number;
	y: number;
}

export interface ISkill extends ITranslated {
	aoe?: Point[]
}

export interface IField {
	id: ID;
	x: number;
	y: number;
}

export interface IPosition {
	icon: string;
	area: IArea;
};

export interface IClass extends ITranslated {
};

export interface IParticipant {
	id: ID;
	name: string;
	health: number;
	maxHealth: number;
	stats: IStats;
	skills: ISkill[];
	battle?: IBattle;
}

export interface INPC extends ITranslated {
}

export interface IEnemy extends IParticipant {
	npc: INPC;
}

export interface ICharacter extends IParticipant {
	birth: time;
	position: IPosition;
	xp: number;
	requiredXp: number;
	level: number;
	classes: IClass[];
	evolutions: IClass[];
	inventory: IStack[];
}

export interface Account {
	id: ID;
	username: string;
	selected?: ICharacter;
	characters: ICharacter[];
};

export interface ISlot extends ITranslated {
	size: number;
}

export interface IItemType extends ITranslated {
	ancestors?: IItemType[];
	icon: boolean;
}

export interface IItem extends ITranslated {
	type: IItemType;
}

export interface IStack {
	id: ID;
	item: IItem;
	amount: number;
	slot: ISlot;
}

export interface IBattle {
	fields: IField[];
	participant: IParticipant[];
}

export interface IProduct {
	id: ID;
	stack: IStack;
	price?: number;
	trade?: IStack;
}

export interface IShop {
	id: ID;
	name: string;
	products: IProduct[];
	owner?: INPC;
}