export type time = string;

interface Translated {
	id: string;
	name: string;
}

export interface Stats {
	[key: string]: number;
}

export interface Position {
	icon: string;
};

export interface Skill extends Translated {
};

export interface Class extends Translated {
};

export interface Participant {
	id: number;
	name: string;
	health: number;
	maxHealth: number;
	stats: Stats;
	skills: Skill[];
}

export interface Character extends Participant {
	birth: time;
	position: Position;
	xp: number;
	requiredXp: number;
	classes: Class[];
}

export interface Account {
	id: number;
	username: string;
	selected?: Character;
	characters: Character[];
};