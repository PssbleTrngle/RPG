package rpg

class Account {

    Date dateCreated
    String name
    String passwordHash

    static belongsTo = [ selected: Character ]
    static hasMany = [ characters: Character, permissions: Permission ]

    static constraints = {
        name blank: false, unique: true, nullable: false
        passwordHash blank: false, nullable: false
    }
}
