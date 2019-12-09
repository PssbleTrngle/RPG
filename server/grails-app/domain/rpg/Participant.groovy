package rpg

abstract class Participant {

    int health, side
    boolean died = false, joined = true

    static belongsTo = [ field: Field ]

    Battle getBattle() {
        return getField()?.getBattle()
    }

    static constraints = {
        field nullable: true
    }

    abstract int maxHealth();

}
