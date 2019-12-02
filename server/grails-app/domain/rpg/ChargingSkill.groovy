package rpg

class ChargingSkill {

    int countdown

    static belongsTo = [ skill: Skill, user: Participant, field: Field ]

    static constraints = {
    }
}
