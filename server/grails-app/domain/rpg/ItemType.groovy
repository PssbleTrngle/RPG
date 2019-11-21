package rpg

class ItemType {

    String id
    boolean hasIcon

    static constraints = {
        id blank: false, nullable: false, unique: true
        stackable hasIcon: false
    }
}
