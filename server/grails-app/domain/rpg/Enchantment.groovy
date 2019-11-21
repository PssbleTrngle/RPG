package rpg

class Enchantment {

    String id

    static constraints = {
        id blank: false, nullable: false, unique: true
    }
}
