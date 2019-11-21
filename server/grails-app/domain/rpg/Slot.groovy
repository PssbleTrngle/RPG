package rpg

class Slot {

    String id
    int space = 1
    boolean applyStats = false

    static constraints = {
        id blank: false, nullable: false, unique: true
    }
}
