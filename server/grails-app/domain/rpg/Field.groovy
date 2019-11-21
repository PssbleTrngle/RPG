package rpg

class Field {

    int x, y, spawnFor

    static belongsTo = [ battle: Battle, participant: Participant ]

    static constraints = {
        id composite: [ x, y, battleId ]
    }
}
