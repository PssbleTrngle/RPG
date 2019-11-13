export type time = string;

export type Position = any;
export type Participant = any;
export type Skill = any;
export type Class = any;

export type Character = {
	id: number,
	name: string,
	birth: time,
	position: Position,
	participant: Participant,
	xp: number,
	requiredXp: number,
	skills: Skill[],
	classes: Class[],
}

export type Account = {
	id: number,
	username: string,
	selected?: Character,
	characters: Character[],
};

export default Account;