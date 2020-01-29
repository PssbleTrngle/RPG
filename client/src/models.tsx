/**
 * 	This file contains defines the form
 * 	in which the data is expected to by returned
 * 	by the server
 */

export type time = string;

export interface ITranslated {
	id: string;
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
	id: number;
	x: number;
	y: number;
	participant?: IParticipant;
}

export interface IPosition {
	icon: string;
	area: IArea;
};

export interface IEvolution {
	from: IClass,
	to: IClass,
	level: number,
}

export interface IClass extends ITranslated {
	stage: number;
};

export interface IParticipant {
	id: number;
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

export interface IAccount {
	id: number;
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
	id: number;
	item: IItem;
	amount: number;
	slot: ISlot;
}

export interface IBattle {
	fields: IField[];
}

export interface IProduct {
	id: number;
	stack: IStack;
	price?: number;
	trade?: IStack;
}

export interface IShop {
	id: number;
	name: string;
	products: IProduct[];
	owner?: INPC;
}