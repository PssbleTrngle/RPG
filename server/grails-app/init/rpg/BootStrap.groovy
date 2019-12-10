package rpg

class BootStrap {

    def init = { servletContext ->

        if(Stats.count() == 0) Stats.withTransaction {
            new Stats(wisdom: 1, strength: 1, resistance: 1, agility: 1, luck: 1).save()
        }

        Stats placeholder = Stats.first()
        if(Clazz.count() == 0) Clazz.withTransaction {
            new Clazz(id: 'apprentice', stats: placeholder).createEvolutions(10, 'mage', 'druid', 'alchemist')
            new Clazz(id: 'traveller', stats: placeholder).createEvolutions(10, 'barde', 'merchant', 'rogue')
            new Clazz(id: 'wild', stats: placeholder).createEvolutions(10, 'warrior', 'ranger', 'hermit')
            new Clazz(id: 'fighter', stats: placeholder).createEvolutions(10, 'warrior', 'duelist', 'focused')
            new Clazz(id: 'psychic', stats: placeholder).createEvolutions(10, 'telepath', 'medium', 'priest')
            
            new Clazz(id: 'druid', stats: placeholder).createEvolutions(10, 'shaman', 'necromancer', 'astromancer')
            new Clazz(id: 'mage', stats: placeholder).createEvolutions(10, 'wizard', 'elementalist', 'warlock')
            new Clazz(id: 'alchemist', stats: placeholder).createEvolutions(10, 'elementalist', 'ritualist', 'infused')
            new Clazz(id: 'rogue', stats: placeholder).createEvolutions(10, 'bandit', 'assasin', 'derwish')
            new Clazz(id: 'barde', stats: placeholder).createEvolutions(10, 'performer', 'charmer', 'narrator')
            new Clazz(id: 'merchant', stats: placeholder).createEvolutions(10, 'artificer', 'inventor')
            new Clazz(id: 'ranger', stats: placeholder).createEvolutions(10, 'hunter', 'tamer', 'assasin')
            new Clazz(id: 'hermit', stats: placeholder).createEvolutions(10, 'bandit', 'shaman', 'monk')
            new Clazz(id: 'duelist', stats: placeholder).createEvolutions(10, 'rebel', 'hero', 'knight')
            new Clazz(id: 'warrior', stats: placeholder).createEvolutions(10, 'mercenary', 'berserk', 'cleric')
            new Clazz(id: 'focused', stats: placeholder).createEvolutions(10, 'blockade', 'derwish', 'monk')
            new Clazz(id: 'priest', stats: placeholder).createEvolutions(10, 'cleric', 'ritualist')
            new Clazz(id: 'medium', stats: placeholder).createEvolutions(10, 'charmer', 'thaumaturge', 'necromancer')
            new Clazz(id: 'telepath', stats: placeholder).createEvolutions(10, 'temporal', 'wizard', 'illusionist')
            new Clazz(id: 'shaman', stats: placeholder).createEvolutions(10, 'guardian')
            new Clazz(id: 'necromancer', stats: placeholder).createEvolutions(10, 'creator')
            new Clazz(id: 'wizard', stats: placeholder).createEvolutions(10, 'sage')
            new Clazz(id: 'elementalist', stats: placeholder).createEvolutions(10, 'spirit')
            new Clazz(id: 'infused', stats: placeholder).createEvolutions(10, 'beast')
            new Clazz(id: 'ritualist', stats: placeholder).createEvolutions(10, 'corrupted', 'possessed')
            new Clazz(id: 'astromancer', stats: placeholder).createEvolutions(10, 'touched')
            new Clazz(id: 'warlock', stats: placeholder).createEvolutions(10, 'conquerer')
            new Clazz(id: 'illusionist', stats: placeholder).createEvolutions(10, 'narrator')
            new Clazz(id: 'assasin', stats: placeholder)
            new Clazz(id: 'derwish', stats: placeholder)
            new Clazz(id: 'bandit', stats: placeholder).createEvolutions(10, 'conquerer')
            new Clazz(id: 'inventor', stats: placeholder).createEvolutions(10, 'creator')
            new Clazz(id: 'fool', stats: placeholder).createEvolutions(10, 'narrator', 'insane')
            new Clazz(id: 'performer', stats: placeholder).createEvolutions(10, 'imposter')
            new Clazz(id: 'charmer', stats: placeholder).createEvolutions(10, 'king')
            new Clazz(id: 'artificer', stats: placeholder)
            new Clazz(id: 'tamer', stats: placeholder).createEvolutions(10, 'beast')
            new Clazz(id: 'hunter', stats: placeholder).createEvolutions(10, 'predator')
            new Clazz(id: 'monk', stats: placeholder).createEvolutions(10, 'sage')
            new Clazz(id: 'rebel', stats: placeholder).createEvolutions(10, 'king')
            new Clazz(id: 'hero', stats: placeholder).createEvolutions(10, 'chosen', 'corrupted')
            new Clazz(id: 'knight', stats: placeholder).createEvolutions(10, 'guardian', 'fallen')
            new Clazz(id: 'berserk', stats: placeholder).createEvolutions(10, 'possessed')
            new Clazz(id: 'mercenary', stats: placeholder)
            new Clazz(id: 'blockade', stats: placeholder).createEvolutions(10, 'guardian')
            new Clazz(id: 'cleric', stats: placeholder).createEvolutions(10, 'touched')
            new Clazz(id: 'thaumaturge', stats: placeholder).createEvolutions(10, 'imposter')
            new Clazz(id: 'temporal', stats: placeholder).createEvolutions(10, 'oracle')

            new Clazz(id: 'possessed', stats: placeholder)
            new Clazz(id: 'sage', stats: placeholder)
            new Clazz(id: 'beast', stats: placeholder)
            new Clazz(id: 'fallen', stats: placeholder)
            new Clazz(id: 'guardian', stats: placeholder)
            new Clazz(id: 'chosen', stats: placeholder)
            new Clazz(id: 'insane', stats: placeholder)
            new Clazz(id: 'narrator', stats: placeholder)
            new Clazz(id: 'imposter', stats: placeholder)
            new Clazz(id: 'king', stats: placeholder)
            new Clazz(id: 'spirit', stats: placeholder)
            new Clazz(id: 'touched', stats: placeholder)
            new Clazz(id: 'oracle', stats: placeholder)
            new Clazz(id: 'predator', stats: placeholder)
            new Clazz(id: 'creator', stats: placeholder)
            new Clazz(id: 'corrupted', stats: placeholder)
            new Clazz(id: 'conquerer', stats: placeholder)
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
