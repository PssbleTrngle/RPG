package rpg

import grails.rest.Resource

@Resource(uri = '/npc', readOnly = true)
class Npc implements Translated {

    String id
    int level, maxHealth

    static belongsTo = [ rank: Rank ]
    static hasMany = [ loot: Item, skills: Skill ]

    static constraints = {
        id bindable: true, generator: 'assigned'
        level unsigned: true
        maxHealth unsigned: true
    }
}
