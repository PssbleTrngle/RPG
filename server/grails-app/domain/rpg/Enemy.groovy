package rpg

class Enemy extends Participant {

    char suffix
    static belongsTo = [ npc: Npc ]

    static constraints = {
        suffix nullable: true, blank: false
    }
}
