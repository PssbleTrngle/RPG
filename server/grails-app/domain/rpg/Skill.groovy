package rpg

class Skill {

    String id
    int timeout, charge = 0, cost, range
    boolean affectDead = false

    static constraints = {
        id blank: false, nullable: false, unique: true
    }
}
