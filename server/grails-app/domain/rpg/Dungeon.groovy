package rpg

import grails.rest.Resource

@Resource(uri = '/dungeon', readOnly = true)
class Dungeon implements Translated {

    String id, icon
    int level, floors
    int mapX, mapY

    static belongsTo = [ area: Area ]

    static constraints = {
        id bindable: true, generator: 'assigned'
        level unsigned: true
        floors unsigned: true
    }
}
