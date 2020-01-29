import React from 'react';
import ReactDOM from 'react-dom';
import App from './App';
import { IAccount, ICharacter, IClass, IStack, ISlot, IPosition, ISkill, IStats, IItemType, IArea } from './Models';

const ri = (m: number) => Math.floor(Math.random() * m);
const c = 'abcdefghijklmonpqrtsufvwxyzABCDEFGHIJKLMNOPQRSTUVW'.split('');
const rs = (length: number = 10) => someOf(() => c[ri(c.length)], length).join('')

function someOf<T>(provider: (i: number) => T, amount: number): T[] {
  return new Array(amount).fill(null).map((_, i) => provider(i));
}

export function fakeClass(stage: number): IClass {
  return {
    id: rs(),
    stage
  }
}

export function fakeItemType(isAncestor = false): IItemType {
  return {
    id: rs(),
    icon: Math.random() < 0.8,
    ancestors: isAncestor ? [] : someOf(() => fakeItemType(true), ri(3) + 2)
  }
}

export function fakeStack(slot: ISlot): IStack {
  const stackable = Math.random() < 0.3;
  return {
    id: ri(100000),
    amount: stackable ? ri(5) + 5 : 1,
    item: {
      id: rs(),
      type: fakeItemType(),
    },
    slot
  }
}

export function fakeArea(d = 0): IArea {
  const r = d < 3 && Math.random() < 0.5;

  return {
    id: rs(),
    x: ri(5) - 10,
    y: ri(5) - 10,
    areas: d ? someOf(() => fakeArea(d - 1), ri(3) + 1) : undefined,
  }
}

export function fakePosition(): IPosition {
  return {
    icon: rs(),
    area: fakeArea()
  }
}

export function fakeSkill(): ISkill {
  return {
    id: rs(),
  }
}

export function fakeStats(): IStats {
  const stats: IStats = {};
  someOf(rs, 5).forEach(s => stats[s] = ri(80) + 10);
  return stats;
}

export function fakeCharacter(): ICharacter {

  const maxHealth = ri(80) + 100;
  const requiredXp = ri(80) + 100;

  const battle = undefined;

  const classes = someOf(fakeClass, 3);
  const evolutions = someOf(() => fakeClass(3), ri(3));

  const side = Math.random() < 0.5 ? 'right' : 'left';
  const inventory = [
    ...someOf(() => fakeStack({ id: `${side}_hand`, size: 1 }), 1),
    ...someOf(() => fakeStack({ id: 'bag', size: 1 }), 10 + ri(15)),
    ...someOf(() => fakeStack({ id: 'loot', size: 1 }), ri(10)),
  ];

  const position = fakePosition();
  const skills = someOf(fakeSkill, ri(5) + 3);
  const stats = fakeStats();

  return {
    id: ri(1000000),
    name: 'Some Character',
    birth: new Date(),
    classes,
    evolutions,
    health: ri(maxHealth),
    inventory,
    level: ri(40),
    maxHealth,
    position,
    requiredXp,
    skills,
    stats,
    xp: ri(requiredXp - 10),
    battle
  }
}

export function fakeAccount(): IAccount {
  const characters = someOf(fakeCharacter, 5);

  const selected = characters[ri(characters.length)];

  return {
    id: ri(1000000),
    username: 'Test',
    characters,
    selected
  }
}

it('renders without crashing', () => {
  const div = document.createElement('div');
  ReactDOM.render(<App />, div);
  ReactDOM.unmountComponentAtNode(div);
});

it('has a server connection', () => {



});