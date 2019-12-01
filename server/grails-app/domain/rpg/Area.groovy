package rpg

import grails.rest.Resource

@Resource(uri = '/area', readOnly = true)
class Area implements Translated {

    String id
    int level
    int mapX, mapY

    static belongsTo = [ parent: Area ]
    static hasMany = [ children: Area, dungeons: Dungeon ]

    static constraints = {
        id bindable: true, generator: 'assigned'
        level unsigned: true
        parent nullable: true
    }

    static mapping = {
        children sort: 'id'
    }
}
