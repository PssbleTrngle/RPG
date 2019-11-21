package rpg

class Area {

    String id, icon
    int level
    int mapX, mapY

    static belongsTo = Area
    static hasMany = [ children: Area, dungeons: Dungeon ]

    static constraints = {
        id blank: false, nullable: false, unique: true
        icon blank: false, nullable: false
        level unsigned: true
    }
}
