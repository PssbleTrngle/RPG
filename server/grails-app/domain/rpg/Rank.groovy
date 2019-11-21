package rpg

class Rank {

    String id

    static constraints = {
        id blank: false, nullable: false, unique: true
    }
}
