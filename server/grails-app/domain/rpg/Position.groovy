package rpg

class Position {

    int floor = 1, attempts = 0
    boolean foundStairs = false

    static belongsTo = [ area: Area, dungeon: Dungeon ]

    static constraints = {
        area nullable: true /* TODO remove */
        dungeon nullable: true
    }
}
