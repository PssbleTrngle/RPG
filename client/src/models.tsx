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