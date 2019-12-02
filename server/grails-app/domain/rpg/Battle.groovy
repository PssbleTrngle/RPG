package rpg

class Battle {

    int round = 0

    static belongsTo = [ active: Participant, position: Position ]
    static hasMany = [ fields: Field, participants: Participant, messages: Message ]

    static constraints = {
        round unsigned: true
    }
}
