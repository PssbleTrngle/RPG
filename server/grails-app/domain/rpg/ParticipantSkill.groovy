package rpg

class ParticipantSkill {

    int nextUse

    static belongsTo = [ participant: Participant, skill: Skill ]

    static constraints = {
        nextUse unsigned: true
    }
}
