package rpg

import grails.rest.Resource

@Resource(uri = '/class', readOnly = true)
class Clazz implements Translated {

    String id

    static belongsTo = [ stats: Stats, starter_weapon: ItemType ]

    static constraints = {
        id bindable: true, generator: 'assigned'
        starter_weapon nullable: true
    }
}
