package rpg

import grails.rest.Resource

class Enemy extends Participant {

    char suffix
    static belongsTo = [ npc: Npc ]

    @Override
    int maxHealth() {
        return npc.getMaxHealth()
    }

    static constraints = {
        suffix nullable: true, blank: false
    }
}
