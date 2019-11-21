package rpg

class Race {

    String id

    static belongsTo = [ stats: Stats ]

    static constraints = {
        id blank: false, nullable: false, unique: true
        stats nullable: false
    }
}
