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
            new Item(id: 'health_potion', type: ItemType.findById('potion')).save(failOnError: true),
            new Item(id: 'sleep_potion', type: ItemType.findById('potion')).save(),
            new Item(id: 'poison_potion', type: ItemType.findById('potion')).save(),
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
