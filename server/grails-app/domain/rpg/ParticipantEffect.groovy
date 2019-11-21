package rpg

class ParticipantEffect {

    int countdown

    static belongsTo = [ participant: Participant, effect: Effect ]

    static constraints = {
        countdown unsigned: true
    }
}
