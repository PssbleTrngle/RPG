package rpg

class Character extends Participant {

    Date dateCreated
    String name
    int xp = 0, level = 0, skillpoints = 5

    static belongsTo = [ account: Account, race: Race, position: Position ]
    static hasMany = [ messages: Message, clazzes: Clazz, inventory: Stack ]

    static constraints = {
        name blank: false, nullable: false
        xp unsigned: true
        skillpoints unsigned: true
        level unsigned: true
    }
}
