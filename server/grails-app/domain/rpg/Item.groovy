package rpg

class Item {

    String id
    boolean stackable = true

    static belongsTo = [type: ItemType, rarity: Rarity, stats: Stats ]

    static constraints = {
        id blank: false, nullable: false, unique: true
    }
}
