package rpg

class Effect {

    String id
    int fade_min, fade_max
    boolean block = false

    static constraints = {
        id blank: false, nullable: false, unique: true
        fade_min unsigned: true
        fade_max unsigned: true
    }
}
