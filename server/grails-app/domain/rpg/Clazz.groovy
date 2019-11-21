package rpg

class Clazz {

    String id

    static belongsTo = [ stats: Stats, starter_weapon: ItemType ]

    static constraints = {
        id nullable: false, blank: false, unique: true
    }
}
