package rpg

class Dungeon {

    String id, icon
    int level, floors
    int mapX, mapY

    static belongsTo = [ area: Area ]

    static constraints = {
        id blank: false, nullable: false, unique: true
        level unsigned: true
        floors unsigned: true
    }
}
