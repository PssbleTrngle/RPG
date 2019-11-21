package rpg

class Permission {

    String id

    static constraints = {
        id blank: false, nullable: false, unique: true
    }
}
