package rpg

class Rarity {

    String id, color

    static constraints = {
        id blank: false, nullable: false, unique: true
    }
}
