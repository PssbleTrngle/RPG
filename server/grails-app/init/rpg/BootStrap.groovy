package rpg

class BootStrap {

    def init = { servletContext ->

        if(Stats.count() == 0) Stats.withTransaction {
            new Stats(wisdom: 1, strength: 1, resistance: 1, agility: 1, luck: 1).save()
        }

        Stats placeholder = Stats.first()
        if(Clazz.count() == 0) Clazz.withTransaction {
            new Clazz(id: 'apprentice', stats: placeholder).save(failOnError: true)
            new Clazz(id: 'traveller', stats: placeholder).save()
            new Clazz(id: 'wild', stats: placeholder).save()
            new Clazz(id: 'fighter', stats: placeholder).save()
            new Clazz(id: 'psychic', stats: placeholder).save()
			            
            new Clazz(id: 'druid', stats: placeholder).save()
			new Clazz(id: 'mage', stats: placeholder).save()
			new Clazz(id: 'alchemist', stats: placeholder).save()
			new Clazz(id: 'rogue', stats: placeholder).save()
			new Clazz(id: 'barde', stats: placeholder).save()
			new Clazz(id: 'merchant', stats: placeholder).save()
			new Clazz(id: 'ranger', stats: placeholder).save()
			new Clazz(id: 'hermit', stats: placeholder).save()
			new Clazz(id: 'duelist', stats: placeholder).save()
			new Clazz(id: 'warrior', stats: placeholder).save()
			new Clazz(id: 'focused', stats: placeholder).save()
			new Clazz(id: 'priest', stats: placeholder).save()
			new Clazz(id: 'medium', stats: placeholder).save()
			new Clazz(id: 'telepath', stats: placeholder).save()
			new Clazz(id: 'shaman', stats: placeholder).save()
			new Clazz(id: 'necromancer', stats: placeholder).save()
			new Clazz(id: 'wizard', stats: placeholder).save()
			new Clazz(id: 'elementalist', stats: placeholder).save()
			new Clazz(id: 'infused', stats: placeholder).save()
			new Clazz(id: 'ritualist', stats: placeholder).save()
			new Clazz(id: 'astromancer', stats: placeholder).save()
			new Clazz(id: 'warlock', stats: placeholder).save()
			new Clazz(id: 'illusionist', stats: placeholder).save()
			new Clazz(id: 'assasin', stats: placeholder).save()
            new Clazz(id: 'derwish', stats: placeholder).save()
            new Clazz(id: 'bandit', stats: placeholder).save()
            new Clazz(id: 'inventor', stats: placeholder).save()
            new Clazz(id: 'fool', stats: placeholder).save()
            new Clazz(id: 'performer', stats: placeholder).save()
			new Clazz(id: 'charmer', stats: placeholder).save()
			new Clazz(id: 'artificer', stats: placeholder).save()
            new Clazz(id: 'tamer', stats: placeholder).save()
			new Clazz(id: 'hunter', stats: placeholder).save()
			new Clazz(id: 'monk', stats: placeholder).save()
			new Clazz(id: 'rebel', stats: placeholder).save()
			new Clazz(id: 'hero', stats: placeholder).save()
			new Clazz(id: 'knight', stats: placeholder).save()
			new Clazz(id: 'berserk', stats: placeholder).save()
			new Clazz(id: 'mercenary', stats: placeholder).save()
            new Clazz(id: 'blockade', stats: placeholder).save()
			new Clazz(id: 'cleric', stats: placeholder).save()
			new Clazz(id: 'thaumaturge', stats: placeholder).save()
            new Clazz(id: 'temporal', stats: placeholder).save()

            new Clazz(id: 'possessed', stats: placeholder).save()
            new Clazz(id: 'sage', stats: placeholder).save()
            new Clazz(id: 'beast', stats: placeholder).save()
            new Clazz(id: 'fallen', stats: placeholder).save()
            new Clazz(id: 'guardian', stats: placeholder).save()
            new Clazz(id: 'chosen', stats: placeholder).save()
            new Clazz(id: 'insane', stats: placeholder).save()
            new Clazz(id: 'narrator', stats: placeholder).save()
            new Clazz(id: 'imposter', stats: placeholder).save()
            new Clazz(id: 'king', stats: placeholder).save()
            new Clazz(id: 'spirit', stats: placeholder).save()
            new Clazz(id: 'touched', stats: placeholder).save()
            new Clazz(id: 'oracle', stats: placeholder).save()
            new Clazz(id: 'predator', stats: placeholder).save()
            new Clazz(id: 'creator', stats: placeholder).save()
            new Clazz(id: 'corrupted', stats: placeholder).save()
            new Clazz(id: 'conquerer', stats: placeholder).save()

			Evolution.createEvolutions('alchemist', 10, 'elementalist', 'ritualist', 'infused')
			Evolution.createEvolutions('apprentice', 10, 'mage', 'druid', 'alchemist')
			Evolution.createEvolutions('astromancer', 10, 'touched')
			Evolution.createEvolutions('bandit', 10, 'conquerer')
			Evolution.createEvolutions('barde', 10, 'performer', 'charmer', 'fool')
			Evolution.createEvolutions('berserk', 10, 'possessed')
			Evolution.createEvolutions('blockade', 10, 'guardian')
			Evolution.createEvolutions('charmer', 10, 'king')
			Evolution.createEvolutions('cleric', 10, 'touched')
			Evolution.createEvolutions('druid', 10, 'shaman', 'necromancer', 'astromancer')
			Evolution.createEvolutions('duelist', 10, 'rebel', 'hero', 'knight')
			Evolution.createEvolutions('elementalist', 10, 'spirit')
			Evolution.createEvolutions('fighter', 10, 'warrior', 'duelist', 'focused')
			Evolution.createEvolutions('focused', 10, 'blockade', 'derwish', 'monk')
			Evolution.createEvolutions('fool', 10, 'narrator', 'insane')
			Evolution.createEvolutions('hermit', 10, 'bandit', 'shaman', 'monk')
			Evolution.createEvolutions('hero', 10, 'chosen', 'corrupted')
			Evolution.createEvolutions('hunter', 10, 'predator')
			Evolution.createEvolutions('illusionist', 10, 'narrator')
			Evolution.createEvolutions('infused', 10, 'beast')
			Evolution.createEvolutions('inventor', 10, 'creator')
			Evolution.createEvolutions('knight', 10, 'guardian', 'fallen')
			Evolution.createEvolutions('mage', 10, 'wizard', 'elementalist', 'warlock')
			Evolution.createEvolutions('medium', 10, 'charmer', 'thaumaturge', 'necromancer')
			Evolution.createEvolutions('merchant', 10, 'artificer', 'inventor')
			Evolution.createEvolutions('monk', 10, 'sage')
			Evolution.createEvolutions('necromancer', 10, 'creator')
			Evolution.createEvolutions('performer', 10, 'imposter')
			Evolution.createEvolutions('priest', 10, 'cleric', 'ritualist')
			Evolution.createEvolutions('psychic', 10, 'telepath', 'medium', 'priest')
			Evolution.createEvolutions('ranger', 10, 'hunter', 'tamer', 'assasin')
			Evolution.createEvolutions('rebel', 10, 'king')
			Evolution.createEvolutions('ritualist', 10, 'corrupted', 'possessed')
			Evolution.createEvolutions('rogue', 10, 'bandit', 'assasin', 'derwish')
			Evolution.createEvolutions('shaman', 10, 'guardian')
			Evolution.createEvolutions('tamer', 10, 'beast')
			Evolution.createEvolutions('telepath', 10, 'temporal', 'wizard', 'illusionist')
			Evolution.createEvolutions('temporal', 10, 'oracle')
			Evolution.createEvolutions('thaumaturge', 10, 'imposter')
			Evolution.createEvolutions('traveller', 10, 'barde', 'merchant', 'rogue')
			Evolution.createEvolutions('warlock', 10, 'conquerer')
			Evolution.createEvolutions('warrior', 10, 'mercenary', 'berserk', 'cleric')
			Evolution.createEvolutions('wild', 10, 'warrior', 'ranger', 'hermit')
			Evolution.createEvolutions('wizard', 10, 'sage')
        }

        if(Area.count() == 0) Area.withTransaction {
            new Area(id: 'battlefield', level: 40, mapX: -4, mapY: 1, children: [
                    new Area(id: 'origin', level: 60, mapX: 0, mapY: 0),
            ]).save(failOnError: true)

            new Area(id: 'forest', level: 0, mapX: 1, mapY: -2, children: [
                    new Area(id: 'pond', level: 0, mapX: -1, mapY: 3),
                    new Area(id: 'oak', level: 5, mapX: -2, mapY: 2),
            ]).save()

            new Area(id: 'plains', level: 0, mapX: 0, mapY: 0, children: [
                    new Area(id: 'tavern', level: 2, mapX: 2, mapY: 1),
            ]).save()
        }

        if(Rarity.count() == 0) Rarity.withTransaction {
            new Rarity(id: 'rusty', color: '8a4722').save()
            new Rarity(id: 'sharp', color: 'a18f85').save()
            new Rarity(id: 'royal', color: 'ba7713').save()
        }

        if(ItemType.count() == 0) ItemType.withTransaction {
            new ItemType(id: 'object', stackable: true, children: [
                    new ItemType(id: 'potion', stackable: true, hasIcon: true),
                    new ItemType(id: 'armor', stackable: false, hasIcon: true),
                    new ItemType(id: 'weapon', stackable: false, hasIcon: true, children: [
                            new ItemType(id: 'shield'),
                            new ItemType(id: 'blade'),
                            new ItemType(id: 'dagger'),
                            new ItemType(id: 'maze'),
                            new ItemType(id: 'club'),
                            new ItemType(id: 'two_handed', children: [
                                new ItemType(id: 'hammer'),
                                new ItemType(id: 'long_sword'),
                            ]),
                    ]),
            ]).save(failOnError: true)
        }

        if(Item.count() == 0) Item.withTransaction {
            new Item(id: 'health_potion', type: ItemType.findById('potion')).save(failOnError: true)
            new Item(id: 'sleep_potion', type: ItemType.findById('potion')).save()
            new Item(id: 'poison_potion', type: ItemType.findById('potion')).save()
        }

        if(Slot.count() == 0) Slot.withTransaction {
            new Slot(id: 'loot', space: 20).save(failOnError: true)
            new Slot(id: 'left_hand').save()
            new Slot(id: 'right_hand').save()
            new Slot(id: 'bag', space: 20).save()
        }

    }

    def destroy = {
    }
}
